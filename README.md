# Cactusman Portfolio (v2)

A walkable pixel-world WordPress theme. Visitors land on a parallax dusk
scene and explore as a Cactuar, with content panels for About, Portfolio,
Blog, and Contact opening when they reach each landmark.

This is a from-scratch rewrite of the original
[`portfolio-theme`](https://github.com/Cactusman07/portfolio-theme) — the
build tooling moves from Gulp/LESS to Vite/SCSS, and the JS is broken into
ES modules instead of a single file.

---

## What's in the box

```
.
├── style.css                 # WP theme metadata header
├── functions.php             # Loads /inc/ modules
├── header.php  footer.php
├── front-page.php            # The walking world
├── single.php  single-portfolio.php  page.php  archive*.php  404.php
├── inc/
│   ├── theme-setup.php       # add_theme_support, image sizes, body classes
│   ├── enqueue.php           # Scripts + styles (dev/prod branch)
│   ├── post-types.php        # `portfolio` CPT, `skill` CPT, tech_tag taxonomy
│   ├── customizer.php        # Hero / status / contact / inventory settings
│   └── helpers.php           # cmp_mod(), cmp_get_portfolio_items(), …
├── template-parts/
│   ├── cactuar.php  hero-sign.php  landmarks.php  parallax.php  menu-bar.php
│   ├── panels/   {about, portfolio, blog, contact}.php
│   └── partials/ {quest-card, journal-entry, menu-icon, stars-night}.php
├── src/
│   ├── scss/    # Variables, base, world, cactuar, menu, ui, components, …
│   └── js/      # main, world, mesh, audio, panels, touch, intro
├── vite.config.js   package.json   .gitignore
└── README.md
```

---

## Install

1. Drop the whole `portfolio-theme/` folder into `wp-content/themes/`.
2. Activate **Cactusman Portfolio** in _Appearance → Themes_.
3. In _Settings → Reading_, set the homepage to a static page (any page —
   the front-page template ignores its content, but WP needs a target).
4. Set permalinks to _Post name_ (or anything non-default) so the
   `portfolio` CPT URLs work.

---

## Local WordPress (Docker via wp-env)

This repo uses `.wp-env.json` to define a local WordPress environment.
`wp-env` runs Docker containers for WordPress + MySQL, so you will not see a
`Dockerfile` in this theme repo.

1. Install dependencies:

```bash
npm install
```

2. Start local WordPress:

```bash
npm run wp:start
```

Or run WordPress + Vite dev in one command:

```bash
npm run dev:wp
```

3. Open:

- Site: `http://localhost:8888`
- WP Admin: `http://localhost:8888/wp-admin`

Use the default `wp-env` credentials unless you have overridden them:

- Username: `admin`
- Password: `password`

Useful commands:

```bash
npm run wp:stop     # stop containers
npm run wp:destroy  # remove containers and volumes
npm run wp:logs     # view container logs
```

---

## Build the assets

```bash
cd wp-content/themes/portfolio-theme
npm install
npm run build
```

This now does two things in one command:

- Builds frontend assets into `/assets/build/main.css` and `/assets/build/main.js`
- Creates a deploy-ready theme folder at `/build/portfolio/`

Zip the `/build/portfolio/` folder and upload it in WP Admin (_Appearance →
Themes → Add New Theme → Upload Theme_).

### Dev mode (HMR)

Add this to `wp-config.php` (above the "stop editing" line):

```php
define( 'CMP_DEV', true );
```

Then in a terminal:

```bash
npm run dev
```

The theme will load styles + scripts from `http://localhost:5173` with hot
reload. Remove the `CMP_DEV` line when you're done.

---

## Adding content

### Portfolio items

_Portfolio → Add new_. Each item supports:

- **Title + content** — used for the case-study page (`/work/<slug>/`).
- **Excerpt** — short description shown on the quest card.
- **Featured image** — falls back to a generic icon when missing.
- **Custom fields:**
  - `_cmp_external_url` — link the card to an external project instead.
  - `_cmp_year` — displayed on the detail page.
  - `_cmp_icon_svg` — optional inline pixel-art SVG for the card.
  - `_cmp_featured_order` — higher numbers appear first.
- **Taxonomies:**
  - **Status** (`portfolio_status`) — values: _Live, WIP, Ongoing, Brand,
    B2B portal_ (creates colour-coded card tags).
  - **Tech tags** (`tech_tag`) — appears in the stack row on the card.

### Skills (character sheet)

_Skills → Add new_. Each skill supports:

- **Title** — e.g. "React" or "Front-end".
- **Excerpt** — paste inline SVG markup here for the small icon.
- **Order** (under _Page Attributes_) — controls display order.
- **Custom fields:**
  - `_cmp_skill_percent` — 0–100, animated bar fill.
  - `_cmp_skill_meta` — short tag like "v18 · Redux Toolkit".
  - `_cmp_skill_levelling` — boolean; switches the bar from rust to green
    with a "still learning" stripe.

### Customizer

_Appearance → Customize → Cactusman portfolio_. Edit:

- **Hero** — top tag, headline, highlighted line, subtitle.
- **About** — body copy, class label, level, inventory (comma-separated).
- **Status** — menu-bar label and the about-panel status card body.
- **Contact** — email, phone, GitHub, LinkedIn, location.

### Blog

Standard WordPress posts. They're queried by `cmp_get_journal_posts()` for
the blog panel and listed on `/blog/` (or wherever your Posts page is).
Add a _Tech tag_ to display it next to the read-time.

---

## Customizing

- **Landmark positions** — filter `cmp_landmark_positions` to move them or
  add new ones. The same array is passed to JS via `wp_localize_script`.
- **Image sizes** — `cmp-quest-card` (600×400, hard-cropped) and
  `cmp-portfolio-hero` (1600×900) are registered in `inc/theme-setup.php`.
- **Asset paths** — `inc/enqueue.php` toggles between Vite dev server and
  the compiled `/assets/build/` files; the toggle is the `CMP_DEV` constant.
- **Colours / type** — all in `src/scss/_variables.scss` as CSS custom
  properties, so anything downstream can override them in a child theme.

---

## License

GPL-3.0-or-later. Same as the original repo.
