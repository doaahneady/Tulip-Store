// CHECKOUT PAGE - TULIP STORE
console.log('✅ Checkout.js loaded');

// Global variables
let currentStep = 1;
let selectedDelivery = 'normal';
let selectedPayment = 'cash';
let selectedCurrency = (window.getCurrencyPreference ? window.getCurrencyPreference() : 'USD'); // Default currency
let map = null;
let marker = null;
let storeMarker = null;
let selectedLocation = null;
window.hasMartItems = false;

function isMartCartItem(item) {
    if (!item) return false;
    if (item.type === 'mart') return true;
    const p = item.product || {};
    if (p.market === 'mart') return true;
    if (p.store_id === 1) return true;
    const storeName = (p.store && p.store.name ? String(p.store.name) : '').toLowerCase();
    if (storeName.includes('mart')) return true;
    const brand = (p.brand ? String(p.brand) : '').toLowerCase();
    if (brand.includes('mart') || brand.includes('مارت')) return true;
    return false;
}

async function refreshHasMartItems() {
    try {
        const response = await fetch('/api/cart/items');
        if (!response.ok) {
            window.hasMartItems = false;
            return false;
        }
        const cart = await response.json();
        const has = Array.isArray(cart) ? cart.some(isMartCartItem) : false;
        window.hasMartItems = has;
        return has;
    } catch (e) {
        window.hasMartItems = false;
        return false;
    }
}

// Multiple storage locations in Sweida
const storageLocations = [
    // Only one storage point (Sweida city) as requested
    { name: 'Tulip mart توليب مارت', lat: 32.749925, lng: 36.573006 }
];

let nearestStorage = null;
let deliveryDistance = 0;
let deliveryCost = 0;
let usdToSyp = (window.TULIP_USD_TO_SYP || 117);
let checkoutIdempotencyKey = null;
let lastCheckoutTotalUSD = 0;

function ensureCheckoutIdempotencyKey() {
    if (checkoutIdempotencyKey) return checkoutIdempotencyKey;
    try {
        if (window.crypto && typeof window.crypto.randomUUID === 'function') {
            checkoutIdempotencyKey = window.crypto.randomUUID();
            return checkoutIdempotencyKey;
        }
    } catch (e) {
    }

    checkoutIdempotencyKey = 'order_' + Date.now() + '_' + Math.random().toString(16).slice(2);
    return checkoutIdempotencyKey;
}

function applyInitialPaymentFromQuery() {
    try {
        const params = new URLSearchParams(window.location.search || '');
        const p = String(params.get('payment') || '').toLowerCase();
        if (p === 'balance') {
            selectPayment('balance');
        }
    } catch (e) {
    }
}

function updateBalancePaymentAvailability(totalUSD) {
    lastCheckoutTotalUSD = parseFloat(totalUSD || 0) || 0;
    const balance = parseFloat(window.userData?.balance || 0) || 0;
    const canPay = balance + 1e-9 >= lastCheckoutTotalUSD && lastCheckoutTotalUSD > 0;

    const option = document.querySelector('.payment-option[data-type="balance"]');
    if (option) {
        option.dataset.disabled = canPay ? '0' : '1';
        option.style.opacity = canPay ? '1' : '0.55';
        option.style.filter = canPay ? '' : 'grayscale(1)';
        option.style.cursor = canPay ? 'pointer' : 'not-allowed';
    }

    const currentEl = document.getElementById('balancePaymentCurrent');
    if (currentEl) currentEl.textContent = balance.toFixed(2);

    const remainingRow = document.getElementById('balancePaymentRemainingRow');
    const remainingEl = document.getElementById('balancePaymentRemaining');
    const insufficientEl = document.getElementById('balancePaymentInsufficient');

    if (canPay) {
        if (remainingRow) remainingRow.style.display = 'block';
        if (remainingEl) remainingEl.textContent = Math.max(0, balance - lastCheckoutTotalUSD).toFixed(2);
        if (insufficientEl) insufficientEl.style.display = 'none';
    } else {
        if (remainingRow) remainingRow.style.display = 'none';
        if (insufficientEl) insufficientEl.style.display = 'block';
    }
}

// All villages and cities in Sweida governorate (accurate coordinates)
const allVillages = [
    // المدن الرئيسية
    { name: 'السويداء', lat: 32.71119, lng: 36.56662 },
    { name: 'شهبا', lat: 32.85428, lng: 36.62730 },
    { name: 'صلخد', lat: 32.4917, lng: 36.7167 },
    
    // البلدات الكبيرة
    { name: 'قنوات', lat: 32.7394, lng: 36.5139 },
    { name: 'الكفر', lat: 32.6833, lng: 36.4500 },
    { name: 'المزرعة', lat: 32.6167, lng: 36.5000 },
    { name: 'شقا', lat: 32.7500, lng: 36.6500 },
    { name: 'المجدل', lat: 32.7833, lng: 36.5167 },
    { name: 'المشنف', lat: 32.6833, lng: 36.6000 },
    { name: 'سليم', lat: 32.7667, lng: 36.6500 },
    { name: 'عتيل', lat: 32.6000, lng: 36.5333 },
    { name: 'نجران', lat: 32.7333, lng: 36.6000 },
    { name: 'عريقة', lat: 32.7333, lng: 36.4833 },
    { name: 'أم الرمان', lat: 32.7000, lng: 36.5500 },
    { name: 'أم الزيتون', lat: 32.7167, lng: 36.5333 },
    { name: 'إمتان', lat: 32.7333, lng: 36.5500 },
    
    // القرى (corrected coordinates)
    { name: 'أم العلق', lat: 32.6900, lng: 36.5450 },
    { name: 'أم حارتين', lat: 32.7050, lng: 36.5620 },
    { name: 'أم رواق', lat: 32.7200, lng: 36.5480 },
    { name: 'الأصلحا', lat: 32.7550, lng: 36.4800 },
    { name: 'البثينة', lat: 32.7480, lng: 36.6150 },
    { name: 'الثعلة', lat: 32.7200, lng: 36.6300 },
    { name: 'الجنينة', lat: 32.6700, lng: 36.5300 },
    { name: 'الخرسا', lat: 32.6550, lng: 36.5650 },
    { name: 'الدارة', lat: 32.7350, lng: 36.5480 },
    { name: 'الدور', lat: 32.7380, lng: 36.5520 },
    { name: 'الدويرة', lat: 32.7050, lng: 36.5300 },
    { name: 'الرحى', lat: 32.7700, lng: 36.5320 },
    { name: 'الرشيدة', lat: 32.7200, lng: 36.5150 },
    { name: 'الرضيمة', lat: 32.6850, lng: 36.4820 },
    { name: 'الرضيمة الشرقية', lat: 32.6850, lng: 36.4980 },
    { name: 'السكاكة', lat: 32.7520, lng: 36.5480 },
    { name: 'السويمرة', lat: 32.7050, lng: 36.6150 },
    { name: 'الصورة الكبيرة', lat: 32.7700, lng: 36.5980 },
    { name: 'الطيرة', lat: 32.6700, lng: 36.4980 },
    { name: 'الغارية', lat: 32.7520, lng: 36.6320 },
    { name: 'القريا', lat: 32.8550, lng: 36.5650 },
    { name: 'المتونة', lat: 32.7700, lng: 36.5650 },
    { name: 'المجيمر', lat: 32.7350, lng: 36.5650 },
    { name: 'الهيات', lat: 32.7200, lng: 36.5480 },
    { name: 'الهيت', lat: 32.6850, lng: 36.5150 },
    { name: 'بريكة', lat: 32.7700, lng: 36.5480 },
    { name: 'بكا', lat: 32.7520, lng: 36.5150 },
    { name: 'تعارة', lat: 32.7350, lng: 36.5820 },
    { name: 'تعلا', lat: 32.7200, lng: 36.5650 },
    { name: 'جبيب', lat: 32.6850, lng: 36.5820 },
    { name: 'جديا', lat: 32.7050, lng: 36.5150 },
    { name: 'جرين', lat: 32.7350, lng: 36.6150 },
    { name: 'حبران', lat: 32.7350, lng: 36.5320 },
    { name: 'حران', lat: 32.7700, lng: 36.4820 },
    { name: 'حزم', lat: 32.7520, lng: 36.5320 },
    { name: 'حوط', lat: 32.7050, lng: 36.5480 },
    { name: 'خربة عواد', lat: 32.7520, lng: 36.4650 },
    { name: 'خلخلة', lat: 32.6700, lng: 36.5150 },
    { name: 'داما', lat: 32.7050, lng: 36.6480 },
    { name: 'ذكير', lat: 32.7200, lng: 36.5320 },
    { name: 'ذيبين', lat: 32.6700, lng: 36.4650 },
    { name: 'ريمة اللحف', lat: 32.7350, lng: 36.5150 },
    { name: 'ريمة حازم', lat: 32.7350, lng: 36.5320 },
    { name: 'سميع', lat: 32.7850, lng: 36.6150 },
    { name: 'سهوة الخضر', lat: 32.7050, lng: 36.6150 },
    { name: 'شعف', lat: 32.7520, lng: 36.6480 },
    { name: 'صما', lat: 32.7700, lng: 36.4150 },
    { name: 'صما البردان', lat: 32.7700, lng: 36.4320 },
    { name: 'صميد', lat: 32.7350, lng: 36.4650 },
    { name: 'طليلين', lat: 32.6850, lng: 36.5650 },
    { name: 'عرى', lat: 32.6550, lng: 36.5320 },
    { name: 'عمرة', lat: 32.7050, lng: 36.4650 },
    { name: 'عنز', lat: 32.7200, lng: 36.4980 },
    { name: 'قراصة', lat: 32.6700, lng: 36.4820 },
    { name: 'الشبكي', lat: 32.7520, lng: 36.5480 },
    { name: 'المشقوق', lat: 32.7350, lng: 36.5650 },
    { name: 'سالة', lat: 32.7700, lng: 36.5320 },
    { name: 'سهوة بلاطة', lat: 32.7050, lng: 36.6320 },
    { name: 'قيصما', lat: 32.7200, lng: 36.5820 },
    { name: 'كفر اللحف', lat: 32.6700, lng: 36.4820 },
    { name: 'كناكر', lat: 32.6550, lng: 36.4980 },
    { name: 'لاهثة', lat: 32.7350, lng: 36.5150 },
    { name: 'لبين', lat: 32.7520, lng: 36.5150 },
    { name: 'مجدل', lat: 32.7700, lng: 36.5150 },
    { name: 'مردك', lat: 32.7050, lng: 36.5320 },
    { name: 'مصاد', lat: 32.71119, lng: 36.56662 },
    { name: 'مفعلة', lat: 32.7350, lng: 36.5820 },
    { name: 'مياماس', lat: 32.7520, lng: 36.5980 },
    { name: 'نمرة', lat: 32.7520, lng: 36.5980 },
    { name: 'ولغا', lat: 32.7200, lng: 36.6650 }
];

// Create villages lookup object
const villages = {};
allVillages.forEach(v => {
    const key = `${v.lat},${v.lng}`;
    villages[key] = v;
});

// Initialize Leaflet Map (OpenStreetMap)
// Google Maps variables
let directionsService;
let directionsRenderer;
let currentRoute = null;

