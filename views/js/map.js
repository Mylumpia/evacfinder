document.addEventListener('DOMContentLoaded', function () {
  if (typeof L === 'undefined') {
    console.error('Leaflet is not loaded');
    return;
  }

  const initialLatLng = [10.6764, 122.9568];

  const mapEl = document.getElementById('homeMap');
  if (!mapEl) return;
  
  mapEl.style.height = 'calc(100vh - 60px)';
  mapEl.style.zIndex = '0';

  const map = L.map('homeMap', {
    zoomControl: true,
    attributionControl: false,
  }).setView(initialLatLng, 12);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
  }).addTo(map);

  // You can add public markers here later
  console.log('Public map loaded');


  // Announcements
  function loadAnnouncements() {
    fetch('ajax/get_announcements.ajax.php')
      .then(res => res.json())
      .then(data => {
        const list = document.getElementById('announcementsList');
        if (!list) return;

        if (!data.length) {
          list.innerHTML = `<div style="background:#fff;border-radius:8px;padding:12px;font-size:13px;color:#888;">No announcements.</div>`;
          return;
        }

        list.innerHTML = data.map(a => `
          <div style="background:#fff;border-radius:8px;padding:12px 14px;box-shadow:0 1px 4px rgba(0,0,0,0.12);">
            <span style="font-size:11px;background:#e0f0ff;color:#1a3c5e;border-radius:4px;padding:2px 7px;font-weight:600;">${a.ann_type}</span>
            <div style="font-weight:600;font-size:13px;margin:6px 0 4px;">${a.ann_title}</div>
            <div style="font-size:12px;color:#555;margin-bottom:6px;">${a.ann_desc}</div>
            <div style="font-size:11px;color:#aaa;">${a.date_created ?? ''}</div>
          </div>
        `).join('');
      })
      .catch(err => console.error('Failed to load announcements:', err));
  }

  loadAnnouncements();
  setInterval(loadAnnouncements, 30000);
  
});