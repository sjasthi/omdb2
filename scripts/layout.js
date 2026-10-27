/* Shared enhancements; navigation and plain tables stay usable without JavaScript. */
(() => {
    'use strict';
    if (window.jQuery && jQuery.fn.dataTable) {
        jQuery.fn.dataTable.ext.errMode = 'none';
        jQuery.extend(true, jQuery.fn.dataTable.defaults, {language:{
            emptyTable:'No records have been added yet.', zeroRecords:'No records match these filters. Try a shorter search or clear a filter.',
            search:'Filter results:', lengthMenu:'Show _MENU_ rows', info:'Showing _START_–_END_ of _TOTAL_ records',
            infoEmpty:'No records to show', infoFiltered:'(from _MAX_ records)', processing:'Loading records…',
            paginate:{previous:'Previous',next:'Next'}
        }});
        jQuery(document).on('error.dt', (_event, _settings) => {
            const table = _settings.nTable;
            if (table.parentElement.querySelector('.table-load-error')) { return; }
            const notice = document.createElement('p'); notice.className = 'notice notice--error table-load-error';
            notice.setAttribute('role','alert'); notice.textContent = 'These records could not be loaded. Reload the page to try again.';
            table.before(notice);
            const loading = document.querySelector('.catalog-status'); if (loading) { loading.hidden = true; }
        });
    }
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
    const wrapRemainingTables = () => {
        document.querySelectorAll('.omdb-content table').forEach(wrapTable);
        document.querySelectorAll('.omdb-content a[title], .omdb-content button[title]').forEach(control => {
            if (!control.textContent.trim() && !control.hasAttribute('aria-label')) { control.setAttribute('aria-label',control.title); }
        });
        // Legacy forms keep usable names while their markup is brought onto shared components.
        document.querySelectorAll('.omdb-content input:not([type="hidden"]):not([type="submit"]):not([type="button"]), .omdb-content textarea, .omdb-content select').forEach((field,index) => {
            if (field.hasAttribute('aria-label') || (field.labels && field.labels.length)) { return; }
            const text = field.placeholder || field.name.replace(/\d+$/,'').replace(/_/g,' ');
            if (!text) { return; }
            if (field.closest('table') || ['checkbox','radio'].includes(field.type)) { field.setAttribute('aria-label',text); return; }
            field.id = field.id || 'omdb-field-' + index;
            const label = document.createElement('label'); label.htmlFor = field.id; label.textContent = text;
            field.before(label);
        });
        const errorSummary = document.querySelector('.errors[tabindex="-1"]'); if (errorSummary) { errorSummary.focus(); }
    };
    window.addEventListener('load', wrapRemainingTables, {once:true});
    window.addEventListener('resize', () => document.querySelectorAll('.table-scroll').forEach(updateRegion));
})();
