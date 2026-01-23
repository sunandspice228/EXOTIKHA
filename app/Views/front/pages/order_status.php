<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="max-w-4xl mx-auto px-6 py-16">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="p-8 bg-slate-900 text-white flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-serif font-bold">Order #<?php echo $data['order']->order_number; ?></h1>
                <p class="text-slate-400 text-sm">Placed on <?php echo date('M d, Y', strtotime($data['order']->created_at)); ?></p>
            </div>
            <div class="bg-accent px-4 py-2 rounded-full font-bold text-xs uppercase tracking-widest">
                <?php echo $data['order']->status; ?>
            </div>
        </div>

        <div class="p-8 border-b border-slate-100 bg-slate-50">
            <div class="flex justify-between mb-2">
                <span class="text-xs font-bold uppercase text-slate-400">Status Tracking</span>
                <span class="text-xs font-bold text-primary italic">Estimated delivery: 2-3 Days</span>
            </div>
            <div class="relative pt-1">
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-slate-200">
                    <?php 
                        $pct = 25;
                        if($data['order']->status == 'paid') $pct = 50;
                        if($data['order']->status == 'shipped') $pct = 75;
                        if($data['order']->status == 'delivered') $pct = 100;
                    ?>
                    <div style="width:<?php echo $pct; ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-accent transition-all duration-1000"></div>
                </div>
                <div class="flex justify-between text-[10px] font-bold uppercase text-slate-400">
                    <div class="text-accent">Placed</div>
                    <div class="<?php echo ($pct >= 50) ? 'text-accent' : ''; ?>">Processing</div>
                    <div class="<?php echo ($pct >= 75) ? 'text-accent' : ''; ?>">Shipped</div>
                    <div class="<?php echo ($pct >= 100) ? 'text-accent' : ''; ?>">Delivered</div>
                </div>
            </div>
        </div>

        <div class="p-8">
            <h3 class="font-bold mb-4">Order Summary</h3>
            <div class="space-y-4">
                <?php foreach($data['items'] as $item): ?>
                    <div class="flex items-center gap-4 py-2 border-b border-slate-50 last:border-0">
                        <img src="<?php echo URLROOT.'/public/'.$item->image; ?>" class="w-16 h-16 object-cover rounded-xl">
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800"><?php echo $item->name; ?></h4>
                            <p class="text-xs text-slate-400">Quantity: <?php echo $item->quantity; ?></p>
                        </div>
                        <div class="font-bold"><?php echo CURRENCY_SYMBOL.' '.number_format($item->price * $item->quantity, 2); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-8 pt-8 border-t border-slate-100 flex justify-end">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between text-slate-500"><span>Subtotal:</span><span><?php echo CURRENCY_SYMBOL.' '.number_format($data['order']->total_amount, 2); ?></span></div>
                    <div class="flex justify-between text-slate-500"><span>Shipping:</span><span class="text-green-600 font-bold">FREE</span></div>
                    <div class="flex justify-between text-xl font-bold text-slate-900 pt-2 border-t border-slate-200"><span>Total:</span><span><?php echo CURRENCY_SYMBOL.' '.number_format($data['order']->total_amount, 2); ?></span></div>
                </div>
            </div>
        </div>

        <div class="p-8 bg-slate-50 text-center">
            <p class="text-sm text-slate-500">Need help? Contact us at <strong>sales@exotikha.com</strong> or via WhatsApp <strong>+233539382808</strong></p>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>