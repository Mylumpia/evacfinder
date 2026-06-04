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

  console.log('Public map loaded');

  function escapeHtml(str) {
    if (!str) return '';
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function getTypeColor(type) {
    const colors = {
      'Advisory':  '#e74c3c',
      'Event':     '#2ecc71',
      'Memo':      '#f39c12',
      'Notice':    '#9b59b6',
      'General':   '#1a3c5e',
    };
    return colors[type] || '#1a3c5e';
  }

  function loadAnnouncements() {
    fetch('ajax/get_announcements.ajax.php')
      .then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
      })
      .then(data => {
        const list = document.getElementById('announcementsList');
        if (!list) return;

        // Update badge count
        const badge = document.getElementById('announcementBadge');
        if (badge && data.length > 0) {
          badge.textContent = data.length;
          badge.style.display = 'flex';
        } else if (badge) {
          badge.style.display = 'none';
        }

        if (!data.length) {
          list.innerHTML = '<div style="text-align: center; padding: 30px; color: #888; font-size: 13px;">📭 No announcements available.</div>';
          return;
        }

        list.innerHTML = data.map((ann, index) => {
          const color = getTypeColor(ann.ann_type);
          return `
            <div class="announcement-item"
              data-id="${escapeHtml(ann.announcement_id || index)}"
              data-title="${escapeHtml(ann.ann_title)}"
              data-desc="${escapeHtml(ann.ann_desc)}"
              data-type="${escapeHtml(ann.ann_type)}"
              style="background: #f8f9fa; border-radius: 8px; padding: 12px 14px; margin-bottom: 12px; border-left: 4px solid ${color}; cursor: pointer; transition: box-shadow 0.2s;"
              onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.12)'"
              onmouseout="this.style.boxShadow='none'"
            >
              <span style="display: inline-block; font-size: 11px; background: ${color}22; color: ${color}; border-radius: 4px; padding: 2px 8px; font-weight: 600; margin-bottom: 6px;">
                ${escapeHtml(ann.ann_type)}
              </span>
              <div style="font-weight: 600; font-size: 13px; margin-bottom: 4px; color: #1a3c5e;">${escapeHtml(ann.ann_title)}</div>
              <div style="font-size: 12px; color: #555; margin-bottom: 6px; line-height: 1.5;">${escapeHtml(ann.ann_desc)}</div>
              <div style="font-size: 11px; color: #aaa;">${ann.date_created || ''}</div>
            </div>
          `;
        }).join('');

        
      })
      .catch(err => {
        console.error('Failed to load announcements:', err);
        const list = document.getElementById('announcementsList');
        if (list) {
          list.innerHTML = '<div style="text-align: center; padding: 30px; color: #e74c3c; font-size: 13px;">⚠️ Failed to load announcements.</div>';
        }
      });
  }

  function initAnnouncementsPanel() {
    const panel   = document.getElementById('announcementsPanel');
    const showBtn = document.getElementById('showAnnouncementsBtn');

    if (!panel || !showBtn) return;

    showBtn.addEventListener('click', function () {
      const isOpen = panel.style.display === 'flex';
      if (isOpen) {
        panel.style.display = 'none';
      } else {
        panel.style.display = 'flex';
        loadAnnouncements();
      }
    });
  }

  initAnnouncementsPanel();
  loadAnnouncements();
  setInterval(loadAnnouncements, 30000);
});