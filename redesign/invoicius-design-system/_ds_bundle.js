/* @ds-bundle: {"format":3,"namespace":"InvoiciusDesignSystem_31395d","components":[{"name":"Badge","sourcePath":"components/core/Badge.jsx"},{"name":"Button","sourcePath":"components/core/Button.jsx"},{"name":"Card","sourcePath":"components/core/Card.jsx"},{"name":"StatCard","sourcePath":"components/data-display/StatCard.jsx"},{"name":"Checkbox","sourcePath":"components/forms/Checkbox.jsx"},{"name":"Input","sourcePath":"components/forms/Input.jsx"},{"name":"Select","sourcePath":"components/forms/Select.jsx"}],"sourceHashes":{"components/core/Badge.jsx":"5813279d6ca8","components/core/Button.jsx":"76ea645dacfb","components/core/Card.jsx":"5306cb820d7c","components/data-display/StatCard.jsx":"3f87ef666b38","components/forms/Checkbox.jsx":"d3f3e5b70d65","components/forms/Input.jsx":"be86738ee696","components/forms/Select.jsx":"dae324e8bbc9","shell.jsx":"9ea6b673a42d","ui_kits/invoicius/app.jsx":"6ac0b1b1a238","ui_kits/invoicius/forms.jsx":"4dc16de5e4b4","ui_kits/invoicius/responsive.jsx":"d7e8e9dddd6c","ui_kits/invoicius/screens.jsx":"cdccfb846be7","ui_kits/invoicius/shell.jsx":"acc584d62f33"},"inlinedExternals":[],"unexposedExports":[]} */

(() => {

const __ds_ns = (window.InvoiciusDesignSystem_31395d = window.InvoiciusDesignSystem_31395d || {});

const __ds_scope = {};

(__ds_ns.__errors = __ds_ns.__errors || []);

// components/core/Badge.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/**
 * Status pill used for invoice states (PrimeVue <Tag rounded>) and other
 * labels. `status` maps to the Invoicius invoice-status palette; `tone`
 * gives generic soft badges.
 */
function Badge({
  children,
  status,
  tone,
  rounded = true,
  dot = false,
  style,
  ...rest
}) {
  const statusMap = {
    paid: {
      bg: 'var(--status-paid-soft)',
      fg: 'var(--status-paid-text)',
      dot: 'var(--status-paid)'
    },
    awaiting: {
      bg: 'var(--status-awaiting-soft)',
      fg: 'var(--status-awaiting-text)',
      dot: 'var(--status-awaiting)'
    },
    overdue: {
      bg: 'var(--status-overdue-soft)',
      fg: 'var(--status-overdue-text)',
      dot: 'var(--status-overdue)'
    },
    draft: {
      bg: 'var(--status-draft-soft)',
      fg: 'var(--status-draft-text)',
      dot: 'var(--status-draft)'
    },
    sent: {
      bg: 'var(--status-sent-soft)',
      fg: 'var(--status-sent-text)',
      dot: 'var(--status-sent)'
    }
  };
  const toneMap = {
    success: {
      bg: 'var(--success-soft)',
      fg: 'var(--success-text)',
      dot: 'var(--success)'
    },
    danger: {
      bg: 'var(--danger-soft)',
      fg: 'var(--danger-text)',
      dot: 'var(--danger)'
    },
    neutral: {
      bg: 'var(--gray-100)',
      fg: 'var(--gray-600)',
      dot: 'var(--gray-400)'
    },
    primary: {
      bg: 'var(--primary-soft)',
      fg: 'var(--primary-active)',
      dot: 'var(--primary)'
    }
  };
  const c = statusMap[status] || toneMap[tone] || toneMap.neutral;
  return /*#__PURE__*/React.createElement("span", _extends({
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      gap: '0.375rem',
      padding: '0.125rem 0.625rem',
      background: c.bg,
      color: c.fg,
      fontSize: 'var(--text-xs)',
      fontWeight: 'var(--weight-medium)',
      fontFamily: 'var(--font-sans)',
      lineHeight: 1.6,
      borderRadius: rounded ? 'var(--radius-full)' : 'var(--radius-sm)',
      whiteSpace: 'nowrap',
      ...style
    }
  }, rest), dot && /*#__PURE__*/React.createElement("span", {
    style: {
      width: 6,
      height: 6,
      borderRadius: '9999px',
      background: c.dot,
      flexShrink: 0
    },
    "aria-hidden": "true"
  }), children);
}
Object.assign(__ds_scope, { Badge });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Badge.jsx", error: String((e && e.message) || e) }); }

// components/core/Button.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/**
 * Invoicius button. Mirrors the PrimeVue Aura <Button> used across the
 * product (emerald primary) plus the Breeze-derived secondary/ghost styles.
 */
function Button({
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
    sm: {
      padding: '0.375rem 0.75rem',
      fontSize: '0.8125rem',
      gap: '0.375rem',
      icon: '0.8125rem'
    },
    md: {
      padding: '0.5rem 1rem',
      fontSize: '0.875rem',
      gap: '0.5rem',
      icon: '0.875rem'
    },
    lg: {
      padding: '0.625rem 1.25rem',
      fontSize: '0.9375rem',
      gap: '0.5rem',
      icon: '1rem'
    }
  };
  const s = sizes[size] || sizes.md;
  const variants = {
    primary: {
      background: 'var(--primary)',
      color: 'var(--primary-contrast)',
      border: '1px solid var(--primary)',
      boxShadow: 'var(--shadow-sm)'
    },
    secondary: {
      background: 'var(--surface-card)',
      color: 'var(--text-body)',
      border: '1px solid var(--border-strong)',
      boxShadow: 'var(--shadow-sm)'
    },
    ghost: {
      background: 'transparent',
      color: 'var(--primary-active)',
      border: '1px solid transparent',
      boxShadow: 'none'
    },
    danger: {
      background: 'var(--danger)',
      color: '#fff',
      border: '1px solid var(--danger)',
      boxShadow: 'var(--shadow-sm)'
    },
    text: {
      background: 'transparent',
      color: 'var(--text-muted)',
      border: '1px solid transparent',
      boxShadow: 'none'
    }
  };
  const v = variants[variant] || variants.primary;
  const isDisabled = disabled || loading;
  const iconEl = name => name ? /*#__PURE__*/React.createElement("i", {
    className: `pi ${loading ? 'pi-spinner pi-spin' : name}`,
    style: {
      fontSize: s.icon
    },
    "aria-hidden": "true"
  }) : null;
  return /*#__PURE__*/React.createElement("button", _extends({
    type: type,
    disabled: isDisabled,
    onClick: onClick,
    style: {
      display: fullWidth ? 'flex' : 'inline-flex',
      width: fullWidth ? '100%' : undefined,
      alignItems: 'center',
      justifyContent: 'center',
      gap: s.gap,
      padding: s.padding,
      fontSize: s.fontSize,
      fontFamily: 'var(--font-sans)',
      fontWeight: 'var(--weight-semibold)',
      lineHeight: 1.2,
      letterSpacing: '0',
      borderRadius: 'var(--radius-sm)',
      cursor: isDisabled ? 'not-allowed' : 'pointer',
      opacity: isDisabled ? 0.5 : 1,
      transition: 'background var(--duration-fast) var(--ease), opacity var(--duration-fast) var(--ease)',
      ...v,
      ...style
    },
    onMouseEnter: e => {
      if (!isDisabled && variant === 'primary') e.currentTarget.style.background = 'var(--primary-hover)';
      if (!isDisabled && (variant === 'ghost' || variant === 'text')) e.currentTarget.style.background = 'var(--surface-muted)';
      if (!isDisabled && variant === 'secondary') e.currentTarget.style.background = 'var(--surface-muted)';
    },
    onMouseLeave: e => {
      e.currentTarget.style.background = v.background;
    }
  }, rest), loading ? iconEl('pi-spinner') : iconPos === 'left' && iconEl(icon), children && /*#__PURE__*/React.createElement("span", null, children), !loading && iconPos === 'right' && iconEl(icon));
}
Object.assign(__ds_scope, { Button });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Button.jsx", error: String((e && e.message) || e) }); }

// components/core/Card.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/**
 * The Invoicius surface primitive: white, rounded-xl, hairline border,
 * shadow-sm. Used for stat cards, panels, list items everywhere.
 */
function Card({
  children,
  title,
  subtitle,
  actions,
  padding = 'md',
  style,
  ...rest
}) {
  const pads = {
    none: '0',
    sm: '1rem',
    md: '1.25rem',
    lg: '1.5rem'
  };
  const p = pads[padding] ?? pads.md;
  const hasHeader = title || actions;
  return /*#__PURE__*/React.createElement("div", _extends({
    style: {
      background: 'var(--surface-card)',
      border: '1px solid var(--border)',
      borderRadius: 'var(--radius-lg)',
      boxShadow: 'var(--shadow-sm)',
      overflow: 'hidden',
      ...style
    }
  }, rest), hasHeader && /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      alignItems: 'flex-start',
      justifyContent: 'space-between',
      gap: '0.75rem',
      padding: `1rem ${p === '0' ? '1.25rem' : p}`,
      borderBottom: '1px solid var(--border-subtle)'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      minWidth: 0
    }
  }, title && /*#__PURE__*/React.createElement("h3", {
    style: {
      margin: 0,
      fontSize: 'var(--text-sm)',
      fontWeight: 'var(--weight-semibold)',
      color: 'var(--text-strong)'
    }
  }, title), subtitle && /*#__PURE__*/React.createElement("p", {
    style: {
      margin: '0.25rem 0 0',
      fontSize: 'var(--text-sm)',
      color: 'var(--text-muted)'
    }
  }, subtitle)), actions && /*#__PURE__*/React.createElement("div", {
    style: {
      flexShrink: 0
    }
  }, actions)), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: p
    }
  }, children));
}
Object.assign(__ds_scope, { Card });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Card.jsx", error: String((e && e.message) || e) }); }

// components/data-display/StatCard.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/**
 * Dashboard KPI card — label, big tabular value, hint and a soft tinted
 * icon chip. Mirrors DashboardHero stat cards (sky/violet/amber accents).
 */
function StatCard({
  label,
  value,
  hint,
  icon,
  accent = 'sky',
  style,
  ...rest
}) {
  const accents = {
    sky: {
      fg: 'var(--accent-sky)',
      bg: 'var(--accent-sky-soft)'
    },
    violet: {
      fg: 'var(--accent-violet)',
      bg: 'var(--accent-violet-soft)'
    },
    amber: {
      fg: 'var(--accent-amber)',
      bg: 'var(--accent-amber-soft)'
    },
    emerald: {
      fg: 'var(--primary-active)',
      bg: 'var(--primary-soft)'
    }
  };
  const a = accents[accent] || accents.sky;
  return /*#__PURE__*/React.createElement("div", _extends({
    style: {
      background: 'var(--surface-card)',
      border: '1px solid var(--border)',
      borderRadius: 'var(--radius-lg)',
      boxShadow: 'var(--shadow-sm)',
      padding: '1.25rem',
      ...style
    }
  }, rest), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      alignItems: 'flex-start',
      justifyContent: 'space-between',
      gap: '1rem'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("p", {
    style: {
      margin: 0,
      fontSize: 'var(--text-sm)',
      fontWeight: 'var(--weight-medium)',
      color: 'var(--text-body)'
    }
  }, label), /*#__PURE__*/React.createElement("p", {
    style: {
      margin: '0.5rem 0 0',
      fontSize: 'var(--text-2xl)',
      fontWeight: 'var(--weight-semibold)',
      color: 'var(--text-strong)',
      fontVariantNumeric: 'tabular-nums',
      lineHeight: 1.1
    }
  }, value), hint && /*#__PURE__*/React.createElement("p", {
    style: {
      margin: '0.25rem 0 0',
      fontSize: 'var(--text-xs)',
      color: 'var(--text-muted)'
    }
  }, hint)), icon && /*#__PURE__*/React.createElement("span", {
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      justifyContent: 'center',
      width: 44,
      height: 44,
      flexShrink: 0,
      borderRadius: 'var(--radius-lg)',
      background: a.bg,
      color: a.fg
    }
  }, /*#__PURE__*/React.createElement("i", {
    className: `pi ${icon}`,
    style: {
      fontSize: '1.125rem'
    },
    "aria-hidden": "true"
  }))));
}
Object.assign(__ds_scope, { StatCard });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/data-display/StatCard.jsx", error: String((e && e.message) || e) }); }

// components/forms/Checkbox.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/** Checkbox with optional inline label. Emerald check to match Aura. */
function Checkbox({
  checked = false,
  label,
  disabled = false,
  onChange,
  id,
  style,
  ...rest
}) {
  const boxId = id || (label ? `cb-${String(label).toLowerCase().replace(/\s+/g, '-')}` : undefined);
  return /*#__PURE__*/React.createElement("label", {
    htmlFor: boxId,
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      gap: '0.5rem',
      cursor: disabled ? 'not-allowed' : 'pointer',
      opacity: disabled ? 0.5 : 1,
      ...style
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: 'relative',
      width: 18,
      height: 18,
      flexShrink: 0,
      borderRadius: '0.25rem',
      border: `1px solid ${checked ? 'var(--primary)' : 'var(--border-strong)'}`,
      background: checked ? 'var(--primary)' : 'var(--surface-card)',
      boxShadow: 'var(--shadow-sm)',
      transition: 'background var(--duration-fast) var(--ease), border-color var(--duration-fast) var(--ease)',
      display: 'inline-flex',
      alignItems: 'center',
      justifyContent: 'center'
    }
  }, /*#__PURE__*/React.createElement("input", _extends({
    id: boxId,
    type: "checkbox",
    checked: checked,
    disabled: disabled,
    onChange: onChange,
    style: {
      position: 'absolute',
      opacity: 0,
      width: '100%',
      height: '100%',
      margin: 0,
      cursor: 'inherit'
    }
  }, rest)), checked && /*#__PURE__*/React.createElement("i", {
    className: "pi pi-check",
    style: {
      fontSize: 11,
      color: '#fff',
      fontWeight: 700
    },
    "aria-hidden": "true"
  })), label && /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 'var(--text-sm)',
      color: 'var(--text-body)',
      fontFamily: 'var(--font-sans)'
    }
  }, label));
}
Object.assign(__ds_scope, { Checkbox });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Checkbox.jsx", error: String((e && e.message) || e) }); }

// components/forms/Input.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/**
 * Labelled text field. PrimeVue InputText geometry: rounded-md, gray border,
 * emerald focus ring. Includes label, hint and error slots.
 */
