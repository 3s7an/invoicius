/* Invoicius UI kit — app shell (emerald top nav + page frame).
   Composes the design-system primitives from window.InvoiciusDesignSystem_31395d. */
(function () {
  const { Badge } = window.InvoiciusDesignSystem_31395d;

  function TopNav({ current, onNavigate, user, onLogout }) {
    const items = [
    { key: 'dashboard', label: 'Prehľad' },
    { key: 'invoices', label: 'Faktúry' },
    { key: 'automatizations', label: 'Automatizácie' }];

    const [menu, setMenu] = React.useState(false);
    return (
      <nav style={{ background: 'var(--surface-nav)', borderBottom: '1px solid rgba(0,0,0,.05)' }}>
        <div style={{ maxWidth: 'var(--container-max)', margin: '0 auto', padding: '0 24px', height: 'var(--nav-height)', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 40 }}>
            <img src="../../assets/logo-wordmark-white.svg" height="26" alt="Invoicius" style={{ display: 'block', height: "46px" }} />
            <div style={{ display: 'flex', gap: 32 }}>
              {items.map((it) => {
                const active = current === it.key;
                return (
                  <button key={it.key} onClick={() => onNavigate(it.key)} style={{
                    background: 'none', border: 'none', cursor: 'pointer', padding: '0 0 2px',
                    height: 'var(--nav-height)', fontFamily: 'var(--font-sans)', fontSize: 16,
                    fontWeight: 600, color: active ? '#fff' : 'rgba(255,255,255,.85)',
                    borderBottom: `2px solid ${active ? '#fff' : 'transparent'}`
                  }}>{it.label}</button>);

              })}
            </div>
          </div>
          <div style={{ position: 'relative' }}>
            <button onClick={() => setMenu((m) => !m)} style={{ display: 'flex', alignItems: 'center', gap: 8, background: 'none', border: 'none', cursor: 'pointer', color: 'rgba(255,255,255,.9)', fontFamily: 'var(--font-sans)', fontSize: 14, fontWeight: 500 }}>
              {user.name}
              <i className="pi pi-chevron-down" style={{ fontSize: 12 }} />
            </button>
            {menu &&
            <div style={{ position: 'absolute', right: 0, top: 'calc(100% + 6px)', background: '#fff', borderRadius: 'var(--radius-md)', boxShadow: 'var(--shadow-md)', border: '1px solid var(--border)', minWidth: 180, padding: 6, zIndex: 20 }}>
                <button onClick={() => {setMenu(false);onNavigate('profile');}} style={menuItem}>Profil</button>
                <button onClick={() => {setMenu(false);onLogout();}} style={menuItem}>Odhlásiť sa</button>
              </div>
            }
          </div>
        </div>
      </nav>);

  }
  const menuItem = { display: 'block', width: '100%', textAlign: 'left', background: 'none', border: 'none', cursor: 'pointer', padding: '8px 10px', borderRadius: 'var(--radius-sm)', fontFamily: 'var(--font-sans)', fontSize: 14, color: 'var(--text-body)' };

  function PageHeader({ title, subtitle, children }) {
    return (
      <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 12, marginBottom: 16 }}>
        <div>
          <h1 style={{ margin: 0, fontSize: 'var(--text-3xl)', fontWeight: 600, letterSpacing: 'var(--tracking-tight)', color: 'var(--text-strong)' }}>{title}</h1>
          {subtitle && <p style={{ margin: '4px 0 0', fontSize: 'var(--text-sm)', color: 'var(--text-muted)' }}>{subtitle}</p>}
        </div>
        <div style={{ display: 'flex', gap: 8, flexShrink: 0 }}>{children}</div>
      </div>);

  }

  function Toast({ msg }) {
    if (!msg) return null;
    return (
      <div style={{ maxWidth: 'var(--container-max)', margin: '16px auto 0', padding: '0 24px' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 8, background: 'var(--success-soft)', color: 'var(--success-text)', border: '1px solid var(--emerald-200)', borderRadius: 'var(--radius-md)', padding: '10px 14px', fontSize: 14, fontWeight: 500, boxShadow: 'var(--shadow-sm)' }}>
          <i className="pi pi-check-circle" /> {msg}
        </div>
      </div>);

  }

  function statusToBadge(code) {
    const map = { paid: ['paid', 'Uhradené'], awaiting: ['awaiting', 'Čaká na úhradu'], overdue: ['overdue', 'Po splatnosti'], sent: ['sent', 'Odoslané'], draft: ['draft', 'Koncept'] };
    const [s, label] = map[code] || ['draft', code];
    return <Badge status={s}>{label}</Badge>;
  }

  Object.assign(window, { InvNav: TopNav, InvPageHeader: PageHeader, InvToast: Toast, statusToBadge });
})();