/* One catalog renderer. PHP supplies only the actions the current role may see. */
(() => {
    'use strict';
    const configElement = document.getElementById('catalog-config');
    const tableElement = document.getElementById('catalog-table');
    if (!configElement || !tableElement || !window.jQuery) { return; }
    const config = JSON.parse(configElement.textContent);
    const $ = window.jQuery;
    const status = document.querySelector('.catalog-status');
    const idKey = {movie:'movie_id',person:'people_id',song:'song_id'}[config.kind];
    const renderActions = (_data, type, row) => {
        if (type !== 'display') { return ''; }
        const group = document.createElement('div'); group.className = 'row-actions';
        config.actions.forEach(action => {
            const link = document.createElement('a');
            link.className = 'btn btn-sm ' + action.class;
            link.href = action.path + '?' + idKey + '=' + encodeURIComponent(row[idKey]);
            link.textContent = action.label;
            const name = row.english_name || row.native_name || row.stage_name || row.title || row[idKey];
            link.setAttribute('aria-label', action.label + ': ' + name);
            group.append(link);
        });
        return group.outerHTML;
    };
    const table = $(tableElement).DataTable({
        ajax: config.ajax,
        columns: [...config.columns.map(column => ({data:column.data,defaultContent:'',render:$.fn.dataTable.render.text()})),
            {data:null,orderable:false,searchable:false,render:renderActions}],
        pageLength: 10, lengthMenu:[10,25,50,100], orderCellsTop:true, autoWidth:false,
        dom:'lfrtip',
        initComplete() { status.hidden = true; }
    });
    const filters = document.createElement('tr'); filters.className = 'column-filters';
    config.columns.forEach((column, index) => {
        const cell = document.createElement('th');
        const input = document.createElement('input'); input.type = 'search'; input.className = 'form-control'; input.size = 1;
        input.placeholder = 'Filter'; input.setAttribute('aria-label','Filter by ' + column.label.toLowerCase());
        input.addEventListener('input', () => table.column(index).search(input.value).draw());
        cell.append(input); filters.append(cell);
    });
    const finalCell = document.createElement('th'); finalCell.setAttribute('aria-label','Actions have no filter'); filters.append(finalCell);
    tableElement.tHead.append(filters);
})();
