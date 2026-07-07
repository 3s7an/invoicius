import { i18n } from '@/i18n';

const UNIT_VALUES = ['pcs', 'hrs', 'days', 'kg', 'm', 'm²'];
const UNIT_KEYS = { pcs: 'pcs', hrs: 'hrs', days: 'days', kg: 'kg', m: 'm', 'm²': 'm2' };

export function getUnitOptions() {
    return UNIT_VALUES.map((value) => ({
        value,
        label: i18n.global.t(`units.${UNIT_KEYS[value]}`),
    }));
}