function Input({
  label,
  id,
  type = 'text',
  value,
  placeholder,
  hint,
  error,
  required = false,
  disabled = false,
  icon,
  fullWidth = true,
  onChange,
  style,
  ...rest
}) {
  const [focus, setFocus] = React.useState(false);
  const inputId = id || (label ? `in-${String(label).toLowerCase().replace(/\s+/g, '-')}` : undefined);
  return /*#__PURE__*/React.createElement("div", {
    style: {
      width: fullWidth ? '100%' : undefined,
      ...style
    }
  }, label && /*#__PURE__*/React.createElement("label", {
    htmlFor: inputId,
    style: {
      display: 'block',
      marginBottom: '0.375rem',
      fontSize: 'var(--text-sm)',
      fontWeight: 'var(--weight-medium)',
      color: 'var(--text-body)'
    }
  }, label, required && /*#__PURE__*/React.createElement("span", {
    style: {
      color: 'var(--danger)',
      marginLeft: 2
    }
  }, "*")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: 'relative',
      display: 'flex',
      alignItems: 'center'
    }
  }, icon && /*#__PURE__*/React.createElement("i", {
    className: `pi ${icon}`,
    style: {
      position: 'absolute',
      left: '0.75rem',
      fontSize: '0.875rem',
      color: 'var(--text-faint)'
    },
    "aria-hidden": "true"
  }), /*#__PURE__*/React.createElement("input", _extends({
    id: inputId,
    type: type,
    value: value,
    placeholder: placeholder,
    required: required,
    disabled: disabled,
    onChange: onChange,
    onFocus: () => setFocus(true),
    onBlur: () => setFocus(false),
    style: {
      width: '100%',
      boxSizing: 'border-box',
      padding: icon ? '0.5rem 0.75rem 0.5rem 2.25rem' : '0.5rem 0.75rem',
      fontFamily: 'var(--font-sans)',
      fontSize: 'var(--text-sm)',
      color: 'var(--text-strong)',
      background: disabled ? 'var(--surface-muted)' : 'var(--surface-card)',
      border: `1px solid ${error ? 'var(--danger)' : focus ? 'var(--primary)' : 'var(--border-strong)'}`,
      borderRadius: 'var(--radius-sm)',
      outline: 'none',
      boxShadow: focus ? `0 0 0 3px ${error ? 'var(--danger-soft)' : 'var(--focus-ring)'}` : 'var(--shadow-sm)',
      transition: 'border-color var(--duration-fast) var(--ease), box-shadow var(--duration-fast) var(--ease)'
    }
  }, rest))), error ? /*#__PURE__*/React.createElement("p", {
    style: {
      margin: '0.375rem 0 0',
      fontSize: 'var(--text-xs)',
      color: 'var(--danger-text)'
    }
  }, error) : hint && /*#__PURE__*/React.createElement("p", {
    style: {
      margin: '0.375rem 0 0',
      fontSize: 'var(--text-xs)',
      color: 'var(--text-muted)'
    }
  }, hint));
}
Object.assign(__ds_scope, { Input });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Input.jsx", error: String((e && e.message) || e) }); }

// components/forms/Select.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/** Select field styled like PrimeVue <Select> — rounded-md, chevron, emerald focus. */
function Select({
  label,
  id,
  value,
  options = [],
  placeholder = 'Vyberte…',
  disabled = false,
  error,
  hint,
  fullWidth = true,
  onChange,
  style,
  ...rest
}) {
  const [focus, setFocus] = React.useState(false);
  const selectId = id || (label ? `sel-${String(label).toLowerCase().replace(/\s+/g, '-')}` : undefined);
  const norm = options.map(o => typeof o === 'object' ? o : {
    value: o,
    label: o
  });
  return /*#__PURE__*/React.createElement("div", {
    style: {
      width: fullWidth ? '100%' : undefined,
      ...style
    }
  }, label && /*#__PURE__*/React.createElement("label", {
    htmlFor: selectId,
    style: {
      display: 'block',
      marginBottom: '0.375rem',
      fontSize: 'var(--text-sm)',
      fontWeight: 'var(--weight-medium)',
      color: 'var(--text-body)'
    }
  }, label), /*#__PURE__*/React.createElement("div", {
    style: {
      position: 'relative'
    }
  }, /*#__PURE__*/React.createElement("select", _extends({
    id: selectId,
    value: value,
    disabled: disabled,
    onChange: onChange,
    onFocus: () => setFocus(true),
    onBlur: () => setFocus(false),
    style: {
      width: '100%',
      boxSizing: 'border-box',
      appearance: 'none',
      padding: '0.5rem 2.25rem 0.5rem 0.75rem',
      fontFamily: 'var(--font-sans)',
      fontSize: 'var(--text-sm)',
      color: value ? 'var(--text-strong)' : 'var(--text-faint)',
      background: disabled ? 'var(--surface-muted)' : 'var(--surface-card)',
      border: `1px solid ${error ? 'var(--danger)' : focus ? 'var(--primary)' : 'var(--border-strong)'}`,
      borderRadius: 'var(--radius-sm)',
      outline: 'none',
      cursor: disabled ? 'not-allowed' : 'pointer',
      boxShadow: focus ? `0 0 0 3px var(--focus-ring)` : 'var(--shadow-sm)',
      transition: 'border-color var(--duration-fast) var(--ease), box-shadow var(--duration-fast) var(--ease)'
    }
  }, rest), placeholder && /*#__PURE__*/React.createElement("option", {
    value: "",
    disabled: true,
    hidden: true
  }, placeholder), norm.map(o => /*#__PURE__*/React.createElement("option", {
    key: o.value,
    value: o.value
  }, o.label))), /*#__PURE__*/React.createElement("i", {
    className: "pi pi-chevron-down",
    style: {
      position: 'absolute',
      right: '0.75rem',
      top: '50%',
      transform: 'translateY(-50%)',
      fontSize: '0.75rem',
      color: 'var(--text-faint)',
      pointerEvents: 'none'
    },
    "aria-hidden": "true"
  })), error ? /*#__PURE__*/React.createElement("p", {
    style: {
      margin: '0.375rem 0 0',
      fontSize: 'var(--text-xs)',
      color: 'var(--danger-text)'
    }
  }, error) : hint && /*#__PURE__*/React.createElement("p", {
    style: {
      margin: '0.375rem 0 0',
      fontSize: 'var(--text-xs)',
      color: 'var(--text-muted)'
    }
  }, hint));
}
Object.assign(__ds_scope, { Select });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Select.jsx", error: String((e && e.message) || e) }); }

// shell.jsx
try { (() => {
/* Invoicius UI kit — app shell (emerald top nav + page frame).
   Composes the design-system primitives from window.InvoiciusDesignSystem_31395d. */
(function () {
  const {
    Badge
  } = window.InvoiciusDesignSystem_31395d;
  function TopNav({
    current,
    onNavigate,
    user,
    onLogout
  }) {
    const items = [{
      key: 'dashboard',
      label: 'Prehľad'
    }, {
      key: 'invoices',
      label: 'Faktúry'
    }, {
      key: 'automatizations',
      label: 'Automatizácie'
    }];
    const [menu, setMenu] = React.useState(false);
    return /*#__PURE__*/React.createElement("nav", {
      style: {
        background: 'var(--surface-nav)',
        borderBottom: '1px solid rgba(0,0,0,.05)'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        maxWidth: 'var(--container-max)',
        margin: '0 auto',
        padding: '0 24px',
        height: 'var(--nav-height)',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 40
      }
    }, /*#__PURE__*/React.createElement("img", {
      src: "../../assets/logo-wordmark-white.svg",
      height: "26",
      alt: "Invoicius",
      style: {
        display: 'block',
        height: "46px"
      }
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        gap: 32
      }
    }, items.map(it => {
      const active = current === it.key;
      return /*#__PURE__*/React.createElement("button", {
        key: it.key,
        onClick: () => onNavigate(it.key),
        style: {
          background: 'none',
          border: 'none',
          cursor: 'pointer',
          padding: '0 0 2px',
          height: 'var(--nav-height)',
          fontFamily: 'var(--font-sans)',
          fontSize: 16,
          fontWeight: 600,
          color: active ? '#fff' : 'rgba(255,255,255,.85)',
          borderBottom: `2px solid ${active ? '#fff' : 'transparent'}`
        }
      }, it.label);
    }))), /*#__PURE__*/React.createElement("div", {
      style: {
        position: 'relative'
      }
    }, /*#__PURE__*/React.createElement("button", {
      onClick: () => setMenu(m => !m),
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 8,
        background: 'none',
        border: 'none',
        cursor: 'pointer',
        color: 'rgba(255,255,255,.9)',
        fontFamily: 'var(--font-sans)',
        fontSize: 14,
        fontWeight: 500
      }
    }, user.name, /*#__PURE__*/React.createElement("i", {
      className: "pi pi-chevron-down",
      style: {
        fontSize: 12
      }
    })), menu && /*#__PURE__*/React.createElement("div", {
      style: {
        position: 'absolute',
        right: 0,
        top: 'calc(100% + 6px)',
        background: '#fff',
        borderRadius: 'var(--radius-md)',
        boxShadow: 'var(--shadow-md)',
        border: '1px solid var(--border)',
        minWidth: 180,
        padding: 6,
        zIndex: 20
      }
    }, /*#__PURE__*/React.createElement("button", {
      onClick: () => {
        setMenu(false);
        onNavigate('profile');
      },
      style: menuItem
    }, "Profil"), /*#__PURE__*/React.createElement("button", {
      onClick: () => {
        setMenu(false);
        onLogout();
      },
      style: menuItem
    }, "Odhl\xE1si\u0165 sa")))));
  }
  const menuItem = {
    display: 'block',
    width: '100%',
    textAlign: 'left',
    background: 'none',
    border: 'none',
    cursor: 'pointer',
    padding: '8px 10px',
    borderRadius: 'var(--radius-sm)',
    fontFamily: 'var(--font-sans)',
    fontSize: 14,
    color: 'var(--text-body)'
  };
  function PageHeader({
    title,
    subtitle,
    children
  }) {
    return /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'flex-start',
        justifyContent: 'space-between',
        gap: 12,
        marginBottom: 16
      }
    }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h1", {
      style: {
        margin: 0,
        fontSize: 'var(--text-3xl)',
        fontWeight: 600,
        letterSpacing: 'var(--tracking-tight)',
        color: 'var(--text-strong)'
      }
    }, title), subtitle && /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '4px 0 0',
        fontSize: 'var(--text-sm)',
        color: 'var(--text-muted)'
      }
    }, subtitle)), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        gap: 8,
        flexShrink: 0
      }
    }, children));
  }
  function Toast({
    msg
  }) {
    if (!msg) return null;
    return /*#__PURE__*/React.createElement("div", {
      style: {
        maxWidth: 'var(--container-max)',
        margin: '16px auto 0',
        padding: '0 24px'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 8,
        background: 'var(--success-soft)',
        color: 'var(--success-text)',
        border: '1px solid var(--emerald-200)',
        borderRadius: 'var(--radius-md)',
        padding: '10px 14px',
        fontSize: 14,
        fontWeight: 500,
        boxShadow: 'var(--shadow-sm)'
      }
    }, /*#__PURE__*/React.createElement("i", {
      className: "pi pi-check-circle"
    }), " ", msg));
  }
  function statusToBadge(code) {
    const map = {
      paid: ['paid', 'Uhradené'],
      awaiting: ['awaiting', 'Čaká na úhradu'],
      overdue: ['overdue', 'Po splatnosti'],
      sent: ['sent', 'Odoslané'],
      draft: ['draft', 'Koncept']
    };
    const [s, label] = map[code] || ['draft', code];
    return /*#__PURE__*/React.createElement(Badge, {
      status: s
    }, label);
  }
  Object.assign(window, {
    InvNav: TopNav,
    InvPageHeader: PageHeader,
    InvToast: Toast,
    statusToBadge
  });
})();
})(); } catch (e) { __ds_ns.__errors.push({ path: "shell.jsx", error: String((e && e.message) || e) }); }

