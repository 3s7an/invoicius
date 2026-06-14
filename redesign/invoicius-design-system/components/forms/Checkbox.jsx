import React from 'react';

/** Checkbox with optional inline label. Emerald check to match Aura. */
export function Checkbox({ checked = false, label, disabled = false, onChange, id, style, ...rest }) {
  const boxId = id || (label ? `cb-${String(label).toLowerCase().replace(/\s+/g, '-')}` : undefined);
  return (
    <label htmlFor={boxId} style={{
      display: 'inline-flex', alignItems: 'center', gap: '0.5rem',
      cursor: disabled ? 'not-allowed' : 'pointer', opacity: disabled ? 0.5 : 1, ...style,
    }}>
      <span style={{
        position: 'relative', width: 18, height: 18, flexShrink: 0,
        borderRadius: '0.25rem',
        border: `1px solid ${checked ? 'var(--primary)' : 'var(--border-strong)'}`,
        background: checked ? 'var(--primary)' : 'var(--surface-card)',
        boxShadow: 'var(--shadow-sm)',
        transition: 'background var(--duration-fast) var(--ease), border-color var(--duration-fast) var(--ease)',
        display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
      }}>
        <input
          id={boxId} type="checkbox" checked={checked} disabled={disabled} onChange={onChange}
          style={{ position: 'absolute', opacity: 0, width: '100%', height: '100%', margin: 0, cursor: 'inherit' }}
          {...rest}
        />
        {checked && <i className="pi pi-check" style={{ fontSize: 11, color: '#fff', fontWeight: 700 }} aria-hidden="true" />}
      </span>
      {label && <span style={{ fontSize: 'var(--text-sm)', color: 'var(--text-body)', fontFamily: 'var(--font-sans)' }}>{label}</span>}
    </label>
  );
}
