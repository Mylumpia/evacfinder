<div class="home-map-page">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body p-0 overflow-hidden">
            <div id="homeMap" class="home-map-view"></div>
            
              <div class="card-body p-0 overflow-hidden" style="position: relative;">
                <div id="homeMap" class="home-map-view"></div>

                <!-- ADD THIS BELOW -->
                <div id="announcementsPanel" style="
                  position: absolute; top: 16px; right: 16px;
                  width: 300px; z-index: 999;
                  display: flex; flex-direction: column; gap: 10px;
                  max-height: calc(100vh - 100px); overflow-y: auto;">
                  <div style="background:#1a3c5e; color:#fff; border-radius:8px; padding:10px 14px; font-weight:600; font-size:14px;">
                    📢 Announcements
                  </div>
                  <div id="announcementsList"></div>
                </div>

              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
