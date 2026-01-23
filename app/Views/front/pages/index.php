<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<section class="relative h-screen max-h-[800px] flex items-center justify-center overflow-hidden bg-slate-900">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo URLROOT; ?>/public/img/hero-main.jpg" alt="Exotikha Essence" class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-slate-900/30"></div>
    </div>

    <div class="relative z-10 text-center text-white px-6 animate-fade-in-up">
        <span class="block text-accent uppercase tracking-[0.4em] text-xs md:text-sm font-bold mb-4">Sensual • Elegant • Confident</span>
        <h1 class="text-5xl md:text-7xl font-serif font-bold mb-6 leading-tight">
            A New African <br><span class="italic text-accent">Boutique Experience</span>
        </h1>
        <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto mb-10 font-light">
            Discover curated collections tailored to the modern African lifestyle. From premium lingerie to wellness essentials.
        </p>
        <div class="flex flex-col md:flex-row gap-4 justify-center">
            <a href="<?php echo URLROOT; ?>/shop" class="bg-white text-slate-900 px-8 py-4 rounded-full font-bold hover:bg-accent hover:text-white transition shadow-lg transform hover:-translate-y-1">
                Shop Collection
            </a>
            <a href="#categories" class="border border-white/30 backdrop-blur-md text-white px-8 py-4 rounded-full font-bold hover:bg-white/10 transition">
                Explore Categories
            </a>
        </div>
    </div>
</section>

<section id="categories" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="<?php echo URLROOT; ?>/shop?category=women" class="group relative h-[500px] rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition duration-500">
                <img src="<?php echo URLROOT; ?>/public/img/cat-women.jpg" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent flex flex-col justify-end p-10">
                    <h3 class="text-3xl font-serif text-white mb-2 italic">For Women</h3>
                    <span class="text-accent text-sm font-bold uppercase tracking-widest border-b border-accent w-fit pb-1 group-hover:text-white transition">Shop Now</span>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/shop?category=men" class="group relative h-[500px] rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition duration-500">
                <img src="<?php echo URLROOT; ?>/public/img/cat-men.jpg" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent flex flex-col justify-end p-10">
                    <h3 class="text-3xl font-serif text-white mb-2 italic">For Men</h3>
                    <span class="text-accent text-sm font-bold uppercase tracking-widest border-b border-accent w-fit pb-1 group-hover:text-white transition">Discover</span>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/shop?category=gifts" class="group relative h-[500px] rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition duration-500">
                <img src="<?php echo URLROOT; ?>/public/img/cat-couple.jpg" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent flex flex-col justify-end p-10">
                    <h3 class="text-3xl font-serif text-white mb-2 italic">Gift Boxes & Intimacy</h3>
                    <span class="text-accent text-sm font-bold uppercase tracking-widest border-b border-accent w-fit pb-1 group-hover:text-white transition">Explore</span>
                </div>
            </a>
        </div>
    </div>
</section>

<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-slate-900 mb-2">New Arrivals</h2>
                <p class="text-slate-500 max-w-md">Stay ahead of trends with our latest collections in fashion and wellness.</p>
            </div>
            <a href="<?php echo URLROOT; ?>/shop" class="hidden md:block text-sm font-bold uppercase tracking-widest border-b-2 border-slate-900 pb-1 hover:text-accent hover:border-accent transition">View All</a>
        </div>

        <?php if(empty($data['products'])): ?>
            <p class="text-center text-slate-400 italic">New collections coming soon.</p>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-10">
                <?php foreach($data['products'] as $product): ?>
                    <div class="group">
                        <div class="relative overflow-hidden rounded-2xl bg-white aspect-[3/4] mb-4 shadow-sm group-hover:shadow-lg transition">
                            <?php if(!empty($product->promo_price) && $product->promo_price > 0): ?>
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider z-10">Sale</span>
                            <?php endif; ?>
                            
                            <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>">
                                <img src="<?php echo URLROOT . '/public/' . $product->image; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                            </a>
                            
                            <form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="absolute bottom-4 right-4 translate-y-20 group-hover:translate-y-0 transition duration-300">
                                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button class="w-10 h-10 bg-white text-slate-900 rounded-full flex items-center justify-center shadow-lg hover:bg-accent hover:text-white transition">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm truncate"><?php echo $product->name; ?></h3>
                        <div class="flex items-center gap-2 mt-1">
                            <?php if(!empty($product->promo_price) && $product->promo_price > 0): ?>
                                <span class="text-accent font-bold"><?php echo CURRENCY_SYMBOL . number_format($product->promo_price, 2); ?></span>
                                <span class="text-xs text-slate-400 line-through"><?php echo number_format($product->price, 2); ?></span>
                            <?php else: ?>
                                <span class="text-slate-900 font-bold"><?php echo CURRENCY_SYMBOL . number_format($product->price, 2); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="mt-12 text-center md:hidden">
            <a href="<?php echo URLROOT; ?>/shop" class="bg-slate-900 text-white px-8 py-3 rounded-full font-bold text-sm">View All Products</a>
        </div>
    </div>
