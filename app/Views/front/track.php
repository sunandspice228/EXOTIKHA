<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-slate-900 text-white py-20 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <span class="text-accent font-black uppercase tracking-widest text-xs mb-4 block animate-fade-in-down">Logistics</span>
        <h1 class="text-4xl md:text-6xl font-serif font-bold mb-6">Track Your Order</h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">
            Enter your order details below to check the real-time status of your shipment.
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-6 pb-20 -mt-16 relative z-20">

    <div class="bg-white p-8 md:p-10 rounded-3xl shadow-xl border border-slate-100 mb-12">
        
        <?php if(!empty($data['error'])): ?>
            <div class="bg-red-50 border border-red-100 text-red-600 p-4 rounded-xl mb-8 flex items-center gap-3 animate-pulse">
                <i class="fas fa-exclamation-circle text-xl"></i>
                <span class="text-sm font-bold"><?php echo $data['error']; ?></span>
            </div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/pages/track" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
            <?php echo csrfField(); ?>
        <div class="md:col-span-2">
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">Order ID</label>
                <div class="relative">
                    <i class="fas fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="order_number" value="<?php echo $data['order_number']; ?>" class="w-full pl-10 pr-4 py-3.5 rounded-xl border border-slate-200 focus:ring-accent focus:border-accent transition font-bold text-slate-900 placeholder-slate-300" placeholder="e.g. 58392">
                </div>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">Billing Email</label>
                <div class="relative">
                    <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="email" name="email" value="<?php echo $data['email']; ?>" class="w-full pl-10 pr-4 py-3.5 rounded-xl border border-slate-200 focus:ring-accent focus:border-accent transition font-medium text-slate-900 placeholder-slate-300" placeholder="Used at checkout">
                </div>
            </div>

            <div class="md:col-span-1">
                <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-accent transition transform hover:-translate-y-1 uppercase tracking-widest text-xs flex justify-center items-center gap-2">
                    Track <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>

    <?php if(!empty($data['order'])): ?>
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden animate-fade-in-up">
            
            <div class="bg-slate-900 text-white p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="text-xs uppercase text-accent font-bold tracking-widest block mb-1">Shipment Status</span>
                    <h3 class="text-2xl font-serif font-bold">Order #<?php echo $data['order']->order_number; ?></h3>
                </div>
                <div class="text-left md:text-right">
                    <span class="block text-xs text-slate-400 uppercase font-bold tracking-wider mb-1">Total Amount</span>
                    <span class="font-serif font-bold text-2xl text-white"><?php echo CURRENCY_SYMBOL . ' ' . number_format($data['order']->total_amount, 2); ?></span>
                </div>
            </div>

            <div class="p-8 md:p-12 bg-slate-50/50 border-b border-slate-100">
                <?php 
                    $status = $data['order']->status;
                    $step = 1; // Pending / Placed
                    if($status == 'processing' || $status == 'paid') $step = 2;
                    if($status == 'shipped') $step = 3;
                    if($status == 'delivered') $step = 4;
                    if($status == 'cancelled') $step = 0;
                ?>

                <?php if($step > 0): ?>
                <div class="relative mx-4 md:mx-12">
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-200 -translate-y-1/2 rounded-full z-0"></div>
                    <div class="absolute top-1/2 left-0 h-1 bg-accent -translate-y-1/2 rounded-full z-0 transition-all duration-1000" style="width: <?php echo ($step - 1) * 33; ?>%;"></div>

                    <div class="relative z-10 flex justify-between">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 <?php echo ($step >= 1) ? 'bg-accent border-accent text-white shadow-lg shadow-accent/30' : 'bg-white border-slate-200 text-slate-300'; ?> transition-all duration-500">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider <?php echo ($step >= 1) ? 'text-slate-900' : 'text-slate-400'; ?>">Placed</span>
                        </div>

                        <div class="flex flex-col items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 <?php echo ($step >= 2) ? 'bg-accent border-accent text-white shadow-lg shadow-accent/30' : 'bg-white border-slate-200 text-slate-300'; ?> transition-all duration-500">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider <?php echo ($step >= 2) ? 'text-slate-900' : 'text-slate-400'; ?>">Processing</span>
                        </div>

                        <div class="flex flex-col items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 <?php echo ($step >= 3) ? 'bg-accent border-accent text-white shadow-lg shadow-accent/30' : 'bg-white border-slate-200 text-slate-300'; ?> transition-all duration-500">
                                <i class="fas fa-truck"></i>
                            </div>
                            <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider <?php echo ($step >= 3) ? 'text-slate-900' : 'text-slate-400'; ?>">Shipped</span>
                        </div>

                        <div class="flex flex-col items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 <?php echo ($step >= 4) ? 'bg-green-500 border-green-500 text-white shadow-lg shadow-green-500/30' : 'bg-white border-slate-200 text-slate-300'; ?> transition-all duration-500">
                                <i class="fas fa-home"></i>
                            </div>
                            <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider <?php echo ($step >= 4) ? 'text-slate-900' : 'text-slate-400'; ?>">Delivered</span>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <div class="bg-red-50 border border-red-100 text-red-600 p-6 rounded-2xl text-center font-bold">
                        <i class="fas fa-times-circle text-2xl mb-2 block"></i>
                        This order has been cancelled.
                    </div>
                <?php endif; ?>
            </div>

            <div class="p-8 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h4 class="text-xs font-black uppercase text-slate-400 mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt"></i> Delivery Address
                    </h4>
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <p class="font-bold text-slate-900 text-lg mb-1"><?php echo $data['order']->shipping_city; ?></p>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <?php echo $data['order']->shipping_address; ?><br>
                            <?php echo $data['order']->shipping_region; ?>, Ghana
                        </p>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase text-slate-400 mb-4 flex items-center gap-2">
                        <i class="fas fa-user"></i> Contact Info
                    </h4>
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <p class="font-bold text-slate-900 text-lg mb-1"><?php echo $data['order']->shipping_phone; ?></p>
                        <p class="text-slate-500 text-sm"><?php echo $data['order']->email; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-50 p-6 text-center border-t border-slate-100">
                <a href="<?php echo URLROOT; ?>/users/account?tab=orders" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition underline">View in My Account</a>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>