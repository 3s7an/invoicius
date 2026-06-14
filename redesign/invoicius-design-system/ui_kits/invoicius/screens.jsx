/* Invoicius UI kit — screens. Faithful to the real app:
   Prehľad (dashboard hero + 2 panels), Faktúry, Automatizácie, Profil.
   Exports screen components + shared data to window for app.jsx. */
(function () {
  const DS = window.InvoiciusDesignSystem_31395d;
  const { Button, Card, Badge, Input, Select, Checkbox, StatCard } = DS;
  const { InvPageHeader } = window;

  const fmt = (n) => n.toLocaleString('sk-SK', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  const todayStr = '14. 6. 2026';

  const SEED_INVOICES = [
    { id: 1, number: '2026-0042', recipient: 'Aurora s.r.o.', date: '12. 6. 2026', amount: 2480.0, status: 'paid' },
    { id: 2, number: '2026-0041', recipient: 'Bistro Lipa', date: '9. 6. 2026', amount: 640.5, status: 'awaiting' },
    { id: 3, number: '2026-0040', recipient: 'Kovomont a.s.', date: '2. 6. 2026', amount: 5120.0, status: 'overdue' },
    { id: 4, number: '2026-0039', recipient: 'Studio Nord', date: '28. 5. 2026', amount: 980.0, status: 'sent' },
    { id: 5, number: '2026-0038', recipient: 'Pekáreň Zora', date: '21. 5. 2026', amount: 312.4, status: 'paid' },
    { id: 6, number: '2026-0037', recipient: 'Tatra Build', date: '15. 5. 2026', amount: 7450.0, status: 'draft' },
  ];

  const SEED_AUTOMATIONS = [
    { id: 1, type: 'invoice_auto_gen', recipient: 'Aurora s.r.o.', next: '1. 7. 2026', active: true },
    { id: 2, type: 'invoice_report', recipient: null, next: '1. 7. 2026', active: true },
    { id: 3, type: 'invoice_due_reminder', recipient: 'Kovomont a.s.', next: '20. 6. 2026', active: true },
    { id: 4, type: 'invoice_auto_gen', recipient: 'Bistro Lipa', next: '5. 7. 2026', active: false },
  ];

  const AUTO_LABEL = {
    invoice_auto_gen: 'Automatické generovanie faktúr',
    invoice_report: 'Mesačný report',
    invoice_due_reminder: 'Pripomienka splatnosti',
  };
  const AUTO_ICON = {
    invoice_auto_gen: 'pi-file',
    invoice_report: 'pi-chart-bar',
    invoice_due_reminder: 'pi-bell',
  };

  const STATUS_OPTS = [
    { value: 'paid', label: 'Uhradené' }, { value: 'awaiting', label: 'Čaká na úhradu' },
    { value: 'overdue', label: 'Po splatnosti' }, { value: 'sent', label: 'Odoslané' }, { value: 'draft', label: 'Koncept' },
  ];
  const STATUS_LABEL = Object.fromEntries(STATUS_OPTS.map((o) => [o.value, o.label]));

  const SEED_RECIPIENTS = [
    { id: 1, name: 'Aurora s.r.o.', ico: '52 814 003', email: 'fakturacia@aurora.sk', city: 'Bratislava', invoices: 12, total: 28640.0 },
    { id: 2, name: 'Bistro Lipa', ico: '47 110 925', email: 'lipa@bistro.sk', city: 'Nitra', invoices: 8, total: 5120.5 },
    { id: 3, name: 'Kovomont a.s.', ico: '36 215 487', email: 'uctaren@kovomont.sk', city: 'Žilina', invoices: 21, total: 94300.0 },
    { id: 4, name: 'Studio Nord', ico: '53 902 144', email: 'hello@studionord.sk', city: 'Košice', invoices: 5, total: 7820.0 },
    { id: 5, name: 'Pekáreň Zora', ico: '46 778 310', email: 'objednavky@zora.sk', city: 'Trnava', invoices: 17, total: 12480.4 },
    { id: 6, name: 'Tatra Build', ico: '50 661 209', email: 'faktury@tatrabuild.sk', city: 'Poprad', invoices: 3, total: 21450.0 },
  ];

  /* ---------- Donut (CSS conic-gradient) ---------- */
  function Donut({ segments, size = 168 }) {
    const total = segments.reduce((s, x) => s + x.value, 0) || 1;
    let acc = 0;
    const stops = segments.filter((s) => s.value > 0).map((s) => {
      const start = (acc / total) * 360; acc += s.value;
      return `${s.color} ${start}deg ${(acc / total) * 360}deg`;
    }).join(', ');
    return (
      <div style={{ position: 'relative', width: size, height: size, flexShrink: 0, borderRadius: '50%', background: `conic-gradient(${stops || 'var(--gray-200) 0deg 360deg'})` }}>
        <div style={{ position: 'absolute', inset: 17, borderRadius: '50%', background: '#fff', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
          <span style={{ fontSize: 18, fontWeight: 600, color: 'var(--text-strong)', fontVariantNumeric: 'tabular-nums' }}>{fmt(total)} €</span>
          <span style={{ fontSize: 12, color: 'var(--text-muted)' }}>fakturované</span>
        </div>
      </div>
    );
  }

  /* ---------- Panel (dashboard 2-up) ---------- */
  function Panel({ title, action, children }) {
    return (
      <Card padding="none" style={{ display: 'flex', flexDirection: 'column' }}>
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 12, padding: '14px 20px', borderBottom: '1px solid var(--border-subtle)' }}>
          <h3 style={{ margin: 0, fontSize: 'var(--text-sm)', fontWeight: 600, color: 'var(--text-strong)' }}>{title}</h3>
          {action}
        </div>
        <div style={{ flex: 1 }}>{children}</div>
      </Card>
    );
  }

  /* ---------- Dashboard ---------- */
  function Dashboard({ invoices, automations, userName, onNew, onNavigate }) {
    const isMobile = window.useIsMobile();
    const sum = (st) => invoices.filter((i) => i.status === st).reduce((s, i) => s + i.amount, 0);
    const segs = [
      { key: 'paid', label: 'Uhradené', value: sum('paid'), color: 'var(--status-paid)' },
      { key: 'awaiting', label: 'Čaká na úhradu', value: sum('awaiting') + sum('sent'), color: 'var(--status-awaiting)' },
      { key: 'overdue', label: 'Po splatnosti', value: sum('overdue'), color: 'var(--status-overdue)' },
      { key: 'draft', label: 'Koncept', value: sum('draft'), color: 'var(--status-draft)' },
    ];
    const activeAuto = automations.filter((a) => a.active);
    const recent = invoices.slice(0, 5);

    return (
      <div style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>
        <InvPageHeader title="Prehľad" subtitle={`Vitaj späť, ${userName}.`}>
          <Button icon="pi-plus" onClick={onNew}>Nová faktúra</Button>
        </InvPageHeader>

        {/* Hero: counts + status donut */}
        <div style={{ display: 'grid', gridTemplateColumns: isMobile ? 'repeat(2, 1fr)' : 'repeat(3, 1fr)', gap: 16 }}>
          <StatCard label="Faktúry" value={invoices.length} hint="celkom v systéme" icon="pi-file" accent="sky" />
          <StatCard label="Klienti" value="34" hint="celkom v systéme" icon="pi-users" accent="violet" />
          <StatCard label="Aktívne automatizácie" value={activeAuto.length} hint="bežia automaticky" icon="pi-bolt" accent="amber" />
        </div>

        <Card title="Fakturované podľa stavu" subtitle="Rozdelenie celkovej fakturovanej sumy">
          <div style={{ display: 'flex', alignItems: 'center', gap: 40, flexWrap: 'wrap' }}>
            <Donut segments={segs} />
            <div style={{ display: 'flex', flexDirection: 'column', gap: 14, flex: 1, minWidth: 220 }}>
              {segs.map((s) => (
                <div key={s.key} style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                  <span style={{ width: 10, height: 10, borderRadius: 9999, background: s.color, flexShrink: 0 }} />
                  <span style={{ fontSize: 14, color: 'var(--text-body)', flex: 1 }}>{s.label}</span>
                  <span style={{ fontSize: 14, fontWeight: 600, color: 'var(--text-strong)', fontVariantNumeric: 'tabular-nums' }}>{fmt(s.value)} €</span>
                </div>
              ))}
            </div>
          </div>
        </Card>

        {/* Two panels */}
        <div style={{ display: 'grid', gridTemplateColumns: isMobile ? '1fr' : 'repeat(2, 1fr)', gap: 24 }}>
          <Panel title="Posledné faktúry" action={<Button variant="text" size="sm" icon="pi-arrow-right" iconPos="right" onClick={() => onNavigate('invoices')}>Zobraziť všetky</Button>}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 14 }}>
              <tbody>
                {recent.map((inv) => (
                  <tr key={inv.id} style={{ borderBottom: '1px solid var(--border-subtle)' }}>
                    <td style={{ padding: '12px 20px' }}>
                      <div style={{ fontWeight: 600, color: 'var(--text-strong)' }}>{inv.number}</div>
                      <div style={{ fontSize: 12, color: 'var(--text-muted)' }}>{inv.date}</div>
                    </td>
                    <td style={{ padding: '12px 8px', color: 'var(--text-body)' }}>{inv.recipient}</td>
                    <td style={{ padding: '12px 8px' }}><Badge status={inv.status}>{STATUS_LABEL[inv.status]}</Badge></td>
                    <td style={{ padding: '12px 20px', textAlign: 'right', fontWeight: 600, color: 'var(--text-strong)', fontVariantNumeric: 'tabular-nums', whiteSpace: 'nowrap' }}>{fmt(inv.amount)} €</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </Panel>

          <Panel title="Aktívne automatizácie" action={<Button variant="text" size="sm" icon="pi-cog" onClick={() => onNavigate('automatizations')}>Spravovať</Button>}>
            {activeAuto.length === 0 ? (
              <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 10, padding: '40px 0', color: 'var(--text-muted)' }}>
                <i className="pi pi-bolt" style={{ fontSize: 30, color: 'var(--gray-300)' }} />
                <p style={{ margin: 0, fontSize: 14 }}>Zatiaľ nemáš žiadne aktívne automatizácie.</p>
              </div>
            ) : (
              <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 14 }}>
                <tbody>
                  {activeAuto.map((a) => (
                    <tr key={a.id} style={{ borderBottom: '1px solid var(--border-subtle)' }}>
                      <td style={{ padding: '12px 20px' }}>
                        <div style={{ fontWeight: 500, color: 'var(--text-strong)' }}>{AUTO_LABEL[a.type]}</div>
                        <div style={{ fontSize: 12, color: 'var(--text-muted)' }}>{a.recipient || 'Pre všetkých klientov'}</div>
                      </td>
                      <td style={{ padding: '12px 8px', color: 'var(--text-muted)', fontSize: 13, whiteSpace: 'nowrap' }}>Ďalšie: {a.next}</td>
                      <td style={{ padding: '12px 20px', textAlign: 'right' }}><Badge tone="success">Aktívne</Badge></td>
                    </tr>
                  ))}
                </tbody>
              </table>
            )}
          </Panel>
        </div>
      </div>
    );
  }

  /* ---------- Invoices ---------- */
  function Invoices({ invoices, onStatus, onNew, onToast }) {
    const isMobile = window.useIsMobile();
    return (
      <div style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>
        <InvPageHeader title="Faktúry" subtitle={`${invoices.length} faktúr celkom`}>
          <Button icon="pi-plus" onClick={onNew}>Nová faktúra</Button>
        </InvPageHeader>
        {isMobile ? (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
            {invoices.map((inv) => (
              <article key={inv.id} style={{ background: 'var(--surface-card)', border: '1px solid var(--border)', borderRadius: 'var(--radius-lg)', boxShadow: 'var(--shadow-sm)', overflow: 'hidden' }}>
                <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 12, padding: '14px 16px 10px' }}>
                  <div style={{ minWidth: 0 }}>
                    <p style={{ margin: 0, fontSize: 15, fontWeight: 700, color: 'var(--text-strong)', fontFamily: 'var(--font-sans)' }}>{inv.number}</p>
                    <p style={{ margin: '2px 0 0', fontSize: 13, color: 'var(--text-muted)', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', fontFamily: 'var(--font-sans)' }}>{inv.recipient}</p>
                  </div>
                  <p style={{ margin: 0, fontSize: 16, fontWeight: 700, color: 'var(--text-strong)', fontVariantNumeric: 'tabular-nums', flexShrink: 0, fontFamily: 'var(--font-sans)' }}>{fmt(inv.amount)} €</p>
                </div>
                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '6px 16px', padding: '0 16px 12px' }}>
                  <div>
                    <p style={{ margin: 0, fontSize: 10, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.07em', color: 'var(--text-muted)', fontFamily: 'var(--font-sans)' }}>Vytvorené</p>
                    <p style={{ margin: '3px 0 0', fontSize: 13, color: 'var(--text-strong)', fontFamily: 'var(--font-sans)' }}>{inv.date}</p>
                  </div>
                  <div>
                    <p style={{ margin: '0 0 4px', fontSize: 10, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.07em', color: 'var(--text-muted)', fontFamily: 'var(--font-sans)' }}>Stav</p>
                    <Select value={inv.status} options={STATUS_OPTS} onChange={(e) => onStatus(inv.id, e.target.value)} />
                  </div>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'flex-end', gap: 2, padding: '8px 10px', borderTop: '1px solid var(--border-subtle)' }}>
                  <Button variant="text" size="sm" icon="pi-file-pdf" onClick={() => onToast('PDF sa generuje…')} />
                  <Button variant="text" size="sm" icon="pi-pencil" onClick={() => onToast('Úprava faktúry ' + inv.number)} />
                  <Button variant="text" size="sm" icon="pi-trash" onClick={() => onToast('Faktúra ' + inv.number + ' zmazaná')} style={{ color: 'var(--danger)' }} />
                </div>
              </article>
            ))}
          </div>
        ) : (
        <Card padding="none" style={{ overflowX: 'auto' }}>
          <table style={{ width: '100%', minWidth: 660, borderCollapse: 'collapse', fontSize: 14 }}>
            <thead>
              <tr style={{ borderBottom: '1px solid var(--border)' }}>
                {['Číslo faktúry', 'Klient', 'Vytvorené', 'Suma', 'Stav', ''].map((h, i) => (
                  <th key={i} style={{ textAlign: i === 3 ? 'right' : 'left', padding: '12px 16px', fontSize: 12, fontWeight: 600, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: 'var(--tracking-wide)', whiteSpace: 'nowrap' }}>{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {invoices.map((inv) => (
                <tr key={inv.id} style={{ borderBottom: '1px solid var(--border-subtle)' }}>
                  <td style={{ padding: '12px 16px', fontWeight: 600, color: 'var(--text-strong)' }}>{inv.number}</td>
                  <td style={{ padding: '12px 16px', color: 'var(--text-body)' }}>{inv.recipient}</td>
                  <td style={{ padding: '12px 16px', color: 'var(--text-muted)' }}>{inv.date}</td>
                  <td style={{ padding: '12px 16px', textAlign: 'right', fontWeight: 600, color: 'var(--text-strong)', fontVariantNumeric: 'tabular-nums', whiteSpace: 'nowrap' }}>{fmt(inv.amount)} €</td>
                  <td style={{ padding: '12px 16px', width: 180 }}>
                    <Select value={inv.status} options={STATUS_OPTS} onChange={(e) => onStatus(inv.id, e.target.value)} />
                  </td>
                  <td style={{ padding: '12px 16px', whiteSpace: 'nowrap', textAlign: 'right' }}>
                    <Button variant="text" size="sm" icon="pi-file-pdf" onClick={() => onToast('PDF sa generuje…')} />
                    <Button variant="text" size="sm" icon="pi-pencil" onClick={() => onToast('Úprava faktúry ' + inv.number)} />
                    <Button variant="text" size="sm" icon="pi-trash" onClick={() => onToast('Faktúra ' + inv.number + ' zmazaná')} style={{ color: 'var(--danger)' }} />
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </Card>
        )}
      </div>
    );
  }

  /* ---------- Automatizácie ---------- */
  function Automations({ automations, onToggle, onToast, onNewAutomation }) {
    const isMobile = window.useIsMobile();
    return (
      <div style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>
        <InvPageHeader title="Automatizácie" subtitle="Automaticky generujte faktúry, reporty a pripomienky.">
          <Button icon="pi-plus" onClick={onNewAutomation}>Nová automatizácia</Button>
        </InvPageHeader>

        {automations.length === 0 ? (
          <Card><p style={{ margin: 0, color: 'var(--text-muted)', fontSize: 14 }}>Zatiaľ nemáte žiadne automatizácie. Vytvorte jednu na automatické generovanie faktúr alebo mesačný report.</p></Card>
        ) : isMobile ? (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
            {automations.map((a) => (
              <article key={a.id} style={{ background: 'var(--surface-card)', border: '1px solid var(--border)', borderRadius: 'var(--radius-lg)', boxShadow: 'var(--shadow-sm)', overflow: 'hidden' }}>
                <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 10, padding: '14px 16px 10px' }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 10, minWidth: 0 }}>
                    <span style={{ width: 36, height: 36, borderRadius: 'var(--radius-md)', background: 'var(--primary-soft)', color: 'var(--primary-active)', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                      <i className={`pi ${AUTO_ICON[a.type]}`} style={{ fontSize: 16 }} />
                    </span>
                    <p style={{ margin: 0, fontSize: 14, fontWeight: 600, color: 'var(--text-strong)', fontFamily: 'var(--font-sans)' }}>{AUTO_LABEL[a.type]}</p>
                  </div>
                  <Badge tone={a.active ? 'success' : 'neutral'}>{a.active ? 'Aktívne' : 'Neaktívne'}</Badge>
                </div>
                <div style={{ display: 'flex', flexDirection: 'column', gap: 6, padding: '0 16px 12px' }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', gap: 8 }}>
                    <span style={{ fontSize: 13, color: 'var(--text-muted)', fontFamily: 'var(--font-sans)' }}>Klient</span>
                    <span style={{ fontSize: 13, fontWeight: 500, color: 'var(--text-strong)', fontFamily: 'var(--font-sans)' }}>{a.recipient || 'Pre všetkých klientov'}</span>
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', gap: 8 }}>
                    <span style={{ fontSize: 13, color: 'var(--text-muted)', fontFamily: 'var(--font-sans)' }}>Nasledujúce spustenie</span>
                    <span style={{ fontSize: 13, fontWeight: 500, color: 'var(--text-strong)', fontFamily: 'var(--font-sans)' }}>{a.active ? a.next : '—'}</span>
                  </div>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'flex-end', gap: 2, padding: '8px 10px', borderTop: '1px solid var(--border-subtle)' }}>
                  <Button variant="text" size="sm" icon={a.active ? 'pi-pause' : 'pi-play'} onClick={() => onToggle(a.id)} />
                  <Button variant="text" size="sm" icon="pi-pencil" onClick={() => onToast('Úprava automatizácie')} />
                  <Button variant="text" size="sm" icon="pi-trash" onClick={() => onToast('Automatizácia zmazaná')} style={{ color: 'var(--danger)' }} />
                </div>
              </article>
            ))}
          </div>
        ) : (
          <Card padding="none" style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', minWidth: 580, borderCollapse: 'collapse', fontSize: 14 }}>
              <thead>
                <tr style={{ borderBottom: '1px solid var(--border)' }}>
                  {['Typ', 'Klient', 'Nasledujúce spustenie', 'Stav', ''].map((h, i) => (
                    <th key={i} style={{ textAlign: 'left', padding: '12px 16px', fontSize: 12, fontWeight: 600, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: 'var(--tracking-wide)', whiteSpace: 'nowrap' }}>{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {automations.map((a) => (
                  <tr key={a.id} style={{ borderBottom: '1px solid var(--border-subtle)' }}>
                    <td style={{ padding: '12px 16px' }}>
                      <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                        <span style={{ width: 34, height: 34, borderRadius: 'var(--radius-md)', background: 'var(--primary-soft)', color: 'var(--primary-active)', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                          <i className={`pi ${AUTO_ICON[a.type]}`} style={{ fontSize: 15 }} />
                        </span>
                        <span style={{ fontWeight: 500, color: 'var(--text-strong)' }}>{AUTO_LABEL[a.type]}</span>
                      </div>
                    </td>
                    <td style={{ padding: '12px 16px', color: 'var(--text-body)' }}>{a.recipient || <span style={{ color: 'var(--text-muted)' }}>Pre všetkých klientov</span>}</td>
                    <td style={{ padding: '12px 16px', color: 'var(--text-muted)' }}>{a.active ? a.next : '—'}</td>
                    <td style={{ padding: '12px 16px' }}><Badge tone={a.active ? 'success' : 'neutral'}>{a.active ? 'Aktívne' : 'Neaktívne'}</Badge></td>
                    <td style={{ padding: '12px 16px', whiteSpace: 'nowrap', textAlign: 'right' }}>
                      <Button variant="text" size="sm" icon={a.active ? 'pi-pause' : 'pi-play'} onClick={() => onToggle(a.id)} title={a.active ? 'Deaktivovať' : 'Aktivovať'} />
                      <Button variant="text" size="sm" icon="pi-pencil" onClick={() => onToast('Úprava automatizácie')} />
                      <Button variant="text" size="sm" icon="pi-trash" onClick={() => onToast('Automatizácia zmazaná')} style={{ color: 'var(--danger)' }} />
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </Card>
          )}
      </div>
    );
  }

  /* ---------- Profil ---------- */
  function Profile({ onToast }) {
    const isMobile = window.useIsMobile();
    const [info, setInfo] = React.useState({ name: 'Tristan Prekop', email: 'test@example.com' });
    const [logo, setLogo] = React.useState(null);
    const [billing, setBilling] = React.useState({
      currency_id: 'EUR', default_vat_type_id: '20',
      ico: '52 814 003', dic: '2121160224', ic_dph: '',
      iban: 'SK89 1100 0000 0029 1234 5678',
      street: 'Hlavná', street_num: '1', city: 'Bratislava', zip: '811 01', state: 'SK',
    });
    const [pw, setPw] = React.useState({ current: '', password: '', confirm: '' });
    const [saved, setSaved] = React.useState({});
    const [delModal, setDelModal] = React.useState(false);
    const [delPw, setDelPw] = React.useState('');

    const flash = (key) => { setSaved((s) => ({ ...s, [key]: true })); setTimeout(() => setSaved((s) => ({ ...s, [key]: false })), 2500); };
    const setB = (k, v) => setBilling((b) => ({ ...b, [k]: v }));
    const setP = (k, v) => setPw((p) => ({ ...p, [k]: v }));

    const COUNTRIES = [
      { value: 'SK', label: 'Slovensko' }, { value: 'CZ', label: 'Česko' },
      { value: 'AT', label: 'Rakúsko' }, { value: 'HU', label: 'Maďarsko' },
      { value: 'PL', label: 'Poľsko' }, { value: 'DE', label: 'Nemecko' },
    ];
    const CURRENCIES = [
      { value: 'EUR', label: 'Euro (€)' }, { value: 'CZK', label: 'Česká koruna (Kč)' }, { value: 'USD', label: 'USD ($)' },
    ];
    const VAT_TYPES = [
      { value: '20', label: '20 % — Štandardná sadzba' }, { value: '10', label: '10 % — Znížená sadzba' }, { value: '0', label: '0 % — Oslobodené' },
    ];

    /* sub-components local to Profile */
    const Divider = () => <div style={{ height: 1, background: 'var(--border-subtle)', margin: '0' }} />;

    const Sec = ({ title, desc, children }) => (
      <div style={{ display: 'grid', gridTemplateColumns: isMobile ? '1fr' : '260px 1fr', gap: isMobile ? 4 : 48, padding: isMobile ? 20 : 28, alignItems: 'start' }}>
        <div>
          <h2 style={{ margin: 0, fontSize: 15, fontWeight: 600, color: 'var(--text-strong)' }}>{title}</h2>
          <p style={{ margin: '6px 0 0', fontSize: 13, color: 'var(--text-muted)', lineHeight: 1.55 }}>{desc}</p>
        </div>
        <div>{children}</div>
      </div>
    );

    const SaveRow = ({ skey }) => (
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'flex-end', gap: 12, marginTop: 20 }}>
        {saved[skey] && (
          <span style={{ fontSize: 13, color: 'var(--success)', display: 'flex', alignItems: 'center', gap: 5 }}>
            <i className="pi pi-check" /> Uložené.
          </span>
        )}
        <Button onClick={() => flash(skey)}>Uložiť</Button>
      </div>
    );

    return (
      <div style={{ maxWidth: isMobile ? '100%' : 920 }}>
        <InvPageHeader title="Profil" subtitle="Spravujte nastavenia vášho účtu." />

        {/* ── Main settings card ── */}
        <div style={{ background: 'var(--surface-card)', border: '1px solid var(--border)', borderRadius: 'var(--radius-lg)', boxShadow: 'var(--shadow-sm)', overflow: 'hidden' }}>

          {/* 1. Údaje profilu */}
          <Sec title="Údaje profilu" desc="Aktualizujte meno a e-mail vášho účtu.">
            <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
              <Input label="Meno" icon="pi-user" value={info.name} onChange={(e) => setInfo((i) => ({ ...i, name: e.target.value }))} />
              <Input label="E-mail" type="email" icon="pi-envelope" value={info.email} onChange={(e) => setInfo((i) => ({ ...i, email: e.target.value }))} />
              <SaveRow skey="info" />
            </div>
          </Sec>

          <Divider />

          {/* 2. Nastavenia faktúry — logo */}
          <Sec title="Nastavenia faktúry" desc="Logo zobrazené na PDF faktúrach. Odporúčaný formát PNG, max. 2 MB.">
            <div style={{ display: 'flex', flexDirection: 'column', gap: 0 }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 20 }}>
                <label style={{ cursor: 'pointer', flexShrink: 0 }}>
                  <div style={{ width: 100, height: 76, border: `2px dashed ${logo ? 'var(--primary)' : 'var(--border-strong)'}`, borderRadius: 'var(--radius-lg)', display: 'flex', alignItems: 'center', justifyContent: 'center', background: logo ? 'transparent' : 'var(--surface-muted)', overflow: 'hidden', transition: 'border-color var(--duration-fast)' }}>
                    {logo
                      ? <img src={logo} alt="Logo" style={{ width: '100%', height: '100%', objectFit: 'contain' }} />
                      : <i className="pi pi-image" style={{ fontSize: 28, color: 'var(--text-faint)' }} />}
                  </div>
                  <input type="file" accept="image/*" style={{ display: 'none' }} onChange={(e) => { const f = e.target.files[0]; if (f) { const r = new FileReader(); r.onload = (ev) => setLogo(ev.target.result); r.readAsDataURL(f); } }} />
                </label>
                <div>
                  <p style={{ margin: 0, fontSize: 14, fontWeight: 500, color: 'var(--text-strong)' }}>{logo ? 'Logo nahraté' : 'Žiadne logo'}</p>
                  <p style={{ margin: '4px 0 8px', fontSize: 13, color: 'var(--text-muted)' }}>Kliknite na plochu a vyberte súbor.</p>
                  {logo && <button onClick={() => setLogo(null)} style={{ background: 'none', border: 'none', cursor: 'pointer', fontSize: 13, color: 'var(--danger)', padding: 0 }}>Odstrániť logo</button>}
                </div>
              </div>
              <SaveRow skey="logo" />
            </div>
          </Sec>

          <Divider />

          {/* 3. Fakturačné údaje */}
          <Sec title="Fakturačné údaje" desc="Údaje vystaviteľa na faktúrach — adresa, identifikátory, platobný účet.">
            <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 }}>
                <Select label="Predvolená mena" value={billing.currency_id} options={CURRENCIES} onChange={(e) => setB('currency_id', e.target.value)} />
                <Select label="Predvolený typ DPH" value={billing.default_vat_type_id} options={VAT_TYPES} onChange={(e) => setB('default_vat_type_id', e.target.value)} />
                <Input label="IČO" value={billing.ico} onChange={(e) => setB('ico', e.target.value)} />
                <Input label="DIČ" value={billing.dic} onChange={(e) => setB('dic', e.target.value)} />
              </div>
              <Input label="IČ DPH" value={billing.ic_dph} onChange={(e) => setB('ic_dph', e.target.value)} placeholder="SK2012345678" hint="Vyplňte len ak je firma platiteľom DPH." />
              <Input label="IBAN" icon="pi-credit-card" value={billing.iban} onChange={(e) => setB('iban', e.target.value)} />
              <div style={{ display: 'grid', gridTemplateColumns: '2fr 1fr', gap: 16 }}>
                <Input label="Ulica" value={billing.street} onChange={(e) => setB('street', e.target.value)} />
                <Input label="Číslo" value={billing.street_num} onChange={(e) => setB('street_num', e.target.value)} />
              </div>
              <div style={{ display: 'grid', gridTemplateColumns: '2fr 1fr', gap: 16 }}>
                <Input label="Mesto" value={billing.city} onChange={(e) => setB('city', e.target.value)} />
                <Input label="PSČ" value={billing.zip} onChange={(e) => setB('zip', e.target.value)} />
              </div>
              <Select label="Krajina" value={billing.state} options={COUNTRIES} onChange={(e) => setB('state', e.target.value)} />
              <SaveRow skey="billing" />
            </div>
          </Sec>

          <Divider />

          {/* 4. Zmena hesla */}
          <Sec title="Zmena hesla" desc="Používajte dlhé, náhodné heslo pre väčšiu bezpečnosť vášho účtu.">
            <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
              <Input label="Aktuálne heslo" type="password" icon="pi-lock" value={pw.current} onChange={(e) => setP('current', e.target.value)} />
              <Input label="Nové heslo" type="password" icon="pi-lock" value={pw.password} onChange={(e) => setP('password', e.target.value)} />
              <Input label="Potvrdenie nového hesla" type="password" icon="pi-lock" value={pw.confirm} onChange={(e) => setP('confirm', e.target.value)} />
              <SaveRow skey="pw" />
            </div>
          </Sec>
        </div>

        {/* ── Danger zone ── */}
        <div style={{ marginTop: 24, background: 'var(--surface-card)', border: '1px solid var(--danger)', borderRadius: 'var(--radius-lg)', overflow: 'hidden' }}>
          <div style={{ display: 'grid', gridTemplateColumns: isMobile ? '1fr' : '260px 1fr', gap: isMobile ? 12 : 48, padding: isMobile ? 20 : 28, alignItems: isMobile ? 'start' : 'center' }}>
            <div>
              <h2 style={{ margin: 0, fontSize: 15, fontWeight: 600, color: 'var(--danger)' }}>Zmazať účet</h2>
              <p style={{ margin: '6px 0 0', fontSize: 13, color: 'var(--text-muted)', lineHeight: 1.55 }}>Po zmazaní sa natrvalo odstránia všetky údaje. Túto akciu nie je možné vrátiť späť.</p>
            </div>
            <div>
              <Button variant="danger" icon="pi-trash" onClick={() => setDelModal(true)}>Zmazať účet</Button>
            </div>
          </div>
        </div>

        {/* ── Delete confirmation modal ── */}
        {delModal && (
          <div onClick={() => setDelModal(false)} style={{ position: 'fixed', inset: 0, background: 'rgba(17,24,39,.45)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 50, padding: 24 }}>
            <div onClick={(e) => e.stopPropagation()} style={{ background: '#fff', border: '1px solid var(--border)', borderRadius: 'var(--radius-lg)', boxShadow: 'var(--shadow-lg)', width: '100%', maxWidth: 420, padding: 28 }}>
              <h2 style={{ margin: '0 0 8px', fontSize: 'var(--text-xl)', fontWeight: 600, color: 'var(--text-strong)' }}>Naozaj chcete zmazať účet?</h2>
              <p style={{ margin: '0 0 20px', fontSize: 14, color: 'var(--text-muted)', lineHeight: 1.5 }}>Po zmazaní sa natrvalo odstránia všetky vaše faktúry, klienti a nastavenia. Zadajte heslo na potvrdenie.</p>
              <Input label="Heslo" type="password" icon="pi-lock" value={delPw} onChange={(e) => setDelPw(e.target.value)} />
              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 20 }}>
                <Button variant="secondary" onClick={() => { setDelModal(false); setDelPw(''); }}>Zrušiť</Button>
                <Button variant="danger" icon="pi-trash" onClick={() => { setDelModal(false); setDelPw(''); onToast('Účet bol zmazaný.'); }}>Zmazať účet</Button>
              </div>
            </div>
          </div>
        )}
      </div>
    );
  }

  /* ---------- Klienti (Recipients) ---------- */
  function Recipients({ recipients, onToast, onNewRecipient }) {
    const isMobile = window.useIsMobile();
    const [q, setQ] = React.useState('');
    const filtered = recipients.filter((r) => (r.name + r.ico + r.email + r.city).toLowerCase().includes(q.toLowerCase()));
    const initials = (name) => name.split(/\s+/).slice(0, 2).map((w) => w[0]).join('').toUpperCase();
    return (
      <div style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>
        <InvPageHeader title="Klienti" subtitle={`${recipients.length} klientov celkom`}>
          <Button icon="pi-plus" onClick={onNewRecipient}>Nový klient</Button>
        </InvPageHeader>
        <div style={{ maxWidth: 320 }}>
          <Input icon="pi-search" placeholder="Vyhľadať klienta…" value={q} onChange={(e) => setQ(e.target.value)} />
        </div>
        {isMobile ? (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
            {filtered.map((r) => (
              <article key={r.id} style={{ background: 'var(--surface-card)', border: '1px solid var(--border)', borderRadius: 'var(--radius-lg)', boxShadow: 'var(--shadow-sm)', overflow: 'hidden' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '14px 16px 12px' }}>
                  <span style={{ width: 42, height: 42, borderRadius: 'var(--radius-full)', background: 'var(--primary-soft)', color: 'var(--primary-active)', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', fontSize: 14, fontWeight: 700, flexShrink: 0, fontFamily: 'var(--font-sans)' }}>{initials(r.name)}</span>
                  <div style={{ minWidth: 0 }}>
                    <p style={{ margin: 0, fontSize: 15, fontWeight: 700, color: 'var(--text-strong)', fontFamily: 'var(--font-sans)' }}>{r.name}</p>
                    <p style={{ margin: '2px 0 0', fontSize: 12, color: 'var(--text-muted)', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', fontFamily: 'var(--font-sans)' }}>{r.email}</p>
                  </div>
                </div>
                <div style={{ display: 'flex', flexDirection: 'column', gap: 6, padding: '0 16px 12px' }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', gap: 8 }}>
                    <span style={{ fontSize: 13, color: 'var(--text-muted)', fontFamily: 'var(--font-sans)' }}>IČO</span>
                    <span style={{ fontSize: 13, fontWeight: 500, color: 'var(--text-strong)', fontFamily: 'var(--font-sans)', fontVariantNumeric: 'tabular-nums' }}>{r.ico}</span>
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', gap: 8 }}>
                    <span style={{ fontSize: 13, color: 'var(--text-muted)', fontFamily: 'var(--font-sans)' }}>Mesto</span>
                    <span style={{ fontSize: 13, fontWeight: 500, color: 'var(--text-strong)', fontFamily: 'var(--font-sans)' }}>{r.city}</span>
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', gap: 8 }}>
                    <span style={{ fontSize: 13, color: 'var(--text-muted)', fontFamily: 'var(--font-sans)' }}>Fakturované</span>
                    <span style={{ fontSize: 13, fontWeight: 700, color: 'var(--text-strong)', fontFamily: 'var(--font-sans)', fontVariantNumeric: 'tabular-nums' }}>{fmt(r.total)} €</span>
                  </div>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'flex-end', gap: 2, padding: '8px 10px', borderTop: '1px solid var(--border-subtle)' }}>
                  <Button variant="text" size="sm" icon="pi-pencil" onClick={() => onToast('Úprava klienta ' + r.name)} />
                  <Button variant="text" size="sm" icon="pi-trash" onClick={() => onToast('Klient ' + r.name + ' zmazaný')} style={{ color: 'var(--danger)' }} />
                </div>
              </article>
            ))}
            {filtered.length === 0 && <p style={{ margin: 0, textAlign: 'center', color: 'var(--text-muted)', fontSize: 14, padding: '32px 0', fontFamily: 'var(--font-sans)' }}>Pre zadaný výraz sa nenašiel žiadny klient.</p>}
          </div>
        ) : (
        <Card padding="none" style={{ overflowX: 'auto' }}>
          <table style={{ width: '100%', minWidth: 580, borderCollapse: 'collapse', fontSize: 14 }}>
            <thead>
              <tr style={{ borderBottom: '1px solid var(--border)' }}>
                {['Klient', 'IČO', 'Mesto', 'Faktúry', 'Fakturované', ''].map((h, i) => (
                  <th key={i} style={{ textAlign: i === 3 || i === 4 ? 'right' : 'left', padding: '12px 16px', fontSize: 12, fontWeight: 600, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: 'var(--tracking-wide)', whiteSpace: 'nowrap' }}>{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {filtered.map((r) => (
                <tr key={r.id} style={{ borderBottom: '1px solid var(--border-subtle)' }}>
                  <td style={{ padding: '12px 16px' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                      <span style={{ width: 38, height: 38, borderRadius: 'var(--radius-full)', background: 'var(--primary-soft)', color: 'var(--primary-active)', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, fontWeight: 600, flexShrink: 0 }}>{initials(r.name)}</span>
                      <div>
                        <div style={{ fontWeight: 600, color: 'var(--text-strong)' }}>{r.name}</div>
                        <div style={{ fontSize: 12, color: 'var(--text-muted)' }}>{r.email}</div>
                      </div>
                    </div>
                  </td>
                  <td style={{ padding: '12px 16px', color: 'var(--text-body)', fontVariantNumeric: 'tabular-nums' }}>{r.ico}</td>
                  <td style={{ padding: '12px 16px', color: 'var(--text-muted)' }}>{r.city}</td>
                  <td style={{ padding: '12px 16px', textAlign: 'right', color: 'var(--text-body)', fontVariantNumeric: 'tabular-nums' }}>{r.invoices}</td>
                  <td style={{ padding: '12px 16px', textAlign: 'right', fontWeight: 600, color: 'var(--text-strong)', fontVariantNumeric: 'tabular-nums', whiteSpace: 'nowrap' }}>{fmt(r.total)} €</td>
                  <td style={{ padding: '12px 16px', whiteSpace: 'nowrap', textAlign: 'right' }}>
                    <Button variant="text" size="sm" icon="pi-pencil" onClick={() => onToast('Úrava klienta ' + r.name)} />
                    <Button variant="text" size="sm" icon="pi-trash" onClick={() => onToast('Klient ' + r.name + ' zmazaný')} style={{ color: 'var(--danger)' }} />
                  </td>
                </tr>
              ))}
              {filtered.length === 0 && (
                <tr><td colSpan={6} style={{ padding: '40px 16px', textAlign: 'center', color: 'var(--text-muted)', fontSize: 14 }}>Pre zadaný výraz sa nenašiel žiadny klient.</td></tr>
              )}
            </tbody>
          </table>
        </Card>
        )}
      </div>
    );
  }

  Object.assign(window, {
    InvDashboard: Dashboard, InvInvoices: Invoices, InvAutomations: Automations,
    InvProfile: Profile, InvRecipients: Recipients,
    INV_SEED_INVOICES: SEED_INVOICES, INV_SEED_AUTOMATIONS: SEED_AUTOMATIONS,
    INV_SEED_RECIPIENTS: SEED_RECIPIENTS, INV_TODAY: todayStr,
  });
})();
