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
  function MobileTopBar({ onMenuOpen, userName, onNavigate }) {
    const initials = (n) => n.split(/\s+/).slice(0, 2).map((w) => w[0]).join('').toUpperCase();
    return (
      <div style={{
        position: 'fixed', top: 0, left: 0, right: 0, height: 56,
        background: 'var(--surface-card)', borderBottom: '1px solid var(--border)',
        display: 'flex', alignItems: 'center', zIndex: 40,
        paddingLeft: 4, paddingRight: 16,
      }}>
        <button onClick={onMenuOpen} style={{ background: 'none', border: 'none', cursor: 'pointer', padding: '8px 12px', color: 'var(--text-body)', display: 'flex', alignItems: 'center', flexShrink: 0 }}>
          <i className="pi pi-bars" style={{ fontSize: 20 }} />
        </button>
        <div style={{ flex: 1, display: 'flex', justifyContent: 'center' }}>
          <img src="../../assets/logo-wordmark.svg" height="22" alt="Invoicius" style={{ display: 'block' }} />
        </div>
        <button onClick={() => onNavigate('profile')} style={{ width: 34, height: 34, borderRadius: 'var(--radius-full)', background: 'var(--primary)', color: '#fff', border: 'none', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 12, fontWeight: 700, flexShrink: 0 }}>
          {initials(userName)}
        </button>
      </div>
    );
  }

  Object.assign(window, { useIsMobile, InvMobileTopBar: MobileTopBar });
})();