function initMap() {
    console.log('✅ Initializing Leaflet Map...');
    
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        console.error('❌ Map element not found!');
        return;
    }
    
    try {
        // Create Leaflet Map centered on Sweida, Syria
        map = L.map('map', {
            center: [32.749925, 36.573006],
            zoom: 12,
            scrollWheelZoom: true, // Enable scroll without Ctrl
            dragging: true,
            zoomControl: false // Remove zoom buttons
        });
        
        // Use satellite imagery tiles (no API key required)
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles © Esri',
            maxNativeZoom: 18,
            maxZoom: 18
        }).addTo(map);
        
        console.log('✅ Leaflet Map created');
        
        // Initialize routing control (hidden by default)
        window.routingControl = null;
        
        // Add storage location markers
        addStorageMarkers();
        console.log('✅ Storage markers added');
        
        // Click event to place marker and calculate route
        map.on('click', function(e) {
            console.log('📍 Map clicked at:', e.latlng.lat, e.latlng.lng);
            if (typeof window.placeMarkerLeaflet === 'function') {
                window.placeMarkerLeaflet(e.latlng);
            } else {
                placeMarker({ lat: e.latlng.lat, lng: e.latlng.lng });
            }
        });
        
        console.log('✅ Map click handler attached - Map is ready!');
    } catch (error) {
        console.error('❌ Error creating map:', error);
        console.error('Error details:', error.message, error.stack);
    }
}

