/* Invoicius UI kit — app root + login. Screens live in screens.jsx, forms in forms.jsx. */
(function () {
  const { Button, Card, Input, Checkbox } = window.InvoiciusDesignSystem_31395d;
  const { InvSidebar, InvToast } = window;
  const { InvDashboard, InvInvoices, InvAutomations, InvProfile, InvRecipients,
          INV_SEED_INVOICES, INV_SEED_AUTOMATIONS, INV_SEED_RECIPIENTS, INV_TODAY } = window;
  const { InvInvoiceFormDrawer, InvRecipientFormModal, InvAutomatizationFormModal } = window;

  /* ---------- Login ---------- */
  function Login({ onLogin }) {
    const [email, setEmail] = React.useState('test@example.com');
    const [pw, setPw] = React.useState('password');
    const [rem, setRem] = React.useState(true);
    return (
      <div style={{ minHeight: '100%', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', background: 'var(--surface-app)', padding: 24, boxSizing: 'border-box' }}>
        <img src="../../assets/logo-wordmark.svg" height="34" alt="Invoicius" style={{ marginBottom: 24 }} />
        <div style={{ width: '100%', maxWidth: 400 }}>
          <Card padding="lg">
            <h1 style={{ margin: '0 0 4px', fontSize: 'var(--text-xl)', fontWeight: 600, color: 'var(--text-strong)' }}>Prihlásenie</h1>
            <p style={{ margin: '0 0 20px', fontSize: 14, color: 'var(--text-muted)' }}>Vitajte späť. Zadajte svoje údaje.</p>
            <form onSubmit={(e) => { e.preventDefault(); onLogin(); }}>
              <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
                <Input label="E-mail" type="email" icon="pi-envelope" value={email} onChange={(e) => setEmail(e.target.value)} />
                <Input label="Heslo" type="password" icon="pi-lock" value={pw} onChange={(e) => setPw(e.target.value)} />
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                  <Checkbox checked={rem} label="Zapamätať si ma" onChange={(e) => setRem(e.target.checked)} />
                  <a href="#" onClick={(e) => e.preventDefault()} style={{ fontSize: 13, color: 'var(--text-link)', textDecoration: 'underline' }}>Zabudli ste heslo?</a>
                </div>
                <Button type="submit" fullWidth>Prihlásiť sa</Button>
              </div>
            </form>
          </Card>
          <p style={{ textAlign: 'center', marginTop: 16, fontSize: 13, color: 'var(--text-muted)' }}>
            Nemáte účet? <a href="#" onClick={(e) => e.preventDefault()} style={{ color: 'var(--primary-active)', fontWeight: 600, textDecoration: 'none' }}>Zaregistrujte sa</a>
          </p>
        </div>
      </div>
    );
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

    const flash = (m) => { setToast(m); setTimeout(() => setToast(''), 2600); };
    const closeModal = () => setActiveModal(null);

    if (!authed) return <Login onLogin={() => { setAuthed(true); setPage('dashboard'); }} />;

    /* handlers */
    const setStatus = (id, status) => {
      setInvoices((inv) => inv.map((x) => (x.id === id ? { ...x, status } : x)));
      flash('Stav faktúry bol zmenený.');
    };
    const createInvoice = (inv) => {
      setInvoices((list) => [{ ...inv, id: Date.now() }, ...list]);
      setSeq((s) => s + 1);
      closeModal(); setPage('invoices');
      flash('Faktúra ' + inv.number + ' bola vytvorená.');
    };
    const createRecipient = (rec) => {
      setRecipients((list) => [...list, rec]);
      closeModal();
      flash('Klient ' + rec.name + ' bol pridaný.');
    };
    const createAutomation = (auto) => {
      setAutomations((list) => [...list, auto]);
      closeModal();
      flash('Automatizácia bola vytvorená.');
    };
    const toggleAuto = (id) => {
      setAutomations((a) => a.map((x) => (x.id === id ? { ...x, active: !x.active } : x)));
      flash('Stav automatizácie bol zmenený.');
    };
    const invoiceNumber = `2026-${String(seq).padStart(4, '0')}`;
    const navigate = (p) => { setPage(p); setSidebarOpen(false); };
    const MobileTopBar = window.InvMobileTopBar;

    return (
      <div style={{ display: 'flex', minHeight: '100%', background: 'var(--surface-app)' }}>
        <InvSidebar current={page} onNavigate={navigate} user={{ name: 'Tristan Prekop' }} onLogout={() => setAuthed(false)} isMobile={isMobile} mobileOpen={sidebarOpen} onMobileClose={() => setSidebarOpen(false)} />
        {isMobile && MobileTopBar && <MobileTopBar onMenuOpen={() => setSidebarOpen(true)} userName="Tristan Prekop" onNavigate={navigate} />}
        <main style={{ flex: 1, marginLeft: isMobile ? 0 : 248, padding: isMobile ? '72px 16px 24px' : '32px', minHeight: '100%', boxSizing: 'border-box' }}>
          <InvToast msg={toast} />
          {page === 'dashboard' && (
            <InvDashboard invoices={invoices} automations={automations} userName="Prekop Studio"
              onNew={() => setActiveModal('invoice')} onNavigate={setPage} />
          )}
          {page === 'invoices' && (
            <InvInvoices invoices={invoices} onStatus={setStatus}
              onNew={() => setActiveModal('invoice')} onToast={flash} />
          )}
          {page === 'recipients' && (
            <InvRecipients recipients={recipients} onToast={flash}
              onNewRecipient={() => setActiveModal('recipient')} />
          )}
          {page === 'automatizations' && (
            <InvAutomations automations={automations} onToggle={toggleAuto} onToast={flash}
              onNewAutomation={() => setActiveModal('automation')} />
          )}
          {page === 'profile' && <InvProfile onToast={flash} />}
        </main>

        {/* Modals */}
        {activeModal === 'invoice' && (
          <InvInvoiceFormDrawer
            onClose={closeModal} onCreate={createInvoice}
            recipients={recipients} invoiceNumber={invoiceNumber} />
        )}
        {activeModal === 'recipient' && (
          <InvRecipientFormModal onClose={closeModal} onCreate={createRecipient} />
        )}
        {activeModal === 'automation' && (
          <InvAutomatizationFormModal
            onClose={closeModal} onCreate={createAutomation} recipients={recipients} />
        )}
      </div>
    );
  }

  ReactDOM.createRoot(document.getElementById('root')).render(<App />);
})();
