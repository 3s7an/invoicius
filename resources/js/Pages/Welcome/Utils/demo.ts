import type { InvoiceStatusCodeValue } from '@/types';

/**
 * Sample data for the public landing page mock-ups.
 * Nothing here comes from the database — it only illustrates the product.
 */

export type DemoItemKey = 'logo' | 'web' | 'hosting';

export interface DemoInvoiceItem {
    key: DemoItemKey
    quantity: number
    unit: 'pcs' | 'months'
    unitPrice: number
}

export const DEMO_VAT_RATE = 23;

export const DEMO_INVOICE = {
    number: '2026-0043',
    variableSymbol: '20260043',
    issuedAt: new Date(2026, 5, 14),
    dueAt: new Date(2026, 5, 28),
    supplier: { logo: 'Studio Mrak', name: 'Studio Mrak s.r.o.', ids: 'IČO 51 234 567 · DIČ 2120456789' },
    customer: { name: 'Kovomont a.s.', ids: 'IČO 36 718 204 · IČ DPH SK2021876543' },
    iban: 'SK31 1200 0000 1987 4263 7541',
    items: [
        { key: 'logo', quantity: 1, unit: 'pcs', unitPrice: 450 },
        { key: 'web', quantity: 1, unit: 'pcs', unitPrice: 1200 },
        { key: 'hosting', quantity: 12, unit: 'months', unitPrice: 8 },
    ] satisfies DemoInvoiceItem[],
};

export const DEMO_CLIENTS = [
    { initials: 'AU', name: 'Aurora s.r.o.', invoices: 8, turnover: 12480 },
    { initials: 'KO', name: 'Kovomont a.s.', invoices: 5, turnover: 9310 },
    { initials: 'SN', name: 'Studio Nord', invoices: 3, turnover: 2740 },
];

export type InvoiceColorKey = 'emerald' | 'blue' | 'violet' | 'orange' | 'graphite';

export const INVOICE_COLORS: { key: InvoiceColorKey; value: string }[] = [
    { key: 'emerald', value: '#10b981' },
    { key: 'blue', value: '#2563eb' },
    { key: 'violet', value: '#7c3aed' },
    { key: 'orange', value: '#e0612b' },
    { key: 'graphite', value: '#1f2937' },
];

export const DEMO_DASHBOARD = {
    month: new Date(2026, 5, 1),
    revenue: 8420,
    awaiting: 3115,
    byStatus: [
        { status: 'paid', count: 18 },
        { status: 'sent', count: 6 },
        { status: 'overdue', count: 2 },
        { status: 'draft', count: 3 },
    ] satisfies { status: InvoiceStatusCodeValue; count: number }[],
};

export const DEMO_STACK = [
    { number: '2026-0040', client: 'Tatra Build', amount: 640, status: 'overdue' },
    { number: '2026-0041', client: 'Bistro Lipa', amount: 1230, status: 'sent' },
    { number: '2026-0042', client: 'Aurora s.r.o.', amount: 1845, status: 'paid' },
] satisfies { number: string; client: string; amount: number; status: InvoiceStatusCodeValue }[];

export function invoiceTotals(items: DemoInvoiceItem[], vatRate: number) {
    const base = items.reduce((sum, item) => sum + item.quantity * item.unitPrice, 0);
    const vat = Math.round(base * vatRate) / 100;

    return { base, vat, total: base + vat };
}
