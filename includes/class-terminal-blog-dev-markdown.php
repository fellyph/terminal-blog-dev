<?php
/**
 * Convert rendered editorial HTML to Markdown without fetching external resources.
 *
 * @package Terminal_Blog_Dev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Terminal_Blog_Dev_Markdown {
	/** @var string Base URL for relative links. */
	private $base_url;

	/**
	 * @param string $base_url Public URL of the rendered page.
	 */
	public function __construct( $base_url ) {
		$this->base_url = $base_url;
	}

	/**
	 * @param string $html Complete rendered HTML document.
	 * @return string Markdown for its main content, or body if there is no main.
	 */
	public function convert( $html ) {
		$document = new DOMDocument();
		$document->loadHTML( '<?xml encoding="UTF-8">' . $html, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING );
		$root = $document->getElementsByTagName( 'main' )->item( 0 ) ?? $document->getElementsByTagName( 'body' )->item( 0 );
		return $root ? trim( $this->node( $root ) ) . "\n" : '';
	}

	/**
	 * @param DOMNode $node Parent node.
	 * @return string Converted children.
	 */
	private function children( $node ) {
		$text = '';
		foreach ( $node->childNodes as $child ) {
			$part = $this->node( $child );
			// Merge block boundaries without normalizing whitespace inside code.
			$text = str_starts_with( $part, "\n\n" ) ? rtrim( $text ) . "\n\n" . ltrim( $part, "\n" ) : $text . $part;
		}
		return $text;
	}

	/**
	 * @param string $url Link or media destination.
	 * @return string Absolute URL with unsafe schemes removed.
	 */
	private function url( $url ) {
		return '' === trim( $url ) ? '' : esc_url_raw( WP_Http::make_absolute_url( $url, $this->base_url ), array( 'http', 'https', 'mailto' ) );
	}

	/**
	 * @param string $text Literal prose.
	 * @return string Escaped inline Markdown.
	 */
	private function text( $text ) {
		return str_replace(
			array( '\\', '`', '*', '_', '[', ']', '<', '>', '#', '|', '~' ),
			array( '\\\\', '\\`', '\\*', '\\_', '\\[', '\\]', '\\<', '\\>', '\\#', '\\|', '\\~' ),
			preg_replace( '/\s+/u', ' ', $text )
		);
	}

	/**
	 * @param string $text Code containing arbitrary runs of backticks.
	 * @param int    $minimum Minimum delimiter length.
	 * @return string A delimiter longer than every run in the code.
	 */
	private function fence( $text, $minimum ) {
		preg_match_all( '/`+/', $text, $runs );
		foreach ( $runs[0] as $run ) {
			$minimum = max( $minimum, strlen( $run ) + 1 );
		}
		return str_repeat( '`', $minimum );
	}

	/**
	 * @param DOMElement $node Table element.
	 * @return string Markdown table, preserving headerless tables with an empty header.
	 */
	private function table( $node ) {
		$rows   = array();
		$width  = 0;
		$header = false;
		foreach ( $node->getElementsByTagName( 'tr' ) as $row ) {
			$cells = array();
			foreach ( $row->childNodes as $cell ) {
				if ( $cell instanceof DOMElement && in_array( $cell->tagName, array( 'td', 'th' ), true ) ) {
					if ( empty( $rows ) && 'th' === $cell->tagName ) {
						$header = true;
					}
					$cells[] = str_replace( array( "\r", "\n" ), array( '', '<br>' ), trim( $this->children( $cell ) ) );
				}
			}
			$width  = max( $width, count( $cells ) );
			$rows[] = $cells;
		}
		if ( 0 === $width ) {
			return '';
		}
		if ( ! $header ) {
			array_unshift( $rows, array_fill( 0, $width, '' ) );
		}
		array_splice( $rows, 1, 0, array( array_fill( 0, $width, '---' ) ) );
		return "\n\n" . implode( "\n", array_map( static function ( $row ) use ( $width ) {
			return '| ' . implode( ' | ', array_pad( $row, $width, '' ) ) . ' |';
		}, $rows ) ) . "\n\n";
	}

	/**
	 * @param DOMNode $node HTML node.
	 * @return string Markdown representation.
	 */
	private function node( $node ) {
		if ( $node instanceof DOMText ) {
			return $this->text( $node->textContent );
		}
		if ( ! $node instanceof DOMElement ) {
			return '';
		}
		$tag = strtolower( $node->tagName );
		if ( in_array( $tag, array( 'head', 'script', 'style', 'template', 'noscript', 'nav', 'form', 'button', 'input', 'textarea', 'select', 'svg', 'canvas' ), true ) ||
			$node->hasAttribute( 'hidden' ) || $node->hasAttribute( 'inert' ) || 'true' === $node->getAttribute( 'aria-hidden' ) ) {
			return '';
		}
		if ( 'pre' === $tag ) {
			$code     = str_replace( "\r\n", "\n", $node->textContent );
			$fence    = $this->fence( $code, 3 );
			$classes  = $node->getAttribute( 'class' ) . ' ' . ( $node->getElementsByTagName( 'code' )->item( 0 )?->getAttribute( 'class' ) ?? '' );
			$language = preg_match( '/\blanguage-([a-zA-Z0-9_+-]+)/', $classes, $match ) ? $match[1] : '';
			return "\n\n" . $fence . $language . "\n" . $code . ( str_ends_with( $code, "\n" ) ? '' : "\n" ) . $fence . "\n\n";
		}
		if ( 'code' === $tag ) {
			$code    = str_replace( array( "\r", "\n" ), ' ', $node->textContent );
			$fence   = $this->fence( $code, 1 );
			$padding = str_contains( $code, '`' ) || str_starts_with( $code, ' ' ) || str_ends_with( $code, ' ' ) ? ' ' : '';
			return $fence . $padding . $code . $padding . $fence;
		}
		if ( 'table' === $tag ) {
			return $this->table( $node );
		}
		if ( 'ul' === $tag || 'ol' === $tag ) {
			$items = array();
			$index = $node->hasAttribute( 'start' ) ? (int) $node->getAttribute( 'start' ) : 1;
			foreach ( $node->childNodes as $item ) {
				if ( $item instanceof DOMElement && 'li' === $item->tagName ) {
					if ( $item->hasAttribute( 'value' ) ) {
						$index = (int) $item->getAttribute( 'value' );
					}
					$prefix  = 'ol' === $tag ? $index++ . '. ' : '- ';
					$items[] = $prefix . str_replace( "\n", "\n" . str_repeat( ' ', strlen( $prefix ) ), trim( $this->children( $item ) ) );
				}
			}
			return "\n\n" . implode( "\n", $items ) . "\n\n";
		}
		$text = $this->children( $node );
		if ( preg_match( '/^h([1-6])$/', $tag, $match ) ) {
			return "\n\n" . str_repeat( '#', (int) $match[1] ) . ' ' . trim( $text ) . "\n\n";
		}
		switch ( $tag ) {
			case 'a':
				$url = $this->url( $node->getAttribute( 'href' ) );
				return '' !== $url && '' !== trim( $text ) ? '[' . trim( $text ) . '](<' . $url . '>)' : $text;
			case 'img':
				$url = $this->url( $node->getAttribute( 'src' ) );
				return '' !== $url ? '![' . $this->text( $node->getAttribute( 'alt' ) ) . '](<' . $url . '>)' : '';
			case 'strong':
			case 'b':
				return '**' . trim( $text ) . '**';
			case 'em':
			case 'i':
				return '*' . trim( $text ) . '*';
			case 'del':
			case 's':
				return '~~' . trim( $text ) . '~~';
			case 'blockquote':
				return "\n\n> " . str_replace( "\n", "\n> ", trim( $text ) ) . "\n\n";
			case 'br':
				return "  \n";
			case 'hr':
				return "\n\n---\n\n";
			case 'iframe':
			case 'video':
			case 'audio':
				$url = $this->url( $node->getAttribute( 'src' ) );
				return $text . ( '' !== $url ? "\n\n[Media](<" . $url . ">)\n\n" : '' );
			case 'p':
			case 'div':
			case 'section':
			case 'article':
			case 'figure':
			case 'figcaption':
				return "\n\n" . trim( $text ) . "\n\n";
			default:
				return $text;
		}
	}
}
