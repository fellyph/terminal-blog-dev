# Auditoria Impeccable

Data: 2 de agosto de 2026  
Escopo: `terminal-blog-dev` em `http://localhost:8888/`  
Método: Impeccable 4.0.4, detector próprio e Puppeteer  
Contexto de produto: `PRODUCT.md`

## Resultado

**13/20, Aceitável**

| Dimensão | Nota | Resumo |
|---|---:|---|
| Acessibilidade | 3/4 | Boa base semântica, com skip link, foco visível, suporte a reduced motion, labels e alternativas de imagem. Alguns alvos de toque independentes ainda são pequenos. |
| Performance | 3/4 | As páginas locais carregaram sem erros de console e com transferência moderada. Há fragmentação de CSS e JavaScript que pode ser reduzida. |
| Theming | 3/4 | O sistema visual escuro, os tokens e a identidade CRT/neon estão coerentes com o produto. O modo alternativo não foi exercitado nesta auditoria e ainda há cores declaradas fora dos tokens. |
| Responsividade | 2/4 | A home se adapta sem overflow horizontal, mas o artigo apresenta overflow, código cortado e colisão do botão de compartilhamento no mobile. |
| Integridade da implementação | 2/4 | O sistema é intencional e consistente, porém há falhas concretas de integração: imagem quebrada, link para o próprio artigo e componente flutuante obstruindo conteúdo. |

Esta é uma nova linha de base feita exclusivamente com o Impeccable. Por isso, a nota não deve ser comparada diretamente com auditorias anteriores que usaram outros critérios.

## Ambiente testado

| Página | Viewport |
|---|---|
| Home | 1440 × 1000 |
| Home | 390 × 844 |
| Artigo `html-in-canvas` | 1440 × 1000 |
| Artigo `html-in-canvas` | 390 × 844 |

No artigo mobile, o documento chegou a **466 px de largura em um viewport de 390 px**. Os sete blocos `pre` tinham `overflow-x: hidden`; tokens de código alcançaram aproximadamente **605 px**, ficando cortados.

## P0, bloqueadores

Nenhum problema P0 foi encontrado.

## P1, problemas importantes

### 1. Overflow horizontal no artigo mobile

- **Local:** página do artigo, especialmente metadados/taxonomias e conteúdo técnico.
- **Categoria:** Responsividade e acessibilidade.
- **Impacto:** o documento fica 76 px mais largo que a viewport. O leitor precisa deslocar a página lateralmente e pode perder conteúdo.
- **Padrão:** WCAG 2.2, critério 1.4.10 Reflow.
- **Recomendação:** permitir quebra dos metadados, remover larguras mínimas indevidas e garantir `max-width: 100%` nos filhos do conteúdo.
- **Comando sugerido:** `$impeccable adapt`

### 2. Blocos de código são cortados no mobile

- **Local:** sete elementos `pre` do artigo, renderizados pelo syntax highlighter.
- **Categoria:** Responsividade e integridade da implementação.
- **Impacto:** linhas de código não podem ser lidas por completo. O tema define rolagem horizontal, mas a regra computada no navegador é `overflow-x: hidden`, indicando conflito de especificidade ou integração com o plugin.
- **Padrão:** WCAG 2.2, critério 1.4.10 Reflow; legibilidade de conteúdo essencial.
- **Recomendação:** aplicar `overflow-x: auto` no seletor efetivamente renderizado pelo highlighter e preservar foco/rolagem por teclado quando necessário.
- **Comando sugerido:** `$impeccable adapt`

### 3. Botão flutuante de compartilhamento cobre conteúdo

- **Local:** componente social com nome acessível `Share`.
- **Categoria:** Layout e responsividade.
- **Impacto:** em 390 px, o botão de 56 × 56 px se sobrepõe ao título do artigo. Na home, também invade a área do título da seção de posts recentes.
- **Padrão:** conteúdo não deve ser obscurecido por componentes persistentes; WCAG 1.4.10 e 2.4.11 como referências de reflow e foco não obscurecido.
- **Recomendação:** mover o botão para uma zona segura, reservar espaço no layout ou mudar sua ancoragem abaixo de um breakpoint.
- **Comando sugerido:** `$impeccable layout`

