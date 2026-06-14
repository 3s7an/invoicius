Emerald action button mirroring the PrimeVue Aura buttons across Invoicius — use for any primary, secondary or destructive action.

```jsx
<Button icon="pi-plus">Nová faktúra</Button>
<Button variant="secondary">Zrušiť</Button>
<Button variant="text" icon="pi-pencil" />
```

Variants: `primary` (emerald), `secondary` (white + border), `ghost`, `danger`, `text`. Sizes `sm | md | lg`. Pass a PrimeIcons class to `icon` (load primeicons.css from CDN). `iconPos="right"` for trailing icons (e.g. "Zobraziť všetky →"), `loading` shows a spinner.
