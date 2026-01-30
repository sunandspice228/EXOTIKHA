<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Base Clients</h1>
            <p class="text-slate-500 mt-1">Gérez vos utilisateurs enregistrés.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <form action="<?php echo URLROOT; ?>/admin/customers" method="GET" class="relative group w-full md:w-64">
                <input type="text" name="q" placeholder="Rechercher nom, email..." 
                       value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>"
                       class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition shadow-sm">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"></i>
            </form>

            <a href="#" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl font-bold text-sm hover:bg-slate-50 transition shadow-sm flex items-center justify-center gap-2 opacity-50 cursor-not-allowed" title="Bientôt disponible">
                <i class="fas fa-file-export"></i> Export CSV
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Client</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Contact</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Lieu</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Activité</th> 
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['customers'])): ?>
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-users-slash text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="text-lg font-bold text-slate-600">Aucun client trouvé.</p>
                                    <?php if(isset($_GET['q'])): ?>
                                        <p class="text-sm text-slate-400">Essayez d'autres termes de recherche.</p>
                                        <a href="<?php echo URLROOT; ?>/admin/customers" class="text-primary text-xs font-bold mt-2 hover:underline">Voir tout</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['customers'] as $customer): ?>
                        
                        <?php 
                            $displayName = $customer->full_name ?? ($customer->first_name . ' ' . $customer->last_name);
                            if(empty(trim($displayName))) $displayName = "Utilisateur Inconnu";
                        ?>

                        <tr class="hover:bg-slate-50 transition group">
                            
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-sm uppercase border border-indigo-100">
                                        <?php echo substr($displayName, 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm"><?php echo $displayName; ?></p>
                                        <p class="text-[10px] text-slate-400 font-mono">ID: #<?php echo $customer->id; ?></p>
                                    </div>
                                </div>
                            </td>

                            <td class="p-5">
                                <div class="flex flex-col">
                                    <a href="mailto:<?php echo $customer->email; ?>" class="text-sm text-slate-600 hover:text-primary transition flex items-center gap-2 group-hover:text-slate-900">
                                        <i class="far fa-envelope text-xs text-slate-400"></i> <?php echo $customer->email; ?>
                                    </a>
                                    <?php if(!empty($customer->billing_phone)): ?>
                                        <span class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                                            <i class="fas fa-phone text-[10px] text-slate-400"></i> <?php echo $customer->billing_phone; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="p-5">
                                <?php if(!empty($customer->billing_city)): ?>
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-map-marker-alt text-slate-300 text-xs"></i>
                                        <span class="text-sm text-slate-700 font-medium"><?php echo $customer->billing_city; ?></span>
                                    </div>
                                    <span class="text-[10px] text-slate-400 pl-4 block"><?php echo $customer->billing_region ?? ''; ?></span>
                                <?php else: ?>
                                    <span class="text-slate-400 text-xs italic">Non renseigné</span>
                                <?php endif; ?>
                            </td>

                            <td class="p-5">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1 text-xs font-bold text-slate-600">
                                        <i class="fas fa-calendar-alt text-slate-400"></i>
                                        <span><?php echo date('d M Y', strtotime($customer->created_at)); ?></span>
                                    </div>
                                    
                                    <?php if(isset($customer->order_count) && $customer->order_count > 0): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">
                                            <?php echo $customer->order_count; ?> Commandes
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-400">
                                            Aucune commande
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="p-5 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition duration-200">
                                    
                                    <a href="<?php echo URLROOT; ?>/admin/orders" 
                                       class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-primary hover:border-primary transition shadow-sm"
                                       title="Voir l'historique">
                                        <i class="fas fa-shopping-bag text-xs"></i>
                                    </a>

                                    <a href="mailto:<?php echo $customer->email; ?>" 
                                       class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-600 transition shadow-sm"
                                       title="Envoyer un email">
                                        <i class="fas fa-paper-plane text-xs"></i>
                                    </a>

                                </div>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center text-xs text-slate-500 font-medium">
            <span>Affichage de <strong><?php echo count($data['customers']); ?></strong> clients</span>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>