## P2, problemas com impacto moderado

### 4. Títulos longos em caixa-alta e com entrelinha apertada

- **Local:** títulos de posts, `h1` do artigo, headings relacionados e CTAs.
- **Categoria:** Tipografia.
- **Impacto:** a identidade terminal continua reconhecível, mas títulos longos ficam mais lentos de escanear. No mobile, o `h1` usa proporção de line-height próxima de 1.1; alguns headings ficam entre 1.2 e 1.3.
- **Padrão:** legibilidade e hierarquia tipográfica.
- **Recomendação:** manter caixa-alta nos rótulos curtos e reduzir seu uso em títulos editoriais longos; ampliar a entrelinha dos headings que quebram em várias linhas.
- **Comando sugerido:** `$impeccable typeset`

### 5. Medida de linha extensa no artigo desktop

- **Local:** corpo do artigo em 1440 px.
- **Categoria:** Tipografia responsiva.
- **Impacto:** o detector encontrou 35 trechos com medida estimada entre 91 e 96 caracteres por linha, acima da faixa confortável para leitura contínua.
- **Padrão:** legibilidade de texto longo.
- **Recomendação:** reduzir a largura do conteúdo editorial sem comprimir imagens e blocos que precisam de largura adicional.
- **Comando sugerido:** `$impeccable typeset`

### 6. Alvos de toque independentes pequenos

- **Local:** links de categoria, CTAs de leitura e alguns links de títulos na home mobile.
- **Categoria:** Acessibilidade e interação.
- **Impacto:** foram observadas alturas aproximadas de 18 px para categorias, 34 px para CTAs e 37 px para alguns títulos. Isso aumenta erros de toque, principalmente para links isolados.
- **Padrão:** WCAG 2.2, critério 2.5.8 Target Size (Minimum), considerando as exceções aplicáveis a links inline.
- **Recomendação:** aumentar a área clicável com padding e espaçamento, preservando o tamanho visual do texto.
- **Comando sugerido:** `$impeccable harden`

### 7. Imagem de Gravatar sem origem válida

- **Local:** artigo mobile, `<img src="" alt="Perfil do Gravatar">`.
- **Categoria:** Integridade da implementação.
- **Impacto:** o navegador recebe uma imagem quebrada/invisível, sem dimensões naturais.
- **Padrão:** robustez de mídia e prevenção de conteúdo quebrado.
- **Recomendação:** não renderizar o elemento quando não houver URL válida ou fornecer um avatar local de fallback.
- **Comando sugerido:** `$impeccable harden`

### 8. `NEXT_READS` inclui o artigo atual

- **Local:** seção de leituras relacionadas no template de artigo.
- **Categoria:** Integridade de conteúdo e navegação.
- **Impacto:** a recomendação leva o leitor de volta à mesma página e desperdiça uma opção de navegação.
- **Padrão:** navegação coerente e ausência de ações redundantes.
- **Recomendação:** excluir o post atual da query de relacionados e garantir itens substitutos quando a lista ficar curta.
- **Comando sugerido:** `$impeccable harden`

### 9. Fragmentação de assets por página

- **Local:** home e artigo renderizados.
- **Categoria:** Performance.
- **Impacto:** a home registrou cerca de 42 folhas de estilo e 22 scripts; o artigo, 48 folhas de estilo e 27 scripts. Mesmo com transferência total entre aproximadamente 362 e 517 KB, a fragmentação aumenta trabalho de descoberta, parse e manutenção.
- **Padrão:** entrega eficiente e carregamento condicional.
- **Recomendação:** identificar assets de plugins que não são usados em cada rota, remover duplicações e carregar funcionalidades apenas onde aparecem.
- **Comando sugerido:** `$impeccable optimize`

### 10. Rótulos operacionais misturam inglês e PT-BR

