/* Apply before styles load to avoid a bright flash on saved dark-mode pages. */
(() => {
    'use strict';
    const key = 'omdb.theme';
    const root = document.documentElement;
    const system = window.matchMedia('(prefers-color-scheme: dark)');
    const valid = value => value === 'dark' || value === 'light';
    let preference = null;
    try {
        const saved = localStorage.getItem(key);
        if (valid(saved)) { preference = saved; }
    } catch (_error) { /* Theme switching also works when browser storage is unavailable. */ }

    const apply = () => {
        const dark = (preference || (system.matches ? 'dark' : 'light')) === 'dark';
        root.dataset.theme = dark ? 'dark' : 'light';
        document.querySelectorAll('[data-theme-toggle]').forEach(button => {
            button.hidden = false;
            button.setAttribute('aria-pressed', String(dark));
            button.title = dark ? 'Switch to light mode' : 'Switch to dark mode';
        });
    };
    apply();
    const connect = () => {
        document.querySelectorAll('[data-theme-toggle]').forEach(button => {
            button.addEventListener('click', () => {
                preference = root.dataset.theme === 'dark' ? 'light' : 'dark';
                try { localStorage.setItem(key, preference); } catch (_error) { /* Keep the current page usable. */ }
                apply();
            });
        });
        apply();
    };
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', connect, {once: true});
    } else { connect(); }
    const followSystem = () => { if (!preference) { apply(); } };
    if (system.addEventListener) { system.addEventListener('change', followSystem); }
    else if (system.addListener) { system.addListener(followSystem); }
    window.addEventListener('storage', event => {
        if (event.key !== key && event.key !== null) { return; }
        preference = valid(event.newValue) ? event.newValue : null;
        apply();
    });
})();
