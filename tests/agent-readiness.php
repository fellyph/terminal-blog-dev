<?php
/** Focused regressions for negotiation, public access, Markdown, and search markup. */
require __DIR__ . '/agent-readiness-bootstrap.php';

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	$GLOBALS['checks'] = ( $GLOBALS['checks'] ?? 0 ) + 1;
}

foreach ( array(
	'' => false,
	'*/*' => false,
	'text/*' => false,
	'text/html' => false,
	'text/markdown' => true,
	'TEXT/MARKDOWN; charset=utf-8' => true,
	'text/markdown;q=0' => false,
	'text/markdown;q=0, */*;q=1' => false,
	'text/markdown, text/html' => false,
	'text/markdown;q=0.9, text/html;q=1' => false,
	'text/markdown;q=1, text/html;q=0.5' => true,
	'text/markdown;q=0.5, text/html;q=0, */*' => true,
	'text/markdown, */*' => true,
	'text/markdown;q=0.5, */*;q=0.8' => false,
	'text/markdown;q=0.5, application/json' => true,
	'text/markdown;q=bogus' => false,
	'text/markdown;q=1.5' => false,
	'text/markdown;q=-1' => false,
) as $accept => $expected ) {
	check( $expected === terminal_blog_dev_prefers_markdown( $accept ), 'Incorrect negotiation: ' . $accept );
}

$_SERVER['REQUEST_METHOD'] = 'GET';
$GLOBALS['request_fixture'] = array( 'front' => true );
check( terminal_blog_dev_is_public_content_request(), 'Homepage should support Markdown.' );
foreach ( array( 'admin', 'ajax', 'logged_in', 'preview', '404', 'feed', 'embed', 'trackback', 'robots', 'favicon' ) as $flag ) {
	$GLOBALS['request_fixture'][ $flag ] = true;
	check( ! terminal_blog_dev_is_public_content_request(), 'Excluded request: ' . $flag );
	unset( $GLOBALS['request_fixture'][ $flag ] );
}
$_SERVER['REQUEST_METHOD'] = 'POST';
check( ! terminal_blog_dev_is_public_content_request(), 'POST must not negotiate.' );
$_SERVER['REQUEST_METHOD'] = 'HEAD';
check( terminal_blog_dev_is_public_content_request(), 'HEAD should negotiate like GET.' );
$_SERVER['REQUEST_METHOD'] = 'GET';
$post = new WP_Post( (object) array( 'post_type' => 'post', 'post_status' => 'publish', 'post_password' => '' ) );
$GLOBALS['request_fixture'] = array( 'singular' => true, 'post' => $post );
check( terminal_blog_dev_is_public_content_request(), 'Published article should negotiate.' );
foreach ( array( 'private', 'draft', 'future' ) as $status ) {
	$post->post_status = $status;
	check( ! terminal_blog_dev_is_public_content_request(), 'Nonpublic status: ' . $status );
}
$post->post_status = 'publish';
$post->post_password = 'secret';
check( ! terminal_blog_dev_is_public_content_request(), 'Password-protected posts must not negotiate, including unlocked sessions.' );
$post->post_password = '';
$post->post_type = 'private_trip';
check( ! terminal_blog_dev_is_public_content_request(), 'Plugin content must keep its existing representation.' );

$converter = new Terminal_Blog_Dev_Markdown( 'https://example.test/article/' );
$html = '<html><head><title>Ignore metadata</title></head><body><header>Ignore chrome</header><main><h1>Olá &amp; WordPress</h1><p>Read <strong>this</strong> <a href="../guide/?a=1&amp;b=2">guide</a>.</p><pre><code class="language-js">const code = ` ``` `;

  keep_indentation();
</code></pre><ul><li>One<ul><li>Nested</li></ul></li><li>Two</li></ul><blockquote><p>Quote</p></blockquote><p><code>inline `code`</code></p><img src="/image.png" alt="A [diagram]"><iframe src="https://video.test/watch"></iframe><script>secret_script()</script><div hidden>hidden text</div><div aria-hidden="true">decoration</div><form>Ignore form</form><a href="javascript:alert(1)">Safe label</a><img><a name="anchor">Anchor</a></main><footer>Ignore footer</footer></body></html>';
$markdown = $converter->convert( $html );
foreach ( array( '# Olá & WordPress', '**this**', '[guide](<https://example.test/guide/?a=1&b=2>)', "````js\nconst code = ` ``` `;\n\n  keep_indentation();\n````", '- One', '  - Nested', '- Two', '> Quote', '`` inline `code` ``', '![A \\[diagram\\]](<https://example.test/image.png>)', '[Media](<https://video.test/watch>)', 'Safe label' ) as $text ) {
	check( str_contains( $markdown, $text ), 'Markdown lost content: ' . $text . "\n" . $markdown );
}
foreach ( array( 'Ignore', 'secret_script', 'hidden text', 'decoration', 'javascript:', '\\n\\n[Media]' ) as $text ) {
	check( ! str_contains( $markdown, $text ), 'Markdown includes unwanted content: ' . $text );
}
check( "# Heading\n\nParagraph\n" === $converter->convert( '<main><div><h1>Heading</h1><p>Paragraph</p></div></main>' ), 'Block spacing should be compact.' );
$table = $converter->convert( '<main><table><thead><tr><th>A</th><th>B</th></tr></thead><tbody><tr><td>x|y</td><td>z</td></tr></tbody></table></main>' );
check( str_contains( $table, "| A | B |\n| --- | --- |\n| x\\|y | z |" ), 'Tables must preserve cell boundaries.' );
check( str_contains( $converter->convert( '<main><table><tr><td>Data</td></tr></table></main>' ), "|  |\n| --- |\n| Data |" ), 'Headerless tables must not turn data into headers.' );
check( str_contains( $converter->convert( '<main><ol start="3"><li>Third</li><li value="8">Eighth</li></ol></main>' ), "3. Third\n8. Eighth" ), 'Ordered lists must preserve numbering.' );

$GLOBALS['request_fixture'] = array();
$form = '<form role="search" method="get" action="https://example.test/"><label for="s">Search</label><input id="s" name="s" type="search"><button>Find</button></form>';
$post_form = str_replace( 'method="get"', 'method="post"', $form );
check( $post_form === terminal_blog_dev_search_tool( $post_form ), 'POST forms must remain unchanged.' );
$annotated = terminal_blog_dev_search_tool( $form );
check( str_contains( $annotated, 'toolname="search_blog"' ), 'Search tool name missing.' );
check( str_contains( $annotated, 'tooldescription=' ) && str_contains( $annotated, 'toolparamdescription=' ), 'Tool descriptions missing.' );
check( str_contains( $annotated, 'toolautosubmit' ) && str_contains( $annotated, 'method="get"' ), 'Search must use the existing GET submission.' );
check( $form === terminal_blog_dev_search_tool( $form ), 'Only one form per page should register the search tool.' );

echo 'Passed ' . $GLOBALS['checks'] . " checks.\n";