// ui_kits/invoicius/app.jsx
try { (() => {
/* Invoicius UI kit — app root + login. Screens live in screens.jsx, forms in forms.jsx. */
(function () {
  const {
    Button,
    Card,
    Input,
    Checkbox
  } = window.InvoiciusDesignSystem_31395d;
  const {
    InvSidebar,
    InvToast
  } = window;
  const {
    InvDashboard,
    InvInvoices,
    InvAutomations,
    InvProfile,
    InvRecipients,
    INV_SEED_INVOICES,
    INV_SEED_AUTOMATIONS,
    INV_SEED_RECIPIENTS,
    INV_TODAY
  } = window;
  const {
    InvInvoiceFormDrawer,
    InvRecipientFormModal,
    InvAutomatizationFormModal
  } = window;

  /* ---------- Login ---------- */
  function Login({
    onLogin
  }) {
    const [email, setEmail] = React.useState('test@example.com');
    const [pw, setPw] = React.useState('password');
    const [rem, setRem] = React.useState(true);
    return /*#__PURE__*/React.createElement("div", {
      style: {
        minHeight: '100%',
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
        background: 'var(--surface-app)',
        padding: 24,
        boxSizing: 'border-box'
      }
    }, /*#__PURE__*/React.createElement("img", {
      src: "../../assets/logo-wordmark.svg",
      height: "34",
      alt: "Invoicius",
      style: {
        marginBottom: 24
      }
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        width: '100%',
        maxWidth: 400
      }
    }, /*#__PURE__*/React.createElement(Card, {
      padding: "lg"
    }, /*#__PURE__*/React.createElement("h1", {
      style: {
        margin: '0 0 4px',
        fontSize: 'var(--text-xl)',
        fontWeight: 600,
        color: 'var(--text-strong)'
      }
    }, "Prihl\xE1senie"), /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '0 0 20px',
        fontSize: 14,
        color: 'var(--text-muted)'
      }
    }, "Vitajte sp\xE4\u0165. Zadajte svoje \xFAdaje."), /*#__PURE__*/React.createElement("form", {
      onSubmit: e => {
        e.preventDefault();
        onLogin();
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 16
      }
    }, /*#__PURE__*/React.createElement(Input, {
      label: "E-mail",
      type: "email",
      icon: "pi-envelope",
      value: email,
      onChange: e => setEmail(e.target.value)
    }), /*#__PURE__*/React.createElement(Input, {
      label: "Heslo",
      type: "password",
      icon: "pi-lock",
      value: pw,
      onChange: e => setPw(e.target.value)
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between'
      }
    }, /*#__PURE__*/React.createElement(Checkbox, {
      checked: rem,
      label: "Zapam\xE4ta\u0165 si ma",
      onChange: e => setRem(e.target.checked)
    }), /*#__PURE__*/React.createElement("a", {
      href: "#",
      onClick: e => e.preventDefault(),
      style: {
        fontSize: 13,
        color: 'var(--text-link)',
        textDecoration: 'underline'
      }
    }, "Zabudli ste heslo?")), /*#__PURE__*/React.createElement(Button, {
      type: "submit",
      fullWidth: true
    }, "Prihl\xE1si\u0165 sa")))), /*#__PURE__*/React.createElement("p", {
      style: {
        textAlign: 'center',
        marginTop: 16,
        fontSize: 13,
        color: 'var(--text-muted)'
      }
    }, "Nem\xE1te \xFA\u010Det? ", /*#__PURE__*/React.createElement("a", {
      href: "#",
      onClick: e => e.preventDefault(),
      style: {
        color: 'var(--primary-active)',
        fontWeight: 600,
        textDecoration: 'none'
      }
    }, "Zaregistrujte sa"))));
  }

  /* ---------- App root ---------- */
  function App() {
    const isMobile = window.useIsMobile();
    const [authed, setAuthed] = React.useState(false);
    const [page, setPage] = React.useState('dashboard');
    const [sidebarOpen, setSidebarOpen] = React.useState(false);
    const [invoices, setInvoices] = React.useState(INV_SEED_INVOICES);
    const [automations, setAutomations] = React.useState(INV_SEED_AUTOMATIONS);
    const [recipients, setRecipients] = React.useState(INV_SEED_RECIPIENTS);
    const [toast, setToast] = React.useState('');
    // activeModal: null | 'invoice' | 'recipient' | 'automation'
    const [activeModal, setActiveModal] = React.useState(null);
    const [seq, setSeq] = React.useState(43);
    const flash = m => {
      setToast(m);
      setTimeout(() => setToast(''), 2600);
    };
    const closeModal = () => setActiveModal(null);
    if (!authed) return /*#__PURE__*/React.createElement(Login, {
      onLogin: () => {
        setAuthed(true);
        setPage('dashboard');
      }
    });

    /* handlers */
    const setStatus = (id, status) => {
      setInvoices(inv => inv.map(x => x.id === id ? {
        ...x,
        status
      } : x));
      flash('Stav faktúry bol zmenený.');
    };
    const createInvoice = inv => {
      setInvoices(list => [{
        ...inv,
        id: Date.now()
      }, ...list]);
      setSeq(s => s + 1);
      closeModal();
      setPage('invoices');
      flash('Faktúra ' + inv.number + ' bola vytvorená.');
    };
    const createRecipient = rec => {
      setRecipients(list => [...list, rec]);
      closeModal();
      flash('Klient ' + rec.name + ' bol pridaný.');
    };
    const createAutomation = auto => {
      setAutomations(list => [...list, auto]);
      closeModal();
      flash('Automatizácia bola vytvorená.');
    };
    const toggleAuto = id => {
      setAutomations(a => a.map(x => x.id === id ? {
        ...x,
        active: !x.active
      } : x));
      flash('Stav automatizácie bol zmenený.');
    };
    const invoiceNumber = `2026-${String(seq).padStart(4, '0')}`;
    const navigate = p => {
      setPage(p);
      setSidebarOpen(false);
    };
    const MobileTopBar = window.InvMobileTopBar;
    return /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        minHeight: '100%',
        background: 'var(--surface-app)'
      }
    }, /*#__PURE__*/React.createElement(InvSidebar, {
      current: page,
      onNavigate: navigate,
      user: {
        name: 'Tristan Prekop'
      },
      onLogout: () => setAuthed(false),
      isMobile: isMobile,
      mobileOpen: sidebarOpen,
      onMobileClose: () => setSidebarOpen(false)
    }), isMobile && MobileTopBar && /*#__PURE__*/React.createElement(MobileTopBar, {
      onMenuOpen: () => setSidebarOpen(true),
      userName: "Tristan Prekop",
      onNavigate: navigate
    }), /*#__PURE__*/React.createElement("main", {
      style: {
        flex: 1,
        marginLeft: isMobile ? 0 : 248,
        padding: isMobile ? '72px 16px 24px' : '32px',
        minHeight: '100%',
        boxSizing: 'border-box'
      }
    }, /*#__PURE__*/React.createElement(InvToast, {
      msg: toast
    }), page === 'dashboard' && /*#__PURE__*/React.createElement(InvDashboard, {
      invoices: invoices,
      automations: automations,
      userName: "Prekop Studio",
      onNew: () => setActiveModal('invoice'),
      onNavigate: setPage
    }), page === 'invoices' && /*#__PURE__*/React.createElement(InvInvoices, {
      invoices: invoices,
      onStatus: setStatus,
      onNew: () => setActiveModal('invoice'),
      onToast: flash
    }), page === 'recipients' && /*#__PURE__*/React.createElement(InvRecipients, {
      recipients: recipients,
      onToast: flash,
      onNewRecipient: () => setActiveModal('recipient')
    }), page === 'automatizations' && /*#__PURE__*/React.createElement(InvAutomations, {
      automations: automations,
      onToggle: toggleAuto,
      onToast: flash,
      onNewAutomation: () => setActiveModal('automation')
    }), page === 'profile' && /*#__PURE__*/React.createElement(InvProfile, {
      onToast: flash
    })), activeModal === 'invoice' && /*#__PURE__*/React.createElement(InvInvoiceFormDrawer, {
      onClose: closeModal,
      onCreate: createInvoice,
      recipients: recipients,
      invoiceNumber: invoiceNumber
    }), activeModal === 'recipient' && /*#__PURE__*/React.createElement(InvRecipientFormModal, {
      onClose: closeModal,
      onCreate: createRecipient
    }), activeModal === 'automation' && /*#__PURE__*/React.createElement(InvAutomatizationFormModal, {
      onClose: closeModal,
      onCreate: createAutomation,
      recipients: recipients
    }));
  }
  ReactDOM.createRoot(document.getElementById('root')).render(/*#__PURE__*/React.createElement(App, null));
})();
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/invoicius/app.jsx", error: String((e && e.message) || e) }); }

