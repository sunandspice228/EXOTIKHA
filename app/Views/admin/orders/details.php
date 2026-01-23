<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/orders" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Commande #<?php echo $data['order']->order_number; ?></h1>
            <p class="text-sm text-slate-500">Passée le <?php echo date('d F Y à H:i', strtotime($data['order']->created_at)); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-50"><h3 class="font-bold text-slate-800">Articles Commandés</h3></div>
                <div class="p-6 space-y-6">
                    <?php foreach($data['items'] as $item): ?>
                        <div class="flex items-center gap-4">
                            <img src="<?php echo URLROOT . '/public/' . $item->image; ?>" class="w-16 h-16 rounded-lg object-cover bg-slate-50">
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800 text-sm"><?php echo $item->name; ?></h4>
                                <p class="text-xs text-slate-500">Quantité: <?php echo $item->quantity; ?></p>
                            </div>
                            <div class="font-bold text-slate-800">
                                <?php echo CURRENCY_SYMBOL . number_format($item->price * $item->quantity, 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                    <span class="font-bold text-slate-500 uppercase text-xs">Total Commande</span>
                    <span class="text-2xl font-bold text-primary"><?php echo CURRENCY_SYMBOL . number_format($data['order']->total_amount, 2); ?></span>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4">Mettre à jour le statut</h3>
                <?php flash('product_message'); ?>
                <form action="<?php echo URLROOT; ?>/admin/orders_details/<?php echo $data['order']->id; ?>" method="POST">
                    <select name="status" class="w-full rounded-xl border-slate-200 focus:ring-primary mb-4 bg-slate-50 font-bold">
                        <option value="pending" <?php echo $data['order']->status == 'pending' ? 'selected' : ''; ?>>Pending (En attente)</option>
                        <option value="paid" <?php echo $data['order']->status == 'paid' ? 'selected' : ''; ?>>Paid (Payé)</option>
                        <option value="shipped" <?php echo $data['order']->status == 'shipped' ? 'selected' : ''; ?>>Shipped (Expédié)</option>
                        <option value="delivered" <?php echo $data['order']->status == 'delivered' ? 'selected' : ''; ?>>Delivered (Livré)</option>
                        <option value="cancelled" <?php echo $data['order']->status == 'cancelled' ? 'selected' : ''; ?>>Cancelled (Annulé)</option>
                    </select>
                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl font-bold shadow hover:bg-slate-800 transition">Mettre à jour</button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-50 pb-2">Informations Livraison</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold">Client</p>
                        <p class="font-bold text-slate-800"><?php echo $data['order']->full_name; ?></p>
                        <p class="text-slate-500"><?php echo $data['order']->email; ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold mt-4">Adresse</p>
                        <p class="text-slate-700">
                            <?php echo $data['order']->shipping_address; ?><br>
                            <?php echo $data['order']->shipping_city; ?>, <?php echo $data['order']->shipping_region; ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold mt-4">Téléphone</p>
                        <p class="font-mono text-slate-700"><?php echo $data['order']->shipping_phone; ?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>