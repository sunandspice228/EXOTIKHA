<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6 md:p-8">
    
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Tableau de Bord</h1>
            <p class="text-sm text-slate-500 mt-1">Aperçu global de l'activité Exotikha.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/products_add" class="bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-primary/30 hover:bg-slate-800 transition transform hover:-translate-y-1 flex items-center gap-2">
            <i class="fas fa-plus"></i> Nouveau Produit
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fas fa-box"></i>
                </div>
                <span class="text-[10px] font-black uppercase text-slate-400 bg-slate-50 px-2 py-1 rounded-full">Catalogue</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-800 mb-1"><?php echo $data['nb_products']; ?></h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Produits Actifs</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <i class="fas fa-users"></i>
                </div>
                <span class="text-[10px] font-black uppercase text-slate-400 bg-slate-50 px-2 py-1 rounded-full">Communauté</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-800 mb-1"><?php echo $data['nb_customers']; ?></h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Clients Inscrits</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="text-[10px] font-black uppercase text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Est. Value</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-800 mb-1">
                <?php echo defined('CURRENCY_SYMBOL') ? CURRENCY_SYMBOL : 'GHS'; ?> 
                <?php echo number_format($data['stock_value'], 0, ',', ' '); ?>
            </h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Valeur Stock</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all relative overflow-hidden group">
            <?php if($data['pending_reviews'] > 0): ?>
                <div class="absolute right-0 top-0 h-16 w-16 bg-orange-400 rotate-45 transform translate-x-8 -translate-y-8"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl group-hover:bg-orange-500 group-hover:text-white transition-colors">
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase text-orange-500 bg-orange-50 px-2 py-1 rounded-full">Modération</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1"><?php echo $data['pending_reviews']; ?></h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Avis en attente</p>
            <?php else: ?>
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl group-hover:bg-red-500 group-hover:text-white transition-colors">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase text-red-500 bg-red-50 px-2 py-1 rounded-full">Stock</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1"><?php echo $data['out_of_stock']; ?></h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Ruptures de stock</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Analyse des Ventes</h3>
                    <p class="text-sm text-slate-500">Performance <?php echo $data['current_year']; ?></p>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-primary"></span> CA Mensuel
                </div>
            </div>
            <div class="relative h-80 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="space-y-8">
            
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-6">Actions Rapides</h3>
                <div class="space-y-3">
                    
                    <a href="<?php echo URLROOT; ?>/admin/add_post" class="group flex items-center p-3 rounded-xl border border-slate-100 hover:border-accent hover:bg-indigo-50 transition-all cursor-pointer">
                        <div class="h-10 w-10 rounded-lg bg-indigo-100 text-accent flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-pen-nib"></i>
                        </div>
                        <div class="ml-3">
                            <h5 class="font-bold text-slate-800 text-xs uppercase tracking-wide">Écrire un Article</h5>
                            <p class="text-[10px] text-slate-500">Blog & News</p>
                        </div>
                        <i class="fas fa-chevron-right ml-auto text-slate-300 text-xs"></i>
                    </a>

                    <a href="<?php echo URLROOT; ?>/admin/reviews" class="group flex items-center p-3 rounded-xl border border-slate-100 hover:border-orange-200 hover:bg-orange-50 transition-all cursor-pointer">
                        <div class="h-10 w-10 rounded-lg bg-orange-100 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform relative">
                            <i class="fas fa-star"></i>
                            <?php if($data['pending_reviews'] > 0): ?>
                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
                            <?php endif; ?>
                        </div>
                        <div class="ml-3">
                            <h5 class="font-bold text-slate-800 text-xs uppercase tracking-wide">Gérer les Avis</h5>
                            <p class="text-[10px] text-slate-500"><?php echo $data['pending_reviews']; ?> en attente</p>
                        </div>
                        <i class="fas fa-chevron-right ml-auto text-slate-300 text-xs"></i>
                    </a>

                    <a href="<?php echo URLROOT; ?>/admin/users_add" class="group flex items-center p-3 rounded-xl border border-slate-100 hover:border-purple-200 hover:bg-purple-50 transition-all cursor-pointer">
                        <div class="h-10 w-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="ml-3">
                            <h5 class="font-bold text-slate-800 text-xs uppercase tracking-wide">Ajouter un Admin</h5>
                            <p class="text-[10px] text-slate-500">Staff Access</p>
                        </div>
                        <i class="fas fa-chevron-right ml-auto text-slate-300 text-xs"></i>
                    </a>

                </div>
            </div>

            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 shadow-lg text-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold">Contenu du Site</h3>
                    <i class="fas fa-layer-group text-slate-600"></i>
                </div>
                <div class="flex justify-between text-center">
                    <div>
                        <span class="block text-2xl font-bold text-accent"><?php echo $data['posts_count']; ?></span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400">Articles</span>
                    </div>
                    <div class="w-px bg-slate-700"></div>
                    <div>
                        <span class="block text-2xl font-bold text-emerald-400"><?php echo $data['nb_products']; ?></span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400">Produits</span>
                    </div>
                </div>
                <a href="<?php echo URLROOT; ?>" target="_blank" class="mt-6 block w-full bg-white/10 hover:bg-white/20 py-2 rounded-lg text-center text-xs font-bold transition">
                    Voir le site en direct
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    const salesData = <?php echo json_encode($data['chart_data']); ?>; 
    const currency = "<?php echo defined('CURRENCY') ? CURRENCY : 'GHS'; ?>";

    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Dégradé Indigo
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.5)'); 
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Ventes',
                data: salesData,
                backgroundColor: gradient,
                borderColor: '#4f46e5',
                borderWidth: 3,
                tension: 0.4, // Courbe lisse
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13 },
                    bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 14, weight: 'bold' },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' ' + currency;
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#f1f5f9', borderDash: [5, 5] },
                    ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 }, color: '#94a3b8' }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 }, color: '#94a3b8' }
                }
            }
        }
    });
</script>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>