// ui_kits/invoicius/forms.jsx
try { (() => {
/* Invoicius UI kit — forms.jsx
   Three fully-styled forms faithful to the repo's field structure:
   · InvoiceFormDrawer  (right-side drawer, line-items table, totals)
   · RecipientFormModal (centered modal, 2-col grid)
   · AutomatizationFormModal (centered modal, radio-card type picker) */
(function () {
  const {
    Button,
    Input,
    Select,
    Checkbox
  } = window.InvoiciusDesignSystem_31395d;
  const fmt = n => n.toLocaleString('sk-SK', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });

  /* ── shared primitives ─────────────────────────────────────────── */
  function Scrim({
    onClose
  }) {
    return /*#__PURE__*/React.createElement("div", {
      onClick: onClose,
      style: {
        position: 'fixed',
        inset: 0,
        background: 'rgba(17,24,39,.45)',
        zIndex: 49
      }
    });
  }
  function SectionHead({
    children
  }) {
    return /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 10,
        margin: '22px 0 14px'
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 11,
        fontWeight: 600,
        textTransform: 'uppercase',
        letterSpacing: '0.08em',
        color: 'var(--text-muted)',
        whiteSpace: 'nowrap'
      }
    }, children), /*#__PURE__*/React.createElement("span", {
      style: {
        flex: 1,
        height: 1,
        background: 'var(--border)'
      }
    }));
  }
  function TextArea({
    label,
    value,
    onChange,
    placeholder,
    hint,
    rows = 3,
    style
  }) {
    const [focus, setFocus] = React.useState(false);
    return /*#__PURE__*/React.createElement("div", {
      style: style
    }, label && /*#__PURE__*/React.createElement("label", {
      style: {
        display: 'block',
        marginBottom: '0.375rem',
        fontSize: 'var(--text-sm)',
        fontWeight: 'var(--weight-medium)',
        color: 'var(--text-body)'
      }
    }, label), /*#__PURE__*/React.createElement("textarea", {
      value: value,
      onChange: onChange,
      placeholder: placeholder,
      rows: rows,
      onFocus: () => setFocus(true),
      onBlur: () => setFocus(false),
      style: {
        width: '100%',
        boxSizing: 'border-box',
        padding: '0.5rem 0.75rem',
        fontFamily: 'var(--font-sans)',
        fontSize: 'var(--text-sm)',
        color: 'var(--text-strong)',
        background: 'var(--surface-card)',
        border: `1px solid ${focus ? 'var(--primary)' : 'var(--border-strong)'}`,
        borderRadius: 'var(--radius-sm)',
        outline: 'none',
        boxShadow: focus ? '0 0 0 3px var(--focus-ring)' : 'var(--shadow-sm)',
        resize: 'vertical',
        transition: 'border-color var(--duration-fast) var(--ease)'
      }
    }), hint && /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '0.375rem 0 0',
        fontSize: 'var(--text-xs)',
        color: 'var(--text-muted)'
      }
    }, hint));
  }

  /* Right-side drawer */
  function DrawerShell({
    title,
    badge,
    onClose,
    footer,
    children
  }) {
    return /*#__PURE__*/React.createElement("div", {
      style: {
        position: 'fixed',
        right: 0,
        top: 0,
        bottom: 0,
        width: 'min(96vw, 920px)',
        background: 'var(--surface-card)',
        boxShadow: '-4px 0 24px rgba(0,0,0,.12)',
        zIndex: 50,
        display: 'flex',
        flexDirection: 'column'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 12,
        padding: '16px 28px',
        borderBottom: '1px solid var(--border)',
        flexShrink: 0
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1,
        display: 'flex',
        alignItems: 'center',
        gap: 10,
        minWidth: 0
      }
    }, /*#__PURE__*/React.createElement("h2", {
      style: {
        margin: 0,
        fontSize: 'var(--text-xl)',
        fontWeight: 600,
        color: 'var(--text-strong)'
      }
    }, title), badge && /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        color: 'var(--text-muted)',
        fontVariantNumeric: 'tabular-nums'
      }
    }, badge)), /*#__PURE__*/React.createElement("button", {
      onClick: onClose,
      style: {
        background: 'none',
        border: 'none',
        cursor: 'pointer',
        color: 'var(--text-faint)',
        fontSize: 20,
        display: 'flex',
        alignItems: 'center',
        padding: 4,
        borderRadius: 'var(--radius-sm)',
        flexShrink: 0
      }
    }, /*#__PURE__*/React.createElement("i", {
      className: "pi pi-times"
    }))), /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1,
        overflowY: 'auto',
        padding: '4px 28px 28px'
      }
    }, children), /*#__PURE__*/React.createElement("div", {
      style: {
        padding: '14px 28px',
        borderTop: '1px solid var(--border)',
        flexShrink: 0,
        background: 'var(--surface-muted)'
      }
    }, footer));
  }

  /* Centered modal */
  function ModalShell({
    title,
    subtitle,
    onClose,
    footer,
    children,
    maxWidth = 560
  }) {
    return /*#__PURE__*/React.createElement("div", {
      onClick: onClose,
      style: {
        position: 'fixed',
        inset: 0,
        background: 'rgba(17,24,39,.45)',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        zIndex: 50,
        padding: 24
      }
    }, /*#__PURE__*/React.createElement("div", {
      onClick: e => e.stopPropagation(),
      style: {
        background: 'var(--surface-card)',
        border: '1px solid var(--border)',
        borderRadius: 'var(--radius-lg)',
        boxShadow: 'var(--shadow-lg)',
        width: '100%',
        maxWidth,
        display: 'flex',
        flexDirection: 'column',
        maxHeight: '92vh'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'flex-start',
        gap: 12,
        padding: '16px 24px',
        borderBottom: '1px solid var(--border)',
        flexShrink: 0
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1
      }
    }, /*#__PURE__*/React.createElement("h2", {
      style: {
        margin: 0,
        fontSize: 'var(--text-xl)',
        fontWeight: 600,
        color: 'var(--text-strong)'
      }
    }, title), subtitle && /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '3px 0 0',
        fontSize: 13,
        color: 'var(--text-muted)'
      }
    }, subtitle)), /*#__PURE__*/React.createElement("button", {
      onClick: onClose,
      style: {
        background: 'none',
        border: 'none',
        cursor: 'pointer',
        color: 'var(--text-faint)',
        fontSize: 20,
        marginTop: 2
      }
    }, /*#__PURE__*/React.createElement("i", {
      className: "pi pi-times"
    }))), /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1,
        overflowY: 'auto',
        padding: '4px 24px 20px'
      }
    }, children), /*#__PURE__*/React.createElement("div", {
      style: {
        padding: '14px 24px',
        borderTop: '1px solid var(--border)',
        flexShrink: 0,
        background: 'var(--surface-muted)'
      }
    }, footer)));
  }

  /* ── INVOICE FORM ───────────────────────────────────────────────── */
  function InvoiceFormDrawer({
    onClose,
    onCreate,
    recipients,
    invoiceNumber
  }) {
    const [form, setForm] = React.useState({
      number: invoiceNumber || '2026-0043',
      issue_date: '2026-06-14',
      tax_point_date: '2026-06-14',
      due_date: '2026-06-28',
      status: 'draft',
      recipient_id: '',
      payment_method: 'bank_transfer',
      note: ''
    });
    const [items, setItems] = React.useState([{
      id: 1,
      description: '',
      quantity: '1',
      unit: 'ks',
      unit_price: '',
      vat_rate: '20'
    }]);
    const set = (k, v) => setForm(f => ({
      ...f,
      [k]: v
    }));
    const addItem = () => setItems(i => [...i, {
      id: Date.now(),
      description: '',
      quantity: '1',
      unit: 'ks',
      unit_price: '',
      vat_rate: '20'
    }]);
    const removeItem = id => setItems(i => i.filter(x => x.id !== id));
    const setItem = (id, k, v) => setItems(i => i.map(x => x.id === id ? {
      ...x,
      [k]: v
    } : x));
    const totals = items.reduce((acc, it) => {
      const base = (parseFloat(it.quantity) || 0) * (parseFloat(it.unit_price) || 0);
      const vatAmt = base * (parseFloat(it.vat_rate) || 0) / 100;
      return {
        base: acc.base + base,
        vat: acc.vat + vatAmt,
        total: acc.total + base + vatAmt
      };
    }, {
      base: 0,
      vat: 0,
      total: 0
    });
    const recipientOpts = [{
      value: '',
      label: '— Vybrať klienta —'
    }, ...(recipients || []).map(r => ({
      value: String(r.id),
      label: r.name
    }))];
    const statusOpts = [{
      value: 'draft',
      label: 'Koncept'
    }, {
      value: 'sent',
      label: 'Odoslané'
    }, {
      value: 'awaiting',
      label: 'Čaká na úhradu'
    }, {
      value: 'paid',
      label: 'Uhradené'
    }];
    const unitOpts = ['ks', 'hod', 'deň', 'm', 'm²', 'kg'].map(v => ({
      value: v,
      label: v
    }));
    const vatOpts = [{
      value: '20',
      label: '20 %'
    }, {
      value: '10',
      label: '10 %'
    }, {
      value: '0',
      label: '0 %'
    }];
    const payOpts = [{
      value: 'bank_transfer',
      label: 'Bankový prevod'
    }, {
      value: 'cash',
      label: 'Hotovosť'
    }, {
      value: 'card',
      label: 'Kartou'
    }];
    const handleCreate = status => {
      const rec = (recipients || []).find(r => String(r.id) === String(form.recipient_id));
      const d = form.issue_date.split('-');
      onCreate({
        number: form.number,
        recipient: rec ? rec.name : '—',
        date: `${d[2]}. ${d[1]}. ${d[0]}`,
        amount: totals.total,
        status: status || form.status
      });
    };
    const COL = '2fr 72px 96px 118px 84px 104px 36px';
    const TH_STYLE = {
      fontSize: 11,
      fontWeight: 600,
      color: 'var(--text-muted)',
      textTransform: 'uppercase',
      letterSpacing: '0.06em',
      display: 'block',
      paddingBottom: 6
    };
    return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(Scrim, {
      onClose: onClose
    }), /*#__PURE__*/React.createElement(DrawerShell, {
      title: "Nov\xE1 fakt\xFAra",
      badge: form.number,
      onClose: onClose,
      footer: /*#__PURE__*/React.createElement("div", {
        style: {
          display: 'flex',
          justifyContent: 'flex-end',
          gap: 8
        }
      }, /*#__PURE__*/React.createElement(Button, {
        variant: "secondary",
        onClick: onClose
      }, "Zru\u0161i\u0165"), /*#__PURE__*/React.createElement(Button, {
        variant: "secondary",
        icon: "pi-save",
        onClick: () => handleCreate('draft')
      }, "Ulo\u017Ei\u0165 ako koncept"), /*#__PURE__*/React.createElement(Button, {
        icon: "pi-check",
        onClick: () => handleCreate(form.status)
      }, "Vytvori\u0165 fakt\xFAru"))
    }, /*#__PURE__*/React.createElement(SectionHead, null, "Z\xE1kladn\xE9 \xFAdaje"), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: '1fr 1fr',
        gap: 16
      }
    }, /*#__PURE__*/React.createElement(Input, {
      label: "\u010C\xEDslo fakt\xFAry",
      value: form.number,
      disabled: true,
      onChange: () => {},
      hint: "Generuje sa automaticky"
    }), /*#__PURE__*/React.createElement(Select, {
      label: "Stav",
      value: form.status,
      options: statusOpts,
      onChange: e => set('status', e.target.value)
    }), /*#__PURE__*/React.createElement(Input, {
      label: "D\xE1tum vystavenia",
      type: "date",
      value: form.issue_date,
      onChange: e => set('issue_date', e.target.value)
    }), /*#__PURE__*/React.createElement(Input, {
      label: "D\xE1tum dodania",
      type: "date",
      value: form.tax_point_date,
      onChange: e => set('tax_point_date', e.target.value)
    }), /*#__PURE__*/React.createElement(Input, {
      label: "D\xE1tum splatnosti",
      type: "date",
      value: form.due_date,
      onChange: e => set('due_date', e.target.value)
    }), /*#__PURE__*/React.createElement(Select, {
      label: "Sp\xF4sob \xFAhrady",
      value: form.payment_method,
      options: payOpts,
      onChange: e => set('payment_method', e.target.value)
    })), /*#__PURE__*/React.createElement("div", {
      style: {
        marginTop: 16
      }
    }, /*#__PURE__*/React.createElement(Select, {
      label: "Klient",
      value: form.recipient_id,
      options: recipientOpts,
      onChange: e => set('recipient_id', e.target.value)
    })), /*#__PURE__*/React.createElement(SectionHead, null, "Polo\u017Eky fakt\xFAry"), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: COL,
        gap: 8,
        marginBottom: 8
      }
    }, ['Popis položky', 'Mn.', 'Jednotka', 'Cena / j.', 'DPH', 'Spolu', ''].map((h, i) => /*#__PURE__*/React.createElement("span", {
      key: i,
      style: {
        ...TH_STYLE,
        textAlign: i >= 3 && i <= 5 ? 'right' : 'left'
      }
    }, h))), items.map(item => {
      const gross = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0) * (1 + (parseFloat(item.vat_rate) || 0) / 100);
      return /*#__PURE__*/React.createElement("div", {
        key: item.id,
        style: {
          display: 'grid',
          gridTemplateColumns: COL,
          gap: 8,
          marginBottom: 10,
          alignItems: 'start'
        }
      }, /*#__PURE__*/React.createElement(Input, {
        value: item.description,
        placeholder: "Popis polo\u017Eky",
        onChange: e => setItem(item.id, 'description', e.target.value)
      }), /*#__PURE__*/React.createElement(Input, {
        value: item.quantity,
        placeholder: "1",
        onChange: e => setItem(item.id, 'quantity', e.target.value)
      }), /*#__PURE__*/React.createElement(Select, {
        value: item.unit,
        options: unitOpts,
        onChange: e => setItem(item.id, 'unit', e.target.value)
      }), /*#__PURE__*/React.createElement(Input, {
        value: item.unit_price,
        placeholder: "0,00",
        onChange: e => setItem(item.id, 'unit_price', e.target.value)
      }), /*#__PURE__*/React.createElement(Select, {
        value: item.vat_rate,
        options: vatOpts,
        onChange: e => setItem(item.id, 'vat_rate', e.target.value)
      }), /*#__PURE__*/React.createElement("div", {
        style: {
          paddingTop: 9,
          textAlign: 'right',
          fontSize: 14,
          fontWeight: 600,
          color: 'var(--text-strong)',
          fontVariantNumeric: 'tabular-nums',
          whiteSpace: 'nowrap'
        }
      }, fmt(gross), " \u20AC"), /*#__PURE__*/React.createElement("div", {
        style: {
          paddingTop: 4
        }
      }, items.length > 1 && /*#__PURE__*/React.createElement(Button, {
        variant: "text",
        size: "sm",
        icon: "pi-trash",
        onClick: () => removeItem(item.id),
        style: {
          color: 'var(--text-faint)'
        }
      })));
    }), /*#__PURE__*/React.createElement(Button, {
      variant: "ghost",
      icon: "pi-plus",
      size: "sm",
      onClick: addItem,
      style: {
        marginTop: 4
      }
    }, "Prida\u0165 polo\u017Eku"), /*#__PURE__*/React.createElement("div", {
      style: {
        marginTop: 24,
        paddingTop: 18,
        borderTop: '1px solid var(--border)',
        display: 'flex',
        justifyContent: 'flex-end'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 300
      }
    }, [['Základ DPH:', totals.base], ['DPH:', totals.vat]].map(([l, v]) => /*#__PURE__*/React.createElement("div", {
      key: l,
      style: {
        display: 'flex',
        justifyContent: 'space-between',
        gap: 24,
        marginBottom: 8,
        fontSize: 14,
        color: 'var(--text-muted)'
      }
    }, /*#__PURE__*/React.createElement("span", null, l), /*#__PURE__*/React.createElement("span", {
      style: {
        fontVariantNumeric: 'tabular-nums'
      }
    }, fmt(v), " \u20AC"))), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        justifyContent: 'space-between',
        gap: 24,
        paddingTop: 10,
        borderTop: '2px solid var(--border-strong)',
        fontSize: 16,
        fontWeight: 700,
        color: 'var(--text-strong)'
      }
    }, /*#__PURE__*/React.createElement("span", null, "Celkom:"), /*#__PURE__*/React.createElement("span", {
      style: {
        fontVariantNumeric: 'tabular-nums'
      }
    }, fmt(totals.total), " \u20AC")))), /*#__PURE__*/React.createElement(SectionHead, null, "Pozn\xE1mka"), /*#__PURE__*/React.createElement(TextArea, {
      value: form.note,
      onChange: e => set('note', e.target.value),
      placeholder: "Pr\xEDpadn\xE1 pozn\xE1mka na fakt\xFAre\u2026",
      rows: 3
    })));
  }

  /* ── RECIPIENT FORM ─────────────────────────────────────────────── */
  function RecipientFormModal({
    onClose,
    onCreate
  }) {
    const empty = {
      name: '',
      ico: '',
      dic: '',
      ic_dph: '',
      street: '',
      city: '',
      zip: '',
      country: 'Slovensko',
      email: '',
      phone: ''
    };
    const [f, setF] = React.useState(empty);
    const set = (k, v) => setF(x => ({
      ...x,
      [k]: v
    }));
    const handleSubmit = e => {
      e && e.preventDefault();
      if (!f.name.trim()) return;
      onCreate({
        ...f,
        id: Date.now(),
        invoices: 0,
        total: 0
      });
    };
    return /*#__PURE__*/React.createElement(ModalShell, {
      title: "Nov\xFD klient",
      subtitle: "Zadajte faktura\u010Dn\xE9 a kontaktn\xE9 \xFAdaje.",
      onClose: onClose,
      footer: /*#__PURE__*/React.createElement("div", {
        style: {
          display: 'flex',
          justifyContent: 'flex-end',
          gap: 8
        }
      }, /*#__PURE__*/React.createElement(Button, {
        variant: "secondary",
        onClick: onClose
      }, "Zru\u0161i\u0165"), /*#__PURE__*/React.createElement(Button, {
        icon: "pi-check",
        onClick: handleSubmit
      }, "Ulo\u017Ei\u0165 klienta"))
    }, /*#__PURE__*/React.createElement(SectionHead, null, "Firemn\xE9 \xFAdaje"), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 14
      }
    }, /*#__PURE__*/React.createElement(Input, {
      label: "N\xE1zov firmy",
      icon: "pi-building",
      value: f.name,
      onChange: e => set('name', e.target.value),
      placeholder: "napr. Aurora s.r.o.",
      required: true
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: '1fr 1fr',
        gap: 14
      }
    }, /*#__PURE__*/React.createElement(Input, {
      label: "I\u010CO",
      value: f.ico,
      onChange: e => set('ico', e.target.value),
      placeholder: "12 345 678"
    }), /*#__PURE__*/React.createElement(Input, {
      label: "DI\u010C",
      value: f.dic,
      onChange: e => set('dic', e.target.value),
      placeholder: "2012345678"
    })), /*#__PURE__*/React.createElement(Input, {
      label: "I\u010C DPH",
      value: f.ic_dph,
      onChange: e => set('ic_dph', e.target.value),
      placeholder: "SK2012345678",
      hint: "Vypl\u0148te len ak je firma platite\u013Eom DPH."
    })), /*#__PURE__*/React.createElement(SectionHead, null, "Adresa"), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 14
      }
    }, /*#__PURE__*/React.createElement(Input, {
      label: "Ulica a \u010D\xEDslo",
      icon: "pi-map-marker",
      value: f.street,
      onChange: e => set('street', e.target.value),
      placeholder: "Hlavn\xE1 1"
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: '2fr 1fr',
        gap: 14
      }
    }, /*#__PURE__*/React.createElement(Input, {
      label: "Mesto",
      value: f.city,
      onChange: e => set('city', e.target.value),
      placeholder: "Bratislava"
    }), /*#__PURE__*/React.createElement(Input, {
      label: "PS\u010C",
      value: f.zip,
      onChange: e => set('zip', e.target.value),
      placeholder: "811 01"
    })), /*#__PURE__*/React.createElement(Input, {
      label: "Krajina",
      value: f.country,
      onChange: e => set('country', e.target.value)
    })), /*#__PURE__*/React.createElement(SectionHead, null, "Kontaktn\xE9 \xFAdaje"), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: '1fr 1fr',
        gap: 14
      }
    }, /*#__PURE__*/React.createElement(Input, {
      label: "E-mail",
      type: "email",
      icon: "pi-envelope",
      value: f.email,
      onChange: e => set('email', e.target.value),
      placeholder: "firma@example.sk"
    }), /*#__PURE__*/React.createElement(Input, {
      label: "Telef\xF3n",
      icon: "pi-phone",
      value: f.phone,
      onChange: e => set('phone', e.target.value),
      placeholder: "+421 900 000 000"
    })));
  }

  /* ── AUTOMATIZATION FORM ────────────────────────────────────────── */
  function AutomatizationFormModal({
    onClose,
    onCreate,
    recipients
  }) {
    const AUTO_TYPES = [{
      value: 'invoice_auto_gen',
      label: 'Automatické generovanie faktúr',
      icon: 'pi-file',
      desc: 'Faktúra sa vytvorí automaticky v zvolený deň.'
    }, {
      value: 'invoice_report',
      label: 'Mesačný report',
      icon: 'pi-chart-bar',
      desc: 'Zhrnutie fakturácie za uplynulý mesiac zasielané e-mailom.'
    }, {
      value: 'invoice_due_reminder',
      label: 'Pripomienka splatnosti',
      icon: 'pi-bell',
      desc: 'Upozornenie klientovi pred termínom splatnosti faktúry.'
    }];
    const FREQ = [{
      value: 'monthly',
      label: 'Mesačne'
    }, {
      value: 'weekly',
      label: 'Týždenne'
    }, {
      value: 'quarterly',
      label: 'Štvrťročne'
    }];
    const [f, setF] = React.useState({
      type: 'invoice_auto_gen',
      recipient_id: '',
      frequency: 'monthly',
      next_run: '2026-07-01',
      active: true
    });
    const set = (k, v) => setF(x => ({
      ...x,
      [k]: v
    }));
    const needsRecipient = f.type !== 'invoice_report';
    const recipientOpts = [{
      value: '',
      label: '— Pre všetkých klientov —'
    }, ...(recipients || []).map(r => ({
      value: String(r.id),
      label: r.name
    }))];
    const handleSubmit = () => {
      const rec = (recipients || []).find(r => String(r.id) === String(f.recipient_id));
      const d = f.next_run.split('-');
      onCreate({
        id: Date.now(),
        type: f.type,
        recipient: rec ? rec.name : null,
        next: `${d[2]}. ${d[1]}. ${d[0]}`,
        active: f.active
      });
    };
    return /*#__PURE__*/React.createElement(ModalShell, {
      title: "Nov\xE1 automatiz\xE1cia",
      subtitle: "Nastavte automatick\xE9 akcie pre va\u0161e fakt\xFAry.",
      onClose: onClose,
      maxWidth: 520,
      footer: /*#__PURE__*/React.createElement("div", {
        style: {
          display: 'flex',
          justifyContent: 'flex-end',
          gap: 8
        }
      }, /*#__PURE__*/React.createElement(Button, {
        variant: "secondary",
        onClick: onClose
      }, "Zru\u0161i\u0165"), /*#__PURE__*/React.createElement(Button, {
        icon: "pi-bolt",
        onClick: handleSubmit
      }, "Vytvori\u0165 automatiz\xE1ciu"))
    }, /*#__PURE__*/React.createElement(SectionHead, null, "Typ automatiz\xE1cie"), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 10
      }
    }, AUTO_TYPES.map(t => {
      const active = f.type === t.value;
      return /*#__PURE__*/React.createElement("label", {
        key: t.value,
        style: {
          display: 'flex',
          alignItems: 'flex-start',
          gap: 14,
          padding: '12px 14px',
          border: `1.5px solid ${active ? 'var(--primary)' : 'var(--border)'}`,
          borderRadius: 'var(--radius-md)',
          background: active ? 'var(--primary-soft)' : 'var(--surface-card)',
          cursor: 'pointer',
          transition: 'all var(--duration-fast) var(--ease)'
        }
      }, /*#__PURE__*/React.createElement("input", {
        type: "radio",
        name: "auto_type",
        value: t.value,
        checked: active,
        onChange: () => set('type', t.value),
        style: {
          marginTop: 3,
          accentColor: 'var(--primary)',
          flexShrink: 0
        }
      }), /*#__PURE__*/React.createElement("span", {
        style: {
          width: 34,
          height: 34,
          borderRadius: 'var(--radius-md)',
          background: active ? 'var(--primary)' : 'var(--gray-100)',
          color: active ? '#fff' : 'var(--text-muted)',
          display: 'inline-flex',
          alignItems: 'center',
          justifyContent: 'center',
          flexShrink: 0,
          transition: 'background var(--duration-fast) var(--ease)'
        }
      }, /*#__PURE__*/React.createElement("i", {
        className: `pi ${t.icon}`,
        style: {
          fontSize: 15
        }
      })), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
        style: {
          fontWeight: 600,
          fontSize: 14,
          color: 'var(--text-strong)'
        }
      }, t.label), /*#__PURE__*/React.createElement("div", {
        style: {
          fontSize: 13,
          color: 'var(--text-muted)',
          marginTop: 2
        }
      }, t.desc)));
    })), /*#__PURE__*/React.createElement(SectionHead, null, "Nastavenia"), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 14
      }
    }, needsRecipient && /*#__PURE__*/React.createElement(Select, {
      label: "Klient",
      value: f.recipient_id,
      options: recipientOpts,
      onChange: e => set('recipient_id', e.target.value),
      hint: "Nechajte pr\xE1zdne pre v\u0161etk\xFDch klientov."
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: '1fr 1fr',
        gap: 14
      }
    }, /*#__PURE__*/React.createElement(Select, {
      label: "Frekvencia",
      value: f.frequency,
      options: FREQ,
      onChange: e => set('frequency', e.target.value)
    }), /*#__PURE__*/React.createElement(Input, {
      label: "Prv\xE9 spustenie",
      type: "date",
      value: f.next_run,
      onChange: e => set('next_run', e.target.value)
    })), /*#__PURE__*/React.createElement("div", {
      style: {
        paddingTop: 4
      }
    }, /*#__PURE__*/React.createElement(Checkbox, {
      checked: f.active,
      label: "Aktivova\u0165 ihne\u010F po vytvoren\xED",
      onChange: e => set('active', e.target.checked)
    }))));
  }
  Object.assign(window, {
    InvInvoiceFormDrawer: InvoiceFormDrawer,
    InvRecipientFormModal: RecipientFormModal,
    InvAutomatizationFormModal: AutomatizationFormModal
  });
})();
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/invoicius/forms.jsx", error: String((e && e.message) || e) }); }

