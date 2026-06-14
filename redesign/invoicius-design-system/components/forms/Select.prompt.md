Dropdown matching PrimeVue Select — used for invoice-status changes and form pickers.

```jsx
<Select label="Stav" value={status} options={[
  { value: 'paid', label: 'Uhradené' },
  { value: 'awaiting', label: 'Čaká na úhradu' },
]} onChange={e => setStatus(e.target.value)} />
```

Accepts `{value,label}` objects or bare strings.
