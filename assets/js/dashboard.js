/*
Dashboard JS
- loads zones & periods
- loads clients & meals for selection
- renders table: rows = clients, cols = days of month
- keyboard navigation and autosave on Enter/blur
*/
document.addEventListener('DOMContentLoaded', async () => {
  const zoneSelect = document.getElementById('zoneSelect');
  const periodSelect = document.getElementById('periodSelect');
  const table = document.getElementById('mealsTable');

  // fetch zones
  const zones = await fetch('/api/getZones.php').then(r=>r.json());
  zones.forEach(z => {
    const opt = document.createElement('option');
    opt.value = z.zone_id;
    opt.textContent = z.zone_name;
    zoneSelect.appendChild(opt);
  });

  // fetch periods
  const periods = await fetch('/api/getPeriods.php').then(r=>r.json());
  periods.forEach(p => {
    const opt = document.createElement('option');
    opt.value = p.period_id;
    const dt = new Date(p.year, p.month-1, 1);
    opt.textContent = dt.toLocaleString('fr-FR', { month: 'long', year:'numeric' });
    periodSelect.appendChild(opt);
  });

  // default selects
  if (zones.length) zoneSelect.value = zones[0].zone_id;
  if (periods.length) {
    // try find current month
    const now = new Date();
    const found = periods.find(p => p.month == (now.getMonth()+1) && p.year == now.getFullYear());
    periodSelect.value = found ? found.period_id : periods[0].period_id;
  }

  async function loadTable() {
    table.innerHTML = '';
    const zoneId = zoneSelect.value;
    const periodId = periodSelect.value;
    if (!zoneId || !periodId) return;

    const res = await fetch(`/api/getMeals.php?zoneId=${zoneId}&periodId=${periodId}`);
    const data = await res.json();
    if (data.error) { table.innerHTML = '<tr><td>Erreur</td></tr>'; return; }

    const clients = data.clients;
    const days = data.days; // array of 'YYYY-MM-DD'
    const meals = data.meals || {};

    // build header
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const firstTh = document.createElement('th');
    firstTh.textContent = 'Client';
    headerRow.appendChild(firstTh);
    days.forEach(d => {
      const dt = new Date(d);
      const label = dt.toLocaleDateString('fr-FR', { weekday: 'long' }) + ' ' + dt.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' });
      const th = document.createElement('th');
      th.textContent = label;
      headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // build body
    const tbody = document.createElement('tbody');
    clients.forEach((c, rIdx) => {
      const tr = document.createElement('tr');
      const tdClient = document.createElement('td');
      tdClient.textContent = (c.title ? c.title + ' ' : '') + c.firstname + ' ' + c.lastname;
      tr.appendChild(tdClient);

      days.forEach((d, cIdx) => {
        const td = document.createElement('td');
        td.tabIndex = 0;
        td.className = 'meal-cell';
        const val = (meals[c.client_id] && meals[c.client_id][d]) ? meals[c.client_id][d] : '';
        td.textContent = val;
        td.dataset.clientId = c.client_id;
        td.dataset.date = d;
        td.dataset.periodId = periodId;
        td.addEventListener('keydown', onKeyDown);
        td.addEventListener('blur', onBlur);
        td.addEventListener('focus', (e)=> e.target.classList.add('cell-active'));
        td.addEventListener('focusout', (e)=> e.target.classList.remove('cell-active'));
        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });
    table.appendChild(tbody);
  }

  zoneSelect.addEventListener('change', loadTable);
  periodSelect.addEventListener('change', loadTable);

  // keyboard navigation
  function onKeyDown(e) {
    const key = e.key;
    const td = e.target;
    const cell = td;
    const tr = td.parentElement;
    const tbody = table.querySelector('tbody');
    const rowIndex = Array.prototype.indexOf.call(tbody.children, tr);
    const cellIndex = Array.prototype.indexOf.call(tr.children, td);

    if (key === 'ArrowRight') {
      e.preventDefault();
      focusCell(rowIndex, cellIndex+1);
    } else if (key === 'ArrowLeft') {
      e.preventDefault();
      focusCell(rowIndex, cellIndex-1);
    } else if (key === 'ArrowDown' || key === 'Enter') {
      e.preventDefault();
      // save then move down
      saveCell(td).then(()=> focusCell(rowIndex+1, cellIndex));
    } else if (key === 'ArrowUp') {
      e.preventDefault();
      focusCell(rowIndex-1, cellIndex);
    } else if (key === 'Tab') {
      // default tab behavior: let it move, but also save
      saveCell(td);
    }
  }

  async function onBlur(e) {
    await saveCell(e.target);
  }

  function focusCell(r, c) {
    const tbody = table.querySelector('tbody');
    if (!tbody) return;
    if (r < 0) r = 0;
    if (c < 1) c = 1; // column 0 is client name
    const row = tbody.children[r];
    if (!row) return;
    const cell = row.children[c];
    if (!cell) return;
    cell.focus();
    // ensure visible
    cell.scrollIntoView({behavior:'smooth', block:'nearest', inline:'nearest'});
  }

  let saving = {};
  async function saveCell(td) {
    const client_id = td.dataset.clientId;
    const date = td.dataset.date;
    const period_id = td.dataset.periodId;
    let count = td.textContent.trim();
    if (count === '') count = 0;
    count = parseInt(count) || 0;
    const key = client_id + '|' + date;
    if (saving[key]) return saving[key];
    saving[key] = fetch('/api/updateMeal.php', {
      method:'POST',
      headers:{ 'Content-Type':'application/json' },
      body: JSON.stringify({ client_id, date, count, period_id })
    }).then(r=>r.json()).then(resp => {
      delete saving[key];
      return resp;
    }).catch(err => { delete saving[key]; console.error(err); });
    return saving[key];
  }

  // initial load
  loadTable();
});
