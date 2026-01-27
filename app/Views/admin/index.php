<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Vue d'ensemble</h1>
            <p class="text-slate-500 mt-1">
                Bon retour, <?php echo isset($_SESSION['user_name']) ? explode(' ', $_SESSION['user_name'])[0] : 'Admin'; ?>. Voici ce qui se passe aujourd'hui.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo URLROOT; ?>/admin/orders" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition shadow-sm">
                <i class="fas fa-file-alt mr-2"></i> Rapports
            </a>
            <a href="<?php echo URLROOT; ?>/admin/products_add" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-indigo-600 transition shadow-lg shadow-primary/30 flex items-center gap-2">
                <i class="fas fa-plus"></i> Nouveau Produit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-lg transition duration-300">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Revenu Total</p>
                <h3 class="text-2xl font-black text-slate-800">
                    <?php echo number_format($data['revenue'] ?? 0, 0, ',', ' '); ?> <span class="text-sm text-slate-400"><?php echo CURRENCY_SYMBOL; ?></span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition">
                <i class="fas fa-wallet"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-lg transition duration-300">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Commandes</p>
                <h3 class="text-2xl font-black text-slate-800"><?php echo $data['total_orders'] ?? 0; ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-lg transition duration-300">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Produits Actifs</p>
                <h3 class="text-2xl font-black text-slate-800"><?php echo $data['total_products'] ?? 0; ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition">
                <i class="fas fa-tshirt"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-lg transition duration-300">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Clients</p>
                <h3 class="text-2xl font-black text-slate-800"><?php echo $data['total_customers'] ?? 0; ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-lg">Commandes Récentes</h3>
                    <a href="<?php echo URLROOT; ?>/admin/orders" class="text-xs font-bold text-primary hover:text-indigo-700 transition flex items-center gap-1">
                        Voir Tout <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50 text-[10px] font-black uppercase text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Réf</th>
                                <th class="px-6 py-4">Client</th>
                                <th class="px-6 py-4">Statut</th>
                                <th class="px-6 py-4 text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php if(empty($data['latest_orders'])): ?>
                                <tr><td colspan="4" class="p-8 text-center text-slate-400 italic text-sm">Aucune commande récente.</td></tr>
                            <?php else: ?>
                                <?php foreach($data['latest_orders'] as $order): ?>
                                <tr class="hover:bg-slate-50 transition cursor-pointer group" onclick="window.location='<?php echo URLROOT; ?>/admin/orders_details/<?php echo $order->id; ?>'">
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-xs font-bold text-primary bg-primary/5 px-2 py-1 rounded">
                                            #<?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500 uppercase">
                                                <?php echo substr($order->full_name, 0, 1); ?>
                                            </div>
                                            <span class="font-bold text-slate-700 text-sm"><?php echo $order->full_name; ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php 
                                            $color = 'bg-slate-100 text-slate-500';
                                            $icon = 'fa-circle';
                                            $status = strtolower($order->status); // Sécurité majuscules/minuscules
                                            
                                            if($status == 'pending') { $color = 'bg-yellow-50 text-yellow-600 border border-yellow-100'; $icon = 'fa-clock'; }
                                            if($status == 'processing') { $color = 'bg-blue-50 text-blue-600 border border-blue-100'; $icon = 'fa-cog fa-spin'; }
                                            if($status == 'shipped') { $color = 'bg-purple-50 text-purple-600 border border-purple-100'; $icon = 'fa-truck'; }
                                            if($status == 'delivered') { $color = 'bg-green-50 text-green-600 border border-green-100'; $icon = 'fa-check'; }
                                            if($status == 'cancelled') { $color = 'bg-red-50 text-red-600 border border-red-100'; $icon = 'fa-times'; }
                                        ?>
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase flex items-center gap-1.5 w-fit <?php echo $color; ?>">
                                            <i class="fas <?php echo $icon; ?> text-[9px]"></i> <?php echo $order->status; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-800 text-sm">
                                        <?php echo number_format($order->total_amount, 0, ',', ' ') . ' ' . CURRENCY_SYMBOL; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-orange-500"></i> Stock Faible
                    </h3>
                    <?php if(!empty($data['low_stock'])): ?>
                        <span class="bg-orange-100 text-orange-600 text-[10px] font-bold px-2 py-1 rounded-full"><?php echo count($data['low_stock']); ?> articles</span>
                    <?php endif; ?>
                </div>

                <?php if(empty($data['low_stock'])): ?>
                    <div class="p-8 text-center">
                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="text-sm text-slate-500 font-medium">Tout va bien ! Aucun stock critique.</p>
                    </div>
                <?php else: ?>
                    <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
                        <?php foreach($data['low_stock'] as $prod): ?>
                        <div class="p-4 flex items-center gap-4 hover:bg-slate-50 transition border-b border-slate-50 last:border-0">
                            <div class="w-10 h-10 rounded-lg bg-slate-100 flex-shrink-0 overflow-hidden border border-slate-200">
                                <?php if($prod->image): ?>
                                    <img src="<?php echo URLROOT; ?>/img/<?php echo $prod->image; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-image"></i></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate"><?php echo $prod->name; ?></p>
                                <p class="text-[10px] text-slate-400 font-mono">SKU: <?php echo $prod->sku; ?></p>
                            </div>

                            <div class="text-right">
                                <?php if($prod->stock == 0): ?>
                                    <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-1 rounded border border-red-100">0</span>
                                <?php else: ?>
                                    <span class="bg-orange-50 text-orange-600 text-[10px] font-bold px-2 py-1 rounded border border-orange-100"><?php echo $prod->stock; ?> restants</span>
                                <?php endif; ?>
                            </div>
                            
                            <a href="<?php echo URLROOT; ?>/admin/products_edit/<?php echo $prod->id; ?>" class="text-slate-300 hover:text-primary transition">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="p-3 bg-slate-50 text-center border-t border-slate-100">
                        <a href="<?php echo URLROOT; ?>/admin/products" class="text-xs font-bold text-primary hover:underline">Gérer l'inventaire</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-lg shadow-slate-900/20">
                <h3 class="font-bold mb-4 text-sm uppercase tracking-widest opacity-80">Actions Rapides</h3>
                <div class="space-y-3">
                    <a href="<?php echo URLROOT; ?>/admin/products_add" class="block w-full bg-white/10 hover:bg-white/20 px-4 py-3 rounded-xl text-sm font-bold transition flex items-center justify-between group">
                        <span class="flex items-center gap-3"><i class="fas fa-plus w-4 text-center text-primary"></i> Ajouter Produit</span>
                        <i class="fas fa-chevron-right text-xs opacity-50 group-hover:translate-x-1 transition"></i>
                    </a>
                    <a href="<?php echo URLROOT; ?>/admin/categories" class="block w-full bg-white/10 hover:bg-white/20 px-4 py-3 rounded-xl text-sm font-bold transition flex items-center justify-between group">
                        <span class="flex items-center gap-3"><i class="fas fa-layer-group w-4 text-center text-accent"></i> Catégories</span>
                        <i class="fas fa-chevron-right text-xs opacity-50 group-hover:translate-x-1 transition"></i>
                    </a>
                    <a href="<?php echo URLROOT; ?>" target="_blank" class="block w-full bg-white/10 hover:bg-white/20 px-4 py-3 rounded-xl text-sm font-bold transition flex items-center justify-between group">
                        <span class="flex items-center gap-3"><i class="fas fa-eye w-4 text-center text-emerald-400"></i> Voir la Boutique</span>
                        <i class="fas fa-external-link-alt text-xs opacity-50 group-hover:translate-x-1 transition"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>