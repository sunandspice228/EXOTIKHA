<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-3xl font-serif font-bold text-slate-900 mb-8">Your Shopping Bag</h1>

        <?php if(empty($data['cart_items'])): ?>
            <div class="bg-white rounded-3xl p-16 text-center shadow-sm border border-slate-100">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <i class="fas fa-shopping-basket text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Your bag is empty</h2>
                <p class="text-slate-500 mb-8">Looks like you haven't added anything yet.</p>
                <a href="<?php echo URLROOT; ?>/shop" class="inline-block bg-slate-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-accent transition">
                    Start Shopping
                </a>
            </div>
        <?php else: ?>
            
            <div class="flex flex-col lg:flex-row gap-12">
                
                <div class="flex-1 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
                                <tr>
                                    <th class="p-6">Product</th>
                                    <th class="p-6">Price</th>
                                    <th class="p-6 text-center">Quantity</th>
                                    <th class="p-6 text-right">Total</th>
                                    <th class="p-6"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach($data['cart_items'] as $item): ?>
                                <tr>
                                    <td class="p-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                                                <?php if(!empty($item->image)): ?>
                                                    <img src="<?php echo URLROOT . '/img/' . $item->image; ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-camera"></i></div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-slate-900 text-sm">
                                                    <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $item->id; ?>" class="hover:text-primary transition"><?php echo $item->name; ?></a>
                                                </h3>
                                                <p class="text-xs text-slate-400 font-mono mt-1">SKU: <?php echo $item->sku; ?></p>
                                                <?php if(!empty($item->size) || !empty($item->color)): ?>
                                                    <p class="text-xs text-slate-500 mt-1">
                                                        <?php echo !empty($item->size) ? 'Size: ' . $item->size . ' ' : ''; ?>
                                                        <?php echo !empty($item->color) ? 'Color: ' . $item->color : ''; ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6 font-bold text-slate-600 text-sm">
                                        <?php echo CURRENCY_SYMBOL . number_format($item->price, 0, ',', ' '); ?>
                                    </td>
                                    <td class="p-6">
                                        <form action="<?php echo URLROOT; ?>/cart/update" method="POST" class="flex items-center justify-center">
                                            <?php echo csrfField(); ?>    
                                            <input type="hidden" name="product_id" value="<?php echo $item->id; ?>">
                                            
                                            <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden">
                                                <input type="number" name="quantity" value="<?php echo $item->qty; ?>" min="1" max="<?php echo $item->stock; ?>" 
                                                       class="w-12 text-center text-sm font-bold border-none p-2 focus:ring-0 appearance-none"
                                                       onchange="this.form.submit()">
                                            </div>
                                        </form>
                                    </td>
                                    <td class="p-6 text-right font-bold text-slate-900 text-sm">
                                        <?php echo CURRENCY_SYMBOL . number_format($item->line_total, 0, ',', ' '); ?>
                                    </td>
                                    <td class="p-6 text-right">
                                        <a href="<?php echo URLROOT; ?>/cart/remove/<?php echo $item->id; ?>" class="text-slate-300 hover:text-red-500 transition p-2">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                        <a href="<?php echo URLROOT; ?>/shop" class="text-sm font-bold text-slate-500 hover:text-slate-800 flex items-center gap-2 transition">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                        <a href="<?php echo URLROOT; ?>/cart/clear" class="text-xs font-bold text-red-400 hover:text-red-600 transition uppercase tracking-wider">
                            Clear Cart
                        </a>
                    </div>
                </div>

                <div class="w-full lg:w-96 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 sticky top-24">
                        <h3 class="font-bold text-slate-900 text-lg mb-6">Order Summary</h3>
                        
                        <div class="space-y-4 mb-6 pb-6 border-b border-slate-100 text-sm">
                            <div class="flex justify-between text-slate-500">
                                <span>Subtotal</span>
                                <span class="font-bold text-slate-800"><?php echo CURRENCY_SYMBOL . number_format($data['total'], 0, ',', ' '); ?></span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Shipping</span>
                                <span class="text-green-600 font-bold text-xs uppercase">Free Shipping</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-8">
                            <span class="font-bold text-slate-900 text-lg">Total</span>
                            <span class="font-black text-2xl text-primary"><?php echo CURRENCY_SYMBOL . number_format($data['total'], 0, ',', ' '); ?></span>
                        </div>

                        <a href="<?php echo URLROOT; ?>/cart/checkout" class="block w-full bg-slate-900 text-white py-4 rounded-xl font-bold uppercase tracking-widest text-center hover:bg-primary transition shadow-xl shadow-slate-900/20 group">
                            Proceed to Checkout <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition"></i>
                        </a>

                        <div class="mt-6 flex items-center justify-center gap-4 text-slate-300 text-2xl">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fas fa-mobile-alt"></i> 
                        </div>
                        <p class="text-center text-[10px] text-slate-400 mt-2 uppercase font-bold tracking-wider">Secure Encrypted Checkout</p>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>