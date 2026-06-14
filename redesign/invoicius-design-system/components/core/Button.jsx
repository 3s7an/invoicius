import React from 'react';

/**
 * Invoicius button. Mirrors the PrimeVue Aura <Button> used across the
 * product (emerald primary) plus the Breeze-derived secondary/ghost styles.
 */
export function Button({
  children,
  variant = 'primary',
  size = 'md',
  icon,
  iconPos = 'left',
  loading = false,
  disabled = false,
  fullWidth = false,
  type = 'button',
  onClick,
  style,
  ...rest
}) {
  const sizes = {
    sm: { padding: '0.375rem 0.75rem', fontSize: '0.8125rem', gap: '0.375rem', icon: '0.8125rem' },
    md: { padding: '0.5rem 1rem',      fontSize: '0.875rem',  gap: '0.5rem',   icon: '0.875rem' },
    lg: { padding: '0.625rem 1.25rem', fontSize: '0.9375rem', gap: '0.5rem',   icon: '1rem' },
  };
  const s = sizes[size] || sizes.md;

  const variants = {
    primary: {
      background: 'var(--primary)', color: 'var(--primary-contrast)',
      border: '1px solid var(--primary)', boxShadow: 'var(--shadow-sm)',
    },
    secondary: {
      background: 'var(--surface-card)', color: 'var(--text-body)',
      border: '1px solid var(--border-strong)', boxShadow: 'var(--shadow-sm)',
    },
    ghost: {
      background: 'transparent', color: 'var(--primary-active)',
      border: '1px solid transparent', boxShadow: 'none',
    },
    danger: {
      background: 'var(--danger)', color: '#fff',
      border: '1px solid var(--danger)', boxShadow: 'var(--shadow-sm)',
    },
    text: {
      background: 'transparent', color: 'var(--text-muted)',
      border: '1px solid transparent', boxShadow: 'none',
    },
  };
  const v = variants[variant] || variants.primary;
  const isDisabled = disabled || loading;

  const iconEl = (name) =>
    name ? <i className={`pi ${loading ? 'pi-spinner pi-spin' : name}`} style={{ fontSize: s.icon }} aria-hidden="true" /> : null;

  return (
    <button
      type={type}
      disabled={isDisabled}
      onClick={onClick}
      style={{
        display: fullWidth ? 'flex' : 'inline-flex',
        width: fullWidth ? '100%' : undefined,
        alignItems: 'center', justifyContent: 'center', gap: s.gap,
        padding: s.padding, fontSize: s.fontSize,
        fontFamily: 'var(--font-sans)', fontWeight: 'var(--weight-semibold)',
        lineHeight: 1.2, letterSpacing: '0',
        borderRadius: 'var(--radius-sm)', cursor: isDisabled ? 'not-allowed' : 'pointer',
        opacity: isDisabled ? 0.5 : 1,
        transition: 'background var(--duration-fast) var(--ease), opacity var(--duration-fast) var(--ease)',
        ...v, ...style,
      }}
      onMouseEnter={(e) => { if (!isDisabled && variant === 'primary') e.currentTarget.style.background = 'var(--primary-hover)'; if (!isDisabled && (variant === 'ghost' || variant === 'text')) e.currentTarget.style.background = 'var(--surface-muted)'; if (!isDisabled && variant === 'secondary') e.currentTarget.style.background = 'var(--surface-muted)'; }}
      onMouseLeave={(e) => { e.currentTarget.style.background = v.background; }}
      {...rest}
    >
      {loading ? iconEl('pi-spinner') : (iconPos === 'left' && iconEl(icon))}
      {children && <span>{children}</span>}
      {!loading && iconPos === 'right' && iconEl(icon)}
    </button>
  );
}
