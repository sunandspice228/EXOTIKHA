<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="relative bg-slate-900 h-[85vh] flex items-center overflow-hidden group">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1543087903-1ac2ec7aa8c5?q=80&w=2098&auto=format&fit=crop" class="w-full h-full object-cover opacity-60 transition duration-1000 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/40 to-transparent"></div>
    </div>

    <div class="relative z-10 container max-w-7xl mx-auto px-6">
        <div class="max-w-3xl animate-fade-in-up">
            <span class="inline-block py-1 px-3 rounded-full bg-accent/20 border border-accent text-accent text-xs font-bold tracking-widest uppercase mb-6 backdrop-blur-sm">
                New Collection 2026
            </span>
            <h1 class="text-5xl md:text-7xl font-serif font-bold text-white leading-tight mb-6">
                Redefine Your <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">African Elegance.</span>
            </h1>
            <p class="text-lg text-slate-300 mb-10 font-light max-w-xl leading-relaxed">
                Experience the perfect blend of traditional craftsmanship and contemporary design.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?php echo URLROOT; ?>/shop" class="px-8 py-4 bg-white text-slate-900 font-bold rounded-full hover:bg-slate-200 transition shadow-xl text-center">
                    Shop Collection
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
            <div class="text-center text-slate-400">No categories defined yet.</div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <?php foreach($data['categories'] as $cat): ?>
                    <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $cat->id; ?>" class="group relative h-[400px] rounded-2xl overflow-hidden cursor-pointer shadow-lg">
                        
                        <?php if(!empty($cat->cover_image)): ?>
                            <img src="<?php echo URLROOT . '/public/' . $cat->cover_image; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                        <?php else: ?>
                            <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                <i class="fas fa-layer-group text-slate-600 text-6xl"></i>
                            </div>
                        <?php endif; ?>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-8">
                            <h3 class="text-white text-3xl font-serif font-bold mb-1 translate-y-2 group-hover:translate-y-0 transition duration-300">
                                <?php echo $cat->name; ?>
                            </h3>
                            <p class="text-slate-300 text-sm mb-4 line-clamp-1 opacity-80"><?php echo $cat->description; ?></p>
                            
                            <div class="flex items-center justify-between border-t border-white/20 pt-4 opacity-0 group-hover:opacity-100 transition duration-500 transform translate-y-4 group-hover:translate-y-0">
                                <span class="text-accent text-sm font-bold uppercase tracking-widest">Shop Now</span>
                                <span class="bg-white/20 text-white text-xs py-1 px-2 rounded backdrop-blur-md">
                                    <?php echo $cat->product_count; ?> Items
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>
    </div>
</section>

<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-slate-900 mb-2">New Arrivals</h2>
                <p class="text-slate-500">Fresh from our workshop.</p>
            </div>
            <a href="<?php echo URLROOT; ?>/shop" class="group flex items-center gap-2 text-primary font-bold hover:text-accent transition">
                View All <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
            <?php foreach($data['products'] as $product): ?>
                
                <div class="group bg-white rounded-xl overflow-hidden hover:shadow-xl transition duration-300 border border-slate-100">
                    <div class="relative h-[300px] bg-slate-100 overflow-hidden">
                        <?php if(!empty($product->promo_price)): ?>
                            <span class="absolute top-2 left-2 z-10 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded">PROMO</span>
                        <?php endif; ?>

                        <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>">
                            <?php if(!empty($product->image)): ?>
                                <img src="<?php echo URLROOT . '/public/' . $product->image; ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-camera text-3xl"></i></div>
                            <?php endif; ?>
                        </a>
                    </div>
                    
                    <div class="p-4">
                        <div class="text-[10px] text-slate-400 uppercase tracking-widest mb-1"><?php echo $product->category_name; ?></div>
                        <h3 class="font-bold text-slate-900 truncate mb-2">
                            <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>"><?php echo $product->name; ?></a>
                        </h3>
                        <div class="flex items-center gap-2">
                            <?php if(!empty($product->promo_price)): ?>
                                <span class="text-accent font-bold"><?php echo CURRENCY_SYMBOL . ' ' . $product->promo_price; ?></span>
                                <span class="text-slate-400 text-xs line-through"><?php echo CURRENCY_SYMBOL . ' ' . $product->price; ?></span>
                            <?php else: ?>
                                <span class="text-slate-900 font-bold"><?php echo CURRENCY_SYMBOL . ' ' . $product->price; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-16 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="p-4">
                <i class="fas fa-shipping-fast text-3xl text-accent mb-4"></i>
                <h4 class="font-bold text-sm uppercase">Fast Delivery</h4>
            </div>
            <div class="p-4">
                <i class="fas fa-lock text-3xl text-accent mb-4"></i>
                <h4 class="font-bold text-sm uppercase">Secure Payment</h4>
            </div>
            <div class="p-4">
                <i class="fas fa-undo text-3xl text-accent mb-4"></i>
                <h4 class="font-bold text-sm uppercase">Easy Returns</h4>
            </div>
            <div class="p-4">
                <i class="fas fa-headset text-3xl text-accent mb-4"></i>
                <h4 class="font-bold text-sm uppercase">24/7 Support</h4>
            </div>
        </div>
    </div>
</section>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>