<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 border-b border-slate-100 pb-6">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="<?php echo URLROOT; ?>/admin/orders" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 transition">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Order #<?php echo $data['order']->order_number; ?></h1>
                
                <?php 
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'processing' => 'bg-blue-100 text-blue-700',
                        'shipped' => 'bg-purple-100 text-purple-700',
                        'delivered' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700'
                    ];
                    $statusColor = $statusColors[$data['order']->status] ?? 'bg-gray-100 text-gray-700';
                ?>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?php echo $statusColor; ?>">
                    <?php echo $data['order']->status; ?>
                </span>
            </div>
            <p class="text-slate-400 text-xs ml-11">Placed on <?php echo date('F j, Y \a\t H:i', strtotime($data['order']->created_at)); ?></p>
        </div>

        <div class="flex gap-2">
            <form action="<?php echo URLROOT; ?>/admin/update_status" method="POST" class="flex gap-2">
    
    <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">
    
    <select name="status" class="text-sm border-slate-300 rounded-lg focus:ring-primary focus:border-primary bg-slate-50 font-bold text-slate-700">
        <option value="pending" <?php echo $data['order']->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
        <option value="processing" <?php echo $data['order']->status == 'processing' ? 'selected' : ''; ?>>Processing</option>
        <option value="shipped" <?php echo $data['order']->status == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
        <option value="delivered" <?php echo $data['order']->status == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
        <option value="cancelled" <?php echo $data['order']->status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
    </select>
    
    <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-primary transition shadow-md">
        Update
    </button>
</form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-box-open text-slate-400"></i> Order Items (<?php echo count($data['items']); ?>)
                </h3>
                
                <div class="space-y-4">
                    <?php foreach($data['items'] as $item): ?>
                        <div class="flex items-center gap-4 bg-white p-4 rounded-lg border border-slate-100 shadow-sm">
                            <div class="w-16 h-16 bg-slate-100 rounded-md overflow-hidden flex-shrink-0 border border-slate-200">
                                <?php if(!empty($item->image)): ?>
                                    <img src="<?php echo URLROOT . '/img/' . $item->image; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-image"></i></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800 text-sm">
                                    <a href="<?php echo URLROOT; ?>/admin/products/edit/<?php echo $item->product_id; ?>" class="hover:text-primary transition">
                                        <?php echo $item->product_name ?? 'Unknown Product'; ?>
                                    </a>
                                </h4>
                                
                                <div class="text-xs text-slate-500 mt-1 flex flex-wrap gap-2">
                                    <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">SKU: <?php echo $item->sku ?? 'N/A'; ?></span>
                                    <?php if(!empty($item->variant_info)): ?>
                                        <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100 font-bold"><?php echo $item->variant_info; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="block text-xs text-slate-400 font-bold uppercase">Qty: <?php echo $item->quantity; ?></span>
                                <span class="block font-bold text-slate-900"><?php echo number_format($item->price, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-200">
                    <div class="flex justify-between text-sm text-slate-500 mb-2">
                        <span>Subtotal (Products)</span>
                        <span><?php echo number_format($data['order']->total_amount - $data['order']->shipping_cost, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                    </div>
                    
                    <div class="flex justify-between text-sm font-bold text-slate-700 mb-2 bg-white p-2 rounded border border-slate-100">
                        <span class="flex items-center gap-2"><i class="fas fa-truck text-slate-400"></i> Shipping Cost</span>
                        <span><?php echo number_format($data['order']->shipping_cost, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                    </div>

                    <div class="flex justify-between text-lg font-bold text-slate-900 mt-4 pt-4 border-t border-slate-200">
                        <span>Total Amount</span>
                        <span class="text-primary text-xl"><?php echo number_format($data['order']->total_amount, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-2"></i> Delivery Details
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 text-sm border border-slate-200">
                            <?php echo substr($data['order']->full_name ?? 'C', 0, 1); ?>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-slate-900"><?php echo $data['order']->full_name; ?></p>
                            <a href="mailto:<?php echo $data['order']->email; ?>" class="text-xs text-blue-500 hover:underline"><?php echo $data['order']->email; ?></a>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <i class="fas fa-phone mt-1 text-slate-400"></i>
                        <span class="font-bold text-slate-800 tracking-wide"><?php echo $data['order']->shipping_phone; ?></span>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Address / Landmark</p>
                        <p class="text-sm font-medium text-slate-800 mb-2 leading-snug">
                            <?php echo $data['order']->shipping_address; ?>
                        </p>
                        <p class="text-xs text-slate-500">
                            <?php echo $data['order']->shipping_city; ?>, <?php echo $data['order']->shipping_region; ?>
                        </p>
                    </div>

                    <?php 
                        if(!empty($data['order']->gps_coordinates)) {
                            // GPS Link (Very Accurate)
                            $mapUrl = "http://maps.google.com/maps?q=" . $data['order']->gps_coordinates;
                            $btnText = "Open GPS Location";
                            $icon = "fa-map-marked-alt text-red-500";
                        } else {
                            // Text Address Fallback
                            $fullAddress = $data['order']->shipping_address . ', ' . $data['order']->shipping_city . ', Ghana';
                            $mapUrl = "http://maps.google.com/maps?q=" . urlencode($fullAddress);
                            $btnText = "Search Address on Maps";
                            $icon = "fa-search-location text-slate-400";
                        }
                    ?>
                    <a href="<?php echo $mapUrl; ?>" target="_blank" class="block w-full text-center bg-white border border-slate-300 text-slate-700 py-3 rounded-lg text-sm font-bold hover:bg-slate-50 hover:text-primary hover:border-primary transition shadow-sm">
                        <i class="fas <?php echo $icon; ?> mr-2"></i> <?php echo $btnText; ?>
                    </a>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">
                    <i class="fas fa-credit-card text-slate-400 mr-2"></i> Payment Info
                </h3>
                
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm text-slate-500">Method</span>
                    <span class="font-bold text-sm uppercase bg-slate-100 px-2 py-1 rounded border border-slate-200"><?php echo $data['order']->payment_method; ?></span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500">Status</span>
                    <?php if($data['order']->payment_status == 'paid'): ?>
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold uppercase flex items-center gap-1 border border-green-200">
                            <i class="fas fa-check-circle"></i> Paid
                        </span>
                    <?php else: ?>
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold uppercase flex items-center gap-1 border border-yellow-200">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                    <?php endif; ?>
                </div>

                <?php if($data['order']->paystack_ref): ?>
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Transaction Ref</p>
                        <p class="text-xs font-mono bg-slate-50 p-2 rounded text-slate-600 break-all border border-slate-100 select-all">
                            <?php echo $data['order']->paystack_ref; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>