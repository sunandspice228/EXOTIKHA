<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-slate-50 py-16">
    <div class="max-w-5xl mx-auto px-6">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <a href="<?php echo URLROOT; ?>/users/account" class="text-sm font-bold text-slate-500 hover:text-slate-900 flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i> Back to My Account
            </a>
            <?php if(isset($data['order']->id)): ?>
            <a href="<?php echo URLROOT; ?>/users/invoice/<?php echo $data['order']->id; ?>" target="_blank" class="bg-white border border-slate-200 text-slate-900 px-6 py-2 rounded-xl font-bold text-sm hover:bg-slate-50 transition shadow-sm flex items-center gap-2">
                <i class="fas fa-file-download"></i> Download Invoice
            </a>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            
            <div class="p-8 md:p-10 bg-slate-900 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative z-10">
                    <span class="text-accent font-black uppercase tracking-widest text-xs mb-1 block">Order Details</span>
                    <h1 class="text-3xl font-serif font-bold text-white mb-2">#<?php echo $data['order']->order_number; ?></h1>
                    <p class="text-slate-400 text-sm flex items-center gap-2">
                        <i class="far fa-calendar"></i> Placed on <?php echo date('F d, Y', strtotime($data['order']->created_at)); ?>
                    </p>
                </div>
                
                <div class="relative z-10 text-right">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 backdrop-blur border border-white/10">
                        <span class="w-2 h-2 rounded-full <?php echo ($data['order']->status == 'cancelled') ? 'bg-red-500' : 'bg-green-500'; ?> animate-pulse"></span>
                        <span class="font-bold text-sm uppercase tracking-wide"><?php echo $data['order']->status; ?></span>
                    </div>
                </div>
            </div>

            <div class="p-8 md:p-12 border-b border-slate-100 bg-slate-50/50">
                <?php 
                    $status = $data['order']->status;
                    $step = 1; // Default: Placed
                    if($status == 'processing') $step = 2;
                    if($status == 'shipped') $step = 3;
                    if($status == 'delivered') $step = 4;
                    if($status == 'cancelled') $step = 0;
                ?>
                
                <div class="relative mx-4 md:mx-12">
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-200 -translate-y-1/2 rounded-full z-0"></div>
                    <div class="absolute top-1/2 left-0 h-1 bg-accent -translate-y-1/2 rounded-full z-0 transition-all duration-1000" style="width: <?php echo ($step > 0) ? ($step - 1) * 33 : 0; ?>%;"></div>

                    <div class="relative z-10 flex justify-between">
                        <div class="flex flex-col items-center gap-2 group">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 <?php echo ($step >= 1) ? 'bg-accent border-accent text-white shadow-lg shadow-accent/30' : 'bg-white border-slate-200 text-slate-300'; ?> transition-all duration-500">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider mt-2 <?php echo ($step >= 1) ? 'text-slate-900' : 'text-slate-400'; ?>">Placed</span>
                        </div>

                        <div class="flex flex-col items-center gap-2 group">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 <?php echo ($step >= 2) ? 'bg-accent border-accent text-white shadow-lg shadow-accent/30' : 'bg-white border-slate-200 text-slate-300'; ?> transition-all duration-500">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider mt-2 <?php echo ($step >= 2) ? 'text-slate-900' : 'text-slate-400'; ?>">Processing</span>
                        </div>

                        <div class="flex flex-col items-center gap-2 group">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 <?php echo ($step >= 3) ? 'bg-accent border-accent text-white shadow-lg shadow-accent/30' : 'bg-white border-slate-200 text-slate-300'; ?> transition-all duration-500">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider mt-2 <?php echo ($step >= 3) ? 'text-slate-900' : 'text-slate-400'; ?>">Shipped</span>
                        </div>

                        <div class="flex flex-col items-center gap-2 group">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 <?php echo ($step >= 4) ? 'bg-green-500 border-green-500 text-white shadow-lg shadow-green-500/30' : 'bg-white border-slate-200 text-slate-300'; ?> transition-all duration-500">
                                <i class="fas fa-home"></i>
                            </div>
                            <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider mt-2 <?php echo ($step >= 4) ? 'text-slate-900' : 'text-slate-400'; ?>">Delivered</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row">
                <div class="flex-1 p-8 md:p-10 border-r border-slate-100">
                    <h3 class="font-serif font-bold text-xl text-slate-900 mb-6">Items Ordered</h3>
                    <div class="space-y-6">
                        <?php foreach($data['items'] as $item): ?>
                            <div class="flex items-center gap-4 group">
                                <div class="w-20 h-20 bg-slate-50 rounded-xl overflow-hidden flex-shrink-0 border border-slate-100">
                                    <?php if(!empty($item->image)): ?>
                                        <img src="<?php echo URLROOT.'/public/img/'.$item->image; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-900 text-sm mb-1"><?php echo $item->product_name; ?></h4>
                                    <p class="text-xs text-slate-500 mb-1">Qty: <?php echo $item->quantity; ?></p>
                                    <?php if(!empty($item->sku)): ?>
                                        <p class="text-[10px] text-slate-400 uppercase">SKU: <?php echo $item->sku; ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="font-bold text-slate-900">
                                    <?php echo CURRENCY_SYMBOL.' '.number_format($item->price * $item->quantity, 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="w-full lg:w-1/3 bg-slate-50 p-8 md:p-10">
                    
                    <div class="mb-8">
                        <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">Delivery Address</h4>
                        <address class="not-italic text-sm text-slate-600 leading-relaxed bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
                            <strong class="text-slate-900 block mb-1"><?php echo $data['order']->full_name; ?></strong>
                            <?php echo $data['order']->shipping_address; ?><br>
                            <?php echo $data['order']->shipping_city; ?><br>
                            <?php echo $data['order']->shipping_phone; ?>
                        </address>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">Payment Method</h4>
                        <div class="flex items-center gap-2 text-sm font-bold text-slate-700 bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
                            <?php if($data['order']->payment_method == 'paystack'): ?>
                                <i class="fas fa-credit-card text-accent"></i> Paid via Paystack
                            <?php else: ?>
                                <i class="fas fa-money-bill-wave text-green-600"></i> Cash on Delivery
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-6 space-y-2 text-sm">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span><?php echo CURRENCY_SYMBOL.' '.number_format($data['order']->total_amount, 2); ?></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Shipping</span>
                            <span class="text-green-600 font-bold text-xs uppercase">Free</span>
                        </div>
                        <div class="flex justify-between font-serif font-bold text-xl text-slate-900 pt-4 border-t border-slate-200 mt-4">
                            <span>Total</span>
                            <span class="text-accent"><?php echo CURRENCY_SYMBOL.' '.number_format($data['order']->total_amount, 2); ?></span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-slate-900 text-white p-6 text-center text-sm">
                <p>Need help with this order? <a href="mailto:sales@exotikha.com" class="font-bold text-accent hover:underline">Contact Support</a> or call <strong class="text-white">+233 53 938 2808</strong></p>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>