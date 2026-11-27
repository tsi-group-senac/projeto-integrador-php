# Identidade Visual — Destyn Trips

Marca: **Destyn Trips** · Domínio: [destyntrips.com.br](https://destyntrips.com.br)

## Logo

Arquivos em `frontend/assets/logos/`:

- `destyn-trips-logo.png` — mark (globo + bússola + avião) + wordmark DESTYN / TRIPS
- `destyn-trips-mark.png` — só o símbolo (sem nome fantasia)
- `destyn-trips-logo.svg` / `destyn-trips-mark.svg` — wrappers SVG do mesmo artwork
- `favicon.svg` — símbolo para favicon

Uso no chrome: `frontend/components/logo.php` → `destyn-trips-logo.png`.

## Favicons

Pacote estilo [RealFaviconGenerator](https://realfavicongenerator.net/) em `frontend/assets/favicons/`, gerado **apenas com o globo/mark** (sem texto):

`favicon.ico`, PNG 16/32/48, apple-touch, android-chrome, mstile, safari-pinned-tab, `site.webmanifest`, `browserconfig.xml`.

Tags em `frontend/components/head.php`.

## Paleta

| Token | HEX | Uso |
| --- | --- | --- |
| Orange / brand | `#faa935` | CTAs, destaques, ícones, mark |
| Ink | `#0b2727` | Títulos e texto principal |
| Muted | `#6e7074` | Labels e corpo secundário |
| Sky | `#cae5f4` | Faixa de newsletter |

## Tipografia

- **UI / títulos:** Montserrat
- **Cidades / eyebrows decorativos:** Island Moments (script)

## Componentes

Header, footer, service cards, destination/package cards, experience, gallery, testimonials, subscribe e auth — em `frontend/components/`.
