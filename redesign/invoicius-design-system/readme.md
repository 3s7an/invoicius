# Invoicius Design System

A design system reconstructed from the **Invoicius** product — a Slovak-language
invoicing web app (*fakturačná aplikácia*) for managing invoices and clients,
exporting PDFs, and running n8n-driven automations.

- **Product:** [invoicius.online](https://invoicius.online)
- **Source repo (input):** https://github.com/3s7an/invoicius
  Explore it to build higher-fidelity designs — the real screens, PrimeVue
  config, and Tailwind tokens all live there.
- **Stack it was lifted from:** Laravel 12 · Vue 3 · Inertia.js · Tailwind CSS ·
  **PrimeVue (Aura preset)** · Chart.js · PrimeIcons. Font: **Figtree**. Locale: **Slovak**.

The visual identity is the PrimeVue **Aura `emerald`** preset (the green top
nav) layered on a calm gray-100 canvas of white, hairline-bordered, softly
shadowed cards. It reads as a tidy, modern, no-nonsense SaaS dashboard.

---

## Content fundamentals

- **Language: Slovak.** All product copy is Slovak. Keep it that way for
  on-brand work (e.g. *Faktúry*, *Klienti*, *Prehľad*, *Nová faktúra*,
  *Čaká na úhradu*, *Po splatnosti*, *Uhradené*).
- **Casing: sentence case** everywhere — buttons, headings, menu items
  (*"Nová faktúra"*, *"Zobraziť všetky"*), never Title Case. Micro-labels above
  values are the one exception: UPPERCASE with wide tracking (*"VYTVORENÉ"*).
- **Voice is mostly formal (vykanie / "vy")** in primary UI: *"Zatiaľ nemáte
  žiadne faktúry."*, *"Nemáte účet?"*. A few secondary spots slip into informal
  *"ty"* (*"Zatiaľ nemáš žiadne sumy na rozdelenie."*) — prefer the **formal
  register** when writing new copy.
- **Tone:** plain, concise, reassuring. Short sentences. Empty states explain
  what's missing and offer the next action (*"Zatiaľ tu nie sú žiadne faktúry."*
  + a *"Vytvoriť faktúru"* button).
- **Numbers & money:** Slovak formatting — space as thousands separator, comma
  decimal, currency symbol **after** the amount with a space: `2 480,00 €`.
  Amounts are always `tabular-nums` and right-aligned in tables.
- **No emoji.** None appear in the product. Meaning is carried by PrimeIcons and
  the status color system instead.
- **Vibe:** professional small-business finance tool — trustworthy, efficient,
  unfussy. Not playful, not corporate-cold.

---

## Visual foundations

