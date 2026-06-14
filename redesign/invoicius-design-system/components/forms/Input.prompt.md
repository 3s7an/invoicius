Labelled text field with hint and error states, matching PrimeVue InputText geometry (rounded-md, emerald focus ring).

```jsx
<Input label="E-mail" type="email" icon="pi-envelope" placeholder="meno@firma.sk" required />
<Input label="IČO" error="Toto pole je povinné." />
```

Pass `error` to show the red invalid state (replaces `hint`). `icon` adds a leading PrimeIcon.
