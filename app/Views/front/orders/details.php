<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php 
// Helper langue
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>

<div class="bg-slate-50 py-12 min-h-screen">
    <div class="max-w-5xl mx-auto px-6">
        
        <div class="flex flex-col md:flex-row md:items-center gap-4 mb-8">
            <a href="<?php echo URLROOT; ?>/users/account?tab=orders" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-900 hover:text-white transition shadow-sm flex-shrink-0">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl font-serif font-bold text-slate-900">
                    <?php echo lang('order_details_title') ?? 'Order Details'; ?> #<?php echo htmlspecialchars($data['order']->order_number); ?>
                </h1>
                <p class="text-sm text-slate-500">
                    <?php echo lang('order_date_placed'); ?> <?php echo date('M j, Y \a\t g:i a', strtotime($data['order']->created_at)); ?>
                </p>
            </div>
            
            <div class="flex gap-3 mt-4 md:mt-0">
                <a href="<?php echo URLROOT; ?>/users/invoice/<?php echo $data['order']->id; ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition shadow-sm">
                    <i class="fas fa-print"></i> <?php echo lang('btn_download_invoice'); ?>
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 mb-8">
            <h3 class="font-bold text-slate-800 mb-8"><?php echo lang('track_title'); ?></h3>
            
            <?php 
                $status = $data['order']->status; 
                
                // Logique des étapes (Steps)
                $step1 = true; // Toujours "Placée"
                $step2 = in_array($status, ['processing', 'shipped', 'delivered']);
                $step3 = in_array($status, ['shipped', 'delivered']);
                $step4 = ($status == 'delivered');
                $isCancelled = ($status == 'cancelled');
            ?>

            <?php if($isCancelled): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-center font-bold border border-red-100">
                    <i class="fas fa-times-circle mr-2"></i> <?php echo lang('track_cancelled_msg'); ?>
                </div>
            <?php else: ?>
                
                

                <div class="relative flex justify-between">
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 z-0"></div>
                    
                    <div class="absolute top-1/2 left-0 h-1 bg-green-500 -translate-y-1/2 z-0 transition-all duration-1000" 
                         style="width: <?php echo ($step4 ? '100%' : ($step3 ? '66%' : ($step2 ? '33%' : '0%'))); ?>"></div>

                    <div class="relative z-10 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-sm border-4 transition-colors <?php echo $step1 ? 'bg-green-500 border-green-200 text-white' : 'bg-slate-100 border-white text-slate-400'; ?>">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <p class="text-[10px] md:text-xs font-bold mt-2 <?php echo $step1 ? 'text-slate-800' : 'text-slate-400'; ?>"><?php echo lang('status_pending'); ?></p>
                    </div>

                    <div class="relative z-10 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-sm border-4 transition-colors <?php echo $step2 ? 'bg-green-500 border-green-200 text-white' : 'bg-slate-100 border-white text-slate-400'; ?>">
                            <i class="fas fa-cog <?php echo ($step2 && !$step3) ? 'fa-spin' : ''; ?>"></i>
                        </div>
                        <p class="text-[10px] md:text-xs font-bold mt-2 <?php echo $step2 ? 'text-slate-800' : 'text-slate-400'; ?>"><?php echo lang('status_processing'); ?></p>
                    </div>

                    <div class="relative z-10 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-sm border-4 transition-colors <?php echo $step3 ? 'bg-green-500 border-green-200 text-white' : 'bg-slate-100 border-white text-slate-400'; ?>">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <p class="text-[10px] md:text-xs font-bold mt-2 <?php echo $step3 ? 'text-slate-800' : 'text-slate-400'; ?>"><?php echo lang('status_shipped'); ?></p>
                    </div>

                    <div class="relative z-10 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-sm border-4 transition-colors <?php echo $step4 ? 'bg-green-500 border-green-200 text-white' : 'bg-slate-100 border-white text-slate-400'; ?>">
                            <i class="fas fa-home"></i>
                        </div>
                        <p class="text-[10px] md:text-xs font-bold mt-2 <?php echo $step4 ? 'text-slate-800' : 'text-slate-400'; ?>"><?php echo lang('status_delivered'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-box-open text-slate-400"></i> <?php echo lang('order_items_title'); ?>
                    </h3>
                    
                    <div class="space-y-4">
                        <?php foreach($data['items'] as $item): ?>
                            <?php 
                                // Gestion multilingue du nom du produit
                                $pName = ($lang == 'fr' && !empty($item->name_fr)) ? $item->name_fr : $item->product_name; 
                                $imgUrl = !empty($item->image) ? URLROOT . '/uploads/' . $item->image : URLROOT . '/img/no-image.jpg';
                            ?>
                            <div class="flex gap-4 items-start border-b border-slate-50 pb-4 last:border-0 last:pb-0">
                                <div class="w-20 h-20 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0 border border-slate-100">
                                    <img src="<?php echo $imgUrl; ?>" class="w-full h-full object-cover">
                                </div>

                                <div class="flex-1">
                                    <h4 class="font-bold text-sm text-slate-900 mb-1">
                                        <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $item->slug ?? $item->product_id; ?>" class="hover:text-primary transition">
                                            <?php echo htmlspecialchars($pName); ?>
                                        </a>
                                    </h4>
                                    
                                    <?php if(!empty($item->variant_info)): ?>
                                        <span class="inline-block bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded uppercase mb-1">
                                            <?php echo htmlspecialchars($item->variant_info); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <p class="text-xs text-slate-400">SKU: <?php echo htmlspecialchars($item->sku ?? 'N/A'); ?></p>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs text-slate-400 mb-1"><?php echo lang('order_qty'); ?>: <?php echo $item->quantity; ?></p>
                                    <p class="font-bold text-slate-900"><?php echo number_format($item->price, 2); ?> <?php echo CURRENCY_SYMBOL; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-slate-100 bg-slate-50/50 -mx-6 -mb-6 p-6 rounded-b-2xl">
                        <div class="flex justify-between text-sm text-slate-500 mb-2">
                            <span><?php echo lang('order_subtotal'); ?></span>
                            <span><?php echo number_format($data['order']->total_amount - $data['order']->shipping_cost, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                        </div>
                        <div class="flex justify-between text-sm text-slate-500 mb-2">
                            <span><?php echo lang('order_shipping'); ?></span>
                            <span><?php echo number_format($data['order']->shipping_cost, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-slate-900 mt-4 pt-4 border-t border-slate-200">
                            <span><?php echo lang('order_total'); ?></span>
                            <span class="text-primary"><?php echo number_format($data['order']->total_amount, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2"><?php echo lang('order_delivery_address'); ?></h3>
                    
                    <div class="space-y-4 text-sm">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 flex-shrink-0"><i class="fas fa-user"></i></div>
                            <div>
                                <p class="font-bold text-slate-900"><?php echo htmlspecialchars($data['order']->full_name); ?></p>
                                <p class="text-xs text-slate-500">Customer</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 flex-shrink-0"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <p class="font-bold text-slate-800 mb-1">
                                    <?php echo htmlspecialchars($data['order']->shipping_address); ?>
                                </p>
                                <p class="text-slate-500 text-xs">
                                    <?php echo htmlspecialchars($data['order']->shipping_city); ?>, <?php echo htmlspecialchars($data['order']->shipping_region); ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 flex-shrink-0"><i class="fas fa-phone"></i></div>
                            <div>
                                <p class="font-bold text-slate-800"><?php echo htmlspecialchars($data['order']->shipping_phone); ?></p>
                                <p class="text-xs text-slate-500">Contact</p>
                            </div>
                        </div>

                        <?php if(!empty($data['order']->gps_coordinates)): ?>
                            <div class="pt-2">
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($data['order']->gps_coordinates); ?>" target="_blank" class="flex items-center justify-center w-full gap-2 border border-slate-200 rounded-lg py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary transition hover:border-primary">
                                    <i class="fas fa-map-marked-alt text-red-500"></i> View GPS Location
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2"><?php echo lang('order_payment_method'); ?></h3>
                    
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm text-slate-500">Method</span>
                        <span class="font-bold text-xs uppercase bg-slate-100 px-2 py-1 rounded border border-slate-200"><?php echo ucfirst($data['order']->payment_method); ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500">Status</span>
                        <?php if($data['order']->payment_status == 'paid'): ?>
                            <span class="text-green-600 font-bold text-xs uppercase flex items-center gap-1 bg-green-50 px-2 py-1 rounded border border-green-100">
                                <i class="fas fa-check-circle"></i> Paid
                            </span>
                        <?php else: ?>
                            <span class="text-yellow-600 font-bold text-xs uppercase flex items-center gap-1 bg-yellow-50 px-2 py-1 rounded border border-yellow-100">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-slate-900 rounded-2xl p-6 text-center text-white">
                    <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4 class="font-bold text-sm mb-1"><?php echo lang('order_help_text'); ?></h4>
                    <p class="text-xs text-slate-400 mb-4">Issues with this order?</p>
                    <a href="https://wa.me/233539382808" class="inline-block bg-white text-slate-900 px-6 py-2 rounded-full text-xs font-bold hover:bg-primary hover:text-white transition">
                        <?php echo lang('order_contact_support'); ?>
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>