// Add storage location markers to Leaflet Map
function addStorageMarkers() {
    storageLocations.forEach((storage) => {
        // Create custom icon
        const storageIcon = L.divIcon({
            className: 'custom-marker',
            html: `<div style="background:#2a7080; width:24px; height:24px; border-radius:50%; border:3px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.3);"></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });
        
        const storageMarker = L.marker([storage.lat, storage.lng], {
            icon: storageIcon,
            title: storage.name,
            zIndexOffset: 1000
        }).addTo(map);
        
        // Add popup
        storageMarker.bindPopup(`
            <div style="font-family:'El Messiri',sans-serif; padding:0.5rem; text-align:center; direction:rtl;">
                <h4 style="margin:0 0 0.5rem 0; color:#2a7080; font-size:0.95rem;">
                    <i class="fas fa-warehouse" style="margin-left:0.3rem;"></i>
                    ${storage.name}
                </h4>
                <p style="margin:0; color:#666; font-size:0.8rem;">نقطة انطلاق التوصيل</p>
            </div>
        `);
        
        // Open popup for main storage
        if (storage.name.includes('الرئيسي')) {
            storageMarker.openPopup();
        }
    });
}

// Setup address search input
function setupAddressSearch() {
    const input = document.getElementById('addressSearch');
    if (!input) return;
    
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchAddress(input.value);
        }
    });
}

// Search for address
function searchAddress(query) {
    if (!query) return;
    
    const geocoder = L.Control.Geocoder.nominatim();
    geocoder.geocode(query, function(results) {
        if (results && results.length > 0) {
            const result = results[0];
            map.setView(result.center, 16);
            if (typeof window.placeMarkerLeaflet === 'function') {
                window.placeMarkerLeaflet(result.center);
            } else {
                placeMarker(result.center);
            }
            console.log('📍 Found:', result.name);
        } else {
            showAlert('لم يتم العثور على العنوان', 'error');
        }
    });
}


// Place marker on Google Map and calculate route
function placeMarker(latlng) {
    const lat = latlng?.lat;
    const lng = latlng?.lng;
    if (typeof window.placeMarkerLeaflet === 'function' && typeof L !== 'undefined') {
        window.placeMarkerLeaflet(L.latLng(lat, lng));
        return;
    }
}

// Calculate route using Google Directions API
function calculateRoute(destination) {
    if (!nearestStorage || !directionsService) return;
    
    const origin = { lat: nearestStorage.lat, lng: nearestStorage.lng };
    
    const request = {
        origin: origin,
        destination: destination,
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC
    };
    
    directionsService.route(request, (result, status) => {
        if (status === 'OK') {
            // Display route on map
            directionsRenderer.setDirections(result);
            
            // Get route distance (in meters)
            const route = result.routes[0];
            const distanceInMeters = route.legs[0].distance.value;
            deliveryDistance = distanceInMeters / 1000; // Convert to km
            
            console.log('✅ Route calculated');
            console.log('📏 Road distance:', deliveryDistance.toFixed(2), 'km');
            console.log('⏱️ Duration:', route.legs[0].duration.text);
            
            // Calculate delivery cost
            deliveryCost = calculateDeliveryCost();
            
            // Show confirmation with distance
            const locationMsg = document.getElementById('selectedLocation');
            locationMsg.innerHTML = `
                <i class="fas fa-check-circle" style="margin-left:0.5rem;"></i>
                تم تحديد الموقع - المسافة: ${deliveryDistance.toFixed(1)} كم - الوقت: ${route.legs[0].duration.text}
            `;
            locationMsg.style.display = 'block';
            setTimeout(() => {
                locationMsg.style.display = 'none';
            }, 5000);
        } else {
            console.error('❌ Directions request failed:', status);
            // Fallback to straight-line distance
            const R = 6371; // Earth radius in km
            const dLat = (destination.lat - origin.lat) * Math.PI / 180;
            const dLon = (destination.lng - origin.lng) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                     Math.cos(origin.lat * Math.PI / 180) * Math.cos(destination.lat * Math.PI / 180) *
                     Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            deliveryDistance = R * c;
            
            deliveryCost = calculateDeliveryCost();
            
            showAlert('تم حساب المسافة المباشرة. قد تختلف المسافة الفعلية على الطرق.', 'warning');
        }
    });
}

// Auto-select village using advanced reverse geocoding (more resilient)
function autoSelectNearestVillage(lat, lng) {
    const villageInput = document.getElementById('village');
    const villageCoordsInput = document.getElementById('villageCoords');
    
    if (!villageInput) return;

    window.__reverseGeocodeSeq = (window.__reverseGeocodeSeq || 0) + 1;
    const seq = window.__reverseGeocodeSeq;
    const lastStableText = villageInput.getAttribute('data-last-location') || '';
    
    // Show loading state
    villageInput.value = lastStableText || 'جاري تحديد المنطقة بدقة...';
    villageInput.style.background = '#f0f8ff';
    
    const reverseUrl = (zoom) => `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=${zoom}&addressdetails=1&accept-language=ar`;

    const fetchReverse = async (zoom) => {
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 4500);
        try {
            const res = await fetch(reverseUrl(zoom), {
                signal: controller.signal,
                headers: {
                    'Accept': 'application/json',
                    'Accept-Language': 'ar'
                },
            });
            if (!res.ok) {
                return null;
            }
            return await res.json();
        } catch (e) {
            return null;
        } finally {
            clearTimeout(timeout);
        }
    };

    (async () => {
        const results = [];
        for (const zoom of [18, 16, 14]) {
            if (seq !== window.__reverseGeocodeSeq) return;
            const data = await fetchReverse(zoom);
            if (data) {
                results.push(data);
                if (data.display_name || (data.address && Object.keys(data.address).length)) {
                    break;
                }
            }
        }

        if (seq !== window.__reverseGeocodeSeq) return;

        if (results.length === 0) {
            if (lastStableText) {
                villageInput.value = lastStableText;
                villageInput.style.background = '#fff3cd';
                villageInput.style.borderColor = '#ffc107';
                if (villageCoordsInput) villageCoordsInput.value = `${lat},${lng}`;
                return;
            }
            fallbackToNearestVillage(lat, lng);
            return;
        }

        console.log('🌍 Geocoding results:', results);

        const addrs = results.map(r => r && r.address ? r.address : {}).filter(Boolean);
        const governorate = (addrs.find(a => a.state) || {}).state || (addrs.find(a => a.province) || {}).province || (addrs.find(a => a.county) || {}).county || '';
        const country = (addrs.find(a => a.country) || {}).country || '';
        const fullAddress = (results[0] && results[0].display_name) ? results[0].display_name : '';

        let possibleNames = [];
        results.forEach(data => {
            if (data && data.address) {
                if (data.address.village) possibleNames.push(data.address.village);
                if (data.address.town) possibleNames.push(data.address.town);
                if (data.address.city) possibleNames.push(data.address.city);
                if (data.address.suburb) possibleNames.push(data.address.suburb);
                if (data.address.neighbourhood) possibleNames.push(data.address.neighbourhood);
            }
        });
        possibleNames = [...new Set(possibleNames)];

        const bestMatch = findBestVillageMatch(possibleNames, lat, lng);
        let display = fullAddress || (bestMatch ? bestMatch.name : (possibleNames[0] || ''));
        // Prefer concise "city/village - governorate" when available
        if ((bestMatch || possibleNames[0] || '').length && (governorate || country)) {
            const locality = (bestMatch ? bestMatch.name : possibleNames[0]);
            const region = governorate || country;
            display = `${locality} - ${region}`;
        }

        if (display) {
            villageInput.value = display;
            villageInput.setAttribute('data-last-location', display);
            villageInput.style.background = bestMatch ? '#d4edda' : '#e8f4f8';
            villageInput.style.borderColor = bestMatch ? '#28a745' : '#2a7080';
            if (villageCoordsInput) villageCoordsInput.value = `${lat},${lng}`;
            return;
        }

        if (lastStableText) {
            villageInput.value = lastStableText;
            villageInput.style.background = '#fff3cd';
            villageInput.style.borderColor = '#ffc107';
            if (villageCoordsInput) villageCoordsInput.value = `${lat},${lng}`;
            return;
        }

        fallbackToNearestVillage(lat, lng);
    })();
}

// Advanced matching algorithm for 100% accuracy
function findBestVillageMatch(possibleNames, lat, lng) {
    if (!possibleNames || possibleNames.length === 0) return null;
    
    let bestMatch = null;
    let highestScore = 0;
    
    allVillages.forEach(village => {
        possibleNames.forEach(name => {
            let score = 0;
            
            // Exact match = highest score
            if (village.name === name) {
                score = 100;
            }
            // Contains match
            else if (village.name.includes(name) || name.includes(village.name)) {
                score = 80;
            }
            // Similar words (remove "ال" prefix for comparison)
            else {
                const villageName = village.name.replace(/^ال/, '');
                const searchName = name.replace(/^ال/, '');
                if (villageName === searchName) {
                    score = 90;
                } else if (villageName.includes(searchName) || searchName.includes(villageName)) {
                    score = 70;
                }
            }
            
            // Bonus points for proximity (within 2km)
            const distance = calculateDistance(village.lat, village.lng, lat, lng);
            if (distance < 2) {
                score += 20;
            } else if (distance < 5) {
                score += 10;
            } else if (distance < 10) {
                score += 5;
            }
            
            // Update best match if this score is higher
            if (score > highestScore) {
                highestScore = score;
                bestMatch = {
                    ...village,
                    confidence: score,
                    distance: distance
                };
            }
        });
    });
    
    // Only return if confidence is high enough (>60)
    return highestScore > 60 ? bestMatch : null;
}

// Fallback method: calculate nearest village from our database
function fallbackToNearestVillage(lat, lng) {
    const villageInput = document.getElementById('village');
    const villageCoordsInput = document.getElementById('villageCoords');
    
    let minDistance = Infinity;
    let nearestVillage = null;
    
    allVillages.forEach(village => {
        const distance = calculateDistance(village.lat, village.lng, lat, lng);
        if (distance < minDistance) {
            minDistance = distance;
            nearestVillage = village;
        }
    });
    
    if (nearestVillage && villageInput) {
        villageInput.value = nearestVillage.name;
        villageInput.style.background = '#fff3cd';
        villageInput.style.borderColor = '#ffc107';
        
        if (villageCoordsInput) {
            villageCoordsInput.value = `${lat},${lng}`;
        }
        
        console.log('🏘️ Fallback: nearest village:', nearestVillage.name, '(', minDistance.toFixed(2), 'km away)');
        showAlert(`تم اختيار أقرب منطقة: ${nearestVillage.name} (${minDistance.toFixed(1)} كم)`, 'info');
    }
}

// Find nearest storage location
function findNearestStorage(lat, lng) {
    let minDistance = Infinity;
    let nearest = null;
    
    storageLocations.forEach(storage => {
        const distance = calculateDistance(storage.lat, storage.lng, lat, lng);
        if (distance < minDistance) {
            minDistance = distance;
            nearest = storage;
        }
    });
    
    nearestStorage = nearest;
    deliveryDistance = minDistance;
    
    return nearest;
}

// Calculate distance between two points (Haversine formula)
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Earth radius in km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Calculate delivery cost based on distance (SYP)
// Base: 10000 SYP for first 3 KM, then +2000 SYP for each additional KM (rounded up).
function calculateDeliveryCost() {
    if (!deliveryDistance) return 0;
    
    const baseDistanceKm = 3;
    const baseFee = 10000;
    const extraFeePerKm = 2000;
    const extraDistance = Math.max(0, deliveryDistance - baseDistanceKm);
    const extraKmCharged = Math.ceil(extraDistance);
    let totalCost = baseFee + (extraKmCharged * extraFeePerKm);
    
    // Apply delivery type multiplier
    let multiplier = 1;
    switch(selectedDelivery) {
        case 'normal':
            multiplier = 1; // Standard rate
            break;
        case 'express':
            multiplier = 1.5; // 50% more
            break;
        case 'instant':
            multiplier = 2; // Double
            break;
    }
    
    return Math.round(totalCost * multiplier);
}



// Village selection is now done only through map clicks
// No manual dropdown selection needed

// Load user data
async function loadUserData() {
    console.log('📥 Loading user data...');
    
    try {
        const response = await fetch('/api/user/profile');
        console.log('📡 Response status:', response.status);
        
        if (response.ok) {
            const data = await response.json();
            console.log('✅ User data received:', data);
            
            const nameInput = document.getElementById('recipientName');
            const phoneInput = document.getElementById('phoneNumber');
            
            // Fill name field
            if (nameInput) {
                if (data.name) {
                    nameInput.value = data.name;
                    console.log('✅ Name filled:', data.name);
                } else {
                    console.log('⚠️ No name in user data');
                }
            } else {
                console.error('❌ Name input not found!');
            }
            
            // Fill phone field
            if (phoneInput) {
                if (data.phone) {
                    phoneInput.value = data.phone;
                    console.log('✅ Phone filled:', data.phone);
                } else {
                    console.log('⚠️ No phone in user data');
                }
            } else {
                console.error('❌ Phone input not found!');
            }
            
            // Show success message
            if (data.name || data.phone) {
                showAlert('تم تعبئة بياناتك تلقائياً', 'success');
            }
        } else {
            console.log('⚠️ Not authenticated or error:', response.status);
        }
    } catch (error) {
        console.error('❌ Error loading user data:', error);
    }
}

// Navigate steps
function goToStep(step) {
    if (step > currentStep && !validateStep(currentStep)) return;
    currentStep = step;
    updateStepUI();
}

// Validate
function validateStep(step) {
    if (step === 1) {
        if (!document.getElementById('recipientName').value.trim()) {
            showAlert('الرجاء إدخال اسم المستلم', 'error');
            return false;
        }
        if (!document.getElementById('phoneNumber').value.trim()) {
            showAlert('الرجاء إدخال رقم الهاتف', 'error');
            return false;
        }
        const village = document.getElementById('village').value.trim();
        if (!village) {
            showAlert('الرجاء تحديد الموقع على الخريطة', 'error');
            return false;
        }
        if (!selectedLocation) {
            showAlert('الرجاء تحديد موقع التوصيل على الخريطة', 'error');
            return false;
        }

        if (window.hasMartItems) {
            const supportedVillages = ['السويداء', 'عتيل', 'قنوات', 'Sweida', 'Atil', 'Kanawat'];
            const isSupported = supportedVillages.some(v => village.includes(v));

            if (!isSupported) {
                showAlert('عذراً، لا يمكن توصيل منتجات Mart إلى موقعك المختار. يرجى اختيار موقع ضمن السويداء، عتيل، أو قنوات والمحاولة مرة أخرى.', 'error');
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return false;
            }
        }
    }
    return true;
}

// Update UI with smooth transitions
function updateStepUI() {
    console.log('Updating UI to step:', currentStep);
    
    // Update body class for responsive CSS
    document.body.classList.remove('step-1', 'step-2', 'step-3', 'step-4');
    document.body.classList.add('step-' + currentStep);
    
    const forms = document.querySelectorAll('.step-content');
    const mapContainer = document.getElementById('mapContainer');
    const cartSummaryContainer = document.getElementById('cartSummaryContainer');
    
    // Hide all forms immediately
    forms.forEach(el => {
        el.style.display = 'none';
        el.style.opacity = '0';
    });
    
    // Toggle between map and cart summary
    if (currentStep === 1) {
        // Show map in step 1
        if (cartSummaryContainer) cartSummaryContainer.style.display = 'none';
        if (mapContainer) mapContainer.style.display = 'block';
    } else {
        // Show cart summary in steps 2-4
        if (mapContainer) mapContainer.style.display = 'none';
        if (cartSummaryContainer) cartSummaryContainer.style.display = 'block';
        loadCartSummary();
    }
    
    // Show current form
    let currentForm;
    if (currentStep === 1) currentForm = document.getElementById('shippingForm');
    else if (currentStep === 2) currentForm = document.getElementById('deliveryForm');
    else if (currentStep === 3) currentForm = document.getElementById('paymentForm');
    else if (currentStep === 4) {
        currentForm = document.getElementById('confirmationForm');
        displayOrderSummary();
    }
    
    console.log('Current form:', currentForm);
    
    if (currentForm) {
        // Reset transform first
        currentForm.style.transform = 'translateX(0)';
        currentForm.style.display = 'block';
        
        // For payment form, ensure payment options are visible
        if (currentStep === 3) {
            const paymentOptions = document.getElementById('paymentOptions');
            if (paymentOptions) {
                paymentOptions.style.display = 'block';
            }
        }
        
        // Scroll form container to top
        const formContainer = document.getElementById('formContainer');
        if (formContainer) {
            formContainer.scrollTop = 0;
        }
        
        // Force reflow to ensure display:block is applied before opacity change
        currentForm.offsetHeight;
        
        // Then fade in
        requestAnimationFrame(() => {
            currentForm.style.opacity = '1';
        });
        
        console.log('Form displayed:', currentForm.id);
    } else {
        console.error('Form not found for step:', currentStep);
    }
    
    updateProgressSteps();
}

// Load cart summary with delivery cost calculation
async function loadCartSummary() {
    try {
        const response = await fetch('/api/cart/items');
        if (!response.ok) {
            console.error('Failed to load cart');
            return;
        }
        
        const cart = await response.json();
        const cartItemsList = document.getElementById('cartItemsList');
        
        // Calculate subtotal (convert USD to SYP)
        let subtotal = 0;
        let itemsHtml = '';
        let hasMartItems = false;
        
        if (cart && cart.length > 0) {
            cart.forEach(item => {
                const priceUSD = item.product.discount_price || item.product.price;
                const itemTotal = priceUSD * item.quantity;
                subtotal += itemTotal;
                
                const isMart = isMartCartItem(item);
                if (isMart) hasMartItems = true;

                let img = item.product.image || '';
                if (img && !img.startsWith('http') && !img.startsWith('/')) {
                    img = '/storage/' + img.replace(/^storage\//, '');
                }

                itemsHtml += `
                    <div style="display:flex; gap:1rem; padding:1rem; background:${isMart ? '#fff9f0' : '#f8f9fa'}; border-radius:10px; margin-bottom:0.8rem; border-right:4px solid ${isMart ? '#ff6b35' : 'transparent'};">
                        <img src="${img || '/images/tulip_store.jpg'}" style="width:60px; height:60px; object-fit:cover; border-radius:8px;" onerror="this.src='/images/tulip_store.jpg'">
                        <div style="flex:1;">
                            <h5 style="font-family:'El Messiri',sans-serif; font-size:0.95rem; font-weight:700; color:#1a1a1a; margin:0 0 0.3rem 0;">
                                ${item.product.name}
                                ${isMart ? '<span style="font-size:0.7rem; background:#ff6b35; color:#fff; padding:2px 6px; border-radius:4px; margin-right:5px;">Mart</span>' : ''}
                            </h5>
                            <p style="font-family:'El Messiri',sans-serif; font-size:0.85rem; color:#666; margin:0;">الكمية: ${item.quantity} × ${formatPrice(priceUSD)}</p>
                        </div>
                        <div style="text-align:left;">
                            <p style="font-family:'El Messiri',sans-serif; font-size:1rem; font-weight:700; color:#ff6b35; margin:0;">${formatPrice(itemTotal)}</p>
                        </div>
                    </div>
                `;
            });
        } else {
            itemsHtml = '<p style="text-align:center; color:#999;">السلة فارغة</p>';
        }
        
        cartItemsList.innerHTML = itemsHtml;
        window.hasMartItems = hasMartItems; // Store globally for validation

        // Show mart delivery message if needed
        if (hasMartItems) {
            const martMsg = document.getElementById('martDeliveryMsg');
            if (martMsg) {
                martMsg.style.display = 'block';
            } else {
                // Inject message if not exists
                const container = document.getElementById('mainContainer');
                const msgDiv = document.createElement('div');
                msgDiv.id = 'martDeliveryMsg';
                msgDiv.style = "grid-column: 1 / -1; background: #fff3cd; color: #856404; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border-right: 5px solid #ffc107; font-weight: 600; animation: slideDown 0.4s ease;";
                msgDiv.innerHTML = '<i class="fas fa-exclamation-triangle" style="margin-left:10px;"></i> تنبيه: منتجات Mart تتوفر للتوصيل فقط إلى (السويداء، عتيل، قنوات)';
                container.parentNode.insertBefore(msgDiv, container);
            }
        }
        
        // Calculate delivery cost
        deliveryCost = calculateDeliveryCost();
        
        // Update delivery info
        const deliveryNames = {
            'normal': 'توصيل عادي',
            'express': 'توصيل مستعجل',
            'instant': 'توصيل فوري'
        };
        
        const storageInfo = nearestStorage ? ` (من ${nearestStorage.name})` : '';
        document.getElementById('deliveryDistance').textContent = deliveryDistance ? deliveryDistance.toFixed(2) + ' كم' + storageInfo : '-- كم';
        document.getElementById('deliveryTypeName').textContent = deliveryNames[selectedDelivery] || '--';
        
        // Calculate delivery cost in selected currency
        const deliveryCostUSD = deliveryCost / usdToSyp;
        document.getElementById('deliveryCost').textContent = formatPrice(deliveryCostUSD);
        
        // Retrieve coupon from sessionStorage
        let couponDiscount = 0;
        const appliedCouponData = sessionStorage.getItem('appliedCoupon');
        const appliedCoupon = appliedCouponData ? JSON.parse(appliedCouponData) : null;
        
        // Calculate coupon discount if applied
        if (appliedCoupon && appliedCoupon.discount_percentage) {
            couponDiscount = (subtotal * appliedCoupon.discount_percentage) / 100;
            
            // Show coupon discount row
            const couponRow = document.getElementById('couponDiscountRow');
            const couponCodeSpan = document.getElementById('couponCode');
            const couponDiscountAmount = document.getElementById('couponDiscountAmount');
            
            if (couponRow && couponCodeSpan && couponDiscountAmount) {
                couponRow.style.display = 'flex';
                couponCodeSpan.textContent = appliedCoupon.code || '';
                couponDiscountAmount.textContent = '-' + formatPrice(couponDiscount);
            }
        } else {
            // Hide coupon discount row if no coupon
            const couponRow = document.getElementById('couponDiscountRow');
            if (couponRow) {
                couponRow.style.display = 'none';
            }
        }
        
        // Update totals (subtotal - discount + delivery)
        const total = subtotal - couponDiscount + deliveryCostUSD;
        document.getElementById('subtotalAmount').textContent = formatPrice(subtotal);
        document.getElementById('shippingAmount').textContent = formatPrice(deliveryCostUSD);
        document.getElementById('totalAmount').textContent = formatPrice(total);
        updateBalancePaymentAvailability(total);
        
    } catch (error) {
        console.error('Error loading cart summary:', error);
    }
}

// Update progress steps (LEFT TO RIGHT - Professional Style)
function updateProgressSteps() {
    const progressLine = document.getElementById('progressLine');
    if (!progressLine) return;
    
    // Get steps by ID (they are in reverse order in HTML: 4,3,2,1)
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    const step4 = document.getElementById('step4');
    
    const stepsArray = [step1, step2, step3, step4];
    
    stepsArray.forEach((step, index) => {
        if (!step) return;
        const stepNum = index + 1; // 1, 2, 3, 4
        const circle = step.querySelector('div');
        if (!circle) return;
        const icon = circle.querySelector('i');
        const text = step.querySelector('p');
        const badge = circle.querySelector('span');
        
        if (stepNum < currentStep) {
            // Completed steps - green with checkmark
            circle.style.background = '#28a745';
            circle.style.borderColor = '#28a745';
            circle.style.boxShadow = '0 4px 20px rgba(40,167,69,0.3)';
            circle.style.transform = 'scale(1)';
            if (icon) {
                icon.className = 'fas fa-check';
                icon.style.color = '#fff';
                icon.style.fontSize = '1.6rem';
            }
            if (text) {
                text.style.color = '#28a745';
                text.style.fontWeight = '700';
            }
            if (badge) {
                badge.style.background = '#28a745';
                badge.style.color = '#fff';
                badge.style.boxShadow = '0 2px 8px rgba(40,167,69,0.3)';
            }
        } else if (stepNum === currentStep) {
            // Current step - orange/active
            circle.style.background = '#fff';
            circle.style.borderColor = '#ff6b35';
            circle.style.boxShadow = '0 4px 20px rgba(255,107,53,0.25)';
            circle.style.transform = 'scale(1.05)';
            if (icon) {
                icon.style.color = '#ff6b35';
                icon.style.fontSize = '1.4rem';
            }
            if (text) {
                text.style.color = '#2a7080';
                text.style.fontWeight = '700';
            }
            if (badge) {
                badge.style.background = '#ff6b35';
                badge.style.color = '#fff';
                badge.style.boxShadow = '0 2px 8px rgba(255,107,53,0.3)';
            }
        } else {
            // Future steps - light gray/inactive
            circle.style.background = '#e8f4f8';
            circle.style.borderColor = '#e8f4f8';
            circle.style.boxShadow = 'none';
            circle.style.transform = 'scale(1)';
            if (icon) {
                icon.style.color = '#99c2cc';
                icon.style.fontSize = '1.4rem';
            }
            if (text) {
                text.style.color = '#99c2cc';
                text.style.fontWeight = '600';
            }
            if (badge) {
                badge.style.background = '#e8f4f8';
                badge.style.color = '#99c2cc';
                badge.style.boxShadow = 'none';
            }
        }
    });
    
    // Calculate progress from LEFT to RIGHT with smooth animation
    const progress = ((currentStep - 1) / 3) * 70; // 70% max width (15% margins on each side)
    progressLine.style.width = progress + '%';
    progressLine.style.right = (85 - progress) + '%';
}

// Back to payment options
window.backToPaymentOptions = function() {
    console.log('🔙 backToPaymentOptions called');
    
    // Hide all payment detail forms with animation
    const creditCardDetails = document.getElementById('creditCardDetails');
    const syriatelDetails = document.getElementById('syriatelDetails');
    const paymentOptions = document.getElementById('paymentOptions');
    
    if (creditCardDetails) {
        creditCardDetails.classList.remove('active');
        creditCardDetails.classList.add('hiding');
    }
    
    if (syriatelDetails) {
        syriatelDetails.classList.remove('active');
        syriatelDetails.classList.add('hiding');
    }
    
    setTimeout(() => {
        if (creditCardDetails) {
            creditCardDetails.style.display = 'none';
            creditCardDetails.classList.remove('hiding');
        }
        if (syriatelDetails) {
            syriatelDetails.style.display = 'none';
            syriatelDetails.classList.remove('hiding');
        }
        
        // Show payment options with animation
        if (paymentOptions) {
            paymentOptions.style.display = 'block';
            paymentOptions.classList.remove('hiding');
            setTimeout(() => paymentOptions.classList.add('active'), 50);
        }
    }, 300);
};

// Select delivery
function selectDelivery(type) {
    selectedDelivery = type;
    
    document.querySelectorAll('.delivery-option').forEach(option => {
        const optionType = option.getAttribute('data-type');
        const statusIcon = option.querySelector('.delivery-status-icon');
        const mainIcon = option.querySelector('.delivery-main-icon');
        
        if (optionType === type) {
            option.style.borderColor = '#ff6b35';
            if (mainIcon) {
                mainIcon.style.filter = 'drop-shadow(0 2px 6px rgba(255,107,53,0.35))';
                mainIcon.style.transform = 'scale(1.1)';
            }
            if (statusIcon) {
                statusIcon.className = 'fas fa-check-circle delivery-status-icon';
                statusIcon.style.color = '#ff6b35';
            }
        } else {
            option.style.borderColor = '#e0e0e0';
            if (mainIcon) {
                mainIcon.style.filter = '';
                mainIcon.style.transform = 'scale(1)';
            }
            if (statusIcon) {
                statusIcon.className = 'far fa-circle delivery-status-icon';
                statusIcon.style.color = '#ccc';
            }
        }
    });
    
    // Recalculate delivery cost when delivery type changes
    loadCartSummary();
}

// Select payment
function selectPayment(type) {
    const selectedOption = document.querySelector(`.payment-option[data-type="${type}"]`);
    if (selectedOption && selectedOption.dataset && selectedOption.dataset.disabled === '1') {
        showAlert('The balance is not enough to submit this order.', 'error');
        return;
    }

    selectedPayment = type;
    
    // Update payment option styles
    document.querySelectorAll('.payment-option').forEach(option => {
        const optionType = option.getAttribute('data-type');
        const icons = option.querySelectorAll('i');
        
        if (optionType === type) {
            option.style.borderColor = '#ff6b35';
            icons[0].style.color = '#ff6b35';
            icons[1].className = 'fas fa-check-circle';
            icons[1].style.color = '#ff6b35';
        } else {
            option.style.borderColor = '#e0e0e0';
            icons[0].style.color = '#2a7080';
            icons[1].className = 'far fa-circle';
            icons[1].style.color = '#ccc';
        }
    });
}

// Proceed with selected payment method
window.proceedWithPayment = function() {
    if (!selectedPayment) {
        showAlert('الرجاء اختيار طريقة الدفع', 'error');
        return;
    }
    
    // Hide payment options with animation
    const paymentOptions = document.getElementById('paymentOptions');
    paymentOptions.classList.add('hiding');
    
    setTimeout(() => {
        paymentOptions.style.display = 'none';
        paymentOptions.classList.remove('hiding');
        
        // Show relevant payment details form with animation
        if (selectedPayment === 'card') {
            const cardDetails = document.getElementById('creditCardDetails');
            cardDetails.style.display = 'block';
            setTimeout(() => cardDetails.classList.add('active'), 50);
            loadSavedCards();
        } else if (selectedPayment === 'balance') {
            goToStep(4);
        } else if (selectedPayment === 'syriatel') {
            // Force switch to SYP for Syriatel Cash
            if (selectedCurrency !== 'SYP') {
                switchCurrency('SYP');
                showAlert('تم التبديل إلى الليرة السورية - Syriatel Cash يدعم الليرة السورية فقط', 'info');
            }
            const syriatelDetails = document.getElementById('syriatelDetails');
            syriatelDetails.style.display = 'block';
            setTimeout(() => syriatelDetails.classList.add('active'), 50);
            prepareSyriatelForm();
        } else if (selectedPayment === 'cash') {
            // Cash on delivery - go directly to confirmation
            goToStep(4);
        } else if (selectedPayment === 'bank') {
            // Bank transfer - go directly to confirmation
            goToStep(4);
        }
    }, 300);
};

// Syriatel prep...

// Prepare Syriatel form with order data and generate QR immediately
async function prepareSyriatelForm() {
    console.log('📱 Preparing Syriatel form...');
    
    try {
        // Get total amount in SYP
        const response = await fetch('/api/cart/items');
        if (!response.ok) {
            console.error('Failed to fetch cart items');
            return;
        }
        
        const cart = await response.json();
        let subtotal = 0;
        
        if (cart && cart.length > 0) {
            cart.forEach(item => {
                const priceUSD = item.product.discount_price || item.product.price;
                subtotal += priceUSD * item.quantity;
            });
        }
        
        // Calculate total with delivery
        const deliveryCostUSD = deliveryCost / usdToSyp;
        const totalUSD = subtotal + deliveryCostUSD;
        const totalSYP = Math.round(totalUSD * usdToSyp);
        
        // Generate order code (8 digits)
        const orderCode = Math.floor(10000000 + Math.random() * 90000000);
        
        // Merchant number (can be configured)
        const merchantNumber = '0944123456';
        
        console.log('💰 Order details:', {
            code: orderCode,
            merchant: merchantNumber,
            amount: totalSYP
        });
        
        // Store for QR generation
        window.syriatelOrderData = {
            code: orderCode,
            merchant: merchantNumber,
            amount: totalSYP
        };
        
        // Generate QR code immediately
        console.log('🔄 Calling generateSyriatelQR...');
        generateSyriatelQR();
        
    } catch (error) {
        console.error('❌ Error preparing Syriatel form:', error);
        showAlert('حدث خطأ في تحضير البيانات', 'error');
    }
}

// Generate Syriatel QR code
function generateSyriatelQR() {
    if (!window.syriatelOrderData) {
        console.error('No Syriatel order data');
        return;
    }
    
    // Check if QRCode library is loaded
    if (typeof QRCode === 'undefined') {
        console.error('QRCode library not loaded yet, retrying...');
        setTimeout(generateSyriatelQR, 500);
        return;
    }
    
    // Create QR data with all information
    const qrData = JSON.stringify({
        type: 'syriatel_payment',
        order: window.syriatelOrderData.code,
        merchant: window.syriatelOrderData.merchant,
        amount: window.syriatelOrderData.amount,
        currency: 'SYP',
        timestamp: Date.now()
    });
    
    // Clear previous QR code
    const qrContainer = document.getElementById('qrCodeContainer');
    if (!qrContainer) {
        console.error('QR container not found');
        return;
    }
    
    qrContainer.innerHTML = '';
    
    // Generate new QR code with website colors
    try {
        new QRCode(qrContainer, {
            text: qrData,
            width: 250,
            height: 250,
            colorDark: '#2a7080',  // Website primary color
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
        
        console.log('✅ Syriatel QR generated with data:', window.syriatelOrderData);
    } catch (error) {
        console.error('❌ Error generating QR code:', error);
        qrContainer.innerHTML = '<p style="color:#e74c3c; font-family:\'El Messiri\',sans-serif; text-align:center; padding:2rem;">حدث خطأ في توليد رمز QR<br><small>الرجاء المحاولة مرة أخرى</small></p>';
    }
}

// Detect card type from number
window.detectCardType = function(input) {
    const value = input.value.replace(/\s/g, '');
    const icon = document.getElementById('cardTypeIcon');
    
    if (!icon) return;
    
    // Remove all classes
    icon.className = 'card-type-icon';
    
    if (value.length < 1) {
        icon.classList.remove('visible');
        return;
    }
    
    // Visa starts with 4
    if (value[0] === '4') {
        icon.classList.add('fab', 'fa-cc-visa', 'visa', 'visible');
    }
    // Mastercard starts with 5 or 2
    else if (value[0] === '5' || value[0] === '2') {
        icon.classList.add('fab', 'fa-cc-mastercard', 'mastercard', 'visible');
    }
    else {
        icon.classList.remove('visible');
    }
};

// Select card type (Visa or Mastercard)
window.selectCardType = function(type) {
    const visaCard = document.getElementById('visaCard');
    const mastercardCard = document.getElementById('mastercardCard');
    
    if (!visaCard || !mastercardCard) return;
    
    if (type === 'visa') {
        // Visa selected
        visaCard.style.borderColor = '#1434CB';
        visaCard.style.boxShadow = '0 4px 15px rgba(20,52,203,0.15)';
        visaCard.querySelector('.fa-circle, .fa-check-circle').className = 'fas fa-check-circle';
        
        // Mastercard unselected
        mastercardCard.style.borderColor = '#e0e0e0';
        mastercardCard.style.boxShadow = 'none';
        mastercardCard.querySelector('.fa-circle, .fa-check-circle').className = 'far fa-circle';
    } else if (type === 'mastercard') {
        // Mastercard selected
        mastercardCard.style.borderColor = '#EB001B';
        mastercardCard.style.boxShadow = '0 4px 15px rgba(235,0,27,0.15)';
        mastercardCard.querySelector('.fa-circle, .fa-check-circle').className = 'fas fa-check-circle';
        
        // Visa unselected
        visaCard.style.borderColor = '#e0e0e0';
        visaCard.style.boxShadow = 'none';
        visaCard.querySelector('.fa-circle, .fa-check-circle').className = 'far fa-circle';
    }
};

// Generate Syriatel QR and show view
window.generateSyriatelQRView = function() {
    if (!window.syriatelOrderData) {
        showAlert('حدث خطأ في البيانات', 'error');
        return;
    }
    
    // Hide form, show QR view
    document.getElementById('syriatelDetails').style.display = 'none';
    document.getElementById('syriatelQRView').style.display = 'block';
    
    // Generate QR code
    const qrData = `syriatel://pay?amount=${window.syriatelOrderData.amount}&order=${window.syriatelOrderData.code}&merchant=TulipStore`;
    
    // Clear previous QR code
    const qrContainer = document.getElementById('qrCodeContainer');
    qrContainer.innerHTML = '';
    
    // Generate new QR code
    new QRCode(qrContainer, {
        text: qrData,
        width: 250,
        height: 250,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });
    
    // Update amount display
    document.getElementById('qrDisplayAmount').textContent = window.syriatelOrderData.amount.toLocaleString() + ' ل.س';
    
    console.log('✅ Syriatel QR generated:', qrData);
};

// Back to Syriatel form
window.backToSyriatelForm = function() {
    document.getElementById('syriatelQRView').style.display = 'none';
    document.getElementById('syriatelDetails').style.display = 'block';
};

// Save QR code as image
window.saveQRCode = function() {
    const qrCanvas = document.querySelector('#qrCodeContainer canvas');
    if (qrCanvas) {
        const link = document.createElement('a');
        link.download = 'syriatel-qr-' + window.syriatelOrderData.code + '.png';
        link.href = qrCanvas.toDataURL();
        link.click();
        showAlert('تم حفظ رمز QR بنجاح', 'success');
    } else {
        showAlert('حدث خطأ في حفظ الرمز', 'error');
    }
}

// Load saved cards
async function loadSavedCards() {
    try {
        const response = await fetch('/api/user/saved-cards');
        if (response.ok) {
            const cards = await response.json();
            const savedCardsList = document.getElementById('savedCardsList');
            
            if (cards && cards.length > 0) {
                savedCardsList.innerHTML = cards.map(card => `
                    <div onclick="selectSavedCard('${card.id}')" class="saved-card-item" data-card-id="${card.id}" style="padding:1rem; background:#f8f9fa; border:2px solid #e0e0e0; border-radius:10px; cursor:pointer; transition:all 0.3s; display:flex; align-items:center; justify-content:space-between;">
                        <div style="display:flex; align-items:center; gap:1rem;">
                            <i class="fas fa-credit-card" style="font-size:1.5rem; color:#2a7080;"></i>
                            <div>
                                <p style="font-family:'El Messiri',sans-serif; font-weight:700; color:#1a1a1a; margin:0;">•••• •••• •••• ${card.last4}</p>
                                <p style="font-family:'El Messiri',sans-serif; font-size:0.85rem; color:#666; margin:0;">ينتهي في ${card.expiry}</p>
                            </div>
                        </div>
                        <i class="far fa-circle" style="font-size:1.2rem; color:#ccc;"></i>
                    </div>
                `).join('');
                
                document.getElementById('newCardForm').style.display = 'none';
            } else {
                savedCardsList.innerHTML = '<p style="font-family:\'El Messiri\',sans-serif; color:#999; text-align:center; padding:1rem;">لا توجد بطاقات محفوظة</p>';
                document.getElementById('newCardForm').style.display = 'block';
            }
        } else {
            // No saved cards or error
            document.getElementById('savedCardsList').innerHTML = '<p style="font-family:\'El Messiri\',sans-serif; color:#999; text-align:center; padding:1rem;">لا توجد بطاقات محفوظة</p>';
            document.getElementById('newCardForm').style.display = 'block';
        }
    } catch (error) {
        console.error('Error loading saved cards:', error);
        document.getElementById('newCardForm').style.display = 'block';
    }
}

// Select saved card
window.selectSavedCard = function(cardId) {
    document.querySelectorAll('.saved-card-item').forEach(item => {
        const icon = item.querySelector('i:last-child');
        if (item.getAttribute('data-card-id') === cardId) {
            item.style.borderColor = '#2a7080';
            item.style.background = '#e8f4f8';
            icon.className = 'fas fa-check-circle';
            icon.style.color = '#2a7080';
        } else {
            item.style.borderColor = '#e0e0e0';
            item.style.background = '#f8f9fa';
            icon.className = 'far fa-circle';
            icon.style.color = '#ccc';
        }
    });
    
    // Hide new card form when saved card is selected
    document.getElementById('newCardForm').style.display = 'none';
};

// Show new card form
window.showNewCardForm = function() {
    document.getElementById('newCardForm').style.display = 'block';
    
    // Deselect all saved cards
    document.querySelectorAll('.saved-card-item').forEach(item => {
        const icon = item.querySelector('i:last-child');
        item.style.borderColor = '#e0e0e0';
        item.style.background = '#f8f9fa';
        icon.className = 'far fa-circle';
        icon.style.color = '#ccc';
    });
};

// Format card number with spaces
window.formatCardNumber = function(input) {
    let value = input.value.replace(/\s/g, '').replace(/[^0-9]/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    input.value = formattedValue;
};

// Format expiry date
window.formatExpiry = function(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    input.value = value;
};

// Old function removed - using the new one defined earlier

// Display summary
function displayOrderSummary() {
    const name = document.getElementById('recipientName').value;
    const phone = document.getElementById('phoneNumber').value;
    const village = document.getElementById('village').value;
    
    const deliveryNames = {
        'normal': 'توصيل عادي',
        'express': 'توصيل مستعجل',
        'instant': 'توصيل فوري'
    };
    
    const paymentNames = {
        'cash': 'الدفع عند الاستلام',
        'balance': 'الدفع بالرصيد',
        'card': 'بطاقة ائتمان',
        'syriatel': 'Syriatel Cash',
        'bank': 'تحويل بنكي'
    };
    
    let html = `
        <div style="display:flex;justify-content:space-between;padding:0.8rem;background:#fff;border-radius:8px;">
            <span style="color:#666;">الاسم:</span>
            <span style="color:#1a1a1a;font-weight:700;">${name}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:0.8rem;background:#fff;border-radius:8px;">
            <span style="color:#666;">الهاتف:</span>
            <span style="color:#1a1a1a;font-weight:700;">${phone}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:0.8rem;background:#fff;border-radius:8px;">
            <span style="color:#666;">القرية/المدينة:</span>
            <span style="color:#1a1a1a;font-weight:700;">${village}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:0.8rem;background:#fff;border-radius:8px;">
            <span style="color:#666;">التوصيل:</span>
            <span style="color:#1a1a1a;font-weight:700;">${deliveryNames[selectedDelivery]}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:0.8rem;background:#fff;border-radius:8px;">
            <span style="color:#666;">الدفع:</span>
            <span style="color:#1a1a1a;font-weight:700;">${paymentNames[selectedPayment]}</span>
        </div>`;
    
    document.getElementById('orderSummary').innerHTML = html;
}

// Submit order (legacy version kept for compatibility – updated to use text input and include delivery cost)
async function submitOrder() {
    const recipient = document.getElementById('recipientName')?.value || '';
    const phone = document.getElementById('phoneNumber')?.value || '';
    const village = document.getElementById('village')?.value || '';
    const note = document.getElementById('addressNote')?.value || '';
    if (!recipient || !phone || !village || !selectedLocation) {
        showAlert('الرجاء ملء الحقول المطلوبة وتحديد الموقع على الخريطة', 'error');
        return;
    }
    const data = {
        recipient_name: recipient,
        phone: phone,
        village: village,
        address_note: note,
        location: selectedLocation,
        delivery_method: selectedDelivery,
        payment_method: selectedPayment,
        delivery_cost: (deliveryCost || 0) / (usdToSyp || 1)
    };
    try {
        const response = await fetch('/api/orders/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result?.success) {
            showAlert('تم إنشاء الطلب بنجاح!', 'success');
            setTimeout(() => window.location.href = '/cart', 1500);
        } else {
            const msg = result?.message || 'حدث خطأ';
            showAlert(msg, 'error');
        }
    } catch (error) {
        showAlert('حدث خطأ في إنشاء الطلب', 'error');
    }
}

// Show alert
function showAlert(msg, type = 'info') {
    const div = document.createElement('div');
    const bg = type === 'error' ? '#e74c3c' : type === 'success' ? '#28a745' : '#2a7080';
    div.style.cssText = `position:fixed;top:2rem;right:2rem;background:${bg};color:#fff;padding:1rem 2rem;border-radius:12px;font-weight:600;z-index:10000;`;
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 3000);
}

// Load user data into form
function loadUserData() {
    console.log('👤 Loading user data...');
    
    if (window.userData) {
        const nameInput = document.getElementById('recipientName');
        const phoneInput = document.getElementById('phoneNumber');
        
        if (nameInput && window.userData.name) {
            nameInput.value = window.userData.name;
            console.log('✅ Name loaded:', window.userData.name);
        }
        
        if (phoneInput && window.userData.phone) {
            phoneInput.value = window.userData.phone;
            console.log('✅ Phone loaded:', window.userData.phone);
        }
    } else {
        console.log('⚠️ No user data available');
    }
}

let savedAddresses = [];

async function loadSavedAddresses() {
    try {
        const res = await fetch('/api/addresses', { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return [];
        const data = await res.json();
        if (data && data.success && Array.isArray(data.items)) {
            return data.items;
        }
    } catch (e) {
    }

    return [];
}

function renderSavedAddressesUI(addresses) {
    const shippingForm = document.getElementById('shippingForm');
    if (!shippingForm) return;

    const existing = document.getElementById('savedAddressesBlock');
    if (existing) existing.remove();

    if (!Array.isArray(addresses) || addresses.length === 0) return;

    const block = document.createElement('div');
    block.id = 'savedAddressesBlock';
    block.style.marginBottom = '1rem';
    block.innerHTML = `
        <div style="background:linear-gradient(135deg,#f5fbfc 0%,#eef7f9 100%); padding:1rem; border-radius:14px; border:1px solid #dbecef;">
            <div style="display:flex; align-items:center; gap:0.65rem; margin-bottom:0.75rem;">
                <div style="width:34px;height:34px;border-radius:10px;background:#e4f2f5;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-map-marker-alt" style="color:#2a7080;"></i>
                </div>
                <span style="font-family:'El Messiri',sans-serif; font-weight:800; color:#1f6b77; font-size:1.02rem;">Saved Locations</span>
            </div>
            <div style="display:grid; grid-template-columns:1fr auto; gap:0.6rem; align-items:center;">
                <select id="savedAddressSelect" style="width:100%; min-width:220px; padding:0.7rem 0.8rem; border:1px solid #cfe2e7; border-radius:12px; font-family:'El Messiri',sans-serif; background:#fff; color:#0f4f55;">
                    ${addresses.map(a => {
                        const delivered = Number(a.order_count || 0);
                        const idx = addresses.indexOf(a) + 1;
                        const autoLabel = `Location ${idx}${delivered > 0 ? ` - delivered to ${delivered} time${delivered > 1 ? 's' : ''}` : ''}`;
                        const label = autoLabel;
                        const suffix = a.is_default ? ' (default)' : '';
                        return `<option value="${a.id}">${label}${suffix}</option>`;
                    }).join('')}
                </select>
                <button id="applySavedAddressBtn" type="button" style="background:#1f6b77; color:#fff; border:none; padding:0.72rem 1.1rem; border-radius:12px; font-family:'El Messiri',sans-serif; font-weight:800; cursor:pointer; white-space:nowrap;">
                    استخدام
                </button>
            </div>
        </div>
    `;

    const title = shippingForm.querySelector('h2');
    if (title && title.parentNode) {
        title.parentNode.insertBefore(block, title.nextSibling);
    } else {
        shippingForm.insertBefore(block, shippingForm.firstChild);
    }

    const btn = document.getElementById('applySavedAddressBtn');
    if (btn) {
        btn.addEventListener('click', () => {
            const sel = document.getElementById('savedAddressSelect');
            const id = sel ? String(sel.value) : null;
            const address = savedAddresses.find(a => String(a.id) === id);
            if (address) {
                applySavedAddress(address);
            }
        });
    }
}

function applySavedAddress(address) {
    const recipient = document.getElementById('recipientName');
    const phone = document.getElementById('phoneNumber');
    const village = document.getElementById('village');
    const villageCoords = document.getElementById('villageCoords');
    const note = document.getElementById('addressNote');

    if (recipient && address.contact_name) recipient.value = address.contact_name;
    if (phone && address.phone) phone.value = address.phone;
    if (village) village.value = [address.line1, address.city].filter(Boolean).join(' - ');
    if (note && address.line2) note.value = address.line2;
    if (villageCoords && address.lat && address.lng) villageCoords.value = `${address.lat},${address.lng}`;

    if (address.lat && address.lng) {
        selectedLocation = { lat: parseFloat(address.lat), lng: parseFloat(address.lng) };
        if (typeof L !== 'undefined' && typeof window.placeMarkerLeaflet === 'function') {
            try {
                window.placeMarkerLeaflet(L.latLng(selectedLocation.lat, selectedLocation.lng));
            } catch (e) {
            }
        }
    }
}

async function saveAddressIfPossible(orderData) {
    try {
        const payload = {
            label: orderData.village || 'عنوان التوصيل',
            contact_name: orderData.recipient_name || null,
            phone: orderData.phone || null,
            line1: orderData.village || '',
            line2: orderData.address_note || null,
            city: 'As-Suwayda',
            country: 'SY',
            lat: orderData.location?.lat ?? null,
            lng: orderData.location?.lng ?? null,
            is_default: false
        };

        if (!payload.line1) return;

        await fetch('/api/addresses', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify(payload)
        });
    } catch (e) {
    }
}

// Initialize map when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('📄 DOM loaded, initializing...');
    ensureCheckoutIdempotencyKey();
    refreshHasMartItems();
    fetchExchangeRate();
    populateVillagesDropdown();
    loadUserData();
    applyInitialPaymentFromQuery();
    loadSavedAddresses().then(items => {
        savedAddresses = items;
        renderSavedAddressesUI(items);
        const def = items.find(a => a.is_default) || items[0];
        if (def) applySavedAddress(def);
    });
    
    // Wait a bit for Leaflet to be fully loaded
    setTimeout(() => {
        console.log('🗺️ Starting map initialization...');
        
        // Check which map library is loaded
        if (typeof google !== 'undefined' && google.maps) {
            console.log('✅ Google Maps library is loaded');
            initMap(); // Will use Google Maps version
        } else if (typeof L !== 'undefined') {
            console.log('✅ Leaflet library is loaded');
            initMap(); // Will use Leaflet version
        } else {
            console.error('❌ No map library loaded!');
            alert('خطأ: لم يتم تحميل مكتبة الخرائط. الرجاء التحقق من الاتصال بالإنترنت.');
        }
    }, 100);
});

// No need to populate dropdown anymore - using input field
function populateVillagesDropdown() {
    console.log('✅ Villages data loaded:', allVillages.length, 'villages');
}

// Switch currency
window.switchCurrency = function(currency) {
    console.log('💱 Switching currency to:', currency, 'Current step:', currentStep);
    
    selectedCurrency = (String(currency || '').toUpperCase() === 'SYP') ? 'SYP' : 'USD';
    if (window.setCurrencyPreference) {
        window.setCurrencyPreference(selectedCurrency);
    }
    
    // Update button styles
    const usdBtn = document.getElementById('currencyUSD');
    const sypBtn = document.getElementById('currencySYP');
    
    if (!usdBtn || !sypBtn) {
        console.error('Currency buttons not found!');
        return;
    }
    
    if (selectedCurrency === 'USD') {
        usdBtn.style.background = '#2a7080';
        usdBtn.style.color = '#fff';
        sypBtn.style.background = '#fff';
        sypBtn.style.color = '#2a7080';
    } else {
        sypBtn.style.background = '#2a7080';
        sypBtn.style.color = '#fff';
        usdBtn.style.background = '#fff';
        usdBtn.style.color = '#2a7080';
    }
    
    // Reload cart summary with new currency (if on steps 2-4)
    if (currentStep >= 2) {
        console.log('Reloading cart summary...');
        loadCartSummary();
    }
    
    showAlert(`تم التبديل إلى ${selectedCurrency === 'USD' ? 'الدولار الأمريكي' : 'الليرة السورية'}`, 'success');
};

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('currencyUSD') && document.getElementById('currencySYP')) {
        window.switchCurrency(selectedCurrency);
    }
});

