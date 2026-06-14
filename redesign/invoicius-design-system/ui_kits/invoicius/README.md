# Invoicius — UI kit

Interactive, high-fidelity recreation of the Invoicius app, composed entirely
from the design-system primitives.

## Flow

1. **Login** — `test@example.com` / `password` are pre-filled; click *Prihlásiť
   sa* to enter.
2. **Prehľad (Dashboard)** — KPI stat tiles + an invoice-status donut with a
   legend. *Nová faktúra* opens the create modal.
3. **Faktúry (Invoices)** — a data table; change any invoice's status inline via
   the `Select`, or use the row PDF / edit / delete actions (they flash a toast).
   Creating an invoice prepends it and jumps you here.
4. **Klienti / Automatizácie / Profil** — intentionally stubbed (the source for
   these screens wasn't reconstructed in depth); shown as labelled empty states.

## Files

- `index.html` — entry; loads React, PrimeIcons, the DS bundle, then the scripts.
- `shell.jsx` — emerald top nav, page header, toast, status→Badge mapping.
- `app.jsx` — Login, Dashboard, Invoices, the new-invoice modal, and the App
  root that holds the fake state.

## Notes

- This is a **cosmetic recreation**, not production code — the original uses
  PrimeVue Vue components, Inertia routing and a Laravel backend. The donut is a
  CSS `conic-gradient` standing in for the Chart.js doughnut.
- All primitives come from `window.InvoiciusDesignSystem_31395d` so the kit stays
  in lock-step with the system.
