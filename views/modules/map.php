<style>
  body, html {
    overflow: hidden;
  }

  /* Hide the default LRM instructions panel that appears on the map */
  .leaflet-routing-container {
    display: none !important;
  }

  /* Pulsing user location dot */
  @keyframes pulse-ring {
    0%   { transform: scale(0.5); opacity: 0.8; }
    100% { transform: scale(2.2); opacity: 0; }
  }
  @keyframes pulse-ring2 {
    0%   { transform: scale(0.5); opacity: 0.5; }
    100% { transform: scale(2.8); opacity: 0; }
  }

  .user-pulse-wrapper {
    position: relative;
    width: 20px;
    height: 20px;
  }
  .user-pulse-ring {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(37, 99, 235, 0.4);
    animation: pulse-ring 1.6s ease-out infinite;
  }
  .user-pulse-ring2 {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(37, 99, 235, 0.2);
    animation: pulse-ring2 1.6s ease-out infinite 0.4s;
  }
  .user-pulse-dot {
    position: absolute;
    inset: 3px;
    border-radius: 50%;
    background: #2563eb;
    border: 2.5px solid white;
    box-shadow: 0 0 6px rgba(37,99,235,0.6);
  }
</style>

<div class="home-map-page">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body p-0" style="position: relative;">
            <div id="homeMap" class="home-map-view"></div>

            <!-- Announcements Panel -->
            <div id="announcementsPanel" style="display: none; position: absolute; top: 20px; right: 80px; width: 350px; max-height: calc(100vh - 100px); background: white; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.15); z-index: 400; flex-direction: column; overflow: hidden;">
              <div style="background: #1a3c5e; color: white; padding: 15px 20px; display: flex; align-items: center; gap: 8px; border-radius: 12px 12px 0 0;">
                <span>📢</span>
                <span style="font-size: 16px; font-weight: 600;">Announcements</span>
              </div>
              <div id="announcementsList" style="padding: 15px; overflow-y: auto; max-height: calc(100vh - 180px);"></div>
            </div>

            <!-- Announcements Floating Button -->
            <button id="showAnnouncementsBtn" style="position: absolute; top: 20px; right: 20px; width: 50px; height: 50px; border-radius: 50%; background: #1a3c5e; color: white; border: none; font-size: 24px; cursor: pointer; z-index: 400; box-shadow: 0 2px 10px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;" title="View Announcements">
              📢
              <span id="announcementBadge" style="position: absolute; top: -5px; right: -5px; background: #ff4757; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 11px; display: none; align-items: center; justify-content: center; font-weight: bold;">0</span>
            </button>

            <!-- Route to Nearest Button -->
            <button id="routeToNearestBtn"
              style="position: absolute; bottom: 30px; left: 20px; height: 50px; padding: 0 16px; border-radius: 25px; background: #1a3c5e; color: white; border: none; font-size: 13px; font-weight: 600; cursor: pointer; z-index: 400; box-shadow: 0 2px 10px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 8px; transition: background 0.2s, transform 0.1s; white-space: nowrap;"
              title="Get directions to the nearest active evacuation center"
              onmouseover="if(!this.disabled) this.style.transform='scale(1.04)'"
              onmouseout="this.style.transform='scale(1)'">
              🧭 <span id="routeBtnLabel">Route to Nearest</span>
            </button>

            <!-- Route Info Panel (shows nearest center name + distance) -->
            <div id="routeInfoPanel"
              style="display: none; position: absolute; top: 20px; left: 50%; transform: translateX(-50%); background: white; border-radius: 10px; padding: 12px 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.18); z-index: 400; min-width: 280px; max-width: 420px;">
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Leaflet Routing Machine — must load AFTER Leaflet -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js"></script>