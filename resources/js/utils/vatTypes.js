/**
 * Human-readable Slovak label for a VAT type row from the API.
 */
export function formatVatTypeLabel(vatType) {
    const code = String(vatType?.code ?? '').toUpperCase();
    const rate = Number.parseFloat(vatType?.rate);

    if (code === 'MIMO') {
        return 'Mimo DPH';
    }
    if (code === 'OSVO') {
        return 'Oslobodené od DPH';
    }
    if (!Number.isNaN(rate) && code !== '') {
        return `${code} %`;
    }

    return code || '—';
}
