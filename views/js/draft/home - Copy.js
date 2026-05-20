// views/js/home.js - Working map without database
document.addEventListener('DOMContentLoaded', function () {
    console.log('Dashboard loading with map...');
    
    // Small delay to ensure DOM is fully ready
    setTimeout(function() {
        if (document.getElementById('homeMap')) {
            initMap();
        } else {
            console.log('homeMap element not found yet, retrying...');
            // Retry after a short delay
            setTimeout(function() {
                if (document.getElementById('homeMap')) {
                    initMap();
                } else {
                    console.error('homeMap element not found!');
                }
            }, 500);
        }
    }, 200);
});

function initMap() {
    console.log('Initializing home page map...');
    
    const defaultLat = 10.4167;  // Negros Oriental
    const defaultLng = 123.3833;
    const defaultZoom = 10;
    
    // Check if map already exists
    if (window.homeMap && window.homeMap.remove) {
        window.homeMap.remove();
    }
    
    // Create the map
    window.homeMap = L.map('homeMap').setView([defaultLat, defaultLng], defaultZoom);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(window.homeMap);
    
    // Force map to render correctly
    setTimeout(function() {
        window.homeMap.invalidateSize();
    }, 100);
    
    console.log('Map initialized successfully');
    
    // Add sample markers for demonstration
    addSampleMarkers(window.homeMap);
}

function addSampleMarkers(map) {
    // Sample evacuation center locations in Negros Oriental
    const sampleCenters = [
        {
            name: "Canlaon City Evacuation Center",
            lat: 10.3869,
            lng: 123.1978,
            address: "Canlaon City Proper",
            status: "Active",
            capacity: 500
        },
        {
            name: "Bais City Gymnasium",
            lat: 9.5908,
            lng: 123.1219,
            address: "Bais City",
            status: "Active",
            capacity: 300
        },
        {
            name: "Dumaguete City Convention Center",
            lat: 9.3070,
            lng: 123.3074,
            address: "Dumaguete City",
            status: "Active",
            capacity: 800
        },
        {
            name: "Tanjay City Coliseum",
            lat: 9.5167,
            lng: 123.1500,
            address: "Tanjay City",
            status: "Full",
            capacity: 400
        },
        {
            name: "Bayawan City Cultural Center",
            lat: 9.3667,
            lng: 122.8000,
            address: "Bayawan City",
            status: "Under Maintenance",
            capacity: 250
        }
    ];
    
    // Add markers for each sample center
    sampleCenters.forEach(function(center) {
        const marker = L.marker([center.lat, center.lng]).addTo(map);
        
        const popupContent = `
            <div style="min-width: 220px; font-family: Arial, sans-serif;">
                <strong style="font-size: 14px; color: #333;">🏢 ${escapeHtml(center.name)}</strong><br>
                <small style="color: #666;">📍 ${escapeHtml(center.address)}</small><br>
                <span style="display: inline-block; margin-top: 8px; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: bold; background: ${getStatusColor(center.status)}; color: white;">
                    ${escapeHtml(center.status)}
                </span>
                ${center.capacity ? `<br><small style="color: #666;">👥 Capacity: ${center.capacity} persons</small>` : ''}
                <br><br>
                <small><a href="?route=centers" style="color: #007bff;">View details →</a></small>
            </div>
        `;
        
        marker.bindPopup(popupContent);
    });
    
    // Fit bounds to show all markers
    const bounds = sampleCenters.map(c => [c.lat, c.lng]);
    if (bounds.length > 0) {
        map.fitBounds(bounds);
    }
    
    console.log(`Added ${sampleCenters.length} sample evacuation centers to map`);
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getStatusColor(status) {
    const colors = {
        'Active': '#28a745',
        'Inactive': '#6c757d',
        'Full': '#dc3545',
        'Under Maintenance': '#fd7e14'
    };
    return colors[status] || '#007bff';
}