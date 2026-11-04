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

## Prévia de pull requests

O workflow `PR Preview` adiciona um botão à descrição dos pull requests deste repositório. Ao abrir a prévia, o WordPress Playground instala e ativa o tema da branch do pull request em um ambiente temporário no navegador.

O Blueprint abre a página inicial com seis posts de demonstração em português, divididos entre WordPress, inteligência artificial e desenvolvimento web. Os artigos incluem resumos, datas, autor, seis tags e blocos nativos de parágrafo, título, lista, citação e código. O conteúdo está em [`.github/playground/posts.xml`](.github/playground/posts.xml), no formato de exportação do WordPress (WXR).

O tema e o WXR são carregados do mesmo commit do pull request. O Blueprint remove o conteúdo inicial do Playground antes da importação e entra como administrador para permitir a edição dos exemplos. Esses dados existem apenas na prévia; instalar ou ativar o tema em outro site não cria posts.

O tema funciona diretamente com os arquivos versionados, sem etapa de compilação. A integração usa o `GITHUB_TOKEN` fornecido pelo GitHub; não é necessário cadastrar um segredo. Esta configuração atende a branches do próprio repositório. Para prévias de pull requests vindos de forks, use os dois workflows descritos no [guia do WordPress Playground](https://developer.wordpress.org/playground/handbook/guides/github-action-pr-preview/).

## Licença

GPL-2.0-or-later.