// Format price based on selected currency
function formatPrice(priceUSD) {
    // Ensure priceUSD is a valid number
    const price = parseFloat(priceUSD) || 0;
    
    console.log('Formatting price:', price, 'Currency:', selectedCurrency, 'Exchange rate:', usdToSyp);
    
    if (selectedCurrency === 'USD') {
        return '$' + price.toFixed(2);
    } else {
        const priceSYP = Math.round(price * usdToSyp);
        return priceSYP.toLocaleString() + ' SYP';
    }
}

// Fetch USD to SYP exchange rate
async function fetchExchangeRate() {
    usdToSyp = (window.TULIP_USD_TO_SYP || 117);
}

console.log('Checkout.js functions defined');


// Validate credit card and submit order
window.validateAndSubmitOrder = function() {
    // Check if using saved card or new card
    const savedCardSelected = document.querySelector('.saved-card-item[style*="border-color: rgb(42, 112, 128)"]');
    
    if (savedCardSelected) {
        // Saved card is selected, submit order
        submitOrder();
        return;
    }
    
    // Check if new card form is visible
    const newCardForm = document.getElementById('newCardForm');
    if (!newCardForm || newCardForm.style.display === 'none') {
        showAlert('الرجاء اختيار بطاقة أو إضافة بطاقة جديدة', 'error');
        return;
    }
    
    // Validate card number
    const cardNumber = document.getElementById('cardNumber');
    if (!cardNumber || !cardNumber.value.trim()) {
        showAlert('الرجاء إدخال رقم البطاقة', 'error');
        if (cardNumber) cardNumber.focus();
        return;
    }
    
    const cardNumberClean = cardNumber.value.replace(/\s/g, '');
    if (cardNumberClean.length < 13 || cardNumberClean.length > 19) {
        showAlert('رقم البطاقة غير صحيح', 'error');
        cardNumber.focus();
        return;
    }
    
    // Validate card holder name
    const cardName = document.getElementById('cardName');
    if (!cardName || !cardName.value.trim()) {
        showAlert('الرجاء إدخال اسم حامل البطاقة', 'error');
        if (cardName) cardName.focus();
        return;
    }
    
    if (cardName.value.trim().length < 3) {
        showAlert('اسم حامل البطاقة قصير جداً', 'error');
        cardName.focus();
        return;
    }
    
    // Validate expiry date
    const cardExpiry = document.getElementById('cardExpiry');
    if (!cardExpiry || !cardExpiry.value.trim()) {
        showAlert('الرجاء إدخال تاريخ الانتهاء', 'error');
        if (cardExpiry) cardExpiry.focus();
        return;
    }
    
    const expiryPattern = /^(0[1-9]|1[0-2])\/\d{2}$/;
    if (!expiryPattern.test(cardExpiry.value)) {
        showAlert('تاريخ الانتهاء غير صحيح (MM/YY)', 'error');
        cardExpiry.focus();
        return;
    }
    
    // Check if card is expired
    const [month, year] = cardExpiry.value.split('/');
    const expDate = new Date(2000 + parseInt(year), parseInt(month) - 1);
    const today = new Date();
    if (expDate < today) {
        showAlert('البطاقة منتهية الصلاحية', 'error');
        cardExpiry.focus();
        return;
    }
    
    // Validate CVV
    const cardCVV = document.getElementById('cardCVV');
    if (!cardCVV || !cardCVV.value.trim()) {
        showAlert('الرجاء إدخال رمز CVV', 'error');
        if (cardCVV) cardCVV.focus();
        return;
    }
    
    if (cardCVV.value.length < 3 || cardCVV.value.length > 4) {
        showAlert('رمز CVV غير صحيح', 'error');
        cardCVV.focus();
        return;
    }
    
    // All validations passed, submit order
    console.log('✅ Card validation passed, submitting order...');
    submitOrder();
};

