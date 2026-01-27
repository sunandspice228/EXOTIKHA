<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="relative bg-slate-900 h-[85vh] flex items-center overflow-hidden group">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1543087903-1ac2ec7aa8c5?q=80&w=2098&auto=format&fit=crop" class="w-full h-full object-cover opacity-60 transition duration-1000 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/40 to-transparent"></div>
    </div>

    <div class="relative z-10 container max-w-7xl mx-auto px-6">
        <div class="max-w-3xl animate-fade-in-up">
            <span class="inline-block py-1 px-3 rounded-full bg-accent/20 border border-accent text-accent text-xs font-bold tracking-widest uppercase mb-6 backdrop-blur-sm">
                New Collection 2026
            </span>
            <h1 class="text-5xl md:text-7xl font-serif font-bold text-white leading-tight mb-6">
                Redefine Your <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400">African Elegance.</span>
            </h1>
            <p class="text-lg text-slate-300 mb-10 font-light max-w-xl leading-relaxed">
                Experience the perfect blend of traditional craftsmanship and contemporary design. Designed in Ghana, worn worldwide.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?php echo URLROOT; ?>/shop" class="px-8 py-4 bg-white text-slate-900 font-bold rounded-xl hover:bg-accent hover:text-white transition shadow-xl shadow-white/10 text-center uppercase tracking-wider text-sm">
                    Shop Collection
                </a>
                <a href="<?php echo URLROOT; ?>/shop/promotions" class="px-8 py-4 border border-white text-white font-bold rounded-xl hover:bg-white hover:text-slate-900 transition text-center uppercase tracking-wider text-sm">
                    View Promos
                </a>
            </div>
        </div>
    </div>
</div>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-slate-900 mb-4">Explore our Collections</h2>
            <div class="h-1 w-24 bg-accent mx-auto rounded-full"></div>
        </div>

        <?php if(empty($data['categories'])): ?>
            <div class="text-center text-slate-400 italic">No categories available at the moment.</div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <?php foreach($data['categories'] as $cat): ?>
                    <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $cat->id; ?>" class="group relative h-[450px] rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition duration-500">
                        
                        <?php if(!empty($cat->cover_image)): ?>
                            <img src="<?php echo URLROOT . '/public/img/' . $cat->cover_image; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                        <?php else: ?>
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                <i class="fas fa-layer-group text-slate-300 text-6xl"></i>
                            </div>
                        <?php endif; ?>

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent flex flex-col justify-end p-8">
                            <h3 class="text-white text-3xl font-serif font-bold mb-1 translate-y-2 group-hover:translate-y-0 transition duration-300">
                                <?php echo $cat->name; ?>
                            </h3>
                            <p class="text-slate-300 text-sm mb-6 line-clamp-2 opacity-0 group-hover:opacity-80 transition duration-500 delay-100 transform translate-y-4 group-hover:translate-y-0">
                                <?php echo $cat->description; ?>
                            </p>
                            
                            <div class="flex items-center justify-between border-t border-white/20 pt-4 opacity-0 group-hover:opacity-100 transition duration-500 delay-200 transform translate-y-4 group-hover:translate-y-0">
                                <span class="text-accent text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                                    Shop Now <i class="fas fa-arrow-right"></i>
                                </span>
                                <span class="bg-white/20 text-white text-[10px] font-bold py-1 px-3 rounded-full backdrop-blur-md uppercase tracking-wide">
                                    <?php echo $cat->product_count ?? '0'; ?> Items
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>
    </div>
</section>

