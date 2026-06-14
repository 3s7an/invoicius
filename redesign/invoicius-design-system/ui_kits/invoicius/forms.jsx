/* Invoicius UI kit — forms.jsx
   Three fully-styled forms faithful to the repo's field structure:
   · InvoiceFormDrawer  (right-side drawer, line-items table, totals)
   · RecipientFormModal (centered modal, 2-col grid)
   · AutomatizationFormModal (centered modal, radio-card type picker) */
(function () {
  const { Button, Input, Select, Checkbox } = window.InvoiciusDesignSystem_31395d;
  const fmt = (n) => n.toLocaleString('sk-SK', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

  /* ── shared primitives ─────────────────────────────────────────── */
  function Scrim({ onClose }) {
    return <div onClick={onClose} style={{ position: 'fixed', inset: 0, background: 'rgba(17,24,39,.45)', zIndex: 49 }} />;
  }

  function SectionHead({ children }) {
    return (
      <div style={{ display: 'flex', alignItems: 'center', gap: 10, margin: '22px 0 14px' }}>
        <span style={{ fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.08em', color: 'var(--text-muted)', whiteSpace: 'nowrap' }}>{children}</span>
        <span style={{ flex: 1, height: 1, background: 'var(--border)' }} />
      </div>
    );
  }

  function TextArea({ label, value, onChange, placeholder, hint, rows = 3, style }) {
    const [focus, setFocus] = React.useState(false);
    return (
      <div style={style}>
        {label && <label style={{ display: 'block', marginBottom: '0.375rem', fontSize: 'var(--text-sm)', fontWeight: 'var(--weight-medium)', color: 'var(--text-body)' }}>{label}</label>}
        <textarea
          value={value} onChange={onChange} placeholder={placeholder} rows={rows}
          onFocus={() => setFocus(true)} onBlur={() => setFocus(false)}
          style={{ width: '100%', boxSizing: 'border-box', padding: '0.5rem 0.75rem', fontFamily: 'var(--font-sans)', fontSize: 'var(--text-sm)', color: 'var(--text-strong)', background: 'var(--surface-card)', border: `1px solid ${focus ? 'var(--primary)' : 'var(--border-strong)'}`, borderRadius: 'var(--radius-sm)', outline: 'none', boxShadow: focus ? '0 0 0 3px var(--focus-ring)' : 'var(--shadow-sm)', resize: 'vertical', transition: 'border-color var(--duration-fast) var(--ease)' }}
        />
        {hint && <p style={{ margin: '0.375rem 0 0', fontSize: 'var(--text-xs)', color: 'var(--text-muted)' }}>{hint}</p>}
      </div>
    );
  }

  /* Right-side drawer */
  function DrawerShell({ title, badge, onClose, footer, children }) {
    return (
      <div style={{ position: 'fixed', right: 0, top: 0, bottom: 0, width: 'min(96vw, 920px)', background: 'var(--surface-card)', boxShadow: '-4px 0 24px rgba(0,0,0,.12)', zIndex: 50, display: 'flex', flexDirection: 'column' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '16px 28px', borderBottom: '1px solid var(--border)', flexShrink: 0 }}>
          <div style={{ flex: 1, display: 'flex', alignItems: 'center', gap: 10, minWidth: 0 }}>
            <h2 style={{ margin: 0, fontSize: 'var(--text-xl)', fontWeight: 600, color: 'var(--text-strong)' }}>{title}</h2>
            {badge && <span style={{ fontSize: 13, color: 'var(--text-muted)', fontVariantNumeric: 'tabular-nums' }}>{badge}</span>}
          </div>
          <button onClick={onClose} style={{ background: 'none', border: 'none', cursor: 'pointer', color: 'var(--text-faint)', fontSize: 20, display: 'flex', alignItems: 'center', padding: 4, borderRadius: 'var(--radius-sm)', flexShrink: 0 }}>
            <i className="pi pi-times" />
          </button>
        </div>
        <div style={{ flex: 1, overflowY: 'auto', padding: '4px 28px 28px' }}>{children}</div>
        <div style={{ padding: '14px 28px', borderTop: '1px solid var(--border)', flexShrink: 0, background: 'var(--surface-muted)' }}>{footer}</div>
      </div>
    );
  }

  /* Centered modal */
  function ModalShell({ title, subtitle, onClose, footer, children, maxWidth = 560 }) {
    return (
      <div onClick={onClose} style={{ position: 'fixed', inset: 0, background: 'rgba(17,24,39,.45)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 50, padding: 24 }}>
        <div onClick={(e) => e.stopPropagation()} style={{ background: 'var(--surface-card)', border: '1px solid var(--border)', borderRadius: 'var(--radius-lg)', boxShadow: 'var(--shadow-lg)', width: '100%', maxWidth, display: 'flex', flexDirection: 'column', maxHeight: '92vh' }}>
          <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12, padding: '16px 24px', borderBottom: '1px solid var(--border)', flexShrink: 0 }}>
            <div style={{ flex: 1 }}>
              <h2 style={{ margin: 0, fontSize: 'var(--text-xl)', fontWeight: 600, color: 'var(--text-strong)' }}>{title}</h2>
              {subtitle && <p style={{ margin: '3px 0 0', fontSize: 13, color: 'var(--text-muted)' }}>{subtitle}</p>}
            </div>
            <button onClick={onClose} style={{ background: 'none', border: 'none', cursor: 'pointer', color: 'var(--text-faint)', fontSize: 20, marginTop: 2 }}><i className="pi pi-times" /></button>
          </div>
          <div style={{ flex: 1, overflowY: 'auto', padding: '4px 24px 20px' }}>{children}</div>
          <div style={{ padding: '14px 24px', borderTop: '1px solid var(--border)', flexShrink: 0, background: 'var(--surface-muted)' }}>{footer}</div>
        </div>
      </div>
    );
  }

  /* ── INVOICE FORM ───────────────────────────────────────────────── */
  function InvoiceFormDrawer({ onClose, onCreate, recipients, invoiceNumber }) {
    const [form, setForm] = React.useState({
      number: invoiceNumber || '2026-0043',
      issue_date: '2026-06-14',
      tax_point_date: '2026-06-14',
      due_date: '2026-06-28',
      status: 'draft',
      recipient_id: '',
      payment_method: 'bank_transfer',
      note: '',
    });
    const [items, setItems] = React.useState([
      { id: 1, description: '', quantity: '1', unit: 'ks', unit_price: '', vat_rate: '20' },
    ]);

    const set = (k, v) => setForm((f) => ({ ...f, [k]: v }));
    const addItem = () => setItems((i) => [...i, { id: Date.now(), description: '', quantity: '1', unit: 'ks', unit_price: '', vat_rate: '20' }]);
    const removeItem = (id) => setItems((i) => i.filter((x) => x.id !== id));
    const setItem = (id, k, v) => setItems((i) => i.map((x) => (x.id === id ? { ...x, [k]: v } : x)));

    const totals = items.reduce((acc, it) => {
      const base = (parseFloat(it.quantity) || 0) * (parseFloat(it.unit_price) || 0);
      const vatAmt = base * (parseFloat(it.vat_rate) || 0) / 100;
      return { base: acc.base + base, vat: acc.vat + vatAmt, total: acc.total + base + vatAmt };
    }, { base: 0, vat: 0, total: 0 });

    const recipientOpts = [
      { value: '', label: '— Vybrať klienta —' },
      ...(recipients || []).map((r) => ({ value: String(r.id), label: r.name })),
    ];
    const statusOpts = [
      { value: 'draft', label: 'Koncept' }, { value: 'sent', label: 'Odoslané' },
      { value: 'awaiting', label: 'Čaká na úhradu' }, { value: 'paid', label: 'Uhradené' },
    ];
    const unitOpts = ['ks', 'hod', 'deň', 'm', 'm²', 'kg'].map((v) => ({ value: v, label: v }));
    const vatOpts = [{ value: '20', label: '20 %' }, { value: '10', label: '10 %' }, { value: '0', label: '0 %' }];
    const payOpts = [{ value: 'bank_transfer', label: 'Bankový prevod' }, { value: 'cash', label: 'Hotovosť' }, { value: 'card', label: 'Kartou' }];

    const handleCreate = (status) => {
      const rec = (recipients || []).find((r) => String(r.id) === String(form.recipient_id));
      const d = form.issue_date.split('-');
      onCreate({ number: form.number, recipient: rec ? rec.name : '—', date: `${d[2]}. ${d[1]}. ${d[0]}`, amount: totals.total, status: status || form.status });
    };

    const COL = '2fr 72px 96px 118px 84px 104px 36px';
    const TH_STYLE = { fontSize: 11, fontWeight: 600, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: '0.06em', display: 'block', paddingBottom: 6 };

    return (
      <>
        <Scrim onClose={onClose} />
        <DrawerShell
          title="Nová faktúra" badge={form.number} onClose={onClose}
          footer={
            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
              <Button variant="secondary" onClick={onClose}>Zrušiť</Button>
              <Button variant="secondary" icon="pi-save" onClick={() => handleCreate('draft')}>Uložiť ako koncept</Button>
              <Button icon="pi-check" onClick={() => handleCreate(form.status)}>Vytvoriť faktúru</Button>
            </div>
          }
        >
          <SectionHead>Základné údaje</SectionHead>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 }}>
            <Input label="Číslo faktúry" value={form.number} disabled onChange={() => {}} hint="Generuje sa automaticky" />
            <Select label="Stav" value={form.status} options={statusOpts} onChange={(e) => set('status', e.target.value)} />
            <Input label="Dátum vystavenia" type="date" value={form.issue_date} onChange={(e) => set('issue_date', e.target.value)} />
            <Input label="Dátum dodania" type="date" value={form.tax_point_date} onChange={(e) => set('tax_point_date', e.target.value)} />
            <Input label="Dátum splatnosti" type="date" value={form.due_date} onChange={(e) => set('due_date', e.target.value)} />
            <Select label="Spôsob úhrady" value={form.payment_method} options={payOpts} onChange={(e) => set('payment_method', e.target.value)} />
          </div>
          <div style={{ marginTop: 16 }}>
            <Select label="Klient" value={form.recipient_id} options={recipientOpts} onChange={(e) => set('recipient_id', e.target.value)} />
          </div>

          <SectionHead>Položky faktúry</SectionHead>
          <div style={{ display: 'grid', gridTemplateColumns: COL, gap: 8, marginBottom: 8 }}>
            {['Popis položky', 'Mn.', 'Jednotka', 'Cena / j.', 'DPH', 'Spolu', ''].map((h, i) => (
              <span key={i} style={{ ...TH_STYLE, textAlign: i >= 3 && i <= 5 ? 'right' : 'left' }}>{h}</span>
            ))}
          </div>
          {items.map((item) => {
            const gross = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0) * (1 + (parseFloat(item.vat_rate) || 0) / 100);
            return (
              <div key={item.id} style={{ display: 'grid', gridTemplateColumns: COL, gap: 8, marginBottom: 10, alignItems: 'start' }}>
                <Input value={item.description} placeholder="Popis položky" onChange={(e) => setItem(item.id, 'description', e.target.value)} />
                <Input value={item.quantity} placeholder="1" onChange={(e) => setItem(item.id, 'quantity', e.target.value)} />
                <Select value={item.unit} options={unitOpts} onChange={(e) => setItem(item.id, 'unit', e.target.value)} />
                <Input value={item.unit_price} placeholder="0,00" onChange={(e) => setItem(item.id, 'unit_price', e.target.value)} />
                <Select value={item.vat_rate} options={vatOpts} onChange={(e) => setItem(item.id, 'vat_rate', e.target.value)} />
                <div style={{ paddingTop: 9, textAlign: 'right', fontSize: 14, fontWeight: 600, color: 'var(--text-strong)', fontVariantNumeric: 'tabular-nums', whiteSpace: 'nowrap' }}>{fmt(gross)} €</div>
                <div style={{ paddingTop: 4 }}>
                  {items.length > 1 && (
                    <Button variant="text" size="sm" icon="pi-trash" onClick={() => removeItem(item.id)} style={{ color: 'var(--text-faint)' }} />
                  )}
                </div>
              </div>
            );
          })}
          <Button variant="ghost" icon="pi-plus" size="sm" onClick={addItem} style={{ marginTop: 4 }}>Pridať položku</Button>

          <div style={{ marginTop: 24, paddingTop: 18, borderTop: '1px solid var(--border)', display: 'flex', justifyContent: 'flex-end' }}>
            <div style={{ minWidth: 300 }}>
              {[['Základ DPH:', totals.base], ['DPH:', totals.vat]].map(([l, v]) => (
                <div key={l} style={{ display: 'flex', justifyContent: 'space-between', gap: 24, marginBottom: 8, fontSize: 14, color: 'var(--text-muted)' }}>
                  <span>{l}</span><span style={{ fontVariantNumeric: 'tabular-nums' }}>{fmt(v)} €</span>
                </div>
              ))}
              <div style={{ display: 'flex', justifyContent: 'space-between', gap: 24, paddingTop: 10, borderTop: '2px solid var(--border-strong)', fontSize: 16, fontWeight: 700, color: 'var(--text-strong)' }}>
                <span>Celkom:</span><span style={{ fontVariantNumeric: 'tabular-nums' }}>{fmt(totals.total)} €</span>
              </div>
            </div>
          </div>

          <SectionHead>Poznámka</SectionHead>
          <TextArea value={form.note} onChange={(e) => set('note', e.target.value)} placeholder="Prípadná poznámka na faktúre…" rows={3} />
        </DrawerShell>
      </>
    );
  }

  /* ── RECIPIENT FORM ─────────────────────────────────────────────── */
  function RecipientFormModal({ onClose, onCreate }) {
    const empty = { name: '', ico: '', dic: '', ic_dph: '', street: '', city: '', zip: '', country: 'Slovensko', email: '', phone: '' };
    const [f, setF] = React.useState(empty);
    const set = (k, v) => setF((x) => ({ ...x, [k]: v }));

    const handleSubmit = (e) => {
      e && e.preventDefault();
      if (!f.name.trim()) return;
      onCreate({ ...f, id: Date.now(), invoices: 0, total: 0 });
    };

    return (
      <ModalShell
        title="Nový klient"
        subtitle="Zadajte fakturačné a kontaktné údaje."
        onClose={onClose}
        footer={
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
            <Button variant="secondary" onClick={onClose}>Zrušiť</Button>
            <Button icon="pi-check" onClick={handleSubmit}>Uložiť klienta</Button>
          </div>
        }
      >
        <SectionHead>Firemné údaje</SectionHead>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
          <Input label="Názov firmy" icon="pi-building" value={f.name} onChange={(e) => set('name', e.target.value)} placeholder="napr. Aurora s.r.o." required />
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>
            <Input label="IČO" value={f.ico} onChange={(e) => set('ico', e.target.value)} placeholder="12 345 678" />
            <Input label="DIČ" value={f.dic} onChange={(e) => set('dic', e.target.value)} placeholder="2012345678" />
          </div>
          <Input label="IČ DPH" value={f.ic_dph} onChange={(e) => set('ic_dph', e.target.value)} placeholder="SK2012345678" hint="Vyplňte len ak je firma platiteľom DPH." />
        </div>

        <SectionHead>Adresa</SectionHead>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
          <Input label="Ulica a číslo" icon="pi-map-marker" value={f.street} onChange={(e) => set('street', e.target.value)} placeholder="Hlavná 1" />
          <div style={{ display: 'grid', gridTemplateColumns: '2fr 1fr', gap: 14 }}>
            <Input label="Mesto" value={f.city} onChange={(e) => set('city', e.target.value)} placeholder="Bratislava" />
            <Input label="PSČ" value={f.zip} onChange={(e) => set('zip', e.target.value)} placeholder="811 01" />
          </div>
          <Input label="Krajina" value={f.country} onChange={(e) => set('country', e.target.value)} />
        </div>

        <SectionHead>Kontaktné údaje</SectionHead>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>
          <Input label="E-mail" type="email" icon="pi-envelope" value={f.email} onChange={(e) => set('email', e.target.value)} placeholder="firma@example.sk" />
          <Input label="Telefón" icon="pi-phone" value={f.phone} onChange={(e) => set('phone', e.target.value)} placeholder="+421 900 000 000" />
        </div>
      </ModalShell>
    );
  }

  /* ── AUTOMATIZATION FORM ────────────────────────────────────────── */
  function AutomatizationFormModal({ onClose, onCreate, recipients }) {
    const AUTO_TYPES = [
      { value: 'invoice_auto_gen', label: 'Automatické generovanie faktúr', icon: 'pi-file', desc: 'Faktúra sa vytvorí automaticky v zvolený deň.' },
      { value: 'invoice_report', label: 'Mesačný report', icon: 'pi-chart-bar', desc: 'Zhrnutie fakturácie za uplynulý mesiac zasielané e-mailom.' },
      { value: 'invoice_due_reminder', label: 'Pripomienka splatnosti', icon: 'pi-bell', desc: 'Upozornenie klientovi pred termínom splatnosti faktúry.' },
    ];
    const FREQ = [{ value: 'monthly', label: 'Mesačne' }, { value: 'weekly', label: 'Týždenne' }, { value: 'quarterly', label: 'Štvrťročne' }];
    const [f, setF] = React.useState({ type: 'invoice_auto_gen', recipient_id: '', frequency: 'monthly', next_run: '2026-07-01', active: true });
    const set = (k, v) => setF((x) => ({ ...x, [k]: v }));

    const needsRecipient = f.type !== 'invoice_report';
    const recipientOpts = [{ value: '', label: '— Pre všetkých klientov —' }, ...(recipients || []).map((r) => ({ value: String(r.id), label: r.name }))];

    const handleSubmit = () => {
      const rec = (recipients || []).find((r) => String(r.id) === String(f.recipient_id));
      const d = f.next_run.split('-');
      onCreate({ id: Date.now(), type: f.type, recipient: rec ? rec.name : null, next: `${d[2]}. ${d[1]}. ${d[0]}`, active: f.active });
    };

    return (
      <ModalShell
        title="Nová automatizácia"
        subtitle="Nastavte automatické akcie pre vaše faktúry."
        onClose={onClose}
        maxWidth={520}
        footer={
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
            <Button variant="secondary" onClick={onClose}>Zrušiť</Button>
            <Button icon="pi-bolt" onClick={handleSubmit}>Vytvoriť automatizáciu</Button>
          </div>
        }
      >
        <SectionHead>Typ automatizácie</SectionHead>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
          {AUTO_TYPES.map((t) => {
            const active = f.type === t.value;
            return (
              <label key={t.value} style={{ display: 'flex', alignItems: 'flex-start', gap: 14, padding: '12px 14px', border: `1.5px solid ${active ? 'var(--primary)' : 'var(--border)'}`, borderRadius: 'var(--radius-md)', background: active ? 'var(--primary-soft)' : 'var(--surface-card)', cursor: 'pointer', transition: 'all var(--duration-fast) var(--ease)' }}>
                <input type="radio" name="auto_type" value={t.value} checked={active} onChange={() => set('type', t.value)} style={{ marginTop: 3, accentColor: 'var(--primary)', flexShrink: 0 }} />
                <span style={{ width: 34, height: 34, borderRadius: 'var(--radius-md)', background: active ? 'var(--primary)' : 'var(--gray-100)', color: active ? '#fff' : 'var(--text-muted)', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, transition: 'background var(--duration-fast) var(--ease)' }}>
                  <i className={`pi ${t.icon}`} style={{ fontSize: 15 }} />
                </span>
                <div>
                  <div style={{ fontWeight: 600, fontSize: 14, color: 'var(--text-strong)' }}>{t.label}</div>
                  <div style={{ fontSize: 13, color: 'var(--text-muted)', marginTop: 2 }}>{t.desc}</div>
                </div>
              </label>
            );
          })}
        </div>

        <SectionHead>Nastavenia</SectionHead>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
          {needsRecipient && (
            <Select label="Klient" value={f.recipient_id} options={recipientOpts} onChange={(e) => set('recipient_id', e.target.value)} hint="Nechajte prázdne pre všetkých klientov." />
          )}
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>
            <Select label="Frekvencia" value={f.frequency} options={FREQ} onChange={(e) => set('frequency', e.target.value)} />
            <Input label="Prvé spustenie" type="date" value={f.next_run} onChange={(e) => set('next_run', e.target.value)} />
          </div>
          <div style={{ paddingTop: 4 }}>
            <Checkbox checked={f.active} label="Aktivovať ihneď po vytvorení" onChange={(e) => set('active', e.target.checked)} />
          </div>
        </div>
      </ModalShell>
    );
  }

  Object.assign(window, {
    InvInvoiceFormDrawer: InvoiceFormDrawer,
    InvRecipientFormModal: RecipientFormModal,
    InvAutomatizationFormModal: AutomatizationFormModal,
  });
})();