// Save QR code as image
window.saveQRCode = function() {
    const qrCanvas = document.querySelector('#qrCodeContainer canvas');
    if (qrCanvas) {
        const link = document.createElement('a');
        link.download = 'syriatel-qr-' + (window.syriatelOrderData ? window.syriatelOrderData.code : Date.now()) + '.png';
        link.href = qrCanvas.toDataURL();
        link.click();
        showAlert('تم حفظ رمز QR بنجاح', 'success');
    } else {
        showAlert('حدث خطأ في حفظ الرمز', 'error');
    }
};


// Submit order function
async function submitOrder() {
    console.log('📦 submitOrder function called!');
    console.log('Current step:', currentStep);
    
    // Validate all required fields
    const recipientName = document.getElementById('recipientName')?.value;
    const phoneNumber = document.getElementById('phoneNumber')?.value;
    const village = document.getElementById('village')?.value;
    const addressNote = document.getElementById('addressNote')?.value || '';
    
    console.log('Form values:', { recipientName, phoneNumber, village, selectedLocation });
    
    if (!recipientName || !phoneNumber || !village) {
        showAlert('الرجاء ملء جميع الحقول المطلوبة', 'error');
        return;
    }

    if (window.hasMartItems) {
        const supportedVillages = ['السويداء', 'عتيل', 'قنوات', 'Sweida', 'Atil', 'Kanawat'];
        const isSupported = supportedVillages.some(v => (village || '').includes(v));

        if (!isSupported) {
            showAlert('عذراً، لا يمكن توصيل منتجات Mart إلى موقعك المختار. يرجى اختيار موقع ضمن السويداء، عتيل، أو قنوات والمحاولة مرة أخرى.', 'error');
            goToStep(1);
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }
    }
    
    if (!selectedLocation) {
        showAlert('الرجاء تحديد موقع التوصيل على الخريطة', 'error');
        goToStep(1);
        return;
    }
    
    console.log('Validation passed, preparing order data...');
    
    if (selectedPayment === 'balance') {
        const option = document.querySelector('.payment-option[data-type="balance"]');
        if (option && option.dataset && option.dataset.disabled === '1') {
            showAlert('The balance is not enough to submit this order.', 'error');
            return;
        }
    }

    // Prepare order data
    // Convert delivery cost from SYP to USD
    const deliveryCostUSD = deliveryCost / usdToSyp;
    const idempotencyKey = ensureCheckoutIdempotencyKey();
    
    // Retrieve coupon from sessionStorage
    const appliedCouponData = sessionStorage.getItem('appliedCoupon');
    const appliedCoupon = appliedCouponData ? JSON.parse(appliedCouponData) : null;
    
    const orderData = {
        recipient_name: recipientName,
        phone: phoneNumber,
        village: village,
        address_note: addressNote,
        location: selectedLocation,
        delivery_method: selectedDelivery,
        payment_method: selectedPayment,
        delivery_cost: deliveryCostUSD,
        service_fee: 0,
        idempotency_key: idempotencyKey
    };
    
    // Add coupon code if applied
    if (appliedCoupon && appliedCoupon.code) {
        orderData.coupon_code = appliedCoupon.code;
    }
    
    console.log('Order data:', orderData);
    
    try {
        await saveAddressIfPossible(orderData);

        const response = await fetch('/api/orders/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Idempotency-Key': idempotencyKey
            },
            body: JSON.stringify(orderData)
        });
        
        const result = await response.json();
        
        if (result.success) {
        console.log('✅ Order created successfully!', result);
        
        // Clear coupon from sessionStorage after successful order
        sessionStorage.removeItem('appliedCoupon');
        
        // Show success card
        showOrderSuccessCard(result.order_id);
    } else {
        console.error('❌ Order creation failed:', result);
        const errorMsg = result.message || 'حدث خطأ في إنشاء الطلب';
        const errorDetail = result.error ? ` (${result.error})` : '';
        showAlert(errorMsg + errorDetail, 'error');
        if (result.error) {
            console.error('Server error:', result.error);
            console.error('Line:', result.line);
        }
    }
} catch (error) {
    console.error('❌ Error submitting order:', error);
    showAlert('حدث خطأ في إنشاء الطلب: ' + error.message, 'error');
}
}

