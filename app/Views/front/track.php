<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-slate-50 py-12 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-4xl font-serif font-bold text-slate-900 mb-2">Track order</h1>
        <p class="text-slate-500 max-w-2xl mx-auto">
            Check the status of your shipment.
        </p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-6 py-16">

    <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 mb-10">
        <p class="text-slate-600 mb-6 text-sm">
            Please enter your <strong>5-digit Order ID</strong> (e.g. 58392) and your email address.
        </p>

        <?php if(!empty($data['error'])): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $data['error']; ?></span>
            </div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/pages/track" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Order ID (5 Digits)</label>
                <input type="text" name="order_number" value="<?php echo $data['order_number']; ?>" class="w-full rounded-lg border-slate-200 focus:border-accent focus:ring focus:ring-accent/20 transition h-12" placeholder="Ex: 58392">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Billing email</label>
                <input type="email" name="email" value="<?php echo $data['email']; ?>" class="w-full rounded-lg border-slate-200 focus:border-accent focus:ring focus:ring-accent/20 transition h-12" placeholder="Email used during checkout">
            </div>

            <div class="md:col-span-2 mt-4">
                <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg hover:bg-slate-800 transition transform hover:-translate-y-1 uppercase tracking-widest">
                    Track
                </button>
            </div>
        </form>
    </div>

    <?php if(!empty($data['order'])): ?>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden animate-fade-in-up">
            <div class="bg-slate-900 text-white p-6 flex justify-between items-center">
                <div>
                    <span class="text-xs uppercase text-slate-400 font-bold tracking-widest">Order #<?php echo $data['order']->order_number; ?></span>
                    <h3 class="text-xl font-bold">Status: <span class="text-accent capitalize"><?php echo $data['order']->status; ?></span></h3>
                </div>
                <div class="text-right">
                    <span class="block text-xs text-slate-400">Total Amount</span>
                    <span class="font-serif font-bold text-xl"><?php echo CURRENCY_SYMBOL . ' ' . number_format($data['order']->total_amount, 2); ?></span>
                </div>
            </div>

            <div class="p-8">
                <?php 
                    $status = $data['order']->status;
                    $step = 1; // Pending
                    if($status == 'paid') $step = 2;
                    if($status == 'shipped') $step = 3;
                    if($status == 'cancelled') $step = 0;
                ?>

                <?php if($step > 0): ?>
                <div class="relative flex items-center justify-between mb-8 mt-4">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-100 -z-0"></div>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-accent transition-all duration-1000 -z-0" 
                         style="width: <?php echo ($step == 1) ? '0%' : (($step == 2) ? '50%' : '100%'); ?>;"></div>

                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs <?php echo ($step >= 1) ? 'bg-accent text-white shadow-lg' : 'bg-slate-200 text-slate-500'; ?>">1</div>
                        <span class="text-xs font-bold uppercase <?php echo ($step >= 1) ? 'text-accent' : 'text-slate-400'; ?>">Pending</span>
                    </div>

                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs <?php echo ($step >= 2) ? 'bg-accent text-white shadow-lg' : 'bg-slate-200 text-slate-500'; ?>">2</div>
                        <span class="text-xs font-bold uppercase <?php echo ($step >= 2) ? 'text-accent' : 'text-slate-400'; ?>">Paid</span>
                    </div>

                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs <?php echo ($step >= 3) ? 'bg-green-500 text-white shadow-lg' : 'bg-slate-200 text-slate-500'; ?>"><i class="fas fa-truck"></i></div>
                        <span class="text-xs font-bold uppercase <?php echo ($step >= 3) ? 'text-green-600' : 'text-slate-400'; ?>">Shipped</span>
                    </div>
                </div>
                <?php else: ?>
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl text-center font-bold">
                        <i class="fas fa-times-circle mr-2"></i> This order has been cancelled.
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 border-t border-slate-100 pt-6">
                    <div>
                        <h4 class="font-bold text-slate-900 mb-2">Delivery Address</h4>
                        <p class="text-sm text-slate-600">
                            <?php echo $data['order']->shipping_address; ?><br>
                            <?php echo $data['order']->shipping_city; ?>, <?php echo $data['order']->shipping_region; ?><br>
                            Ghana
                        </p>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 mb-2">Contact</h4>
                        <p class="text-sm text-slate-600">
                            <?php echo $data['order']->shipping_phone; ?><br>
                            <?php echo $data['order']->email; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>