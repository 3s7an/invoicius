import type { VatTypeResource } from '@/types'

export function formatVatTypeLabel(vatType: VatTypeResource | null | undefined): string {
    const code = String(vatType?.code ?? '').toUpperCase();
    const rate = Number.parseFloat(String(vatType?.rate ?? ''));

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