// Make submitOrder available globally
window.submitOrder = submitOrder;


// Show order success card
function showOrderSuccessCard(orderId) {
    // Check if payment method is bank transfer
    const isBankTransfer = selectedPayment === 'bank';
    
    // Create modal overlay
    const modalHTML = `
        <div id="successModal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; display:flex; align-items:center; justify-content:center; animation:fadeIn 0.3s ease; padding:1rem;">
            <div style="background:#fff; border-radius:16px; max-width:${isBankTransfer ? '550px' : '500px'}; width:90%; padding:2rem 1.5rem; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.3); animation:scaleIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55); max-height:90vh; overflow-y:auto;">
            <div style="width:80px; height:80px; background:linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius:50%; margin:0 auto 1rem; display:flex; align-items:center; justify-content:center; box-shadow:0 8px 30px rgba(40,167,69,0.3);">
                <i class="fas fa-check" style="font-size:2.5rem; color:#fff;"></i>
            </div>
            
            <h2 style="font-family:'El Messiri',sans-serif; font-size:1.6rem; font-weight:800; color:#28a745; margin:0 0 0.5rem 0;">
                تم إرسال طلبك بنجاح!
            </h2>
            
            <p style="font-family:'El Messiri',sans-serif; font-size:0.95rem; color:#666; margin:0 0 1.2rem 0; line-height:1.6;">
                شكراً لثقتك بنا! سنتواصل معك قريباً 🎉
            </p>
            
            <div style="background:#f8f9fa; border-radius:12px; padding:1.2rem; margin:1.2rem 0; border-right:3px solid #28a745;">
                <div style="display:flex; align-items:center; justify-content:center; gap:0.5rem; margin-bottom:0.5rem;">
                    <i class="fas fa-receipt" style="font-size:1.1rem; color:#28a745;"></i>
                    <h3 style="font-family:'El Messiri',sans-serif; font-size:1rem; font-weight:700; color:#1a1a1a; margin:0;">
                        رقم الطلب
                    </h3>
                </div>
                <p style="font-family:'El Messiri',sans-serif; font-size:1.3rem; font-weight:700; color:#2a7080; margin:0;">
                    #${orderId}
                </p>
            </div>
            
            ${isBankTransfer ? `
            <div style="background:#fff3cd; border-radius:12px; padding:1.2rem; margin:1.2rem 0; border-right:3px solid #ffc107;">
                <div style="display:flex; align-items:center; justify-content:center; gap:0.5rem; margin-bottom:0.8rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size:1.5rem; color:#ff6b35;"></i>
                    <h3 style="font-family:'El Messiri',sans-serif; font-size:1.1rem; font-weight:700; color:#856404; margin:0;">
                        تنبيه هام!
                    </h3>
                </div>
                <p style="font-family:'El Messiri',sans-serif; font-size:0.9rem; color:#856404; margin:0 0 0.8rem 0; line-height:1.6; font-weight:600;">
                    يرجى تحويل المبلغ إلى حسابنا البنكي ثم رفع إيصال الدفع من صفحة "طلباتي"
                </p>
                <div style="background:#fff; padding:0.8rem; border-radius:8px; margin-bottom:0.8rem;">
                    <p style="font-family:'El Messiri',sans-serif; font-size:0.8rem; color:#666; margin:0 0 0.3rem 0;">
                        <strong>البنك:</strong> بنك سوريا الدولي الإسلامي
                    </p>
                    <p style="font-family:'El Messiri',sans-serif; font-size:0.8rem; color:#666; margin:0;">
                        <strong>رقم الحساب:</strong> 123456789
                    </p>
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem; background:#ffeaa7; padding:0.6rem; border-radius:6px;">
                    <input type="checkbox" id="bankAcknowledge" style="width:18px; height:18px; cursor:pointer;">
                    <label for="bankAcknowledge" style="font-family:'El Messiri',sans-serif; font-size:0.8rem; color:#856404; margin:0; cursor:pointer; font-weight:600;">
                        فهمت، سأقوم برفع الإيصال
                    </label>
                </div>
            </div>
            ` : `
            <div style="background:#e8f5e9; border-radius:10px; padding:1rem; margin:1.2rem 0;">
                <p style="font-family:'El Messiri',sans-serif; font-size:0.85rem; color:#2e7d32; margin:0; line-height:1.5;">
                    <i class="fas fa-info-circle" style="margin-left:0.3rem;"></i>
                    سنتواصل معك لتأكيد الطلب والتوصيل
                </p>
            </div>
            `}
            
            <div style="display:flex; gap:0.8rem; justify-content:center; margin-top:1.5rem;" id="actionButtons">
                <button id="goToOrdersBtn" style="flex:1; background:linear-gradient(135deg, #2a7080 0%, #1a5060 100%); color:#fff; border:none; padding:0.8rem 1.2rem; font-family:'El Messiri',sans-serif; font-size:0.95rem; font-weight:700; border-radius:10px; cursor:pointer; transition:all 0.3s; box-shadow:0 4px 12px rgba(42,112,128,0.3);">
                    <i class="fas fa-shopping-bag" style="margin-left:0.3rem;"></i>
                    طلباتي
                </button>
                <button id="goToHomeBtn" style="flex:1; background:#fff; color:#2a7080; border:2px solid #2a7080; padding:0.8rem 1.2rem; font-family:'El Messiri',sans-serif; font-size:0.95rem; font-weight:700; border-radius:10px; cursor:pointer; transition:all 0.3s;">
                    <i class="fas fa-home" style="margin-left:0.3rem;"></i>
                    الرئيسية
                </button>
            </div>
        </div>
        
        <style>
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes scaleIn {
                from { transform: scale(0); }
                to { transform: scale(1); }
            }
            </div>
        </div>
        
        <style>
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes scaleIn {
                from { transform: scale(0); opacity: 0; }
                to { transform: scale(1); opacity: 1; }
            }
        </style>
    `;
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Add event listeners to buttons after DOM is ready
    setTimeout(() => {
        const ordersBtn = document.getElementById('goToOrdersBtn');
        const homeBtn = document.getElementById('goToHomeBtn');
        const checkbox = document.getElementById('bankAcknowledge');
        const actionButtons = document.getElementById('actionButtons');
        
        // If bank transfer, disable buttons until checkbox is checked
        if (isBankTransfer) {
            ordersBtn.disabled = true;
            homeBtn.disabled = true;
            ordersBtn.style.opacity = '0.5';
            homeBtn.style.opacity = '0.5';
            ordersBtn.style.cursor = 'not-allowed';
            homeBtn.style.cursor = 'not-allowed';
            
            if (checkbox) {
                checkbox.onchange = function() {
                    if (this.checked) {
                        ordersBtn.disabled = false;
                        homeBtn.disabled = false;
                        ordersBtn.style.opacity = '1';
                        homeBtn.style.opacity = '1';
                        ordersBtn.style.cursor = 'pointer';
                        homeBtn.style.cursor = 'pointer';
                    } else {
                        ordersBtn.disabled = true;
                        homeBtn.disabled = true;
                        ordersBtn.style.opacity = '0.5';
                        homeBtn.style.opacity = '0.5';
                        ordersBtn.style.cursor = 'not-allowed';
                        homeBtn.style.cursor = 'not-allowed';
                    }
                };
            }
        }
        
        if (ordersBtn) {
            ordersBtn.onclick = function(e) {
                e.preventDefault();
                if (!this.disabled) {
                    window.location.href = '/my-orders';
                }
            };
        }
        
        if (homeBtn) {
            homeBtn.onclick = function(e) {
                e.preventDefault();
                if (!this.disabled) {
                    window.location.href = '/';
                }
            };
        }
    }, 200);
    
    // Clear cart count in navbar
    if (window.updateCartCount) {
        window.updateCartCount(0);
    }
}


