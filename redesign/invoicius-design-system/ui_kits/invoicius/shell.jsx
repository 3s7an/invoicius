/* Invoicius UI kit — shell.jsx (sidebar layout)
   White 248px sidebar: logo top · nav middle · user bottom */
(function () {
  const { Badge } = window.InvoiciusDesignSystem_31395d;

  const NAV_ITEMS = [
    { key: 'dashboard',       label: 'Prehľad',        icon: 'pi-home' },
    { key: 'invoices',        label: 'Faktúry',         icon: 'pi-file' },
    { key: 'recipients',      label: 'Klienti',         icon: 'pi-users' },
    { key: 'automatizations', label: 'Automatizácie',   icon: 'pi-bolt' },
  ];

  const MENU_BTN = {
    display: 'block', width: '100%', textAlign: 'left', background: 'none',
    border: 'none', cursor: 'pointer', padding: '8px 10px',
    borderRadius: 'var(--radius-sm)', fontFamily: 'var(--font-sans)',
    fontSize: 14, color: 'var(--text-body)',
  };

  function Sidebar({ current, onNavigate, user, onLogout, isMobile, mobileOpen, onMobileClose }) {
    const [menu, setMenu] = React.useState(false);
    const initials = (n) => n.split(/\s+/).slice(0, 2).map((w) => w[0]).join('').toUpperCase();

    return (
      <>
        {isMobile && mobileOpen && <div onClick={onMobileClose} style={{ position: 'fixed', inset: 0, background: 'rgba(17,24,39,.45)', zIndex: 38 }} />}
        <aside style={{
          position: 'fixed', left: 0, top: 0, bottom: 0, width: 248,
          background: 'var(--surface-card)', borderRight: '1px solid var(--border)',
          display: 'flex', flexDirection: 'column', zIndex: 39, userSelect: 'none',
          transform: isMobile ? (mobileOpen ? 'translateX(0)' : 'translateX(-260px)') : 'none',
          transition: 'transform var(--duration) var(--ease)',
          boxShadow: isMobile && mobileOpen ? 'var(--shadow-lg)' : 'none',
        }}>
        {/* Logo */}
        <div style={{ padding: '22px 20px 20px', borderBottom: '1px solid var(--border-subtle)', flexShrink: 0 }}>
          <img src="../../assets/logo-wordmark.svg" height="28" alt="Invoicius" style={{ display: 'block' }} />
        </div>

        {/* Nav links */}
        <nav style={{ flex: 1, padding: '10px', display: 'flex', flexDirection: 'column', gap: 2, overflowY: 'auto' }}>
          {NAV_ITEMS.map((it) => {
            const active = current === it.key;
            return (
              <button key={it.key} onClick={() => onNavigate(it.key)}
                style={{
                  display: 'flex', alignItems: 'center', gap: 10, width: '100%',
                  padding: '9px 12px', borderRadius: 'var(--radius-md)', textAlign: 'left',
                  background: active ? 'var(--primary-soft)' : 'transparent',
                  color: active ? 'var(--primary-active)' : 'var(--text-body)',
                  fontFamily: 'var(--font-sans)', fontSize: 14, fontWeight: active ? 600 : 500,
                  border: 'none', cursor: 'pointer',
                  transition: 'background var(--duration-fast) var(--ease), color var(--duration-fast) var(--ease)',
                }}
                onMouseEnter={(e) => { if (!active) e.currentTarget.style.background = 'var(--surface-muted)'; }}
                onMouseLeave={(e) => { if (!active) e.currentTarget.style.background = 'transparent'; }}
              >
                <i className={`pi ${it.icon}`} style={{ fontSize: 16, width: 20, textAlign: 'center', flexShrink: 0 }} />
                <span>{it.label}</span>
              </button>
            );
          })}
        </nav>

        {/* User section */}
        <div style={{ padding: '10px', borderTop: '1px solid var(--border-subtle)', flexShrink: 0, position: 'relative' }}>
          {/* Popover */}
          {menu && (
            <div style={{ position: 'absolute', bottom: 'calc(100% + 4px)', left: 10, right: 10, background: '#fff', borderRadius: 'var(--radius-md)', boxShadow: 'var(--shadow-md)', border: '1px solid var(--border)', padding: 6, zIndex: 40 }}>
              <button onClick={() => { setMenu(false); onNavigate('profile'); }} style={MENU_BTN}>
                <i className="pi pi-user" style={{ marginRight: 8, fontSize: 13 }} />Profil
              </button>
              <div style={{ height: 1, background: 'var(--border-subtle)', margin: '4px 0' }} />
              <button onClick={() => { setMenu(false); onLogout(); }} style={{ ...MENU_BTN, color: 'var(--danger)' }}>
                <i className="pi pi-sign-out" style={{ marginRight: 8, fontSize: 13 }} />Odhlásiť sa
              </button>
            </div>
          )}
          <button onClick={() => setMenu((m) => !m)}
            style={{ display: 'flex', alignItems: 'center', gap: 10, width: '100%', padding: '8px 10px', borderRadius: 'var(--radius-md)', background: menu ? 'var(--surface-muted)' : 'transparent', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-sans)' }}
            onMouseEnter={(e) => { if (!menu) e.currentTarget.style.background = 'var(--surface-muted)'; }}
            onMouseLeave={(e) => { if (!menu) e.currentTarget.style.background = menu ? 'var(--surface-muted)' : 'transparent'; }}
          >
            <span style={{ width: 32, height: 32, borderRadius: 'var(--radius-full)', background: 'var(--primary)', color: '#fff', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', fontSize: 12, fontWeight: 700, flexShrink: 0 }}>
              {initials(user.name)}
            </span>
            <span style={{ flex: 1, textAlign: 'left', minWidth: 0 }}>
              <span style={{ display: 'block', fontSize: 13, fontWeight: 600, color: 'var(--text-strong)', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{user.name}</span>
              <span style={{ fontSize: 11, color: 'var(--text-muted)' }}>Admin</span>
            </span>
            <i className="pi pi-chevron-up" style={{ fontSize: 10, color: 'var(--text-faint)', transition: `transform var(--duration-fast) var(--ease)`, transform: menu ? 'rotate(180deg)' : 'none' }} />
          </button>
        </div>
      </aside>
      </>
    );
  }

  function PageHeader({ title, subtitle, children }) {
    return (
      <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 12, marginBottom: 24 }}>
        <div>
          <h1 style={{ margin: 0, fontSize: 'var(--text-3xl)', fontWeight: 600, letterSpacing: 'var(--tracking-tight)', color: 'var(--text-strong)' }}>{title}</h1>
          {subtitle && <p style={{ margin: '4px 0 0', fontSize: 'var(--text-sm)', color: 'var(--text-muted)' }}>{subtitle}</p>}
        </div>
        <div style={{ display: 'flex', gap: 8, flexShrink: 0 }}>{children}</div>
      </div>
    );
  }

  function Toast({ msg }) {
    if (!msg) return null;
    return (
      <div style={{ display: 'flex', alignItems: 'center', gap: 8, background: 'var(--success-soft)', color: 'var(--success-text)', border: '1px solid var(--emerald-200)', borderRadius: 'var(--radius-md)', padding: '10px 14px', fontSize: 14, fontWeight: 500, boxShadow: 'var(--shadow-sm)', marginBottom: 20 }}>
        <i className="pi pi-check-circle" /> {msg}
      </div>
    );
  }

  function statusToBadge(code) {
    const map = { paid: ['paid', 'Uhradené'], awaiting: ['awaiting', 'Čaká na úhradu'], overdue: ['overdue', 'Po splatnosti'], sent: ['sent', 'Odoslané'], draft: ['draft', 'Koncept'] };
    const [s, label] = map[code] || ['draft', code];
    return React.createElement(Badge, { status: s }, label);
  }

  Object.assign(window, { InvSidebar: Sidebar, InvPageHeader: PageHeader, InvToast: Toast, statusToBadge });
})();