// ui_kits/invoicius/responsive.jsx
try { (() => {
/* Invoicius UI kit — responsive.jsx
   useIsMobile hook + InvMobileTopBar for the hamburger topbar on small screens */
(function () {
  function useIsMobile(bp = 768) {
    const [mobile, setMobile] = React.useState(window.innerWidth < bp);
    React.useEffect(() => {
      const h = () => setMobile(window.innerWidth < bp);
      window.addEventListener('resize', h);
      return () => window.removeEventListener('resize', h);
    }, [bp]);
    return mobile;
  }

  /* Mobile topbar: hamburger · logo · user avatar */
  function MobileTopBar({
    onMenuOpen,
    userName,
    onNavigate
  }) {
    const initials = n => n.split(/\s+/).slice(0, 2).map(w => w[0]).join('').toUpperCase();
    return /*#__PURE__*/React.createElement("div", {
      style: {
        position: 'fixed',
        top: 0,
        left: 0,
        right: 0,
        height: 56,
        background: 'var(--surface-card)',
        borderBottom: '1px solid var(--border)',
        display: 'flex',
        alignItems: 'center',
        zIndex: 40,
        paddingLeft: 4,
        paddingRight: 16
      }
    }, /*#__PURE__*/React.createElement("button", {
      onClick: onMenuOpen,
      style: {
        background: 'none',
        border: 'none',
        cursor: 'pointer',
        padding: '8px 12px',
        color: 'var(--text-body)',
        display: 'flex',
        alignItems: 'center',
        flexShrink: 0
      }
    }, /*#__PURE__*/React.createElement("i", {
      className: "pi pi-bars",
      style: {
        fontSize: 20
      }
    })), /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1,
        display: 'flex',
        justifyContent: 'center'
      }
    }, /*#__PURE__*/React.createElement("img", {
      src: "../../assets/logo-wordmark.svg",
      height: "22",
      alt: "Invoicius",
      style: {
        display: 'block'
      }
    })), /*#__PURE__*/React.createElement("button", {
      onClick: () => onNavigate('profile'),
      style: {
        width: 34,
        height: 34,
        borderRadius: 'var(--radius-full)',
        background: 'var(--primary)',
        color: '#fff',
        border: 'none',
        cursor: 'pointer',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        fontSize: 12,
        fontWeight: 700,
        flexShrink: 0
      }
    }, initials(userName)));
  }
  Object.assign(window, {
    useIsMobile,
    InvMobileTopBar: MobileTopBar
  });
})();
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/invoicius/responsive.jsx", error: String((e && e.message) || e) }); }