</section>

<section class="py-24 bg-slate-900 text-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-64 h-64 bg-accent/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <span class="text-4xl mb-4 block">💎</span>
        <h2 class="text-3xl md:text-5xl font-serif font-bold mb-6 italic">Join our Exclusive Private Sales</h2>
        <p class="text-slate-400 mb-10 text-lg leading-relaxed">
            We bring a curated selection of items, and you choose what you love. <br class="hidden md:block">Sign up to get notified about our next private event.
        </p>
        
        <form action="<?php echo URLROOT; ?>/pages/subscribe" method="POST" class="flex flex-col md:flex-row gap-4 max-w-2xl mx-auto">
            <input type="email" name="email" placeholder="Your Email Address" required class="flex-1 bg-white/10 border border-white/20 rounded-xl px-6 py-4 text-white placeholder-slate-400 focus:ring-2 focus:ring-accent focus:border-transparent outline-none transition">
            <button type="submit" class="bg-accent text-white px-8 py-4 rounded-xl font-bold hover:bg-white hover:text-slate-900 transition shadow-lg shadow-accent/25">Join the Club</button>
        </form>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-center text-3xl font-serif font-bold mb-16">What Our Community Says</h2>
        
        <?php if(empty($data['reviews'])): ?>
            <p class="text-center text-slate-400">Be the first to leave a review!</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach($data['reviews'] as $review): ?>
                    <div class="bg-slate-50 p-8 rounded-3xl relative">
                        <div class="flex text-yellow-400 text-xs mb-4">
                            <?php for($i=1; $i<=5; $i++) echo ($i<=$review->rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star text-slate-300"></i>'; ?>
                        </div>
                        <p class="text-slate-600 italic mb-6 leading-relaxed">"<?php echo $review->comment; ?>"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-500">
                                <?php echo substr($review->full_name, 0, 1); ?>
                            </div>
                            <div>
                                <span class="block font-bold text-sm text-slate-900"><?php echo $review->full_name; ?></span>
                                <span class="block text-[10px] uppercase font-bold text-slate-400">Verified Buyer</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-serif font-bold mb-2">Exotikha Lifestyle</h2>
                <p class="text-slate-500">Inspirations for the modern soul.</p>
            </div>
        </div>

        <?php if(empty($data['posts'])): ?>
            <p class="text-slate-400">No articles yet.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach($data['posts'] as $post): ?>
                    <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group h-full flex flex-col">
                        <div class="h-56 overflow-hidden">
                            <img src="<?php echo URLROOT . '/public/' . $post->image; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                        </div>
                        <div class="p-8 flex-1 flex flex-col">
                            <div class="mb-3 flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase text-accent tracking-widest"><?php echo $post->category; ?></span>
                                <span class="text-[10px] text-slate-400"><?php echo date('M d', strtotime($post->created_at)); ?></span>
                            </div>
                            <h3 class="text-xl font-bold mb-3 text-slate-900 leading-tight group-hover:text-accent transition">
                                <a href="<?php echo URLROOT; ?>/pages/post/<?php echo $post->id; ?>"><?php echo $post->title; ?></a>
                            </h3>
                            <div class="mt-auto pt-4 border-t border-slate-50">
                                <a href="<?php echo URLROOT; ?>/pages/post/<?php echo $post->id; ?>" class="text-xs font-bold uppercase tracking-wider text-slate-500 hover:text-slate-900">Read Article</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>