<section class="py-24 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
            <div>
                <span class="text-accent font-bold text-xs uppercase tracking-widest mb-2 block">Fresh In</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-slate-900 mb-2">New Arrivals</h2>
                <p class="text-slate-500">Fresh from our workshop to your wardrobe.</p>
            </div>
            <a href="<?php echo URLROOT; ?>/shop" class="group flex items-center gap-2 text-slate-900 font-bold hover:text-accent transition uppercase text-sm tracking-wide">
                View All Products <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition"></i>
            </a>
        </div>

        <?php if(empty($data['products'])): ?>
            <p class="text-slate-400 text-center py-10">New products coming soon.</p>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                <?php foreach($data['products'] as $product): ?>
                    
                    <div class="group relative">
                        <div class="relative overflow-hidden rounded-2xl bg-white aspect-[3/4] mb-4 shadow-sm border border-slate-100 group-hover:shadow-xl transition-all duration-500">
                            
                            <?php if(!empty($product->promo_price)): ?>
                                <span class="absolute top-3 left-3 z-10 bg-red-600 text-white text-[10px] font-black px-2 py-1 rounded shadow-md uppercase tracking-wider">Sale</span>
                            <?php endif; ?>
                            
                            <?php if($product->stock < 5 && $product->stock > 0): ?>
                                <span class="absolute top-3 right-3 z-10 bg-white/90 backdrop-blur text-orange-600 border border-orange-100 text-[10px] font-bold px-2 py-1 rounded">Low Stock</span>
                            <?php endif; ?>

                            <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" class="block w-full h-full">
                                <?php if(!empty($product->image)): ?>
                                    <img src="<?php echo URLROOT . '/public/img/' . $product->image; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-camera text-3xl"></i></div>
                                <?php endif; ?>
                            </a>

                            <div class="absolute bottom-4 left-4 right-4 translate-y-20 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 z-20">
                                <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" class="block w-full bg-slate-900 text-white py-3 rounded-xl font-bold text-xs uppercase tracking-wider text-center hover:bg-accent shadow-lg">
                                    View Details
                                </a>
                            </div>
                        </div>
                        
                        <div class="relative z-0">
                            <div class="text-[10px] text-slate-400 uppercase tracking-widest mb-1 font-bold"><?php echo $product->category_name ?? 'Collection'; ?></div>
                            <h3 class="font-bold text-slate-900 truncate text-sm md:text-base mb-1 group-hover:text-accent transition">
                                <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>"><?php echo $product->name; ?></a>
                            </h3>
                            <div class="flex items-center gap-2">
                                <?php if(!empty($product->promo_price)): ?>
                                    <span class="text-red-600 font-black"><?php echo CURRENCY_SYMBOL . number_format($product->promo_price, 2); ?></span>
                                    <span class="text-slate-400 text-xs line-through"><?php echo number_format($product->price, 2); ?></span>
                                <?php else: ?>
                                    <span class="text-slate-900 font-bold"><?php echo CURRENCY_SYMBOL . number_format($product->price, 2); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="py-20 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="group p-4">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-900 text-2xl group-hover:bg-accent group-hover:text-white transition duration-500">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h4 class="font-bold text-sm uppercase tracking-wide text-slate-900">Fast Delivery</h4>
                <p class="text-xs text-slate-500 mt-2">Across Ghana & Togo</p>
            </div>
            <div class="group p-4">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-900 text-2xl group-hover:bg-accent group-hover:text-white transition duration-500">
                    <i class="fas fa-lock"></i>
                </div>
                <h4 class="font-bold text-sm uppercase tracking-wide text-slate-900">Secure Payment</h4>
                <p class="text-xs text-slate-500 mt-2">Mobile Money & Cards</p>
            </div>
            <div class="group p-4">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-900 text-2xl group-hover:bg-accent group-hover:text-white transition duration-500">
                    <i class="fas fa-undo"></i>
                </div>
                <h4 class="font-bold text-sm uppercase tracking-wide text-slate-900">Easy Returns</h4>
                <p class="text-xs text-slate-500 mt-2">14 Days Policy</p>
            </div>
            <div class="group p-4">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-900 text-2xl group-hover:bg-accent group-hover:text-white transition duration-500">
                    <i class="fas fa-headset"></i>
                </div>
                <h4 class="font-bold text-sm uppercase tracking-wide text-slate-900">24/7 Support</h4>
                <p class="text-xs text-slate-500 mt-2">Always here for you</p>
            </div>
        </div>
    </div>
</section>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>