// ui_kits/invoicius/screens.jsx
try { (() => {
/* Invoicius UI kit — screens. Faithful to the real app:
   Prehľad (dashboard hero + 2 panels), Faktúry, Automatizácie, Profil.
   Exports screen components + shared data to window for app.jsx. */
(function () {
  const DS = window.InvoiciusDesignSystem_31395d;
  const {
    Button,
    Card,
    Badge,
    Input,
    Select,
    Checkbox,
    StatCard
  } = DS;
  const {
    InvPageHeader
  } = window;
  const fmt = n => n.toLocaleString('sk-SK', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
  const todayStr = '14. 6. 2026';
  const SEED_INVOICES = [{
    id: 1,
    number: '2026-0042',
    recipient: 'Aurora s.r.o.',
    date: '12. 6. 2026',
    amount: 2480.0,
    status: 'paid'
  }, {
    id: 2,
    number: '2026-0041',
    recipient: 'Bistro Lipa',
    date: '9. 6. 2026',
    amount: 640.5,
    status: 'awaiting'
  }, {
    id: 3,
    number: '2026-0040',
    recipient: 'Kovomont a.s.',
    date: '2. 6. 2026',
    amount: 5120.0,
    status: 'overdue'
  }, {
    id: 4,
    number: '2026-0039',
    recipient: 'Studio Nord',
    date: '28. 5. 2026',
    amount: 980.0,
    status: 'sent'
  }, {
    id: 5,
    number: '2026-0038',
    recipient: 'Pekáreň Zora',
    date: '21. 5. 2026',
    amount: 312.4,
    status: 'paid'
  }, {
    id: 6,
    number: '2026-0037',
    recipient: 'Tatra Build',
    date: '15. 5. 2026',
    amount: 7450.0,
    status: 'draft'
  }];
  const SEED_AUTOMATIONS = [{
    id: 1,
    type: 'invoice_auto_gen',
    recipient: 'Aurora s.r.o.',
    next: '1. 7. 2026',
    active: true
  }, {
    id: 2,
    type: 'invoice_report',
    recipient: null,
    next: '1. 7. 2026',
    active: true
  }, {
    id: 3,
    type: 'invoice_due_reminder',
    recipient: 'Kovomont a.s.',
    next: '20. 6. 2026',
    active: true
  }, {
    id: 4,
    type: 'invoice_auto_gen',
    recipient: 'Bistro Lipa',
    next: '5. 7. 2026',
    active: false
  }];
  const AUTO_LABEL = {
    invoice_auto_gen: 'Automatické generovanie faktúr',
    invoice_report: 'Mesačný report',
    invoice_due_reminder: 'Pripomienka splatnosti'
  };
  const AUTO_ICON = {
    invoice_auto_gen: 'pi-file',
    invoice_report: 'pi-chart-bar',
    invoice_due_reminder: 'pi-bell'
  };
  const STATUS_OPTS = [{
    value: 'paid',
    label: 'Uhradené'
  }, {
    value: 'awaiting',
    label: 'Čaká na úhradu'
  }, {
    value: 'overdue',
    label: 'Po splatnosti'
  }, {
    value: 'sent',
    label: 'Odoslané'
  }, {
    value: 'draft',
    label: 'Koncept'
  }];
  const STATUS_LABEL = Object.fromEntries(STATUS_OPTS.map(o => [o.value, o.label]));
  const SEED_RECIPIENTS = [{
    id: 1,
    name: 'Aurora s.r.o.',
    ico: '52 814 003',
    email: 'fakturacia@aurora.sk',
    city: 'Bratislava',
    invoices: 12,
    total: 28640.0
  }, {
    id: 2,
    name: 'Bistro Lipa',
    ico: '47 110 925',
    email: 'lipa@bistro.sk',
    city: 'Nitra',
    invoices: 8,
    total: 5120.5
  }, {
    id: 3,
    name: 'Kovomont a.s.',
    ico: '36 215 487',
    email: 'uctaren@kovomont.sk',
    city: 'Žilina',
    invoices: 21,
    total: 94300.0
  }, {
    id: 4,
    name: 'Studio Nord',
    ico: '53 902 144',
    email: 'hello@studionord.sk',
    city: 'Košice',
    invoices: 5,
    total: 7820.0
  }, {
    id: 5,
    name: 'Pekáreň Zora',
    ico: '46 778 310',
    email: 'objednavky@zora.sk',
    city: 'Trnava',
    invoices: 17,
    total: 12480.4
  }, {
    id: 6,
    name: 'Tatra Build',
    ico: '50 661 209',
    email: 'faktury@tatrabuild.sk',
    city: 'Poprad',
    invoices: 3,
    total: 21450.0
  }];

  /* ---------- Donut (CSS conic-gradient) ---------- */
  function Donut({
    segments,
    size = 168
  }) {
    const total = segments.reduce((s, x) => s + x.value, 0) || 1;
    let acc = 0;
    const stops = segments.filter(s => s.value > 0).map(s => {
      const start = acc / total * 360;
      acc += s.value;
      return `${s.color} ${start}deg ${acc / total * 360}deg`;
    }).join(', ');
    return /*#__PURE__*/React.createElement("div", {
      style: {
        position: 'relative',
        width: size,
        height: size,
        flexShrink: 0,
        borderRadius: '50%',
        background: `conic-gradient(${stops || 'var(--gray-200) 0deg 360deg'})`
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        position: 'absolute',
        inset: 17,
        borderRadius: '50%',
        background: '#fff',
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center'
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 18,
        fontWeight: 600,
        color: 'var(--text-strong)',
        fontVariantNumeric: 'tabular-nums'
      }
    }, fmt(total), " \u20AC"), /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 12,
        color: 'var(--text-muted)'
      }
    }, "fakturovan\xE9")));
  }

  /* ---------- Panel (dashboard 2-up) ---------- */
  function Panel({
    title,
    action,
    children
  }) {
    return /*#__PURE__*/React.createElement(Card, {
      padding: "none",
      style: {
        display: 'flex',
        flexDirection: 'column'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        gap: 12,
        padding: '14px 20px',
        borderBottom: '1px solid var(--border-subtle)'
      }
    }, /*#__PURE__*/React.createElement("h3", {
      style: {
        margin: 0,
        fontSize: 'var(--text-sm)',
        fontWeight: 600,
        color: 'var(--text-strong)'
      }
    }, title), action), /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1
      }
    }, children));
  }

  /* ---------- Dashboard ---------- */
  function Dashboard({
    invoices,
    automations,
    userName,
    onNew,
    onNavigate
  }) {
    const isMobile = window.useIsMobile();
    const sum = st => invoices.filter(i => i.status === st).reduce((s, i) => s + i.amount, 0);
    const segs = [{
      key: 'paid',
      label: 'Uhradené',
      value: sum('paid'),
      color: 'var(--status-paid)'
    }, {
      key: 'awaiting',
      label: 'Čaká na úhradu',
      value: sum('awaiting') + sum('sent'),
      color: 'var(--status-awaiting)'
    }, {
      key: 'overdue',
      label: 'Po splatnosti',
      value: sum('overdue'),
      color: 'var(--status-overdue)'
    }, {
      key: 'draft',
      label: 'Koncept',
      value: sum('draft'),
      color: 'var(--status-draft)'
    }];
    const activeAuto = automations.filter(a => a.active);
    const recent = invoices.slice(0, 5);
    return /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 24
      }
    }, /*#__PURE__*/React.createElement(InvPageHeader, {
      title: "Preh\u013Ead",
      subtitle: `Vitaj späť, ${userName}.`
    }, /*#__PURE__*/React.createElement(Button, {
      icon: "pi-plus",
      onClick: onNew
    }, "Nov\xE1 fakt\xFAra")), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: isMobile ? 'repeat(2, 1fr)' : 'repeat(3, 1fr)',
        gap: 16
      }
    }, /*#__PURE__*/React.createElement(StatCard, {
      label: "Fakt\xFAry",
      value: invoices.length,
      hint: "celkom v syst\xE9me",
      icon: "pi-file",
      accent: "sky"
    }), /*#__PURE__*/React.createElement(StatCard, {
      label: "Klienti",
      value: "34",
      hint: "celkom v syst\xE9me",
      icon: "pi-users",
      accent: "violet"
    }), /*#__PURE__*/React.createElement(StatCard, {
      label: "Akt\xEDvne automatiz\xE1cie",
      value: activeAuto.length,
      hint: "be\u017Eia automaticky",
      icon: "pi-bolt",
      accent: "amber"
    })), /*#__PURE__*/React.createElement(Card, {
      title: "Fakturovan\xE9 pod\u013Ea stavu",
      subtitle: "Rozdelenie celkovej fakturovanej sumy"
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 40,
        flexWrap: 'wrap'
      }
    }, /*#__PURE__*/React.createElement(Donut, {
      segments: segs
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 14,
        flex: 1,
        minWidth: 220
      }
    }, segs.map(s => /*#__PURE__*/React.createElement("div", {
      key: s.key,
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 10
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        width: 10,
        height: 10,
        borderRadius: 9999,
        background: s.color,
        flexShrink: 0
      }
    }), /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 14,
        color: 'var(--text-body)',
        flex: 1
      }
    }, s.label), /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 14,
        fontWeight: 600,
        color: 'var(--text-strong)',
        fontVariantNumeric: 'tabular-nums'
      }
    }, fmt(s.value), " \u20AC")))))), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: isMobile ? '1fr' : 'repeat(2, 1fr)',
        gap: 24
      }
    }, /*#__PURE__*/React.createElement(Panel, {
      title: "Posledn\xE9 fakt\xFAry",
      action: /*#__PURE__*/React.createElement(Button, {
        variant: "text",
        size: "sm",
        icon: "pi-arrow-right",
        iconPos: "right",
        onClick: () => onNavigate('invoices')
      }, "Zobrazi\u0165 v\u0161etky")
    }, /*#__PURE__*/React.createElement("table", {
      style: {
        width: '100%',
        borderCollapse: 'collapse',
        fontSize: 14
      }
    }, /*#__PURE__*/React.createElement("tbody", null, recent.map(inv => /*#__PURE__*/React.createElement("tr", {
      key: inv.id,
      style: {
        borderBottom: '1px solid var(--border-subtle)'
      }
    }, /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 20px'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        fontWeight: 600,
        color: 'var(--text-strong)'
      }
    }, inv.number), /*#__PURE__*/React.createElement("div", {
      style: {
        fontSize: 12,
        color: 'var(--text-muted)'
      }
    }, inv.date)), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 8px',
        color: 'var(--text-body)'
      }
    }, inv.recipient), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 8px'
      }
    }, /*#__PURE__*/React.createElement(Badge, {
      status: inv.status
    }, STATUS_LABEL[inv.status])), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 20px',
        textAlign: 'right',
        fontWeight: 600,
        color: 'var(--text-strong)',
        fontVariantNumeric: 'tabular-nums',
        whiteSpace: 'nowrap'
      }
    }, fmt(inv.amount), " \u20AC")))))), /*#__PURE__*/React.createElement(Panel, {
      title: "Akt\xEDvne automatiz\xE1cie",
      action: /*#__PURE__*/React.createElement(Button, {
        variant: "text",
        size: "sm",
        icon: "pi-cog",
        onClick: () => onNavigate('automatizations')
      }, "Spravova\u0165")
    }, activeAuto.length === 0 ? /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        gap: 10,
        padding: '40px 0',
        color: 'var(--text-muted)'
      }
    }, /*#__PURE__*/React.createElement("i", {
      className: "pi pi-bolt",
      style: {
        fontSize: 30,
        color: 'var(--gray-300)'
      }
    }), /*#__PURE__*/React.createElement("p", {
      style: {
        margin: 0,
        fontSize: 14
      }
    }, "Zatia\u013E nem\xE1\u0161 \u017Eiadne akt\xEDvne automatiz\xE1cie.")) : /*#__PURE__*/React.createElement("table", {
      style: {
        width: '100%',
        borderCollapse: 'collapse',
        fontSize: 14
      }
    }, /*#__PURE__*/React.createElement("tbody", null, activeAuto.map(a => /*#__PURE__*/React.createElement("tr", {
      key: a.id,
      style: {
        borderBottom: '1px solid var(--border-subtle)'
      }
    }, /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 20px'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        fontWeight: 500,
        color: 'var(--text-strong)'
      }
    }, AUTO_LABEL[a.type]), /*#__PURE__*/React.createElement("div", {
      style: {
        fontSize: 12,
        color: 'var(--text-muted)'
      }
    }, a.recipient || 'Pre všetkých klientov')), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 8px',
        color: 'var(--text-muted)',
        fontSize: 13,
        whiteSpace: 'nowrap'
      }
    }, "\u010Eal\u0161ie: ", a.next), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 20px',
        textAlign: 'right'
      }
    }, /*#__PURE__*/React.createElement(Badge, {
      tone: "success"
    }, "Akt\xEDvne")))))))));
  }

  /* ---------- Invoices ---------- */
  function Invoices({
    invoices,
    onStatus,
    onNew,
    onToast
  }) {
    const isMobile = window.useIsMobile();
    return /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 24
      }
    }, /*#__PURE__*/React.createElement(InvPageHeader, {
      title: "Fakt\xFAry",
      subtitle: `${invoices.length} faktúr celkom`
    }, /*#__PURE__*/React.createElement(Button, {
      icon: "pi-plus",
      onClick: onNew
    }, "Nov\xE1 fakt\xFAra")), isMobile ? /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 10
      }
    }, invoices.map(inv => /*#__PURE__*/React.createElement("article", {
      key: inv.id,
      style: {
        background: 'var(--surface-card)',
        border: '1px solid var(--border)',
        borderRadius: 'var(--radius-lg)',
        boxShadow: 'var(--shadow-sm)',
        overflow: 'hidden'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'flex-start',
        justifyContent: 'space-between',
        gap: 12,
        padding: '14px 16px 10px'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 0
      }
    }, /*#__PURE__*/React.createElement("p", {
      style: {
        margin: 0,
        fontSize: 15,
        fontWeight: 700,
        color: 'var(--text-strong)',
        fontFamily: 'var(--font-sans)'
      }
    }, inv.number), /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '2px 0 0',
        fontSize: 13,
        color: 'var(--text-muted)',
        overflow: 'hidden',
        textOverflow: 'ellipsis',
        whiteSpace: 'nowrap',
        fontFamily: 'var(--font-sans)'
      }
    }, inv.recipient)), /*#__PURE__*/React.createElement("p", {
      style: {
        margin: 0,
        fontSize: 16,
        fontWeight: 700,
        color: 'var(--text-strong)',
        fontVariantNumeric: 'tabular-nums',
        flexShrink: 0,
        fontFamily: 'var(--font-sans)'
      }
    }, fmt(inv.amount), " \u20AC")), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: '1fr 1fr',
        gap: '6px 16px',
        padding: '0 16px 12px'
      }
    }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
      style: {
        margin: 0,
        fontSize: 10,
        fontWeight: 600,
        textTransform: 'uppercase',
        letterSpacing: '0.07em',
        color: 'var(--text-muted)',
        fontFamily: 'var(--font-sans)'
      }
    }, "Vytvoren\xE9"), /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '3px 0 0',
        fontSize: 13,
        color: 'var(--text-strong)',
        fontFamily: 'var(--font-sans)'
      }
    }, inv.date)), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '0 0 4px',
        fontSize: 10,
        fontWeight: 600,
        textTransform: 'uppercase',
        letterSpacing: '0.07em',
        color: 'var(--text-muted)',
        fontFamily: 'var(--font-sans)'
      }
    }, "Stav"), /*#__PURE__*/React.createElement(Select, {
      value: inv.status,
      options: STATUS_OPTS,
      onChange: e => onStatus(inv.id, e.target.value)
    }))), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'flex-end',
        gap: 2,
        padding: '8px 10px',
        borderTop: '1px solid var(--border-subtle)'
      }
    }, /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-file-pdf",
      onClick: () => onToast('PDF sa generuje…')
    }), /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-pencil",
      onClick: () => onToast('Úprava faktúry ' + inv.number)
    }), /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-trash",
      onClick: () => onToast('Faktúra ' + inv.number + ' zmazaná'),
      style: {
        color: 'var(--danger)'
      }
    }))))) : /*#__PURE__*/React.createElement(Card, {
      padding: "none",
      style: {
        overflowX: 'auto'
      }
    }, /*#__PURE__*/React.createElement("table", {
      style: {
        width: '100%',
        minWidth: 660,
        borderCollapse: 'collapse',
        fontSize: 14
      }
    }, /*#__PURE__*/React.createElement("thead", null, /*#__PURE__*/React.createElement("tr", {
      style: {
        borderBottom: '1px solid var(--border)'
      }
    }, ['Číslo faktúry', 'Klient', 'Vytvorené', 'Suma', 'Stav', ''].map((h, i) => /*#__PURE__*/React.createElement("th", {
      key: i,
      style: {
        textAlign: i === 3 ? 'right' : 'left',
        padding: '12px 16px',
        fontSize: 12,
        fontWeight: 600,
        color: 'var(--text-muted)',
        textTransform: 'uppercase',
        letterSpacing: 'var(--tracking-wide)',
        whiteSpace: 'nowrap'
      }
    }, h)))), /*#__PURE__*/React.createElement("tbody", null, invoices.map(inv => /*#__PURE__*/React.createElement("tr", {
      key: inv.id,
      style: {
        borderBottom: '1px solid var(--border-subtle)'
      }
    }, /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        fontWeight: 600,
        color: 'var(--text-strong)'
      }
    }, inv.number), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        color: 'var(--text-body)'
      }
    }, inv.recipient), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        color: 'var(--text-muted)'
      }
    }, inv.date), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        textAlign: 'right',
        fontWeight: 600,
        color: 'var(--text-strong)',
        fontVariantNumeric: 'tabular-nums',
        whiteSpace: 'nowrap'
      }
    }, fmt(inv.amount), " \u20AC"), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        width: 180
      }
    }, /*#__PURE__*/React.createElement(Select, {
      value: inv.status,
      options: STATUS_OPTS,
      onChange: e => onStatus(inv.id, e.target.value)
    })), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        whiteSpace: 'nowrap',
        textAlign: 'right'
      }
    }, /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-file-pdf",
      onClick: () => onToast('PDF sa generuje…')
    }), /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-pencil",
      onClick: () => onToast('Úprava faktúry ' + inv.number)
    }), /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-trash",
      onClick: () => onToast('Faktúra ' + inv.number + ' zmazaná'),
      style: {
        color: 'var(--danger)'
      }
    }))))))));
  }

  /* ---------- Automatizácie ---------- */
  function Automations({
    automations,
    onToggle,
    onToast,
    onNewAutomation
  }) {
    const isMobile = window.useIsMobile();
    return /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 24
      }
    }, /*#__PURE__*/React.createElement(InvPageHeader, {
      title: "Automatiz\xE1cie",
      subtitle: "Automaticky generujte fakt\xFAry, reporty a pripomienky."
    }, /*#__PURE__*/React.createElement(Button, {
      icon: "pi-plus",
      onClick: onNewAutomation
    }, "Nov\xE1 automatiz\xE1cia")), automations.length === 0 ? /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("p", {
      style: {
        margin: 0,
        color: 'var(--text-muted)',
        fontSize: 14
      }
    }, "Zatia\u013E nem\xE1te \u017Eiadne automatiz\xE1cie. Vytvorte jednu na automatick\xE9 generovanie fakt\xFAr alebo mesa\u010Dn\xFD report.")) : isMobile ? /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 10
      }
    }, automations.map(a => /*#__PURE__*/React.createElement("article", {
      key: a.id,
      style: {
        background: 'var(--surface-card)',
        border: '1px solid var(--border)',
        borderRadius: 'var(--radius-lg)',
        boxShadow: 'var(--shadow-sm)',
        overflow: 'hidden'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'flex-start',
        justifyContent: 'space-between',
        gap: 10,
        padding: '14px 16px 10px'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 10,
        minWidth: 0
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        width: 36,
        height: 36,
        borderRadius: 'var(--radius-md)',
        background: 'var(--primary-soft)',
        color: 'var(--primary-active)',
        display: 'inline-flex',
        alignItems: 'center',
        justifyContent: 'center',
        flexShrink: 0
      }
    }, /*#__PURE__*/React.createElement("i", {
      className: `pi ${AUTO_ICON[a.type]}`,
      style: {
        fontSize: 16
      }
    })), /*#__PURE__*/React.createElement("p", {
      style: {
        margin: 0,
        fontSize: 14,
        fontWeight: 600,
        color: 'var(--text-strong)',
        fontFamily: 'var(--font-sans)'
      }
    }, AUTO_LABEL[a.type])), /*#__PURE__*/React.createElement(Badge, {
      tone: a.active ? 'success' : 'neutral'
    }, a.active ? 'Aktívne' : 'Neaktívne')), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 6,
        padding: '0 16px 12px'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        justifyContent: 'space-between',
        gap: 8
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        color: 'var(--text-muted)',
        fontFamily: 'var(--font-sans)'
      }
    }, "Klient"), /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        fontWeight: 500,
        color: 'var(--text-strong)',
        fontFamily: 'var(--font-sans)'
      }
    }, a.recipient || 'Pre všetkých klientov')), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        justifyContent: 'space-between',
        gap: 8
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        color: 'var(--text-muted)',
        fontFamily: 'var(--font-sans)'
      }
    }, "Nasleduj\xFAce spustenie"), /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        fontWeight: 500,
        color: 'var(--text-strong)',
        fontFamily: 'var(--font-sans)'
      }
    }, a.active ? a.next : '—'))), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'flex-end',
        gap: 2,
        padding: '8px 10px',
        borderTop: '1px solid var(--border-subtle)'
      }
    }, /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: a.active ? 'pi-pause' : 'pi-play',
      onClick: () => onToggle(a.id)
    }), /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-pencil",
      onClick: () => onToast('Úprava automatizácie')
    }), /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-trash",
      onClick: () => onToast('Automatizácia zmazaná'),
      style: {
        color: 'var(--danger)'
      }
    }))))) : /*#__PURE__*/React.createElement(Card, {
      padding: "none",
      style: {
        overflowX: 'auto'
      }
    }, /*#__PURE__*/React.createElement("table", {
      style: {
        width: '100%',
        minWidth: 580,
        borderCollapse: 'collapse',
        fontSize: 14
      }
    }, /*#__PURE__*/React.createElement("thead", null, /*#__PURE__*/React.createElement("tr", {
      style: {
        borderBottom: '1px solid var(--border)'
      }
    }, ['Typ', 'Klient', 'Nasledujúce spustenie', 'Stav', ''].map((h, i) => /*#__PURE__*/React.createElement("th", {
      key: i,
      style: {
        textAlign: 'left',
        padding: '12px 16px',
        fontSize: 12,
        fontWeight: 600,
        color: 'var(--text-muted)',
        textTransform: 'uppercase',
        letterSpacing: 'var(--tracking-wide)',
        whiteSpace: 'nowrap'
      }
    }, h)))), /*#__PURE__*/React.createElement("tbody", null, automations.map(a => /*#__PURE__*/React.createElement("tr", {
      key: a.id,
      style: {
        borderBottom: '1px solid var(--border-subtle)'
      }
    }, /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 10
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        width: 34,
        height: 34,
        borderRadius: 'var(--radius-md)',
        background: 'var(--primary-soft)',
        color: 'var(--primary-active)',
        display: 'inline-flex',
        alignItems: 'center',
        justifyContent: 'center',
        flexShrink: 0
      }
    }, /*#__PURE__*/React.createElement("i", {
      className: `pi ${AUTO_ICON[a.type]}`,
      style: {
        fontSize: 15
      }
    })), /*#__PURE__*/React.createElement("span", {
      style: {
        fontWeight: 500,
        color: 'var(--text-strong)'
      }
    }, AUTO_LABEL[a.type]))), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        color: 'var(--text-body)'
      }
    }, a.recipient || /*#__PURE__*/React.createElement("span", {
      style: {
        color: 'var(--text-muted)'
      }
    }, "Pre v\u0161etk\xFDch klientov")), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        color: 'var(--text-muted)'
      }
    }, a.active ? a.next : '—'), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px'
      }
    }, /*#__PURE__*/React.createElement(Badge, {
      tone: a.active ? 'success' : 'neutral'
    }, a.active ? 'Aktívne' : 'Neaktívne')), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        whiteSpace: 'nowrap',
        textAlign: 'right'
      }
    }, /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: a.active ? 'pi-pause' : 'pi-play',
      onClick: () => onToggle(a.id),
      title: a.active ? 'Deaktivovať' : 'Aktivovať'
    }), /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-pencil",
      onClick: () => onToast('Úprava automatizácie')
    }), /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-trash",
      onClick: () => onToast('Automatizácia zmazaná'),
      style: {
        color: 'var(--danger)'
      }
    }))))))));
  }

  /* ---------- Profil ---------- */
  function Profile({
    onToast
  }) {
    const isMobile = window.useIsMobile();
    const [info, setInfo] = React.useState({
      name: 'Tristan Prekop',
      email: 'test@example.com'
    });
    const [logo, setLogo] = React.useState(null);
    const [billing, setBilling] = React.useState({
      currency_id: 'EUR',
      default_vat_type_id: '20',
      ico: '52 814 003',
      dic: '2121160224',
      ic_dph: '',
      iban: 'SK89 1100 0000 0029 1234 5678',
      street: 'Hlavná',
      street_num: '1',
      city: 'Bratislava',
      zip: '811 01',
      state: 'SK'
    });
    const [pw, setPw] = React.useState({
      current: '',
      password: '',
      confirm: ''
    });
    const [saved, setSaved] = React.useState({});
    const [delModal, setDelModal] = React.useState(false);
    const [delPw, setDelPw] = React.useState('');
    const flash = key => {
      setSaved(s => ({
        ...s,
        [key]: true
      }));
      setTimeout(() => setSaved(s => ({
        ...s,
        [key]: false
      })), 2500);
    };
    const setB = (k, v) => setBilling(b => ({
      ...b,
      [k]: v
    }));
    const setP = (k, v) => setPw(p => ({
      ...p,
      [k]: v
    }));
    const COUNTRIES = [{
      value: 'SK',
      label: 'Slovensko'
    }, {
      value: 'CZ',
      label: 'Česko'
    }, {
      value: 'AT',
      label: 'Rakúsko'
    }, {
      value: 'HU',
      label: 'Maďarsko'
    }, {
      value: 'PL',
      label: 'Poľsko'
    }, {
      value: 'DE',
      label: 'Nemecko'
    }];
    const CURRENCIES = [{
      value: 'EUR',
      label: 'Euro (€)'
    }, {
      value: 'CZK',
      label: 'Česká koruna (Kč)'
    }, {
      value: 'USD',
      label: 'USD ($)'
    }];
    const VAT_TYPES = [{
      value: '20',
      label: '20 % — Štandardná sadzba'
    }, {
      value: '10',
      label: '10 % — Znížená sadzba'
    }, {
      value: '0',
      label: '0 % — Oslobodené'
    }];

    /* sub-components local to Profile */
    const Divider = () => /*#__PURE__*/React.createElement("div", {
      style: {
        height: 1,
        background: 'var(--border-subtle)',
        margin: '0'
      }
    });
    const Sec = ({
      title,
      desc,
      children
    }) => /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: isMobile ? '1fr' : '260px 1fr',
        gap: isMobile ? 4 : 48,
        padding: isMobile ? 20 : 28,
        alignItems: 'start'
      }
    }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h2", {
      style: {
        margin: 0,
        fontSize: 15,
        fontWeight: 600,
        color: 'var(--text-strong)'
      }
    }, title), /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '6px 0 0',
        fontSize: 13,
        color: 'var(--text-muted)',
        lineHeight: 1.55
      }
    }, desc)), /*#__PURE__*/React.createElement("div", null, children));
    const SaveRow = ({
      skey
    }) => /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'flex-end',
        gap: 12,
        marginTop: 20
      }
    }, saved[skey] && /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        color: 'var(--success)',
        display: 'flex',
        alignItems: 'center',
        gap: 5
      }
    }, /*#__PURE__*/React.createElement("i", {
      className: "pi pi-check"
    }), " Ulo\u017Een\xE9."), /*#__PURE__*/React.createElement(Button, {
      onClick: () => flash(skey)
    }, "Ulo\u017Ei\u0165"));
    return /*#__PURE__*/React.createElement("div", {
      style: {
        maxWidth: isMobile ? '100%' : 920
      }
    }, /*#__PURE__*/React.createElement(InvPageHeader, {
      title: "Profil",
      subtitle: "Spravujte nastavenia v\xE1\u0161ho \xFA\u010Dtu."
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        background: 'var(--surface-card)',
        border: '1px solid var(--border)',
        borderRadius: 'var(--radius-lg)',
        boxShadow: 'var(--shadow-sm)',
        overflow: 'hidden'
      }
    }, /*#__PURE__*/React.createElement(Sec, {
      title: "\xDAdaje profilu",
      desc: "Aktualizujte meno a e-mail v\xE1\u0161ho \xFA\u010Dtu."
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 16
      }
    }, /*#__PURE__*/React.createElement(Input, {
      label: "Meno",
      icon: "pi-user",
      value: info.name,
      onChange: e => setInfo(i => ({
        ...i,
        name: e.target.value
      }))
    }), /*#__PURE__*/React.createElement(Input, {
      label: "E-mail",
      type: "email",
      icon: "pi-envelope",
      value: info.email,
      onChange: e => setInfo(i => ({
        ...i,
        email: e.target.value
      }))
    }), /*#__PURE__*/React.createElement(SaveRow, {
      skey: "info"
    }))), /*#__PURE__*/React.createElement(Divider, null), /*#__PURE__*/React.createElement(Sec, {
      title: "Nastavenia fakt\xFAry",
      desc: "Logo zobrazen\xE9 na PDF fakt\xFArach. Odpor\xFA\u010Dan\xFD form\xE1t PNG, max. 2 MB."
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 0
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 20
      }
    }, /*#__PURE__*/React.createElement("label", {
      style: {
        cursor: 'pointer',
        flexShrink: 0
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        width: 100,
        height: 76,
        border: `2px dashed ${logo ? 'var(--primary)' : 'var(--border-strong)'}`,
        borderRadius: 'var(--radius-lg)',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        background: logo ? 'transparent' : 'var(--surface-muted)',
        overflow: 'hidden',
        transition: 'border-color var(--duration-fast)'
      }
    }, logo ? /*#__PURE__*/React.createElement("img", {
      src: logo,
      alt: "Logo",
      style: {
        width: '100%',
        height: '100%',
        objectFit: 'contain'
      }
    }) : /*#__PURE__*/React.createElement("i", {
      className: "pi pi-image",
      style: {
        fontSize: 28,
        color: 'var(--text-faint)'
      }
    })), /*#__PURE__*/React.createElement("input", {
      type: "file",
      accept: "image/*",
      style: {
        display: 'none'
      },
      onChange: e => {
        const f = e.target.files[0];
        if (f) {
          const r = new FileReader();
          r.onload = ev => setLogo(ev.target.result);
          r.readAsDataURL(f);
        }
      }
    })), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
      style: {
        margin: 0,
        fontSize: 14,
        fontWeight: 500,
        color: 'var(--text-strong)'
      }
    }, logo ? 'Logo nahraté' : 'Žiadne logo'), /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '4px 0 8px',
        fontSize: 13,
        color: 'var(--text-muted)'
      }
    }, "Kliknite na plochu a vyberte s\xFAbor."), logo && /*#__PURE__*/React.createElement("button", {
      onClick: () => setLogo(null),
      style: {
        background: 'none',
        border: 'none',
        cursor: 'pointer',
        fontSize: 13,
        color: 'var(--danger)',
        padding: 0
      }
    }, "Odstr\xE1ni\u0165 logo"))), /*#__PURE__*/React.createElement(SaveRow, {
      skey: "logo"
    }))), /*#__PURE__*/React.createElement(Divider, null), /*#__PURE__*/React.createElement(Sec, {
      title: "Faktura\u010Dn\xE9 \xFAdaje",
      desc: "\xDAdaje vystavite\u013Ea na fakt\xFArach \u2014 adresa, identifik\xE1tory, platobn\xFD \xFA\u010Det."
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 16
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: '1fr 1fr',
        gap: 16
      }
    }, /*#__PURE__*/React.createElement(Select, {
      label: "Predvolen\xE1 mena",
      value: billing.currency_id,
      options: CURRENCIES,
      onChange: e => setB('currency_id', e.target.value)
    }), /*#__PURE__*/React.createElement(Select, {
      label: "Predvolen\xFD typ DPH",
      value: billing.default_vat_type_id,
      options: VAT_TYPES,
      onChange: e => setB('default_vat_type_id', e.target.value)
    }), /*#__PURE__*/React.createElement(Input, {
      label: "I\u010CO",
      value: billing.ico,
      onChange: e => setB('ico', e.target.value)
    }), /*#__PURE__*/React.createElement(Input, {
      label: "DI\u010C",
      value: billing.dic,
      onChange: e => setB('dic', e.target.value)
    })), /*#__PURE__*/React.createElement(Input, {
      label: "I\u010C DPH",
      value: billing.ic_dph,
      onChange: e => setB('ic_dph', e.target.value),
      placeholder: "SK2012345678",
      hint: "Vypl\u0148te len ak je firma platite\u013Eom DPH."
    }), /*#__PURE__*/React.createElement(Input, {
      label: "IBAN",
      icon: "pi-credit-card",
      value: billing.iban,
      onChange: e => setB('iban', e.target.value)
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: '2fr 1fr',
        gap: 16
      }
    }, /*#__PURE__*/React.createElement(Input, {
      label: "Ulica",
      value: billing.street,
      onChange: e => setB('street', e.target.value)
    }), /*#__PURE__*/React.createElement(Input, {
      label: "\u010C\xEDslo",
      value: billing.street_num,
      onChange: e => setB('street_num', e.target.value)
    })), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: '2fr 1fr',
        gap: 16
      }
    }, /*#__PURE__*/React.createElement(Input, {
      label: "Mesto",
      value: billing.city,
      onChange: e => setB('city', e.target.value)
    }), /*#__PURE__*/React.createElement(Input, {
      label: "PS\u010C",
      value: billing.zip,
      onChange: e => setB('zip', e.target.value)
    })), /*#__PURE__*/React.createElement(Select, {
      label: "Krajina",
      value: billing.state,
      options: COUNTRIES,
      onChange: e => setB('state', e.target.value)
    }), /*#__PURE__*/React.createElement(SaveRow, {
      skey: "billing"
    }))), /*#__PURE__*/React.createElement(Divider, null), /*#__PURE__*/React.createElement(Sec, {
      title: "Zmena hesla",
      desc: "Pou\u017E\xEDvajte dlh\xE9, n\xE1hodn\xE9 heslo pre v\xE4\u010D\u0161iu bezpe\u010Dnos\u0165 v\xE1\u0161ho \xFA\u010Dtu."
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 16
      }
    }, /*#__PURE__*/React.createElement(Input, {
      label: "Aktu\xE1lne heslo",
      type: "password",
      icon: "pi-lock",
      value: pw.current,
      onChange: e => setP('current', e.target.value)
    }), /*#__PURE__*/React.createElement(Input, {
      label: "Nov\xE9 heslo",
      type: "password",
      icon: "pi-lock",
      value: pw.password,
      onChange: e => setP('password', e.target.value)
    }), /*#__PURE__*/React.createElement(Input, {
      label: "Potvrdenie nov\xE9ho hesla",
      type: "password",
      icon: "pi-lock",
      value: pw.confirm,
      onChange: e => setP('confirm', e.target.value)
    }), /*#__PURE__*/React.createElement(SaveRow, {
      skey: "pw"
    })))), /*#__PURE__*/React.createElement("div", {
      style: {
        marginTop: 24,
        background: 'var(--surface-card)',
        border: '1px solid var(--danger)',
        borderRadius: 'var(--radius-lg)',
        overflow: 'hidden'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'grid',
        gridTemplateColumns: isMobile ? '1fr' : '260px 1fr',
        gap: isMobile ? 12 : 48,
        padding: isMobile ? 20 : 28,
        alignItems: isMobile ? 'start' : 'center'
      }
    }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h2", {
      style: {
        margin: 0,
        fontSize: 15,
        fontWeight: 600,
        color: 'var(--danger)'
      }
    }, "Zmaza\u0165 \xFA\u010Det"), /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '6px 0 0',
        fontSize: 13,
        color: 'var(--text-muted)',
        lineHeight: 1.55
      }
    }, "Po zmazan\xED sa natrvalo odstr\xE1nia v\u0161etky \xFAdaje. T\xFAto akciu nie je mo\u017En\xE9 vr\xE1ti\u0165 sp\xE4\u0165.")), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement(Button, {
      variant: "danger",
      icon: "pi-trash",
      onClick: () => setDelModal(true)
    }, "Zmaza\u0165 \xFA\u010Det")))), delModal && /*#__PURE__*/React.createElement("div", {
      onClick: () => setDelModal(false),
      style: {
        position: 'fixed',
        inset: 0,
        background: 'rgba(17,24,39,.45)',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        zIndex: 50,
        padding: 24
      }
    }, /*#__PURE__*/React.createElement("div", {
      onClick: e => e.stopPropagation(),
      style: {
        background: '#fff',
        border: '1px solid var(--border)',
        borderRadius: 'var(--radius-lg)',
        boxShadow: 'var(--shadow-lg)',
        width: '100%',
        maxWidth: 420,
        padding: 28
      }
    }, /*#__PURE__*/React.createElement("h2", {
      style: {
        margin: '0 0 8px',
        fontSize: 'var(--text-xl)',
        fontWeight: 600,
        color: 'var(--text-strong)'
      }
    }, "Naozaj chcete zmaza\u0165 \xFA\u010Det?"), /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '0 0 20px',
        fontSize: 14,
        color: 'var(--text-muted)',
        lineHeight: 1.5
      }
    }, "Po zmazan\xED sa natrvalo odstr\xE1nia v\u0161etky va\u0161e fakt\xFAry, klienti a nastavenia. Zadajte heslo na potvrdenie."), /*#__PURE__*/React.createElement(Input, {
      label: "Heslo",
      type: "password",
      icon: "pi-lock",
      value: delPw,
      onChange: e => setDelPw(e.target.value)
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        justifyContent: 'flex-end',
        gap: 8,
        marginTop: 20
      }
    }, /*#__PURE__*/React.createElement(Button, {
      variant: "secondary",
      onClick: () => {
        setDelModal(false);
        setDelPw('');
      }
    }, "Zru\u0161i\u0165"), /*#__PURE__*/React.createElement(Button, {
      variant: "danger",
      icon: "pi-trash",
      onClick: () => {
        setDelModal(false);
        setDelPw('');
        onToast('Účet bol zmazaný.');
      }
    }, "Zmaza\u0165 \xFA\u010Det")))));
  }

  /* ---------- Klienti (Recipients) ---------- */
  function Recipients({
    recipients,
    onToast,
    onNewRecipient
  }) {
    const isMobile = window.useIsMobile();
    const [q, setQ] = React.useState('');
    const filtered = recipients.filter(r => (r.name + r.ico + r.email + r.city).toLowerCase().includes(q.toLowerCase()));
    const initials = name => name.split(/\s+/).slice(0, 2).map(w => w[0]).join('').toUpperCase();
    return /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 24
      }
    }, /*#__PURE__*/React.createElement(InvPageHeader, {
      title: "Klienti",
      subtitle: `${recipients.length} klientov celkom`
    }, /*#__PURE__*/React.createElement(Button, {
      icon: "pi-plus",
      onClick: onNewRecipient
    }, "Nov\xFD klient")), /*#__PURE__*/React.createElement("div", {
      style: {
        maxWidth: 320
      }
    }, /*#__PURE__*/React.createElement(Input, {
      icon: "pi-search",
      placeholder: "Vyh\u013Eada\u0165 klienta\u2026",
      value: q,
      onChange: e => setQ(e.target.value)
    })), isMobile ? /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 10
      }
    }, filtered.map(r => /*#__PURE__*/React.createElement("article", {
      key: r.id,
      style: {
        background: 'var(--surface-card)',
        border: '1px solid var(--border)',
        borderRadius: 'var(--radius-lg)',
        boxShadow: 'var(--shadow-sm)',
        overflow: 'hidden'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 12,
        padding: '14px 16px 12px'
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        width: 42,
        height: 42,
        borderRadius: 'var(--radius-full)',
        background: 'var(--primary-soft)',
        color: 'var(--primary-active)',
        display: 'inline-flex',
        alignItems: 'center',
        justifyContent: 'center',
        fontSize: 14,
        fontWeight: 700,
        flexShrink: 0,
        fontFamily: 'var(--font-sans)'
      }
    }, initials(r.name)), /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 0
      }
    }, /*#__PURE__*/React.createElement("p", {
      style: {
        margin: 0,
        fontSize: 15,
        fontWeight: 700,
        color: 'var(--text-strong)',
        fontFamily: 'var(--font-sans)'
      }
    }, r.name), /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '2px 0 0',
        fontSize: 12,
        color: 'var(--text-muted)',
        overflow: 'hidden',
        textOverflow: 'ellipsis',
        whiteSpace: 'nowrap',
        fontFamily: 'var(--font-sans)'
      }
    }, r.email))), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: 6,
        padding: '0 16px 12px'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        justifyContent: 'space-between',
        gap: 8
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        color: 'var(--text-muted)',
        fontFamily: 'var(--font-sans)'
      }
    }, "I\u010CO"), /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        fontWeight: 500,
        color: 'var(--text-strong)',
        fontFamily: 'var(--font-sans)',
        fontVariantNumeric: 'tabular-nums'
      }
    }, r.ico)), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        justifyContent: 'space-between',
        gap: 8
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        color: 'var(--text-muted)',
        fontFamily: 'var(--font-sans)'
      }
    }, "Mesto"), /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        fontWeight: 500,
        color: 'var(--text-strong)',
        fontFamily: 'var(--font-sans)'
      }
    }, r.city)), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        justifyContent: 'space-between',
        gap: 8
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        color: 'var(--text-muted)',
        fontFamily: 'var(--font-sans)'
      }
    }, "Fakturovan\xE9"), /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 13,
        fontWeight: 700,
        color: 'var(--text-strong)',
        fontFamily: 'var(--font-sans)',
        fontVariantNumeric: 'tabular-nums'
      }
    }, fmt(r.total), " \u20AC"))), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'flex-end',
        gap: 2,
        padding: '8px 10px',
        borderTop: '1px solid var(--border-subtle)'
      }
    }, /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-pencil",
      onClick: () => onToast('Úprava klienta ' + r.name)
    }), /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-trash",
      onClick: () => onToast('Klient ' + r.name + ' zmazaný'),
      style: {
        color: 'var(--danger)'
      }
    })))), filtered.length === 0 && /*#__PURE__*/React.createElement("p", {
      style: {
        margin: 0,
        textAlign: 'center',
        color: 'var(--text-muted)',
        fontSize: 14,
        padding: '32px 0',
        fontFamily: 'var(--font-sans)'
      }
    }, "Pre zadan\xFD v\xFDraz sa nena\u0161iel \u017Eiadny klient.")) : /*#__PURE__*/React.createElement(Card, {
      padding: "none",
      style: {
        overflowX: 'auto'
      }
    }, /*#__PURE__*/React.createElement("table", {
      style: {
        width: '100%',
        minWidth: 580,
        borderCollapse: 'collapse',
        fontSize: 14
      }
    }, /*#__PURE__*/React.createElement("thead", null, /*#__PURE__*/React.createElement("tr", {
      style: {
        borderBottom: '1px solid var(--border)'
      }
    }, ['Klient', 'IČO', 'Mesto', 'Faktúry', 'Fakturované', ''].map((h, i) => /*#__PURE__*/React.createElement("th", {
      key: i,
      style: {
        textAlign: i === 3 || i === 4 ? 'right' : 'left',
        padding: '12px 16px',
        fontSize: 12,
        fontWeight: 600,
        color: 'var(--text-muted)',
        textTransform: 'uppercase',
        letterSpacing: 'var(--tracking-wide)',
        whiteSpace: 'nowrap'
      }
    }, h)))), /*#__PURE__*/React.createElement("tbody", null, filtered.map(r => /*#__PURE__*/React.createElement("tr", {
      key: r.id,
      style: {
        borderBottom: '1px solid var(--border-subtle)'
      }
    }, /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 12
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        width: 38,
        height: 38,
        borderRadius: 'var(--radius-full)',
        background: 'var(--primary-soft)',
        color: 'var(--primary-active)',
        display: 'inline-flex',
        alignItems: 'center',
        justifyContent: 'center',
        fontSize: 13,
        fontWeight: 600,
        flexShrink: 0
      }
    }, initials(r.name)), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
      style: {
        fontWeight: 600,
        color: 'var(--text-strong)'
      }
    }, r.name), /*#__PURE__*/React.createElement("div", {
      style: {
        fontSize: 12,
        color: 'var(--text-muted)'
      }
    }, r.email)))), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        color: 'var(--text-body)',
        fontVariantNumeric: 'tabular-nums'
      }
    }, r.ico), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        color: 'var(--text-muted)'
      }
    }, r.city), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        textAlign: 'right',
        color: 'var(--text-body)',
        fontVariantNumeric: 'tabular-nums'
      }
    }, r.invoices), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        textAlign: 'right',
        fontWeight: 600,
        color: 'var(--text-strong)',
        fontVariantNumeric: 'tabular-nums',
        whiteSpace: 'nowrap'
      }
    }, fmt(r.total), " \u20AC"), /*#__PURE__*/React.createElement("td", {
      style: {
        padding: '12px 16px',
        whiteSpace: 'nowrap',
        textAlign: 'right'
      }
    }, /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-pencil",
      onClick: () => onToast('Úrava klienta ' + r.name)
    }), /*#__PURE__*/React.createElement(Button, {
      variant: "text",
      size: "sm",
      icon: "pi-trash",
      onClick: () => onToast('Klient ' + r.name + ' zmazaný'),
      style: {
        color: 'var(--danger)'
      }
    })))), filtered.length === 0 && /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("td", {
      colSpan: 6,
      style: {
        padding: '40px 16px',
        textAlign: 'center',
        color: 'var(--text-muted)',
        fontSize: 14
      }
    }, "Pre zadan\xFD v\xFDraz sa nena\u0161iel \u017Eiadny klient."))))));
  }
  Object.assign(window, {
    InvDashboard: Dashboard,
    InvInvoices: Invoices,
    InvAutomations: Automations,
    InvProfile: Profile,
    InvRecipients: Recipients,
    INV_SEED_INVOICES: SEED_INVOICES,
    INV_SEED_AUTOMATIONS: SEED_AUTOMATIONS,
    INV_SEED_RECIPIENTS: SEED_RECIPIENTS,
    INV_TODAY: todayStr
  });
})();
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/invoicius/screens.jsx", error: String((e && e.message) || e) }); }