- **Color.** Primary is **emerald** (`--primary` #10b981, the Aura preset & nav
  bar; hover #059669, active #047857). Neutrals are the **Tailwind gray** ramp:
  `gray-100` app background, white cards, `gray-200` borders, `gray-900`
  headings/amounts, `gray-500` hints. Every invoice **status** carries its own
  semantic color used consistently across pills, dots and the dashboard donut:
  paid = cyan, awaiting = amber, overdue = red, sent = sky, draft = slate.
  Dashboard KPI icon chips use soft sky / violet / amber tints.
- **Type.** **Figtree** for everything. Headings are **semibold (600) with tight
  tracking** (`-0.02em`); page titles 24–30px. Body 14px, captions/labels 12px.
  Labels above fields are 12px uppercase with `0.05em` tracking. Amounts use
  tabular numerals.
- **Backgrounds.** Flat color only — `gray-100` app canvas, white surfaces.
  **No gradients, no imagery, no patterns or textures** in the app chrome. The
  only "art" is the Chart.js donut on the dashboard.
- **Cards.** The signature surface: **white, `rounded-xl` (12px), 1px
  `gray-200` border, `shadow-sm`.** Headers are divided with a `gray-100`
  hairline. This card recurs as stat tiles, panels, list rows and modals.
- **Corner radius.** `rounded-md` (6px) for buttons & inputs, `rounded-xl`
  (12px) for cards, `rounded-full` for status pills, dots and avatars.
- **Shadows.** The product is almost entirely **`shadow-sm`** (a 1px soft drop).
  Heavier shadows only for dropdowns/modals. No glows, no colored shadows.
- **Borders.** Hairline `gray-200` is the workhorse divider; `gray-100` for
  intra-card separators; inputs use `gray-300`.
- **Status pills.** PrimeVue `<Tag rounded>` — soft tinted background + darker
  text in the matching status hue, optionally with a leading dot.
- **Buttons.** Primary = solid emerald, white text, `shadow-sm`, semibold.
  Secondary = white + gray border. Icon-only ghost/text buttons (PDF, edit,
  delete) fill toolbars and table rows. Hover darkens the fill one step.
- **Focus.** Emerald ring (`box-shadow: 0 0 0 3px` of a translucent primary).
- **Motion.** Restrained. ~150–200ms ease transitions on hover/color; a gentle
  fade + translate-y on flash toasts. **No bounces, no decorative animation.**
- **Hover / press.** Hover = one step darker fill (primary) or a `gray-50` wash
  (secondary/ghost); links go from `gray-600` to `gray-900`. Press = the darkest
  step. No scaling.
- **Transparency / blur.** Barely used — only the modal scrim
  (`rgba(17,24,39,.45)`), no backdrop blur.
- **Layout.** Centered `max-w-7xl` (80rem) container, 24px gutters; fixed
  64px-tall emerald top nav; vertical rhythm in 24px stacks; responsive grids
  collapse to single column and to card lists on mobile.

---

## Iconography

- **PrimeIcons** is the icon system (`primeicons` package, classes like
  `pi pi-file`, `pi pi-users`, `pi pi-bolt`, `pi pi-plus`, `pi pi-pencil`,
  `pi pi-trash`, `pi pi-file-pdf`, `pi pi-play`, `pi pi-pause`,
  `pi pi-arrow-right`, `pi pi-inbox`, `pi pi-chevron-down`). It is an **icon
  font**, single-weight, lightly rounded line style.
  - In this design system, load it from CDN:
    `<link rel="stylesheet" href="https://unpkg.com/primeicons@7.0.0/primeicons.css">`
  - Components accept a PrimeIcons class via an `icon` prop (e.g.
    `<Button icon="pi-plus">`).
- **No emoji, no Unicode-glyph icons.** Status is communicated by color +
  PrimeIcons, not pictographs.
- **Logo:** the app draws its own mark inline — a stylized invoice/document
  sheet with lines — beside the *"Invoicius"* wordmark (Figtree semibold). It is
  recreated here in `assets/` (emerald and reversed-white variants). There is no
  bitmap logo in the source repo (the favicon is a placeholder), so these SVGs
  are faithful reconstructions of the in-code SVG.

---

## What's in here (manifest)

**Foundations**
- `styles.css` — the single entry point consumers link. `@import`s only.
- `tokens/` — `colors.css`, `typography.css`, `spacing.css`, `fonts.css`.
- `guidelines/*.card.html` — specimen cards (Colors, Type, Spacing, Brand).
- `assets/` — logo mark & wordmark (emerald + white variants).

**Components** (`window.InvoiciusDesignSystem_31395d.*`)
- Core — `Button`, `Card`, `Badge` (status pill) — `components/core/`
- Forms — `Input`, `Select`, `Checkbox` — `components/forms/`
- Data — `StatCard` — `components/data-display/`

Each directory has `<Name>.jsx` + `<Name>.d.ts` + `<Name>.prompt.md` and one
`@dsCard` HTML preview.

**UI kit**
- `ui_kits/invoicius/` — interactive recreation of the app (login → dashboard →
  invoices, change invoice status, create an invoice). `index.html` + `shell.jsx`
  + `app.jsx`. See its `README.md`.

**Other**
- `SKILL.md` — makes this folder usable as a downloadable Agent Skill.

---

## Using it

Link the stylesheet, the PrimeIcons CDN, React UMD, and the compiled bundle,
then read components off the namespace:

```html
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://unpkg.com/primeicons@7.0.0/primeicons.css">
<!-- React 18 UMD + Babel … -->
<script src="_ds_bundle.js"></script>
<script type="text/babel">
  const { Button, Card, Badge, StatCard } = window.InvoiciusDesignSystem_31395d;
</script>
```

> **Note on the bundle:** `_ds_bundle.js`, `_ds_manifest.json` and the adherence
> config are generated automatically by the compiler — don't edit them.