- **Local:** nome acessível `Share` e rótulos como `NEXT_READS` e `OPEN_FILE`.
- **Categoria:** Clareza de interface.
- **Impacto:** o conteúdo principal é PT-BR, mas ações e rótulos de navegação mudam de idioma. Termos técnicos podem permanecer em inglês; comandos dirigidos ao leitor devem ser consistentes.
- **Padrão:** linguagem previsível e contexto de produto definido em `PRODUCT.md`.
- **Recomendação:** localizar o nome acessível do compartilhamento e revisar quais rótulos em inglês são marca intencional e quais são ações para o leitor.
- **Comando sugerido:** `$impeccable clarify`

## P3, refinamento visual

### 11. Glow aplicado em muitos níveis da hierarquia

- **Local:** títulos, links, bordas, CTAs e elementos decorativos.
- **Categoria:** Hierarquia visual.
- **Impacto:** o glow é um compromisso explícito da marca e não deve ser removido. Porém, quando aparece em muitos níveis simultaneamente, perde função hierárquica e compete com o conteúdo.
- **Padrão:** diferenciação de níveis de ênfase.
- **Recomendação:** preservar neon/CRT nos pontos de maior destaque e reduzir sua intensidade em elementos secundários.
- **Comando sugerido:** `$impeccable quieter`

## Alertas descartados ou reinterpretados

O detector é deliberadamente sensível. Estes alertas foram verificados no DOM antes da classificação:

- **`text-overflow` em 35 labels do syntax highlighter:** falso positivo visual. Os labels ficam dentro de um wrapper assistivo de 1 × 1 px, posicionado e recortado. O problema real dos blocos de código foi confirmado separadamente nos tokens visíveis.
- **`layout-transition`:** não havia elemento visível com transição de largura ativa nas páginas testadas; o componente de busca associado não estava renderizado.
- **`repeating-stripes-gradient`:** alerta consultivo aceito. Scanlines fazem parte do compromisso de marca CRT documentado em `PRODUCT.md`.
- **`overused-font`:** alerta aceito. Space Grotesk é a tipografia assumida pelo produto; não é uma falha funcional.
- **`all-caps-body`:** classificação automática imprecisa. As amostras eram majoritariamente headings, títulos, CTAs e texto para leitor de tela, não parágrafos corridos. O efeito em títulos longos permanece registrado como questão tipográfica P2.
- **`dark-glow`:** o uso é intencional segundo o contexto do produto. Apenas a distribuição do efeito pela hierarquia foi mantida como refinamento P3.

## Pontos positivos

- A identidade terminal/CRT/neon é coerente e reconhecível entre home e artigo.
- O tema usa `theme.json` v3 e uma base clara de tokens de cor e tipografia.
- As páginas têm `header`, navegação, `main`, footer, sequência de headings e skip link.
- O foco de teclado é visível e há suporte a `prefers-reduced-motion`.
- As imagens de conteúdo testadas tinham texto alternativo; seis das sete imagens da home usavam lazy loading.
- A home não apresentou overflow horizontal em desktop nem em 390 px.
- Não foram observados erros de console durante as quatro execuções.
- A transferência local ficou abaixo de 520 KB nas páginas testadas.
- O detector do Impeccable está operacional em modo URL com Puppeteer no projeto.

## Ordem recomendada

1. `$impeccable adapt` para reflow mobile e blocos de código.
2. `$impeccable layout` para reposicionar o compartilhamento.
3. `$impeccable typeset` para medida de linha, caixa-alta e entrelinha.
4. `$impeccable harden` para alvos de toque, Gravatar e relacionados.
5. `$impeccable clarify` para consistência dos rótulos operacionais.
6. `$impeccable optimize` para carregamento condicional de assets.
7. `$impeccable quieter` para concentrar o glow nos níveis certos.
8. `$impeccable polish` como passe final, depois das correções estruturais.

Você pode pedir para executar essas ações uma por vez, todas de uma vez ou na ordem que preferir.

Execute novamente `$impeccable audit` depois das correções para acompanhar a evolução da nota.
