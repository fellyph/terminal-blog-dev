# Traduções

O idioma de origem dos textos do tema é português brasileiro. Sem um catálogo instalado, os rótulos continuam em português. Os colchetes, os sublinhados, os prompts e as extensões de arquivo fazem parte da identidade visual de terminal.

Use `terminal-blog-dev.pot` para criar um catálogo de outro idioma, como `en_US.po`, e compile-o para `en_US.mo` nesta pasta. O tema usa o domínio `terminal-blog-dev` e carrega os catálogos pela API nativa do WordPress. Os arquivos globais em `wp-content/languages/themes/` também são compatíveis com o mecanismo do WordPress.

Preserve os espaços reservados, como `%s`, e mantenha os comandos reais (`cat`, `grep`, `ls` e `whoami`), as extensões (`.txt`, `.md` e `.sh`), as marcas e os URLs. Os caminhos fictícios que aparecem como decoração podem ser adaptados para o idioma.

Nos patterns PHP, o texto passa por gettext e é escapado conforme o contexto. Atributos de blocos são emitidos como JSON, incluindo escape de delimitadores HTML. Os templates HTML e as descrições dos SVGs têm texto em português como alternativa estática. Os rótulos decorativos conhecidos dos templates também passam pelo catálogo ao carregar, incluindo versões antigas salvas no Editor do site. Essa compatibilidade trabalha com cópias dos modelos; não altera o banco, o cache de posts, os menus ou o texto dos artigos.

Para atualizar o catálogo em uma instalação gerenciada pelo Studio, substitua `/caminho/do/tema` pelo caminho absoluto:

```bash
studio wp i18n make-pot /caminho/do/tema /caminho/do/tema/languages/terminal-blog-dev.pot --domain=terminal-blog-dev --exclude=node_modules,.agents,.codex,tests,.github
studio wp i18n make-mo /caminho/do/tema/languages
studio wp eval-file /caminho/do/tema/tests/i18n.php
```