// Detect card type based on number
function detectCardType(input) {
    const value = input.value.replace(/\s/g, '');
    const cardTypeIcon = document.getElementById('cardTypeIcon');
    const previewCardType = document.getElementById('previewCardType');
    
    let cardType = 'fa-cc-visa';
    let color = '#94a3b8';
    
    if (value.length > 0) {
        // Visa
        if (/^4/.test(value)) {
            cardType = 'fa-cc-visa';
            color = '#1A1F71';
        }
        // Mastercard
        else if (/^5[1-5]/.test(value) || /^2[2-7]/.test(value)) {
            cardType = 'fa-cc-mastercard';
            color = '#EB001B';
        }
        // American Express
        else if (/^3[47]/.test(value)) {
            cardType = 'fa-cc-amex';
            color = '#006FCF';
        }
        // Discover
        else if (/^6(?:011|5)/.test(value)) {
            cardType = 'fa-cc-discover';
            color = '#FF6000';
        }
        // JCB
        else if (/^35/.test(value)) {
            cardType = 'fa-cc-jcb';
            color = '#0E4C96';
        }
        // Diners Club
        else if (/^3(?:0[0-5]|[68])/.test(value)) {
            cardType = 'fa-cc-diners-club';
            color = '#0079BE';
        }
        // Default for unknown
        else {
            cardType = 'fa-credit-card';
            color = '#667eea';
        }
    }
    
    if (cardTypeIcon) {
        // Use 'fab' for brand icons, 'fas' for generic
        const iconClass = cardType === 'fa-credit-card' ? 'fas' : 'fab';
        cardTypeIcon.className = `${iconClass} ${cardType}`;
        cardTypeIcon.style.color = color;
    }
    
    if (previewCardType) {
        const iconClass = cardType === 'fa-credit-card' ? 'fas' : 'fab';
        previewCardType.className = `${iconClass} ${cardType}`;
    }
}

