Soft status pill — the rounded invoice-state Tag shown in the invoice table, dashboard and card lists.

```jsx
<Badge status="paid">Uhradené</Badge>
<Badge status="overdue">Po splatnosti</Badge>
<Badge tone="success" dot>Aktívne</Badge>
```

`status` uses the invoice palette (`paid` cyan, `awaiting` amber, `overdue` red, `draft` slate, `sent` sky). For non-invoice labels use `tone` (`success | danger | neutral | primary`). `dot` adds a leading colour dot.
