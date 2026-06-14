// templates/invoicius-app/ds-base.js
// Loads the Invoicius Design System (global CSS + compiled component bundle).
// Consuming projects: point `base` at the bound _ds/<folder> tree relative to
// this page (e.g. '_ds/invoicius' at project root, '../_ds/invoicius' one level down).
(() => {
  const base = '../..';
  for (const p of ['styles.css']) {
    const l = document.createElement('link');
    l.rel = 'stylesheet';
    l.href = base + '/' + p;
    document.head.appendChild(l);
  }
  // PrimeIcons (brand icon font) from CDN
  const pi = document.createElement('link');
  pi.rel = 'stylesheet';
  pi.href = 'https://unpkg.com/primeicons@7.0.0/primeicons.css';
  document.head.appendChild(pi);

  const s = document.createElement('script');
  s.src = base + '/_ds_bundle.js';
  s.onerror = () => console.error('ds-base.js: failed to load ' + s.src + ' — if this is a consuming project, point the base line in ds-base.js at the bound _ds/<folder> tree relative to this page; in a fresh design system this can just mean the bundle is not compiled yet');
  document.head.appendChild(s);
})();
