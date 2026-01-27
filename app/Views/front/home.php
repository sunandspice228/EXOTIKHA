<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="relative h-[85vh] w-full bg-slate-900 overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1534126511673-b6899657816a?q=80&w=1920&auto=format&fit=crop" class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
    </div>

    <div class="relative z-10 h-full flex flex-col items-center justify-center text-center px-4">
        <span class="text-accent font-black uppercase tracking-[0.3em] text-xs md:text-sm mb-4 animate-fade-in-down">Est. 2026</span>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-serif font-bold text-white mb-6 leading-tight animate-fade-in-up">
            Modern African <br> <span class="italic text-accent">Elegance</span>
        </h1>
        <p class="text-slate-300 text-lg md:text-xl max-w-2xl mb-10 leading-relaxed animate-fade-in-up delay-100">
            Discover a curated collection where tradition meets contemporary fashion. Designed in Ghana, worn worldwide.
        </p>
        <div class="flex gap-4 animate-fade-in-up delay-200">
            <a href="<?php echo URLROOT; ?>/shop" class="bg-white text-slate-900 px-8 py-4 rounded-xl font-bold uppercase tracking-widest hover:bg-accent hover:text-white transition shadow-xl shadow-white/10">
                Shop Collection
            </a>
            <a href="<?php echo URLROOT; ?>/shop/promotions" class="border border-white text-white px-8 py-4 rounded-xl font-bold uppercase tracking-widest hover:bg-white hover:text-slate-900 transition">
                Flash Sales
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-serif font-bold text-slate-900 mb-3">Shop by Category</h2>
        <div class="w-20 h-1 bg-accent mx-auto rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <a href="<?php echo URLROOT; ?>/shop?gender=women" class="group relative h-[500px] rounded-3xl overflow-hidden shadow-xl">
            <img src="https://images.unsplash.com/photo-1605763240004-7e93b172d754?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
            <div class="absolute inset-0 bg-slate-900/30 group-hover:bg-slate-900/20 transition"></div>
            <div class="absolute bottom-8 left-8 text-white">
                <span class="block text-accent text-xs font-black uppercase tracking-widest mb-1">Collection</span>
                <h3 class="text-4xl font-serif font-bold mb-4">For Her</h3>
                <span class="inline-block border-b border-white pb-1 text-sm font-bold uppercase tracking-wider group-hover:text-accent group-hover:border-accent transition">Explore</span>
            </div>
        </a>

        <a href="<?php echo URLROOT; ?>/shop?gender=men" class="group relative h-[500px] rounded-3xl overflow-hidden shadow-xl">
            <img src="https://images.unsplash.com/photo-1550614000-4b9519e099a9?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
            <div class="absolute inset-0 bg-slate-900/30 group-hover:bg-slate-900/20 transition"></div>
            <div class="absolute bottom-8 left-8 text-white">
                <span class="block text-accent text-xs font-black uppercase tracking-widest mb-1">Collection</span>
                <h3 class="text-4xl font-serif font-bold mb-4">For Him</h3>
                <span class="inline-block border-b border-white pb-1 text-sm font-bold uppercase tracking-wider group-hover:text-accent group-hover:border-accent transition">Explore</span>
            </div>
        </a>
    </div>
</div>

<div class="bg-slate-50 py-20 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-accent font-bold text-xs uppercase tracking-widest mb-2 block">Fresh In</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-slate-900">New Arrivals</h2>
            </div>
            <a href="<?php echo URLROOT; ?>/shop" class="hidden md:flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-accent transition uppercase tracking-wide">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <?php if(empty($data['products'])): ?>
            <div class="text-center py-20">
                <p class="text-slate-400 italic text-lg">New collections are arriving soon.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                <?php foreach($data['products'] as $product): ?>
                    <div class="group relative">
                        <div class="relative overflow-hidden rounded-2xl bg-white aspect-[3/4] mb-4 shadow-sm border border-slate-100 group-hover:shadow-xl transition-all duration-500">
                            
                            <?php if($product->stock < 5 && $product->stock > 0): ?>
                                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-orange-600 text-[10px] font-bold px-2 py-1 rounded z-20 border border-orange-100">
                                    Low Stock
                                </span>
                            <?php endif; ?>

                            <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" class="block w-full h-full">
                                <?php if(!empty($product->main_image)): ?>
                                    <img src="<?php echo URLROOT . '/public/img/' . $product->main_image; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-50"><i class="fas fa-camera text-2xl"></i></div>
                                <?php endif; ?>
                            </a>

                            <div class="absolute bottom-4 left-4 right-4 translate-y-20 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 z-20">
                                <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" class="block w-full bg-slate-900 text-white py-3 rounded-xl font-bold text-xs uppercase tracking-wider text-center hover:bg-accent shadow-lg">
                                    View Details
                                </a>
                            </div>
                        </div>

                        <div class="relative z-0">
                            <p class="text-[10px] uppercase text-slate-400 font-bold tracking-wider mb-1">
                                <?php echo $product->category_name ?? 'Collection'; ?>
                            </p>
                            <h3 class="font-bold text-slate-900 text-sm leading-tight truncate mb-1 group-hover:text-accent transition">
                                <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>"><?php echo $product->name; ?></a>
                            </h3>
                            <p class="text-slate-900 font-bold"><?php echo CURRENCY_SYMBOL . number_format($product->price, 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-12 text-center md:hidden">
                <a href="<?php echo URLROOT; ?>/shop" class="inline-block border border-slate-200 bg-white px-8 py-3 rounded-xl font-bold text-sm uppercase tracking-wide text-slate-900 shadow-sm">
                    View All Products
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            
            <div class="group">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-900 text-3xl group-hover:bg-accent group-hover:text-white transition duration-500">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2 uppercase tracking-wide">Fast Delivery</h3>
                <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto">
                    We ship quickly across Ghana and Togo within 48 hours.
                </p>
            </div>

            <div class="group">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-900 text-3xl group-hover:bg-accent group-hover:text-white transition duration-500">
                    <i class="fas fa-lock"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2 uppercase tracking-wide">Secure Payment</h3>
                <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto">
                    Pay securely with Mobile Money (Flooz, T-Money) or Credit Cards.
                </p>
            </div>

            <div class="group">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-900 text-3xl group-hover:bg-accent group-hover:text-white transition duration-500">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2 uppercase tracking-wide">24/7 Support</h3>
                <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto">
                    Have a question? Our support team is here to assist you anytime.
                </p>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>