The universal Invoicius surface — white, rounded-xl, hairline border, shadow-sm. Wrap any panel, KPI stat, or list block in it.

```jsx
<Card title="Posledné faktúry" actions={<Button variant="text" icon="pi-arrow-right" iconPos="right">Zobraziť všetky</Button>}>
  …content…
</Card>
```

Omit `title`/`actions` for a plain padded surface. Use `padding="none"` when the body is a full-bleed table or list.
