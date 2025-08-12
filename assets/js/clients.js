document.addEventListener('DOMContentLoaded', async () => {
  const list = document.getElementById('clientsList');
  // load zones for filter
  const zones = await fetch('/api/getZones.php').then(r=>r.json());
  // simple clients loader: load first zone clients
  if (zones && zones.length) {
    const zoneId = zones[0].zone_id;
    const clients = await fetch('/api/getClients.php?zoneId='+zoneId).then(r=>r.json());
    renderList(clients);
  }
  document.getElementById('newClient').addEventListener('click', ()=> {
    renderForm();
  });

  function renderList(clients) {
    list.innerHTML = '';
    clients.forEach(c => {
      const div = document.createElement('div');
      div.className = 'card mb-2';
      div.innerHTML = '<div class="card-body"><strong>'+c.firstname+' '+c.lastname+'</strong> <button class="btn btn-sm btn-outline-primary float-end edit">Éditer</button></div>';
      div.querySelector('.edit').addEventListener('click', ()=> renderForm(c));
      list.appendChild(div);
    });
  }
  function renderForm(client={}) {
    list.innerHTML = '';
    const form = document.createElement('form');
    form.innerHTML = `
      <div class="card"><div class="card-body">
      <div class="mb-3"><label>Prénom</label><input name="firstname" class="form-control" value="${client.firstname||''}"></div>
      <div class="mb-3"><label>Nom</label><input name="lastname" class="form-control" value="${client.lastname||''}"></div>
      <div class="mb-3"><label>Téléphone</label><input name="phone" class="form-control" value="${client.phone||''}"></div>
      <div class="mb-3"><label>Adresse</label><input name="address" class="form-control" value="${client.address||''}"></div>
      <div class="mb-3"><button class="btn btn-primary">Enregistrer</button></div>
      </div></div>
    `;
    form.addEventListener('submit', async (e)=> {
      e.preventDefault();
      const fd = new FormData(form);
      if (client.client_id) fd.append('client_id', client.client_id);
      const res = await fetch('/api/updateClient.php', { method:'POST', body: fd });
      const data = await res.json();
      if (data.success) alert('OK'); else alert('Erreur');
    });
    list.appendChild(form);
  }
});
