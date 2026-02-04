<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-6xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/admin/orders" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                    Order #<?php echo $data['order']->order_number; ?>
                </h1>
                <div class="flex items-center gap-2 mt-1">
                    <?php 
                        $statusClass = match($data['order']->status) {
                            'completed', 'delivered' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                            'shipped' => 'bg-purple-100 text-purple-700 border-purple-200',
                            default => 'bg-amber-100 text-amber-700 border-amber-200'
                        };
                    ?>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border <?php echo $statusClass; ?>">
                        <?php echo lang('status_' . $data['order']->status); ?>
                    </span>
                    <span class="text-slate-400 text-xs flex items-center gap-1">
                        <i class="far fa-clock"></i> 
                        <?php echo lang('order_date_placed'); ?> <?php echo date('F j, Y \a\t g:i a', strtotime($data['order']->created_at)); ?>
                    </span>
                </div>
            </div>
        </div>
        
        <button onclick="window.print()" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl font-bold text-sm hover:bg-slate-50 transition shadow-sm flex items-center gap-2">
            <i class="fas fa-print"></i> <?php echo lang('btn_print_invoice'); ?>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide"><?php echo lang('order_items_title'); ?> (<?php echo count($data['items']); ?>)</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php 
                    $subtotal = 0;
                    foreach($data['items'] as $item): 
                        $itemTotal = $item->price * $item->quantity;
                        $subtotal += $itemTotal;
                        $imgUrl = !empty($item->image) ? URLROOT . '/uploads/' . $item->image : URLROOT . '/img/no-image.jpg';
                    ?>
                    <div class="p-6 flex gap-4 hover:bg-slate-50 transition">
                        <div class="w-16 h-16 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                            <img src="<?php echo $imgUrl; ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-bold text-slate-800 text-sm"><?php echo $item->product_name; ?></h4>
                                <span class="font-bold text-slate-800"><?php echo number_format($itemTotal, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                            </div>
                            <?php if(isset($item->sku)): ?>
                            <p class="text-xs text-slate-500 mb-1">SKU: <span class="font-mono"><?php echo $item->sku; ?></span></p>
                            <?php endif; ?>
                            
                            <p class="text-xs text-slate-500">
                                <?php echo lang('order_qty'); ?>: <span class="font-bold text-slate-700"><?php echo $item->quantity; ?></span> 
                                x <?php echo number_format($item->price, 2); ?>
                                
                                <?php if(!empty($item->variant_info)): ?>
                                    <span class="ml-2 px-2 py-0.5 bg-slate-100 rounded text-[10px] text-slate-600">
                                        <?php echo $item->variant_info; ?>
                                    </span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="bg-slate-50 p-6 border-t border-slate-200">
                    <div class="flex justify-between items-center mb-2 text-sm">
                        <span class="text-slate-500"><?php echo lang('order_subtotal'); ?></span>
                        <span class="font-bold text-slate-800"><?php echo number_format($subtotal, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                    </div>
                    <div class="flex justify-between items-center mb-4 text-sm">
                        <span class="text-slate-500"><?php echo lang('order_shipping'); ?></span>
                        <span class="font-bold text-slate-800"><?php echo number_format($data['order']->shipping_cost, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                    </div>
                    <div class="border-t border-slate-200 pt-4 flex justify-between items-center">
                        <span class="font-black text-slate-800 text-lg"><?php echo lang('order_total'); ?></span>
                        <span class="font-black text-primary text-xl"><?php echo number_format($data['order']->total_amount, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                    </div>
                </div>
            </div>

        </div>

        <div class="space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide mb-4"><?php echo lang('adm_quick_action'); ?></h3>
                <form action="<?php echo URLROOT; ?>/admin/update_status" method="POST">
                    <?php echo isset($_SESSION['csrf_token']) ? csrfField() : ''; ?>
                    <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">
                    
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-2"><?php echo lang('adm_update_status'); ?></label>
                    <div class="flex gap-2">
                        <select name="status" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-medium focus:outline-none focus:border-primary">
                            <option value="pending" <?php echo ($data['order']->status == 'pending') ? 'selected' : ''; ?>><?php echo lang('status_pending'); ?></option>
                            <option value="processing" <?php echo ($data['order']->status == 'processing') ? 'selected' : ''; ?>><?php echo lang('status_processing'); ?></option>
                            <option value="shipped" <?php echo ($data['order']->status == 'shipped') ? 'selected' : ''; ?>><?php echo lang('status_shipped'); ?></option>
                            <option value="delivered" <?php echo ($data['order']->status == 'delivered') ? 'selected' : ''; ?>><?php echo lang('status_delivered'); ?></option>
                            <option value="cancelled" <?php echo ($data['order']->status == 'cancelled') ? 'selected' : ''; ?>><?php echo lang('status_cancelled'); ?></option>
                        </select>
                        <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-primary transition shadow-sm">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fas fa-truck text-6xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide mb-4 border-b border-slate-50 pb-2"><?php echo lang('adm_customer_delivery'); ?></h3>
                
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm uppercase">
                        <?php echo substr($data['order']->full_name ?? 'C', 0, 1); ?>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-sm"><?php echo htmlspecialchars($data['order']->full_name ?? 'Client'); ?></p>
                        <a href="mailto:<?php echo $data['order']->email; ?>" class="text-xs text-primary hover:underline"><?php echo htmlspecialchars($data['order']->email); ?></a>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-slate-400 mb-1"><?php echo lang('form_phone'); ?></span>
                        <p class="text-sm text-slate-600 font-medium flex items-center gap-2">
                            <i class="fas fa-phone text-slate-300 text-xs"></i> 
                            <?php echo htmlspecialchars($data['order']->shipping_phone ?? 'N/A'); ?>
                        </p>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-slate-400 mb-1"><?php echo lang('order_delivery_address'); ?></span>
                        <p class="text-sm text-slate-600 font-medium flex items-start gap-2">
                            <i class="fas fa-map-marker-alt text-slate-300 text-xs mt-1"></i> 
                            <span class="flex-1">
                                <?php echo htmlspecialchars($data['order']->shipping_address ?? ''); ?>
                                <br>
                                <span class="text-xs text-slate-400"><?php echo htmlspecialchars($data['order']->shipping_city ?? ''); ?></span>
                            </span>
                        </p>
                        
                        <?php if(!empty($data['order']->gps_coordinates)): ?>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $data['order']->gps_coordinates; ?>" target="_blank" 
   class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded mt-2 hover:bg-blue-100 transition">
    <i class="fas fa-location-arrow"></i> <?php echo lang('adm_open_gps'); ?>
</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide mb-4 border-b border-slate-50 pb-2"><?php echo lang('adm_payment_info'); ?></h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500 font-medium"><?php echo lang('order_payment_method'); ?></span>
                        <span class="font-bold text-slate-800 uppercase text-xs bg-slate-100 px-2 py-1 rounded border border-slate-200">
                            <?php echo htmlspecialchars($data['order']->payment_method ?? 'N/A'); ?>
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500 font-medium"><?php echo lang('adm_payment_status'); ?></span>
                        <?php if($data['order']->payment_status == 'paid'): ?>
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-[10px] font-bold uppercase border border-green-200"><?php echo lang('status_paid'); ?></span>
                        <?php else: ?>
                            <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-[10px] font-bold uppercase border border-orange-200"><?php echo lang('status_unpaid'); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>