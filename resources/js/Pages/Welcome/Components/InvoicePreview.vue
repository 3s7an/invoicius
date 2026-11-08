<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { DEMO_INVOICE, DEMO_VAT_RATE, invoiceTotals } from '../Utils/demo';
import { useDemoFormat } from '../Composables/useDemoFormat';

const { t } = useI18n();
const { money, amount, date } = useDemoFormat();

defineProps<{
    color: string
}>();

const invoice = DEMO_INVOICE;
const totals = invoiceTotals(invoice.items, DEMO_VAT_RATE);
</script>

<template>
    <div class="doc" :style="{ '--inv': color }">
        <div class="doc-top" />
        <div class="doc-body">
            <div class="doc-head">
                <span class="doc-logo"><i />{{ invoice.supplier.logo }}</span>
                <div class="doc-no">
                    <b>{{ t('welcome.invoicePreview.title', { number: invoice.number }) }}</b>
                    <span class="g-mono">
                        {{ t('welcome.invoicePreview.issued', { issued: date(invoice.issuedAt), due: date(invoice.dueAt) }) }}
                    </span>
                </div>
            </div>

            <div class="doc-parties">
                <div>
                    <span class="doc-label">{{ t('welcome.invoicePreview.supplier') }}</span>
                    <b>{{ invoice.supplier.name }}</b>
                    <span class="g-mono">{{ invoice.supplier.ids }}</span>
                </div>
                <div>
                    <span class="doc-label">{{ t('welcome.invoicePreview.customer') }}</span>
                    <b>{{ invoice.customer.name }}</b>
                    <span class="g-mono">{{ invoice.customer.ids }}</span>
                </div>
            </div>

            <div class="doc-table">
                <table>
                    <thead>
                        <tr>
                            <th>{{ t('welcome.invoicePreview.item') }}</th>
                            <th class="num">{{ t('welcome.invoicePreview.qty') }}</th>
                            <th class="num">{{ t('welcome.invoicePreview.unitPrice') }}</th>
                            <th class="num">{{ t('welcome.invoicePreview.vat') }}</th>
                            <th class="num">{{ t('welcome.invoicePreview.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in invoice.items" :key="item.key">
                            <td>{{ t(`welcome.invoicePreview.items.${item.key}`) }}</td>
                            <td class="num">
                                {{ item.quantity }}
                                {{ item.unit === 'pcs' ? t('units.pcs') : t('welcome.invoicePreview.monthsUnit') }}
                            </td>
                            <td class="num">{{ amount(item.unitPrice) }}</td>
                            <td class="num">{{ DEMO_VAT_RATE }} %</td>
                            <td class="num">{{ amount(item.quantity * item.unitPrice) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="doc-foot">
                <div class="pay">
                    <span class="doc-label">{{ t('welcome.invoicePreview.payment') }}</span>
                    <span class="g-mono">IBAN {{ invoice.iban }}</span>
                    <span class="g-mono">{{ t('welcome.invoicePreview.amount') }} {{ money(totals.total) }}</span>
                    <span class="g-mono">VS {{ invoice.variableSymbol }}</span>
                    <span>{{ invoice.supplier.name }}</span>
                </div>
                <div class="sums">
                    <div><span>{{ t('welcome.invoicePreview.base') }}</span><span class="g-mono">{{ money(totals.base) }}</span></div>
                    <div><span>{{ t('welcome.invoicePreview.vatRate', { rate: DEMO_VAT_RATE }) }}</span><span class="g-mono">{{ money(totals.vat) }}</span></div>
                    <div class="sum-total"><span>{{ t('welcome.invoicePreview.grandTotal') }}</span><span class="g-mono">{{ money(totals.total) }}</span></div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* The invoice is a paper document: fixed light palette on purpose. */
.doc {
    --inv: #10b981;
    overflow: hidden;
    border: 1px solid #e3e6e1;
    border-radius: 14px;
    background: #ffffff;
    color: #1a1f1c;
    font-size: 12.5px;
}
.doc-top {
    height: 6px;
    background: var(--inv);
    transition: background-color 0.3s ease;
}
.doc-body {
    display: grid;
    gap: 18px;
    padding: 22px 24px 20px;
}
.doc-head {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}
.doc-logo {
    display: flex;
    align-items: center;
    gap: 8px;
    font: 600 15px/1 var(--g-font);
    letter-spacing: -0.02em;
}
.doc-logo i {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    background: var(--inv);
    transition: background-color 0.3s ease;
}
.doc-no { text-align: right; }
.doc-no b {
    display: block;
    font: 600 17px/1.1 var(--g-font);
    letter-spacing: -0.02em;
}
.doc-no span { color: #737b75; }
.doc-parties {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.doc-parties div {
    display: grid;
    gap: 2px;
    min-width: 0;
}
.doc-parties .g-mono {
    color: #5b635d;
    font-size: 11.5px;
}
.doc-label {
    font: 500 10.5px/1.6 var(--g-font-data);
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--inv);
    filter: brightness(0.82);
}
.doc-table { overflow-x: auto; }
.doc table {
    width: 100%;
    min-width: 380px;
    border-collapse: collapse;
}
.doc th {
    padding: 0 0 8px;
    border-bottom: 1px solid #e3e6e1;
    text-align: left;
    font: 500 10.5px/1 var(--g-font-data);
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: #737b75;
}
.doc td {
    padding: 9px 0;
    border-bottom: 1px solid #eef0ec;
}
.doc .num {
    text-align: right;
    font-family: var(--g-font-data);
    font-variant-numeric: tabular-nums;
}
.doc-foot {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: end;
    gap: 20px;
}
/* Mirrors the payment block of the real PDF (resources/views/pdf/invoice.blade.php). */
.pay {
    display: grid;
    gap: 2px;
    justify-self: start;
    padding: 12px 14px;
    border: 1px solid #e3e6e1;
    border-radius: 10px;
    background: #fafbfa;
    font-size: 11.5px;
    color: #5b635d;
}
.pay .g-mono {
    font-size: 11px;
    color: #1a1f1c;
}
.sums {
    display: grid;
    gap: 4px;
    justify-self: end;
    min-width: 200px;
    margin-left: auto;
    max-width: 280px;
}
.sums div {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    color: #5b635d;
}
.sums .sum-total {
    margin-top: 6px;
    padding-top: 8px;
    border-top: 1px solid #e3e6e1;
    color: #1a1f1c;
    font-weight: 600;
    font-size: 15px;
}
@media (max-width: 520px) {
    .doc-parties,
    .doc-foot { grid-template-columns: 1fr; }
    .sums { max-width: none; margin-left: 0; }
}
</style>
