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
});