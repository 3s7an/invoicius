import { i18n } from '@/i18n'
import type { VatTypeResource } from '@/types'

export function formatVatTypeLabel(vatType: VatTypeResource | null | undefined): string {
    const code = String(vatType?.code ?? '').toUpperCase();
    const rate = Number.parseFloat(String(vatType?.rate ?? ''));

    if (code === 'MIMO') {
        return i18n.global.t('vatType.mimo');
    }
    if (code === 'OSVO') {
        return i18n.global.t('vatType.osvo');
    }
    if (!Number.isNaN(rate) && code !== '') {
        return `${code} %`;
    }

    return code || '—';
}
