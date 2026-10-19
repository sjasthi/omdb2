/* Shared enhancements; navigation and plain tables stay usable without JavaScript. */
(() => {
    'use strict';
    document.documentElement.classList.add('has-js');
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.getElementById('primary-nav');
    const closeMenu = () => {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
    };
    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const open = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', String(open));
        });
        nav.addEventListener('keydown', event => {
            if (event.key === 'Escape') { closeMenu(); toggle.focus(); }
        });
        const desktop = matchMedia('(min-width: 992px)');
        desktop.addEventListener('change', closeMenu);
    }

    const updateRegion = region => {
        if (!region) { return; }
        const overflow = region.scrollWidth > region.clientWidth + 1;
        region.tabIndex = overflow ? 0 : -1;
        const hint = region.previousElementSibling;
        if (hint && hint.classList.contains('table-hint')) { hint.classList.toggle('is-needed', overflow); }
    };
    const observer = typeof ResizeObserver === 'function' ? new ResizeObserver(entries => {
        entries.forEach(entry => updateRegion(entry.target.closest('.table-scroll')));
    }) : null;
    function wrapTable(table) {
        if (table.closest('.table-scroll') || table.parentElement.closest('table')) { return; }
        const region = document.createElement('div');
        region.className = 'table-scroll';
        region.setAttribute('role', 'region');
        region.setAttribute('aria-label', 'Data table. Scroll sideways to view all columns.');
        const hint = document.createElement('p');
        hint.className = 'table-hint';
        hint.textContent = 'Scroll sideways to view all columns.';
        table.before(hint, region);
        region.append(table);
        updateRegion(region);
        if (observer) { observer.observe(region); observer.observe(table); }
    }
    if (window.jQuery) {
        jQuery(document).on('init.dt', (_event, settings) => wrapTable(settings.nTable));
        jQuery(document).on('draw.dt', (_event, settings) => {
            const region = settings.nTable.closest('.table-scroll');
            if (region) { updateRegion(region); }
        });
    }
    const wrapRemainingTables = () => document.querySelectorAll('.omdb-content table').forEach(wrapTable);
    window.addEventListener('load', wrapRemainingTables, {once:true});
    window.addEventListener('resize', () => document.querySelectorAll('.table-scroll').forEach(updateRegion));
})();
