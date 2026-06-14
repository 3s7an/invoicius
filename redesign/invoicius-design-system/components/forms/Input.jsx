import React from 'react';

/**
 * Labelled text field. PrimeVue InputText geometry: rounded-md, gray border,
 * emerald focus ring. Includes label, hint and error slots.
 */
export function Input({
  label, id, type = 'text', value, placeholder, hint, error,
  required = false, disabled = false, icon, fullWidth = true,
  onChange, style, ...rest
}) {
  const [focus, setFocus] = React.useState(false);
  const inputId = id || (label ? `in-${String(label).toLowerCase().replace(/\s+/g, '-')}` : undefined);

  return (
    <div style={{ width: fullWidth ? '100%' : undefined, ...style }}>
      {label && (
        <label htmlFor={inputId} style={{
          display: 'block', marginBottom: '0.375rem',
          fontSize: 'var(--text-sm)', fontWeight: 'var(--weight-medium)', color: 'var(--text-body)',
        }}>
          {label}{required && <span style={{ color: 'var(--danger)', marginLeft: 2 }}>*</span>}
        </label>
      )}
      <div style={{ position: 'relative', display: 'flex', alignItems: 'center' }}>
        {icon && <i className={`pi ${icon}`} style={{ position: 'absolute', left: '0.75rem', fontSize: '0.875rem', color: 'var(--text-faint)' }} aria-hidden="true" />}
        <input
          id={inputId} type={type} value={value} placeholder={placeholder}
          required={required} disabled={disabled} onChange={onChange}
          onFocus={() => setFocus(true)} onBlur={() => setFocus(false)}
          style={{
            width: '100%', boxSizing: 'border-box',
            padding: icon ? '0.5rem 0.75rem 0.5rem 2.25rem' : '0.5rem 0.75rem',
            fontFamily: 'var(--font-sans)', fontSize: 'var(--text-sm)', color: 'var(--text-strong)',
            background: disabled ? 'var(--surface-muted)' : 'var(--surface-card)',
            border: `1px solid ${error ? 'var(--danger)' : focus ? 'var(--primary)' : 'var(--border-strong)'}`,
            borderRadius: 'var(--radius-sm)', outline: 'none',
            boxShadow: focus ? `0 0 0 3px ${error ? 'var(--danger-soft)' : 'var(--focus-ring)'}` : 'var(--shadow-sm)',
            transition: 'border-color var(--duration-fast) var(--ease), box-shadow var(--duration-fast) var(--ease)',
          }}
          {...rest}
        />
      </div>
      {error
        ? <p style={{ margin: '0.375rem 0 0', fontSize: 'var(--text-xs)', color: 'var(--danger-text)' }}>{error}</p>
        : hint && <p style={{ margin: '0.375rem 0 0', fontSize: 'var(--text-xs)', color: 'var(--text-muted)' }}>{hint}</p>}
    </div>
  );
}
