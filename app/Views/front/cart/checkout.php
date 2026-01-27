<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-6">
        
        <div class="flex items-center gap-4 mb-8">
            <a href="<?php echo URLROOT; ?>/cart" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-900 hover:text-white transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-serif font-bold text-slate-900">Passer la commande</h1>
        </div>

        <?php flash('cart_msg'); ?>

        <div class="flex flex-col lg:flex-row gap-12">
            
            <div class="flex-1">
                <form action="<?php echo URLROOT; ?>/cart/checkout" method="POST" id="checkoutForm">
                    <?php echo csrfField(); ?>
                    
                    <input type="hidden" name="paystack_ref" id="paystack_ref_final">
                    <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
                    <input type="hidden" name="distance_km" id="distance_km_input" value="0">
                    <input type="hidden" name="gps_lat" id="gps_lat">
                    <input type="hidden" name="gps_lng" id="gps_lng">

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 mb-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-slate-50 pb-4">
                            <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-sm">1</div>
                            <h2 class="text-xl font-bold text-slate-800">Informations de Livraison</h2>
                        </div>

                        <div class="bg-blue-50 border border-blue-100 text-blue-600 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-3 mb-6">
                            <i class="fas fa-info-circle text-lg"></i>
                            <span>Nous livrons actuellement uniquement à <strong>Accra</strong>.</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nom Complet</label>
                                <div class="relative">
                                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="full_name" placeholder="ex: John Doe" required 
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-accent focus:border-accent transition" 
                                           value="<?php echo $_SESSION['user_name'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Téléphone</label>
                                <div class="relative">
                                    <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="tel" name="phone" placeholder="ex: 024 123 4567" required 
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-accent focus:border-accent transition" 
                                           value="<?php echo $_SESSION['user_phone'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Région</label>
                                <div class="relative">
                                    <i class="fas fa-map absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="region" value="Greater Accra" readonly 
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed font-bold">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Ville</label>
                                <div class="relative">
                                    <i class="fas fa-city absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="city" value="Accra" readonly 
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed font-bold">
                                </div>
                            </div>

                            <div class="md:col-span-2 mt-4">
                                <label class="block text-xs font-bold uppercase text-slate-900 mb-2 flex justify-between">
                                    <span>📍 Position exacte (Requis)</span>
                                    <span class="text-primary text-[10px] font-normal">Déplacez le marqueur bleu sur votre maison</span>
                                </label>
                                
                                <div id="map" class="w-full h-72 rounded-xl border-2 border-slate-200 z-0 mb-3 shadow-inner relative group">
                                    <div class="absolute inset-0 bg-black/5 flex items-center justify-center pointer-events-none group-hover:hidden z-[400]">
                                        <span class="bg-white/90 px-3 py-1 rounded-full text-xs font-bold shadow text-slate-600">Cliquez pour déplacer</span>
                                    </div>
                                </div>
                                
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Détails de l'adresse (Complément)</label>
                                <input type="text" name="address" id="address_text" placeholder="ex: Maison portail noir, près de la station Shell..." 
                                       class="w-full pl-4 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-accent focus:border-accent transition">
                            </div>

                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <div class="flex items-center gap-3 mb-6 border-b border-slate-50 pb-4">
                            <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-sm">2</div>
                            <h2 class="text-xl font-bold text-slate-800">Mode de Paiement</h2>
                        </div>

                        <div class="space-y-4">
                            <label class="relative flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-primary transition group has-[:checked]:border-primary has-[:checked]:bg-slate-50">
                                <input type="radio" name="payment_method" value="paystack" class="h-5 w-5 text-primary focus:ring-primary border-gray-300" checked>
                                <div class="ml-4 flex-1">
                                    <span class="block font-bold text-slate-900">Payer maintenant</span>
                                    <span class="block text-xs text-slate-500">Carte / Mobile Money (MTN, Telecel) via Paystack</span>
                                </div>
                                <div class="flex gap-2 text-slate-400 text-xl">
                                    <i class="fas fa-mobile-alt"></i>
                                    <i class="fab fa-cc-visa"></i>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-primary transition group has-[:checked]:border-primary has-[:checked]:bg-slate-50">
                                <input type="radio" name="payment_method" value="cod" class="h-5 w-5 text-primary focus:ring-primary border-gray-300">
                                <div class="ml-4 flex-1">
                                    <span class="block font-bold text-slate-900">Payer à la livraison</span>
                                    <span class="block text-xs text-slate-500">Espèces à la réception du colis</span>
                                </div>
                                <i class="fas fa-money-bill-wave text-slate-300 group-hover:text-primary text-xl"></i>
                            </label>
                        </div>
                    </div>

                </form>
            </div>

            <div class="w-full lg:w-96 flex-shrink-0">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 sticky top-24">
                    <h3 class="font-bold text-slate-900 text-lg mb-6">Résumé de la commande</h3>

                    <div class="space-y-4 mb-6 max-h-60 overflow-y-auto custom-scrollbar pr-2">
                        <?php foreach($data['cartItems'] as $item): ?>
                            <div class="flex gap-4 items-center">
                                <div class="w-12 h-12 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0 border border-slate-200">
                                    <?php if(!empty($item->image)): ?>
                                        <img src="<?php echo URLROOT . '/img/' . $item->image; ?>" class="w-full h-full object-cover">
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-900 truncate"><?php echo $item->name; ?></p>
                                    <p class="text-xs text-slate-500">
                                        Qté: <?php echo $item->qty; ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-slate-800">
                                        <?php echo number_format($item->line_total, 2); ?> <?php echo CURRENCY_SYMBOL; ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="border-t border-slate-100 pt-4 space-y-2 mb-6">
                        <div class="flex justify-between text-slate-500 text-sm">
                            <span>Sous-total</span>
                            <span><?php echo number_format($data['total'], 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                        </div>
                        <div class="flex justify-between text-slate-500 text-sm" id="shipping_display_row">
                            <span>Livraison</span>
                            <span class="text-green-600 font-bold text-xs uppercase shipping-text">Calcul en cours...</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-8 pt-4 border-t border-slate-100">
                        <span class="font-bold text-lg text-slate-900">Total à payer</span>
                        <span class="font-black text-2xl text-primary total-display"><?php echo number_format($data['total'], 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                    </div>

                    <button type="button" onclick="handlePay()" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-primary transition shadow-lg shadow-slate-900/20 flex justify-center items-center gap-2 group">
                        <span>Confirmer la commande</span>
                        <i class="fas fa-check-circle group-hover:scale-110 transition"></i>
                    </button>
                    
                    <p class="text-center text-[10px] text-slate-400 mt-4 flex items-center justify-center gap-1">
                        <i class="fas fa-lock"></i> Paiement 100% sécurisé par Paystack
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });

    // --- CONFIGURATION ---
    const warehouseLat = <?php echo defined('WAREHOUSE_LAT') ? WAREHOUSE_LAT : 5.6206; ?>;
    const warehouseLng = <?php echo defined('WAREHOUSE_LNG') ? WAREHOUSE_LNG : -0.1717; ?>;
    const pricePerKm = <?php echo defined('SHIPPING_PER_KM') ? SHIPPING_PER_KM : 5; ?>; // Prix par KM
    const subtotal = <?php echo $data['total']; ?>;
    
    let map, marker;

    function initMap() {
        if(!document.getElementById('map')) return;

        // 1. Carte centrée sur Accra
        map = L.map('map').setView([5.6037, -0.1870], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // 2. Marqueur par défaut
        marker = L.marker([5.6037, -0.1870], {draggable: true}).addTo(map);

        // 3. Calcul initial pour éviter le prix 0
        calculateDistance(5.6037, -0.1870);

        // 4. Événements
        marker.on('dragend', function(event) {
            const position = marker.getLatLng();
            calculateDistance(position.lat, position.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            calculateDistance(e.latlng.lat, e.latlng.lng);
        });
        
        // 5. Géolocalisation (Optionnelle)
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                // Si l'utilisateur est proche d'Accra
                if(lat > 5.4 && lat < 5.8 && lng > -0.4 && lng < 0.1) {
                    const userPos = [lat, lng];
                    map.setView(userPos, 15);
                    marker.setLatLng(userPos);
                    calculateDistance(lat, lng);
                }
            });
        }
    }

    async function calculateDistance(clientLat, clientLng) {
        // Feedback visuel
        const shippingSpan = document.querySelector('.shipping-text');
        if(shippingSpan) shippingSpan.innerHTML = "<em>Calcul...</em>";
        
        // Sauvegarde GPS
        document.getElementById('gps_lat').value = clientLat;
        document.getElementById('gps_lng').value = clientLng;

        // Appel API OSRM
        const url = `https://router.project-osrm.org/route/v1/driving/${warehouseLng},${warehouseLat};${clientLng},${clientLat}?overview=false`;

        try {
            // Timeout de 2s pour basculer sur le calcul mathématique si l'API est lente
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 2000);

            const response = await fetch(url, { signal: controller.signal });
            const data = await response.json();

            if (data.code === 'Ok') {
                const distanceKm = data.routes[0].distance / 1000;
                applyPricing(distanceKm);
            } else {
                throw new Error("API Route Error");
            }
        } catch (error) {
            // FALLBACK : Calcul Mathématique (Haversine)
            const dist = getDistanceFromLatLonInKm(warehouseLat, warehouseLng, clientLat, clientLng);
            applyPricing(dist * 1.3); // *1.3 pour approximation route
        }
    }

    function applyPricing(km) {
        let cost = km * pricePerKm;
        if(cost < 15) cost = 15; // Minimum 15 GHS
        
        cost = Math.ceil(cost); // Arrondir à l'entier sup
        km = Math.round(km * 10) / 10;

        updateTotalDisplay(cost, km);
    }

    function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
        var R = 6371; 
        var dLat = deg2rad(lat2-lat1); 
        var dLon = deg2rad(lon2-lon1); 
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2); 
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
        return R * c;
    }
    function deg2rad(deg) { return deg * (Math.PI/180); }

    function updateTotalDisplay(cost, km) {
        // Affichage Frais
        const shippingSpan = document.querySelector('.shipping-text');
        if(shippingSpan) {
            shippingSpan.innerHTML = `Livraison (~${km} km): <strong>${cost.toFixed(2)} <?php echo CURRENCY_SYMBOL; ?></strong>`;
            shippingSpan.classList.remove('text-green-600');
            shippingSpan.classList.add('text-slate-900');
        }

        // Affichage Total
        const totalSpan = document.querySelector('.total-display');
        const newTotal = subtotal + cost;
        if(totalSpan) {
            totalSpan.innerText = newTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " <?php echo CURRENCY_SYMBOL; ?>";
        }

        // Inputs Cachés
        document.getElementById('shipping_cost_input').value = cost;
        document.getElementById('distance_km_input').value = km;
    }

    // Fonction de Paiement attachée à window
    window.handlePay = function() {
        const form = document.getElementById('checkoutForm');
        
        if(!form.checkValidity()){ 
            form.reportValidity(); 
            return; 
        }

        const shippingCost = parseFloat(document.getElementById('shipping_cost_input').value);
        if(isNaN(shippingCost) || shippingCost <= 0) {
            // Relance rapide si bug
            if(marker) {
                const pos = marker.getLatLng();
                calculateDistance(pos.lat, pos.lng);
                setTimeout(handlePay, 800);
                return;
            }
            alert("Veuillez vérifier votre position sur la carte.");
            return;
        }

        const method = document.querySelector('input[name="payment_method"]:checked').value;
        const finalAmount = subtotal + shippingCost; 

        if(method === 'paystack') {
            let handler = PaystackPop.setup({
                key: '<?php echo defined("PAYSTACK_PUBLIC_KEY") ? PAYSTACK_PUBLIC_KEY : ""; ?>',
                email: '<?php echo $_SESSION['user_email']; ?>',
                amount: Math.ceil(finalAmount * 100), // En centimes
                currency: 'GHS',
                metadata: {
                    custom_fields: [
                        { display_name: "Mobile", variable_name: "mobile_number", value: document.querySelector('input[name="phone"]').value },
                        { display_name: "GPS", variable_name: "gps_coords", value: document.getElementById('gps_lat').value + ',' + document.getElementById('gps_lng').value }
                    ]
                },
                callback: function(response){ 
                    document.getElementById('paystack_ref_final').value = response.reference;
                    form.submit(); 
                },
                onClose: function(){ alert('Paiement annulé.'); }
            });
            handler.openIframe();
        } else {
            form.submit();
        }
    }
</script>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>