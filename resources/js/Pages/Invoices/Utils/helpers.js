export function prefixedId(prefix, name) {
    if (!prefix) return name;
    return `${prefix}-${name}`;
}

export const toYMD = (date) => date.toISOString().slice(0, 10);
export const todayYMD = () => toYMD(new Date());
export const defaultDueYMD = (days = 14) => toYMD(new Date(Date.now() + days * 24 * 60 * 60 * 1000));

export const formatDate = (date) => (date ? String(date).slice(0, 10) : '');
export const nullIfBlank = (value) => (value === '' || value == null ? null : value);

