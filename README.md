# Terminal Blog Dev

Block theme do blog de Fellyph Cintra, com estética inspirada em terminal e foco em conteúdo técnico sobre WordPress, inteligência artificial, PWA e tecnologias web.

## Requisitos

- WordPress 6.4 ou superior
- PHP 8.0 ou superior
- Node.js 22 LTS (22.12 ou superior) e npm 10 para executar os linters

## Navegação de contato

O cabeçalho padrão não pressupõe que exista uma página de contato. Para adicionar esse destino, publique a página e selecione-a no bloco Navegação do cabeçalho, no Editor do site. Se o cabeçalho já tiver personalizações salvas, edite essa versão da navegação.

## Desenvolvimento

```bash
nvm use
npm ci
npm run lint:css
npm run lint:js
```

O TypeScript fica fixado em 5.3.3 para manter compatibilidade com o parser usado pelo ESLint do WordPress. A validação de JavaScript não depende da instalação global do TypeScript.

## Descoberta por agentes

O tema acrescenta os links HTTP `service-desc` (índice da REST API do próprio site) e `service-doc` (referência oficial da REST API do WordPress) à página inicial, preservando os cabeçalhos `Link` existentes.

Requisições anônimas `GET` ou `HEAD` para a página inicial, o índice do blog e posts ou páginas públicos podem negociar `Accept: text/markdown`. HTML continua sendo o padrão, inclusive quando tem preferência igual ou maior no cabeçalho. O Markdown é produzido a partir do mesmo template usado pelo HTML, mantendo o conteúdo de `<main>`, títulos, links, listas, tabelas simples e blocos de código. A extensão PHP DOM é necessária; sem ela, o tema mantém HTML. Páginas protegidas por senha, prévias, conteúdo não publicado, tipos de post de plugins e sessões autenticadas mantêm a representação original.

As duas representações recebem `Vary: Accept`; as respostas Markdown também recebem cabeçalhos para impedir seu armazenamento em cache. Um cache de página completa que responde antes de carregar o tema precisa encaminhar as requisições Markdown ao WordPress e respeitar `Vary: Accept`. Após publicar, limpe o cache e confirme que alternar entre HTML e Markdown na mesma URL devolve os tipos corretos. Para desativar a negociação em uma integração incompatível, use o filtro `terminal_blog_dev_markdown_enabled` com `__return_false`.

O primeiro bloco de pesquisa de cada página recebe os atributos declarativos WebMCP `toolname="search_blog"`, `tooldescription`, `toolparamdescription` e `toolautosubmit`. A ação continua sendo a pesquisa GET do WordPress: o agente informa o assunto, a página abre os resultados e o estado vazio existente permite tentar outra pesquisa. Outros blocos de pesquisa da mesma página não registram ferramentas duplicadas. Navegadores sem essa API continuam usando o formulário normalmente. O tema não altera ferramentas oferecidas pelos plugins.

Verificação local, com PHP DOM, PHP CGI (`php-cgi`), Node.js e os arquivos de uma instalação do WordPress disponíveis:

```bash
npm run test:agent-readiness
```

Se o tema estiver fora de uma instalação, defina `WP_TEST_CORE_DIR` com o caminho do WordPress. Os testes usam suas APIs de HTML e URL, sem carregar a configuração, os plugins ou o banco de dados. Cobrem preferências de mídia, conteúdo protegido, preservação de código, marcação da pesquisa e respostas CGI `GET`/`HEAD`, sem abrir um servidor. Defina `PHP_CGI` se o executável CGI tiver outro caminho. No navegador com WebMCP, confirme `search_blog` no painel Application → WebMCP do DevTools, execute uma pesquisa e verifique a navegação, a recuperação sem resultados e a ausência de duplicação. Confira também a categoria Agentic browsing do Lighthouse quando disponível.

## Licença

GPL-2.0-or-later.

