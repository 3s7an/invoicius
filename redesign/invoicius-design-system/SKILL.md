---
name: invoicius-design
description: Use this skill to generate well-branded interfaces and assets for Invoicius (a Slovak invoicing / fakturačná web app), either for production or throwaway prototypes/mocks/etc. Contains essential design guidelines, colors, type, fonts, assets, and UI kit components for prototyping.
user-invocable: true
---

Read the README.md file within this skill, and explore the other available files.

If creating visual artifacts (slides, mocks, throwaway prototypes, etc), copy assets out and create static HTML files for the user to view. If working on production code, you can copy assets and read the rules here to become an expert in designing with this brand.

If the user invokes this skill without any other guidance, ask them what they want to build or design, ask some questions, and act as an expert designer who outputs HTML artifacts _or_ production code, depending on the need.

Key facts to anchor on:
- **Brand color is emerald** (#10b981) on a gray-100 canvas of white, hairline-bordered, shadow-sm `rounded-xl` cards.
- **Font is Figtree**; headings semibold with tight tracking; sentence case copy.
- **Copy is Slovak and formal (vykanie)**; money formats as `2 480,00 €`; no emoji.
- **Icons are PrimeIcons** (`pi pi-*`), loaded from CDN; status is conveyed by the invoice-status color palette (paid cyan, awaiting amber, overdue red, sent sky, draft slate).
- Components live under `window.InvoiciusDesignSystem_31395d` (Button, Card, Badge, Input, Select, Checkbox, StatCard). Load `styles.css` + the PrimeIcons CDN + `_ds_bundle.js`.
- A full interactive app recreation is in `ui_kits/invoicius/`.
