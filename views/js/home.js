document.addEventListener('DOMContentLoaded', function () {
  if (typeof L === 'undefined') {
    return;
  }

  const initialLatLng = [10.6764, 122.9568];

  const mapEl = document.getElementById('homeMap');
  mapEl.style.height = 'calc(100vh - 60px)'; // adjust 60px to your navbar height
  mapEl.style.zIndex = '0';

  const map = L.map('homeMap', {
    zoomControl: true,
    attributionControl: false,
  }).setView(initialLatLng, 12);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
  }).addTo(map);

  // REMOVED OR COMMENTED OUT - This creates the pin point
  // const marker = L.marker(initialLatLng).addTo(map);
  // marker.bindPopup('EvacFinder Map Center<br>Bacolod City').openPopup();
});