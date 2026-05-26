<style>
  body, html {
    overflow: hidden;
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

            <!-- Floating Button -->
            <button id="showAnnouncementsBtn" style="position: absolute; top: 20px; right: 20px; width: 50px; height: 50px; border-radius: 50%; background: #1a3c5e; color: white; border: none; font-size: 24px; cursor: pointer; z-index: 400; box-shadow: 0 2px 10px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;" title="View Announcements">
              📢
              <span id="announcementBadge" style="position: absolute; top: -5px; right: -5px; background: #ff4757; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 11px; display: none; align-items: center; justify-content: center; font-weight: bold;">0</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>