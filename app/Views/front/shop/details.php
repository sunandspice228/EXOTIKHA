<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="max-w-7xl mx-auto px-6 py-12">
    
    <nav class="text-sm mb-8 text-slate-500">
        <a href="<?php echo URLROOT; ?>" class="hover:text-primary">Home</a> <span class="mx-2">/</span>
        <a href="<?php echo URLROOT; ?>/shop" class="hover:text-primary">Shop</a> <span class="mx-2">/</span>
        <span class="font-bold text-slate-800"><?php echo $data['product']->name; ?></span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-20">
        
        <div x-data="{ activeImage: '<?php echo URLROOT . '/public/' . $data['product']->image; ?>' }">
            
            <div class="aspect-[4/5] bg-slate-100 rounded-2xl overflow-hidden mb-4 border border-slate-100 shadow-sm">
                <img :src="activeImage" class="w-full h-full object-cover transition duration-300">
            </div>

            <?php if(!empty($data['gallery'])): ?>
                <div class="flex gap-4 overflow-x-auto pb-2">
                    <button @click="activeImage = '<?php echo URLROOT . '/public/' . $data['product']->image; ?>'" 
                            class="w-20 h-24 flex-shrink-0 rounded-lg overflow-hidden border-2 border-transparent hover:border-accent transition">
                        <img src="<?php echo URLROOT . '/public/' . $data['product']->image; ?>" class="w-full h-full object-cover">
                    </button>

                    <?php foreach($data['gallery'] as $img): ?>
                        <button @click="activeImage = '<?php echo URLROOT . '/public/' . $img->image_path; ?>'" 
                                class="w-20 h-24 flex-shrink-0 rounded-lg overflow-hidden border-2 border-transparent hover:border-accent transition">
                            <img src="<?php echo URLROOT . '/public/' . $img->image_path; ?>" class="w-full h-full object-cover">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="mb-6">
                <span class="text-accent font-bold text-xs uppercase tracking-widest bg-accent/10 px-2 py-1 rounded">
                    <?php echo $data['product']->category_name ?? 'Collection'; ?>
                </span>
            </div>

            <h1 class="text-4xl font-serif font-bold text-slate-900 mb-4"><?php echo $data['product']->name; ?></h1>

            <div class="flex items-end gap-4 mb-8">
                <?php if(!empty($data['product']->promo_price)): ?>
                    <span class="text-3xl font-bold text-red-600"><?php echo CURRENCY_SYMBOL . ' ' . number_format($data['product']->promo_price, 2); ?></span>
                    <span class="text-xl text-slate-400 line-through mb-1"><?php echo CURRENCY_SYMBOL . ' ' . number_format($data['product']->price, 2); ?></span>
                    <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded mb-2">PROMO</span>
                <?php else: ?>
                    <span class="text-3xl font-bold text-slate-900"><?php echo CURRENCY_SYMBOL . ' ' . number_format($data['product']->price, 2); ?></span>
                <?php endif; ?>
            </div>

            <p class="text-slate-600 leading-relaxed mb-8 border-b border-slate-100 pb-8">
                <?php echo nl2br($data['product']->description); ?>
            </p>

            <form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="space-y-6">
                <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
                
                <div class="flex items-center gap-4">
                    <label class="font-bold text-slate-700">Quantity:</label>
                    <div class="flex items-center border border-slate-300 rounded-lg" x-data="{ qty: 1 }">
                        <button type="button" @click="qty > 1 ? qty-- : qty = 1" class="px-3 py-2 text-slate-500 hover:text-primary"><i class="fas fa-minus"></i></button>
                        <input type="number" name="quantity" x-model="qty" class="w-12 text-center border-none focus:ring-0 p-0 text-slate-900 font-bold" min="1" max="<?php echo $data['product']->stock; ?>">
                        <button type="button" @click="qty < <?php echo $data['product']->stock; ?> ? qty++ : qty" class="px-3 py-2 text-slate-500 hover:text-primary"><i class="fas fa-plus"></i></button>
                    </div>
                    <div class="text-xs text-slate-500">
                        <?php echo $data['product']->stock > 0 ? $data['product']->stock . ' items in stock' : '<span class="text-red-500 font-bold">Out of Stock</span>'; ?>
                    </div>
                </div>

                <?php if($data['product']->stock > 0): ?>
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-primary text-white py-4 rounded-full font-bold uppercase tracking-wide hover:bg-slate-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Add to Cart
                        </button>
                        <button type="button" class="w-14 h-14 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:border-red-400 hover:text-red-500 transition">
                            <i class="far fa-heart text-xl"></i>
                        </button>
                    </div>
                <?php else: ?>
                    <button type="button" disabled class="w-full bg-slate-200 text-slate-400 py-4 rounded-full font-bold uppercase tracking-wide cursor-not-allowed">
                        Sold Out
                    </button>
                <?php endif; ?>
            </form>

            <div class="mt-8 grid grid-cols-2 gap-4 text-sm text-slate-500">
                <div class="flex items-center gap-2"><i class="fas fa-truck text-accent"></i> Fast Delivery (Ghana)</div>
                <div class="flex items-center gap-2"><i class="fas fa-check-circle text-accent"></i> Authentic Quality</div>
            </div>
        </div>
    </div>

    <?php if(!empty($data['related'])): ?>
        <div class="border-t border-slate-100 pt-16">
            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-8">You may also like</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php foreach($data['related'] as $related): ?>
                    <div class="group">
                        <div class="relative h-[250px] bg-slate-100 rounded-xl overflow-hidden mb-3">
                            <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $related->id; ?>">
                                <img src="<?php echo URLROOT . '/public/' . $related->image; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </a>
                        </div>
                        <h3 class="font-bold text-slate-900 truncate"><a href="<?php echo URLROOT; ?>/shop/product/<?php echo $related->id; ?>"><?php echo $related->name; ?></a></h3>
                        <span class="text-slate-600"><?php echo CURRENCY_SYMBOL . ' ' . $related->price; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>