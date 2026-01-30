<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-white border-b border-slate-100 py-4">
    <div class="max-w-7xl mx-auto px-6 text-xs font-bold uppercase tracking-widest text-slate-400">
        <a href="<?php echo URLROOT; ?>" class="hover:text-primary">Home</a>
        <span class="mx-2">/</span>
        <a href="<?php echo URLROOT; ?>/shop" class="hover:text-primary">Shop</a>
        <span class="mx-2">/</span>
        <span class="text-slate-900"><?php echo $data['product']->name; ?></span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
        
        <div class="space-y-4">
            <div class="relative aspect-[3/4] bg-slate-50 rounded-2xl overflow-hidden shadow-sm group">
                <?php if($data['product']->promo_price > 0): ?>
                    <span class="absolute top-4 left-4 bg-red-600 text-white text-xs font-black uppercase px-3 py-1.5 rounded z-10 shadow-md">
                        Sale
                    </span>
                <?php endif; ?>
                
                <?php if(!empty($data['product']->image)): ?>
                    <img src="<?php echo URLROOT . '/img/' . $data['product']->image; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 cursor-zoom-in">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-image text-4xl"></i></div>
                <?php endif; ?>
            </div>
            
            <div class="grid grid-cols-4 gap-4">
                <div class="aspect-square rounded-xl bg-slate-100 overflow-hidden border-2 border-slate-900 cursor-pointer">
                    <?php if(!empty($data['product']->image)): ?>
                        <img src="<?php echo URLROOT . '/img/' . $data['product']->image; ?>" class="w-full h-full object-cover">
                    <?php endif; ?>
                </div>
                <div class="aspect-square rounded-xl bg-slate-50 border border-transparent hover:border-slate-300 transition"></div>
                <div class="aspect-square rounded-xl bg-slate-50 border border-transparent hover:border-slate-300 transition"></div>
                <div class="aspect-square rounded-xl bg-slate-50 border border-transparent hover:border-slate-300 transition"></div>
            </div>
        </div>

        <div class="lg:sticky lg:top-32 h-fit">
            
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-slate-900 mb-4"><?php echo $data['product']->name; ?></h1>
            
            <div class="flex items-center gap-4 mb-6">
                <?php if($data['product']->promo_price > 0): ?>
                    <span class="text-3xl font-bold text-red-600"><?php echo CURRENCY_SYMBOL . number_format($data['product']->promo_price, 2); ?></span>
                    <span class="text-xl text-slate-400 line-through"><?php echo number_format($data['product']->price, 2); ?></span>
                    <span class="bg-red-50 text-red-600 text-xs font-bold px-2 py-1 rounded">
                        Save <?php echo round((($data['product']->price - $data['product']->promo_price)/$data['product']->price)*100); ?>%
                    </span>
                <?php else: ?>
                    <span class="text-3xl font-bold text-slate-900"><?php echo CURRENCY_SYMBOL . number_format($data['product']->price, 2); ?></span>
                <?php endif; ?>
            </div>

            <div class="flex items-center gap-2 mb-8">
                <div class="flex text-yellow-400 text-sm">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <span class="text-sm font-bold text-slate-500 underline cursor-pointer">4.8 (12 Reviews)</span>
            </div>

            <div class="prose prose-slate text-slate-600 leading-relaxed mb-8 text-lg">
                <?php echo nl2br($data['product']->description); ?>
            </div>

            <?php if($data['product']->stock > 0): ?>
            <form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="space-y-8">
                <?php echo csrfField(); ?>    
                <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
                
                <div class="flex items-center gap-6">
                    <span class="text-sm font-bold uppercase tracking-wide text-slate-900">Quantity</span>
                    <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden h-12 w-36">
                        <button type="button" onclick="if(this.nextElementSibling.value>1) this.nextElementSibling.value--" class="w-12 h-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center border-r border-slate-300 transition text-slate-500">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $data['product']->stock; ?>" class="w-full h-full text-center border-none focus:ring-0 font-bold text-slate-900 appearance-none bg-white text-lg">
                        <button type="button" onclick="if(this.previousElementSibling.value < <?php echo $data['product']->stock; ?>) this.previousElementSibling.value++" class="w-12 h-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center border-l border-slate-300 transition text-slate-500">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                    </div>
                    <span class="text-xs text-green-600 font-bold bg-green-50 px-3 py-1 rounded-full border border-green-100 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span> In Stock
                    </span>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-slate-900 text-white py-4 rounded-xl font-bold uppercase tracking-widest hover:bg-primary transition shadow-xl shadow-slate-900/20 flex items-center justify-center gap-3 group">
                        <i class="fas fa-shopping-bag group-hover:-translate-y-1 transition"></i> Add to Cart
                    </button>
                    <a href="<?php echo URLROOT; ?>/wishlist/toggle/<?php echo $data['product']->id; ?>" class="w-16 h-16 border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition shadow-sm" title="Add to Wishlist">
                        <i class="far fa-heart text-2xl"></i>
                    </a>
                </div>
            </form>
            
            <?php else: ?>
                <div class="bg-red-50 border border-red-100 p-6 rounded-xl text-center">
                    <p class="text-red-600 font-bold uppercase tracking-widest text-sm">Sold Out</p>
                    <p class="text-sm text-red-400 mt-2">This item is currently unavailable. Please check back later.</p>
                </div>
            <?php endif; ?>

            <div class="mt-12 border-t border-slate-100" x-data="{ active: 1 }">
                <div class="border-b border-slate-100">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full py-5 flex justify-between items-center text-left font-bold text-slate-900 hover:text-primary transition">
                        <span>Details & Materials</span>
                        <i class="fas fa-chevron-down transition-transform" :class="active === 1 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="active === 1" x-collapse class="pb-6 text-sm text-slate-600 leading-relaxed">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Premium quality fabric tailored for comfort.</li>
                            <li>Authentic African prints mixed with modern cuts.</li>
                            <li>Designed in Ghana, shipped worldwide.</li>
                            <li>Care: Machine wash cold, do not bleach.</li>
                        </ul>
                    </div>
                </div>
                <div class="border-b border-slate-100">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full py-5 flex justify-between items-center text-left font-bold text-slate-900 hover:text-primary transition">
                        <span>Shipping & Returns</span>
                        <i class="fas fa-chevron-down transition-transform" :class="active === 2 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="active === 2" x-collapse class="pb-6 text-sm text-slate-600 leading-relaxed">
                        <p class="mb-2"><strong>Shipping:</strong> Free shipping on orders over 1,000 GHS in Accra. Standard delivery takes 2-5 business days.</p>
                        <p><strong>Returns:</strong> We accept returns within 14 days of delivery for unworn items with tags attached.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php if(!empty($data['related'])): ?>
    <div class="mt-24 border-t border-slate-100 pt-16">
        <h2 class="text-2xl font-serif font-bold text-slate-900 mb-8">You May Also Like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            <?php foreach($data['related'] as $item): ?>
                <?php if($item->id != $data['product']->id): ?>
                
                <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $item->slug; ?>" class="group block">
                    <div class="aspect-[3/4] bg-slate-100 rounded-xl overflow-hidden mb-4 relative">
                         <?php if(!empty($item->image)): ?>
                            <img src="<?php echo URLROOT . '/img/' . $item->image; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <?php endif; ?>
                        
                        <?php if($item->promo_price > 0): ?>
                            <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded">Sale</span>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="font-bold text-slate-900 text-sm truncate group-hover:text-primary transition"><?php echo $item->name; ?></h3>
                    
                    <div class="flex gap-2 items-center mt-1">
                         <?php if($item->promo_price > 0): ?>
                            <span class="text-red-500 font-bold text-sm"><?php echo CURRENCY_SYMBOL . number_format($item->promo_price, 2); ?></span>
                            <span class="text-slate-400 text-xs line-through"><?php echo number_format($item->price, 2); ?></span>
                         <?php else: ?>
                            <span class="text-slate-500 text-sm font-bold"><?php echo CURRENCY_SYMBOL . number_format($item->price, 2); ?></span>
                         <?php endif; ?>
                    </div>
                </a>
                
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>