// Format card number with spaces
function formatCardNumber(input) {
    let value = input.value.replace(/\s/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    input.value = formattedValue;
}

// Format expiry date
function formatExpiry(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    input.value = value;
}

// Update card preview in real-time
function updateCardPreview() {
    const cardNumber = document.getElementById('cardNumber')?.value || '';
    const cardName = document.getElementById('cardName')?.value || '';
    const cardExpiry = document.getElementById('cardExpiry')?.value || '';
    
    // Update card number preview
    const previewNumber = document.getElementById('previewCardNumber');
    if (previewNumber) {
        if (cardNumber) {
            previewNumber.textContent = cardNumber;
        } else {
            previewNumber.textContent = '•••• •••• •••• ••••';
        }
    }
    
    // Update card name preview
    const previewName = document.getElementById('previewCardName');
    if (previewName) {
        if (cardName) {
            previewName.textContent = cardName;
        } else {
            previewName.textContent = 'الاسم الكامل';
        }
    }
    
    // Update expiry preview
    const previewExpiry = document.getElementById('previewCardExpiry');
    if (previewExpiry) {
        if (cardExpiry) {
            previewExpiry.textContent = cardExpiry;
        } else {
            previewExpiry.textContent = 'MM/YY';
        }
    }
}

// Validate card and proceed to confirmation
window.validateCardAndProceed = function() {
    console.log('💳 Validating credit card...');
    
    // Check if a saved card is selected
    const savedCardItems = document.querySelectorAll('.saved-card-item');
    let savedCardSelected = false;
    savedCardItems.forEach(item => {
        if (item.style.borderColor === 'rgb(42, 112, 128)' || item.style.borderColor === '#2a7080') {
            savedCardSelected = true;
        }
    });
    
    if (savedCardSelected) {
        console.log('✅ Saved card selected, proceeding...');
        goToStep(4);
        return;
    }
    
    // Validate new card fields
    const cardNumber = document.getElementById('cardNumber');
    const cardName = document.getElementById('cardName');
    const cardExpiry = document.getElementById('cardExpiry');
    const cardCVV = document.getElementById('cardCVV');
    
    let isValid = true;
    
    // Reset errors
    document.getElementById('cardNumberError').style.display = 'none';
    document.getElementById('cardNameError').style.display = 'none';
    document.getElementById('cardExpiryError').style.display = 'none';
    document.getElementById('cardCVVError').style.display = 'none';
    
    cardNumber.style.borderColor = '#e0e7ff';
    cardName.style.borderColor = '#e0e7ff';
    cardExpiry.style.borderColor = '#e0e7ff';
    cardCVV.style.borderColor = '#e0e7ff';
    
    // Card Number Validation (16 digits minimum)
    const cardNumVal = cardNumber.value.replace(/\s/g, '');
    if (cardNumVal.length < 16 || !/^\d+$/.test(cardNumVal)) {
        document.getElementById('cardNumberError').style.display = 'block';
        cardNumber.style.borderColor = '#e74c3c';
        isValid = false;
    }
    
    // Card Name Validation
    if (cardName.value.trim().length < 3) {
        document.getElementById('cardNameError').style.display = 'block';
        cardName.style.borderColor = '#e74c3c';
        isValid = false;
    }
    
    // Card Expiry Validation (MM/YY)
    const expiryVal = cardExpiry.value;
    const expiryRegex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
    if (!expiryRegex.test(expiryVal)) {
        document.getElementById('cardExpiryError').style.display = 'block';
        cardExpiry.style.borderColor = '#e74c3c';
        isValid = false;
    } else {
        // Check if expired
        const parts = expiryVal.split('/');
        const month = parseInt(parts[0], 10);
        const year = parseInt('20' + parts[1], 10);
        const now = new Date();
        const currentYear = now.getFullYear();
        const currentMonth = now.getMonth() + 1;
        
        if (year < currentYear || (year === currentYear && month < currentMonth)) {
            document.getElementById('cardExpiryError').textContent = 'البطاقة منتهية الصلاحية';
            document.getElementById('cardExpiryError').style.display = 'block';
            cardExpiry.style.borderColor = '#e74c3c';
            isValid = false;
        }
    }
    
    // CVV Validation
    const cvvVal = cardCVV.value;
    if (cvvVal.length < 3 || !/^\d+$/.test(cvvVal)) {
        document.getElementById('cardCVVError').style.display = 'block';
        cardCVV.style.borderColor = '#e74c3c';
        isValid = false;
    }
    
    if (isValid) {
        console.log('✅ New card validation successful, proceeding...');
        goToStep(4);
    } else {
        console.log('❌ Card validation failed');
        showAlert('الرجاء تصحيح الأخطاء في بيانات البطاقة', 'error');
    }
};


// Override placeMarker for Leaflet
window.placeMarkerLeaflet = function(latlng) {
    const lat = latlng.lat;
    const lng = latlng.lng;
    
    console.log('📍 Placing marker at:', lat, lng);
    
    // Remove old marker if exists
    if (window.deliveryMarker) {
        map.removeLayer(window.deliveryMarker);
    }
    
    // Remove old route if exists
    if (window.routingControl) {
        map.removeControl(window.routingControl);
    }
    
    // Create custom delivery marker icon
    const deliveryIcon = L.divIcon({
        className: 'delivery-marker',
        html: `<div style="width:40px; height:50px; position:relative;">
            <svg width="40" height="50" viewBox="0 0 40 50" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 0C11.716 0 5 6.716 5 15c0 8.284 15 35 15 35s15-26.716 15-35c0-8.284-6.716-15-15-15z" fill="#ff6b35"/>
                <circle cx="20" cy="15" r="8" fill="#fff"/>
            </svg>
        </div>`,
        iconSize: [40, 50],
        iconAnchor: [20, 50]
    });
    
    // Create delivery location marker
    window.deliveryMarker = L.marker([lat, lng], {
        icon: deliveryIcon,
        title: 'موقع التوصيل'
    }).addTo(map);
    
    // Save location
    selectedLocation = { lat, lng };
    
    // Find nearest storage
    findNearestStorage(lat, lng);
    
    // Calculate route using Leaflet Routing Machine
    if (nearestStorage) {
        window.routingControl = L.Routing.control({
            waypoints: [
                L.latLng(nearestStorage.lat, nearestStorage.lng),
                L.latLng(lat, lng)
            ],
            routeWhileDragging: false,
            show: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: false,
            showAlternatives: false,
            createMarker: function() { return null; }, // Hide default markers
            lineOptions: {
                styles: [{color: '#ff6b35', opacity: 0.8, weight: 5}]
            },
            containerClassName: 'leaflet-routing-container-hidden'
        }).addTo(map);
        
        // Hide the routing container completely - multiple attempts
        const hideRoutingUI = () => {
            // Hide all routing-related elements
            const selectors = [
                '.leaflet-routing-container',
                '.leaflet-routing-alternatives-container',
                '.leaflet-bar.leaflet-routing-container',
                'div[class*="leaflet-routing"]',
                'div[class*="routing-container"]'
            ];
            
            selectors.forEach(selector => {
                const elements = document.querySelectorAll(selector);
                elements.forEach(el => {
                    el.style.display = 'none';
                    el.style.visibility = 'hidden';
                    el.style.opacity = '0';
                    el.style.width = '0';
                    el.style.height = '0';
                    el.remove(); // Remove from DOM completely
                });
            });
            
            // Also hide anything in top-right corner except zoom
            const topRight = document.querySelector('.leaflet-top.leaflet-right');
            if (topRight) {
                topRight.style.display = 'none';
            }
        };
        
        // Run immediately and after delays
        hideRoutingUI();
        setTimeout(hideRoutingUI, 100);
        setTimeout(hideRoutingUI, 500);
        setTimeout(hideRoutingUI, 1000);
        
        // Get route distance
        window.routingControl.on('routesfound', function(e) {
            const routes = e.routes;
            const summary = routes[0].summary;
            deliveryDistance = summary.totalDistance / 1000; // Convert to km
            
            console.log('✅ Route calculated');
            console.log('📏 Road distance:', deliveryDistance.toFixed(2), 'km');
            
            // Calculate delivery cost
            deliveryCost = calculateDeliveryCost();
            
            // Don't show the green confirmation box - we have route info panel instead
            // const locationMsg = document.getElementById('selectedLocation');
            // Removed to avoid duplicate information
        });
    }
    
    // Auto-select nearest village
    autoSelectNearestVillage(lat, lng);
    
    console.log('✅ Location saved:', selectedLocation);
    console.log('📦 Nearest storage:', nearestStorage?.name);
    
    const mapInstructions = document.getElementById('mapInstructions');
    if (mapInstructions) {
        mapInstructions.style.opacity = '0.5';
    }
};

// Replace placeMarker with Leaflet version
if (typeof L !== 'undefined') {
    window.placeMarker = window.placeMarkerLeaflet;
}


// Search location on map
function searchMapLocation() {
    const query = document.getElementById('mapSearch').value.trim();
    if (!query) {
        alert('الرجاء إدخال عنوان أو منطقة للبحث');
        return;
    }
    
    // Use Nominatim geocoding service (free)
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Sweida, Syria')}&limit=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                
                // Move map to location
                map.setView([lat, lng], 15);
                
                // Place marker
                placeMarker({ lat, lng });
                
                console.log('✅ Location found:', result.display_name);
            } else {
                alert('لم يتم العثور على الموقع. حاول البحث بطريقة أخرى.');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            alert('حدث خطأ في البحث. الرجاء المحاولة مرة أخرى.');
        });
}

// Get current location
function getCurrentLocation() {
    if (!navigator.geolocation) {
        alert('المتصفح لا يدعم تحديد الموقع الجغرافي');
        return;
    }
    
    // Show loading
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;
    
    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Move map to current location
            map.setView([lat, lng], 15);
            
            // Place marker
            placeMarker({ lat, lng });
            
            // Restore button
            btn.innerHTML = originalHTML;
            btn.disabled = false;
            
            console.log('✅ Current location:', lat, lng);
        },
        (error) => {
            console.error('Geolocation error:', error);
            alert('لم نتمكن من تحديد موقعك. الرجاء السماح بالوصول للموقع أو البحث يدوياً.');
            
            // Restore button
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

// Update route info panel
function updateRouteInfo(distance, cost) {
    const routeInfo = document.getElementById('routeInfo');
    const routeDistance = document.getElementById('routeDistance');
    const routeCost = document.getElementById('routeCost');
    
    if (routeInfo && routeDistance && routeCost) {
        routeDistance.textContent = distance.toFixed(2) + ' كم';
        routeCost.textContent = Math.round(cost).toLocaleString() + ' SYP';
        routeInfo.style.display = 'block';
    }
}

// Enhanced placeMarker to show route info
const originalPlaceMarker = window.placeMarkerLeaflet;
window.placeMarkerLeaflet = function(latlng) {
    // Call original function
    originalPlaceMarker(latlng);
    
    // Update route info when route is calculated
    setTimeout(() => {
        if (deliveryDistance && deliveryCost) {
            updateRouteInfo(deliveryDistance, deliveryCost);
        }
    }, 1000);
};

// Update placeMarker reference
if (typeof L !== 'undefined') {
    window.placeMarker = window.placeMarkerLeaflet;
}

// Add Enter key support for search
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const searchInput = document.getElementById('mapSearch');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchMapLocation();
                }
            });
        }
    }, 500);
});
