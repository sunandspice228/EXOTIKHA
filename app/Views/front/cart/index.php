<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="max-w-7xl mx-auto px-6 py-16">
    <h1 class="text-3xl font-serif font-bold text-slate-900 mb-10 text-center">Your Shopping Bag</h1>

    <?php if(empty($data['cartItems'])): ?>
        <div class="text-center py-20 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm text-slate-300">
                <i class="fas fa-shopping-basket text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">Your bag is empty</h3>
            <p class="text-slate-500 mb-8">Looks like you haven't added anything yet.</p>
            <a href="<?php echo URLROOT; ?>/shop" class="inline-block bg-primary text-white px-8 py-3 rounded-full font-bold hover:bg-slate-800 transition shadow-lg">
                Start Shopping
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <div class="lg:col-span-2 space-y-6">
                <?php foreach($data['cartItems'] as $item): ?>
                    <div class="flex flex-col sm:flex-row items-center gap-6 p-6 bg-white rounded-2xl shadow-sm border border-slate-100">
                        <div class="w-24 h-24 flex-shrink-0 bg-slate-100 rounded-lg overflow-hidden">
                            <img src="<?php echo URLROOT . '/public/' . $item['image']; ?>" class="w-full h-full object-cover">
                        </div>
                        
                        <div class="flex-1 text-center sm:text-left">
                            <div class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1">SKU: <?php echo $item['sku']; ?></div>
                            <h3 class="font-bold text-slate-900 text-lg mb-1">
                                <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a>
                            </h3>
                            <div class="text-accent font-bold"><?php echo CURRENCY_SYMBOL . ' ' . number_format($item['price'], 2); ?></div>
                        </div>

                        <form action="<?php echo URLROOT; ?>/cart/update" method="POST" class="flex items-center border border-slate-200 rounded-lg">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="qty" value="<?php echo $item['qty'] - 1; ?>" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-slate-50 rounded-l-lg">-</button>
                            <input type="text" value="<?php echo $item['qty']; ?>" class="w-10 text-center border-none p-0 text-sm font-bold text-slate-900 focus:ring-0" readonly>
                            <button type="submit" name="qty" value="<?php echo $item['qty'] + 1; ?>" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-slate-50 rounded-r-lg">+</button>
                        </form>

                        <div class="text-right flex flex-col items-center sm:items-end gap-2">
                            <span class="font-bold text-slate-900 text-lg"><?php echo CURRENCY_SYMBOL . ' ' . number_format($item['subtotal'], 2); ?></span>
                            <a href="<?php echo URLROOT; ?>/cart/remove/<?php echo $item['id']; ?>" class="text-xs text-red-400 hover:text-red-600 underline">Remove</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-slate-50 p-8 rounded-2xl border border-slate-200 sticky top-24">
                    <h3 class="font-serif font-bold text-xl text-slate-900 mb-6">Order Summary</h3>
                    
                    <div class="space-y-4 mb-6 border-b border-slate-200 pb-6">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-bold"><?php echo CURRENCY_SYMBOL . ' ' . number_format($data['total'], 2); ?></span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Shipping</span>
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded font-bold">Calculated at checkout</span>
                        </div>
                    </div>

                    <div class="flex justify-between text-xl font-bold text-slate-900 mb-8">
                        <span>Total</span>
                        <span><?php echo CURRENCY_SYMBOL . ' ' . number_format($data['total'], 2); ?></span>
                    </div>

                    <a href="<?php echo URLROOT; ?>/cart/checkout" class="block w-full bg-primary text-white text-center py-4 rounded-xl font-bold shadow-lg hover:bg-slate-900 transition uppercase tracking-widest">
                        Proceed to Checkout
                    </a>
                    
                    <div class="mt-6 text-center">
                        <p class="text-xs text-slate-400 flex justify-center items-center gap-2">
                            <i class="fas fa-lock"></i> Secure Checkout
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>