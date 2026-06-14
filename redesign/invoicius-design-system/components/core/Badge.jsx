import React from 'react';

/**
 * Status pill used for invoice states (PrimeVue <Tag rounded>) and other
 * labels. `status` maps to the Invoicius invoice-status palette; `tone`
 * gives generic soft badges.
 */
export function Badge({ children, status, tone, rounded = true, dot = false, style, ...rest }) {
  const statusMap = {
    paid:     { bg: 'var(--status-paid-soft)',     fg: 'var(--status-paid-text)',     dot: 'var(--status-paid)' },
    awaiting: { bg: 'var(--status-awaiting-soft)', fg: 'var(--status-awaiting-text)', dot: 'var(--status-awaiting)' },
    overdue:  { bg: 'var(--status-overdue-soft)',  fg: 'var(--status-overdue-text)',  dot: 'var(--status-overdue)' },
    draft:    { bg: 'var(--status-draft-soft)',    fg: 'var(--status-draft-text)',    dot: 'var(--status-draft)' },
    sent:     { bg: 'var(--status-sent-soft)',     fg: 'var(--status-sent-text)',     dot: 'var(--status-sent)' },
  };
  const toneMap = {
    success: { bg: 'var(--success-soft)', fg: 'var(--success-text)', dot: 'var(--success)' },
    danger:  { bg: 'var(--danger-soft)',  fg: 'var(--danger-text)',  dot: 'var(--danger)' },
    neutral: { bg: 'var(--gray-100)',     fg: 'var(--gray-600)',     dot: 'var(--gray-400)' },
    primary: { bg: 'var(--primary-soft)', fg: 'var(--primary-active)',dot: 'var(--primary)' },
  };
  const c = statusMap[status] || toneMap[tone] || toneMap.neutral;

  return (
    <span
      style={{
        display: 'inline-flex', alignItems: 'center', gap: '0.375rem',
        padding: '0.125rem 0.625rem',
        background: c.bg, color: c.fg,
        fontSize: 'var(--text-xs)', fontWeight: 'var(--weight-medium)',
        fontFamily: 'var(--font-sans)', lineHeight: 1.6,
        borderRadius: rounded ? 'var(--radius-full)' : 'var(--radius-sm)',
        whiteSpace: 'nowrap', ...style,
      }}
      {...rest}
    >
      {dot && <span style={{ width: 6, height: 6, borderRadius: '9999px', background: c.dot, flexShrink: 0 }} aria-hidden="true" />}
      {children}
    </span>
  );
}
