import React from 'react';

/** Select field styled like PrimeVue <Select> — rounded-md, chevron, emerald focus. */
export function Select({
  label, id, value, options = [], placeholder = 'Vyberte…',
  disabled = false, error, hint, fullWidth = true, onChange, style, ...rest
}) {
  const [focus, setFocus] = React.useState(false);
  const selectId = id || (label ? `sel-${String(label).toLowerCase().replace(/\s+/g, '-')}` : undefined);
  const norm = options.map((o) => (typeof o === 'object' ? o : { value: o, label: o }));

  return (
    <div style={{ width: fullWidth ? '100%' : undefined, ...style }}>
      {label && (
        <label htmlFor={selectId} style={{
          display: 'block', marginBottom: '0.375rem',
          fontSize: 'var(--text-sm)', fontWeight: 'var(--weight-medium)', color: 'var(--text-body)',
        }}>{label}</label>
      )}
      <div style={{ position: 'relative' }}>
        <select
          id={selectId} value={value} disabled={disabled} onChange={onChange}
          onFocus={() => setFocus(true)} onBlur={() => setFocus(false)}
          style={{
            width: '100%', boxSizing: 'border-box', appearance: 'none',
            padding: '0.5rem 2.25rem 0.5rem 0.75rem',
            fontFamily: 'var(--font-sans)', fontSize: 'var(--text-sm)',
            color: value ? 'var(--text-strong)' : 'var(--text-faint)',
            background: disabled ? 'var(--surface-muted)' : 'var(--surface-card)',
            border: `1px solid ${error ? 'var(--danger)' : focus ? 'var(--primary)' : 'var(--border-strong)'}`,
            borderRadius: 'var(--radius-sm)', outline: 'none', cursor: disabled ? 'not-allowed' : 'pointer',
            boxShadow: focus ? `0 0 0 3px var(--focus-ring)` : 'var(--shadow-sm)',
            transition: 'border-color var(--duration-fast) var(--ease), box-shadow var(--duration-fast) var(--ease)',
          }}
          {...rest}
        >
          {placeholder && <option value="" disabled hidden>{placeholder}</option>}
          {norm.map((o) => <option key={o.value} value={o.value}>{o.label}</option>)}
        </select>
        <i className="pi pi-chevron-down" style={{ position: 'absolute', right: '0.75rem', top: '50%', transform: 'translateY(-50%)', fontSize: '0.75rem', color: 'var(--text-faint)', pointerEvents: 'none' }} aria-hidden="true" />
      </div>
      {error
        ? <p style={{ margin: '0.375rem 0 0', fontSize: 'var(--text-xs)', color: 'var(--danger-text)' }}>{error}</p>
        : hint && <p style={{ margin: '0.375rem 0 0', fontSize: 'var(--text-xs)', color: 'var(--text-muted)' }}>{hint}</p>}
    </div>
  );
}
