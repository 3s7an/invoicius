import React from 'react';

/**
 * The Invoicius surface primitive: white, rounded-xl, hairline border,
 * shadow-sm. Used for stat cards, panels, list items everywhere.
 */
export function Card({ children, title, subtitle, actions, padding = 'md', style, ...rest }) {
  const pads = { none: '0', sm: '1rem', md: '1.25rem', lg: '1.5rem' };
  const p = pads[padding] ?? pads.md;
  const hasHeader = title || actions;

  return (
    <div
      style={{
        background: 'var(--surface-card)',
        border: '1px solid var(--border)',
        borderRadius: 'var(--radius-lg)',
        boxShadow: 'var(--shadow-sm)',
        overflow: 'hidden',
        ...style,
      }}
      {...rest}
    >
      {hasHeader && (
        <div style={{
          display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between',
          gap: '0.75rem', padding: `1rem ${p === '0' ? '1.25rem' : p}`,
          borderBottom: '1px solid var(--border-subtle)',
        }}>
          <div style={{ minWidth: 0 }}>
            {title && <h3 style={{ margin: 0, fontSize: 'var(--text-sm)', fontWeight: 'var(--weight-semibold)', color: 'var(--text-strong)' }}>{title}</h3>}
            {subtitle && <p style={{ margin: '0.25rem 0 0', fontSize: 'var(--text-sm)', color: 'var(--text-muted)' }}>{subtitle}</p>}
          </div>
          {actions && <div style={{ flexShrink: 0 }}>{actions}</div>}
        </div>
      )}
      <div style={{ padding: p }}>{children}</div>
    </div>
  );
}
