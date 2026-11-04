# Terminal Blog Dev

Block theme do blog de Fellyph Cintra, com estética inspirada em terminal e foco em conteúdo técnico sobre WordPress, inteligência artificial, PWA e tecnologias web.

## Requisitos

- WordPress 6.6 ou superior (configuração `theme.json` versão 3)
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

## Licença

GPL-2.0-or-later.