// ui_kits/invoicius/shell.jsx
try { (() => {
/* Invoicius UI kit — shell.jsx (sidebar layout)
   White 248px sidebar: logo top · nav middle · user bottom */
(function () {
  const {
    Badge
  } = window.InvoiciusDesignSystem_31395d;
  const NAV_ITEMS = [{
    key: 'dashboard',
    label: 'Prehľad',
    icon: 'pi-home'
  }, {
    key: 'invoices',
    label: 'Faktúry',
    icon: 'pi-file'
  }, {
    key: 'recipients',
    label: 'Klienti',
    icon: 'pi-users'
  }, {
    key: 'automatizations',
    label: 'Automatizácie',
    icon: 'pi-bolt'
  }];
  const MENU_BTN = {
    display: 'block',
    width: '100%',
    textAlign: 'left',
    background: 'none',
    border: 'none',
    cursor: 'pointer',
    padding: '8px 10px',
    borderRadius: 'var(--radius-sm)',
    fontFamily: 'var(--font-sans)',
    fontSize: 14,
    color: 'var(--text-body)'
  };
  function Sidebar({
    current,
    onNavigate,
    user,
    onLogout,
    isMobile,
    mobileOpen,
    onMobileClose
  }) {
    const [menu, setMenu] = React.useState(false);
    const initials = n => n.split(/\s+/).slice(0, 2).map(w => w[0]).join('').toUpperCase();
    return /*#__PURE__*/React.createElement(React.Fragment, null, isMobile && mobileOpen && /*#__PURE__*/React.createElement("div", {
      onClick: onMobileClose,
      style: {
        position: 'fixed',
        inset: 0,
        background: 'rgba(17,24,39,.45)',
        zIndex: 38
      }
    }), /*#__PURE__*/React.createElement("aside", {
      style: {
        position: 'fixed',
        left: 0,
        top: 0,
        bottom: 0,
        width: 248,
        background: 'var(--surface-card)',
        borderRight: '1px solid var(--border)',
        display: 'flex',
        flexDirection: 'column',
        zIndex: 39,
        userSelect: 'none',
        transform: isMobile ? mobileOpen ? 'translateX(0)' : 'translateX(-260px)' : 'none',
        transition: 'transform var(--duration) var(--ease)',
        boxShadow: isMobile && mobileOpen ? 'var(--shadow-lg)' : 'none'
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        padding: '22px 20px 20px',
        borderBottom: '1px solid var(--border-subtle)',
        flexShrink: 0
      }
    }, /*#__PURE__*/React.createElement("img", {
      src: "../../assets/logo-wordmark.svg",
      height: "28",
      alt: "Invoicius",
      style: {
        display: 'block'
      }
    })), /*#__PURE__*/React.createElement("nav", {
      style: {
        flex: 1,
        padding: '10px',
        display: 'flex',
        flexDirection: 'column',
        gap: 2,
        overflowY: 'auto'
      }
    }, NAV_ITEMS.map(it => {
      const active = current === it.key;
      return /*#__PURE__*/React.createElement("button", {
        key: it.key,
        onClick: () => onNavigate(it.key),
        style: {
          display: 'flex',
          alignItems: 'center',
          gap: 10,
          width: '100%',
          padding: '9px 12px',
          borderRadius: 'var(--radius-md)',
          textAlign: 'left',
          background: active ? 'var(--primary-soft)' : 'transparent',
          color: active ? 'var(--primary-active)' : 'var(--text-body)',
          fontFamily: 'var(--font-sans)',
          fontSize: 14,
          fontWeight: active ? 600 : 500,
          border: 'none',
          cursor: 'pointer',
          transition: 'background var(--duration-fast) var(--ease), color var(--duration-fast) var(--ease)'
        },
        onMouseEnter: e => {
          if (!active) e.currentTarget.style.background = 'var(--surface-muted)';
        },
        onMouseLeave: e => {
          if (!active) e.currentTarget.style.background = 'transparent';
        }
      }, /*#__PURE__*/React.createElement("i", {
        className: `pi ${it.icon}`,
        style: {
          fontSize: 16,
          width: 20,
          textAlign: 'center',
          flexShrink: 0
        }
      }), /*#__PURE__*/React.createElement("span", null, it.label));
    })), /*#__PURE__*/React.createElement("div", {
      style: {
        padding: '10px',
        borderTop: '1px solid var(--border-subtle)',
        flexShrink: 0,
        position: 'relative'
      }
    }, menu && /*#__PURE__*/React.createElement("div", {
      style: {
        position: 'absolute',
        bottom: 'calc(100% + 4px)',
        left: 10,
        right: 10,
        background: '#fff',
        borderRadius: 'var(--radius-md)',
        boxShadow: 'var(--shadow-md)',
        border: '1px solid var(--border)',
        padding: 6,
        zIndex: 40
      }
    }, /*#__PURE__*/React.createElement("button", {
      onClick: () => {
        setMenu(false);
        onNavigate('profile');
      },
      style: MENU_BTN
    }, /*#__PURE__*/React.createElement("i", {
      className: "pi pi-user",
      style: {
        marginRight: 8,
        fontSize: 13
      }
    }), "Profil"), /*#__PURE__*/React.createElement("div", {
      style: {
        height: 1,
        background: 'var(--border-subtle)',
        margin: '4px 0'
      }
    }), /*#__PURE__*/React.createElement("button", {
      onClick: () => {
        setMenu(false);
        onLogout();
      },
      style: {
        ...MENU_BTN,
        color: 'var(--danger)'
      }
    }, /*#__PURE__*/React.createElement("i", {
      className: "pi pi-sign-out",
      style: {
        marginRight: 8,
        fontSize: 13
      }
    }), "Odhl\xE1si\u0165 sa")), /*#__PURE__*/React.createElement("button", {
      onClick: () => setMenu(m => !m),
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 10,
        width: '100%',
        padding: '8px 10px',
        borderRadius: 'var(--radius-md)',
        background: menu ? 'var(--surface-muted)' : 'transparent',
        border: 'none',
        cursor: 'pointer',
        fontFamily: 'var(--font-sans)'
      },
      onMouseEnter: e => {
        if (!menu) e.currentTarget.style.background = 'var(--surface-muted)';
      },
      onMouseLeave: e => {
        if (!menu) e.currentTarget.style.background = menu ? 'var(--surface-muted)' : 'transparent';
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        width: 32,
        height: 32,
        borderRadius: 'var(--radius-full)',
        background: 'var(--primary)',
        color: '#fff',
        display: 'inline-flex',
        alignItems: 'center',
        justifyContent: 'center',
        fontSize: 12,
        fontWeight: 700,
        flexShrink: 0
      }
    }, initials(user.name)), /*#__PURE__*/React.createElement("span", {
      style: {
        flex: 1,
        textAlign: 'left',
        minWidth: 0
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        display: 'block',
        fontSize: 13,
        fontWeight: 600,
        color: 'var(--text-strong)',
        overflow: 'hidden',
        textOverflow: 'ellipsis',
        whiteSpace: 'nowrap'
      }
    }, user.name), /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 11,
        color: 'var(--text-muted)'
      }
    }, "Admin")), /*#__PURE__*/React.createElement("i", {
      className: "pi pi-chevron-up",
      style: {
        fontSize: 10,
        color: 'var(--text-faint)',
        transition: `transform var(--duration-fast) var(--ease)`,
        transform: menu ? 'rotate(180deg)' : 'none'
      }
    })))));
  }
  function PageHeader({
    title,
    subtitle,
    children
  }) {
    return /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'flex-start',
        justifyContent: 'space-between',
        gap: 12,
        marginBottom: 24
      }
    }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h1", {
      style: {
        margin: 0,
        fontSize: 'var(--text-3xl)',
        fontWeight: 600,
        letterSpacing: 'var(--tracking-tight)',
        color: 'var(--text-strong)'
      }
    }, title), subtitle && /*#__PURE__*/React.createElement("p", {
      style: {
        margin: '4px 0 0',
        fontSize: 'var(--text-sm)',
        color: 'var(--text-muted)'
      }
    }, subtitle)), /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        gap: 8,
        flexShrink: 0
      }
    }, children));
  }
  function Toast({
    msg
  }) {
    if (!msg) return null;
    return /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        alignItems: 'center',
        gap: 8,
        background: 'var(--success-soft)',
        color: 'var(--success-text)',
        border: '1px solid var(--emerald-200)',
        borderRadius: 'var(--radius-md)',
        padding: '10px 14px',
        fontSize: 14,
        fontWeight: 500,
        boxShadow: 'var(--shadow-sm)',
        marginBottom: 20
      }
    }, /*#__PURE__*/React.createElement("i", {
      className: "pi pi-check-circle"
    }), " ", msg);
  }
  function statusToBadge(code) {
    const map = {
      paid: ['paid', 'Uhradené'],
      awaiting: ['awaiting', 'Čaká na úhradu'],
      overdue: ['overdue', 'Po splatnosti'],
      sent: ['sent', 'Odoslané'],
      draft: ['draft', 'Koncept']
    };
    const [s, label] = map[code] || ['draft', code];
    return React.createElement(Badge, {
      status: s
    }, label);
  }
  Object.assign(window, {
    InvSidebar: Sidebar,
    InvPageHeader: PageHeader,
    InvToast: Toast,
    statusToBadge
  });
})();
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/invoicius/shell.jsx", error: String((e && e.message) || e) }); }

__ds_ns.Badge = __ds_scope.Badge;

__ds_ns.Button = __ds_scope.Button;

__ds_ns.Card = __ds_scope.Card;

__ds_ns.StatCard = __ds_scope.StatCard;

__ds_ns.Checkbox = __ds_scope.Checkbox;

__ds_ns.Input = __ds_scope.Input;

__ds_ns.Select = __ds_scope.Select;

})();
