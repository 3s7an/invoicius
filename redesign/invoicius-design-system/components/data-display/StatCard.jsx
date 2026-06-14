import React from 'react';

/**
 * Dashboard KPI card — label, big tabular value, hint and a soft tinted
 * icon chip. Mirrors DashboardHero stat cards (sky/violet/amber accents).
 */
export function StatCard({ label, value, hint, icon, accent = 'sky', style, ...rest }) {
  const accents = {
    sky:    { fg: 'var(--accent-sky)',    bg: 'var(--accent-sky-soft)' },
    violet: { fg: 'var(--accent-violet)', bg: 'var(--accent-violet-soft)' },
    amber:  { fg: 'var(--accent-amber)',  bg: 'var(--accent-amber-soft)' },
    emerald:{ fg: 'var(--primary-active)',bg: 'var(--primary-soft)' },
  };
  const a = accents[accent] || accents.sky;

  return (
    <div
      style={{
        background: 'var(--surface-card)', border: '1px solid var(--border)',
        borderRadius: 'var(--radius-lg)', boxShadow: 'var(--shadow-sm)',
        padding: '1.25rem', ...style,
      }}
      {...rest}
    >
      <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: '1rem' }}>
        <div style={{ minWidth: 0 }}>
          <p style={{ margin: 0, fontSize: 'var(--text-sm)', fontWeight: 'var(--weight-medium)', color: 'var(--text-body)' }}>{label}</p>
          <p style={{ margin: '0.5rem 0 0', fontSize: 'var(--text-2xl)', fontWeight: 'var(--weight-semibold)', color: 'var(--text-strong)', fontVariantNumeric: 'tabular-nums', lineHeight: 1.1 }}>{value}</p>
          {hint && <p style={{ margin: '0.25rem 0 0', fontSize: 'var(--text-xs)', color: 'var(--text-muted)' }}>{hint}</p>}
        </div>
        {icon && (
          <span style={{
            display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
            width: 44, height: 44, flexShrink: 0, borderRadius: 'var(--radius-lg)',
            background: a.bg, color: a.fg,
          }}>
            <i className={`pi ${icon}`} style={{ fontSize: '1.125rem' }} aria-hidden="true" />
          </span>
        )}
      </div>
    </div>
  );
}
