<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="fixed top-24 right-5 z-50 flex flex-col gap-2 pointer-events-none">
    <?php flash('wishlist_msg'); ?>
    <?php flash('newsletter_msg'); ?>
    <?php flash('cart_msg'); ?>
</div>

<section class="relative h-[85vh] min-h-[600px] flex items-center justify-center overflow-hidden bg-slate-900">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1531384441138-2736e62e0919?q=80&w=1974&auto=format&fit=crop" 
             alt="Exotikha Essence" 
             class="w-full h-full object-cover opacity-60 animate-slow-zoom">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-slate-900/40"></div>
    </div>

    <div class="relative z-10 text-center text-white px-6 animate-fade-in-up max-w-4xl">
        <div class="mb-6 flex justify-center">
            <span class="px-4 py-1 border border-white/30 rounded-full text-[10px] md:text-xs font-black uppercase tracking-[0.3em] backdrop-blur-sm">
                <?php echo date('Y'); ?> Collection
            </span>
        </div>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-serif font-bold mb-8 leading-tight drop-shadow-lg">
            Modern <br><span class="italic text-primary bg-clip-text text-transparent bg-gradient-to-r from-primary to-yellow-200">African Elegance</span>
        </h1>
        <p class="text-slate-200 text-lg md:text-xl max-w-2xl mx-auto mb-10 font-light leading-relaxed">
            A bold fusion of traditions and contemporary cuts. 
            Reveal your authenticity with Exotikha.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="<?php echo URLROOT; ?>/shop" class="w-full sm:w-auto bg-white text-slate-900 px-8 py-4 rounded-full font-bold hover:bg-primary hover:text-white transition shadow-xl shadow-white/10 transform hover:-translate-y-1">
                Shop Collection
            </a>
            <a href="#new-arrivals" class="w-full sm:w-auto px-8 py-4 rounded-full font-bold text-white border border-white/30 backdrop-blur-md hover:bg-white/10 transition flex items-center justify-center gap-2">
                <i class="fas fa-arrow-down animate-bounce"></i> New Arrivals
            </a>
        </div>
    </div>
</section>

<section class="bg-white border-b border-slate-100 py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-primary text-xl group-hover:scale-110 transition"><i class="fas fa-motorcycle"></i></div>
                <div><h4 class="font-bold text-sm">Fast Delivery</h4> <p class="text-xs text-slate-500">Accra & Surroundings</p> </div>
            </div>
            <div class="flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-primary text-xl group-hover:scale-110 transition"><i class="fas fa-lock"></i></div>
                <div><h4 class="font-bold text-sm">Secure Payment</h4><p class="text-xs text-slate-500">MOMO & Cards</p></div>
            </div>
            <div class="flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-primary text-xl group-hover:scale-110 transition"><i class="fas fa-sync-alt"></i></div>
                <div><h4 class="font-bold text-sm">Easy Returns</h4><p class="text-xs text-slate-500">Within 14 days</p></div>
            </div>
            <div class="flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-primary text-xl group-hover:scale-110 transition"><i class="fas fa-gem"></i></div>
                <div><h4 class="font-bold text-sm">Premium Quality</h4><p class="text-xs text-slate-500">Authentic Fabrics</p></div>
            </div>
        </div>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="<?php echo URLROOT; ?>/shop?gender=women" class="group relative h-[500px] rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition duration-500">
                <img src="https://images.unsplash.com/photo-1589156280159-27698a70f29e?q=80&w=1886&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent flex flex-col justify-end p-10">
                    <h3 class="text-3xl font-serif text-white mb-2 italic transform translate-y-4 group-hover:translate-y-0 transition">For Her</h3>
                    <span class="text-primary text-sm font-bold uppercase tracking-widest border-b border-primary w-fit pb-1 opacity-0 group-hover:opacity-100 transition duration-500">Discover</span>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/shop?gender=men" class="group relative h-[500px] rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition duration-500 md:-mt-12">
                <img src="https://images.unsplash.com/photo-1504199367641-aba8151af406?q=80&w=1888&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent flex flex-col justify-end p-10">
                    <h3 class="text-3xl font-serif text-white mb-2 italic transform translate-y-4 group-hover:translate-y-0 transition">For Him</h3>
                    <span class="text-primary text-sm font-bold uppercase tracking-widest border-b border-primary w-fit pb-1 opacity-0 group-hover:opacity-100 transition duration-500">Explore</span>
                </div>
            </a>
            <a href="<?php echo URLROOT; ?>/shop?type_id=3" class="group relative h-[500px] rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition duration-500">
                <img src="https://images.unsplash.com/photo-1627930060042-30085448bc31?q=80&w=1887&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent flex flex-col justify-end p-10">
                    <h3 class="text-3xl font-serif text-white mb-2 italic transform translate-y-4 group-hover:translate-y-0 transition">Accessories</h3>
                    <span class="text-primary text-sm font-bold uppercase tracking-widest border-b border-primary w-fit pb-1 opacity-0 group-hover:opacity-100 transition duration-500">View All</span>
                </div>
            </a>
        </div>
    </div>
</section>

<section id="new-arrivals" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-primary font-black uppercase tracking-widest text-xs mb-2 block">Latest Drops</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-slate-900">New Arrivals</h2>
            </div>
            <a href="<?php echo URLROOT; ?>/shop" class="hidden md:flex items-center gap-2 text-sm font-bold uppercase tracking-widest hover:text-primary transition group">
                View All <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition"></i>
            </a>
        </div>

        <?php if(empty($data['new_arrivals'])): ?>
            <div class="bg-white p-12 rounded-2xl text-center border border-dashed border-slate-200">
                <p class="text-slate-400 italic">New collections coming very soon!</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                <?php foreach($data['new_arrivals'] as $product): ?>
                    <div class="group">
                        <div class="relative overflow-hidden rounded-2xl bg-white aspect-[3/4] mb-4 shadow-sm group-hover:shadow-lg transition">
                            
                            <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                                <span class="bg-white/90 backdrop-blur text-slate-900 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider shadow-sm">New</span>
                                <?php if($product->promo_price > 0): ?>
                                    <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider shadow-sm">Sale</span>
                                <?php endif; ?>
                            </div>

                            <a href="<?php echo URLROOT; ?>/wishlist/toggle/<?php echo $product->id; ?>" class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-white/90 flex items-center justify-center text-slate-400 hover:text-red-500 hover:scale-110 transition shadow-sm" title="Add to wishlist">
                                <i class="far fa-heart"></i>
                            </a>

                            <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $product->slug; ?>">
                                <?php if(!empty($product->image)): ?>
                                    <img src="<?php echo URLROOT . '/img/' . $product->image; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-300"><i class="fas fa-camera text-2xl"></i></div>
                                <?php endif; ?>
                            </a>

                            <?php if($product->stock > 0): ?>
                            <form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="absolute bottom-4 right-4 translate-y-20 group-hover:translate-y-0 transition duration-300 z-20">
                                <?php echo csrfField(); ?>    
                                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button class="w-10 h-10 bg-white text-slate-900 rounded-full flex items-center justify-center shadow-lg hover:bg-slate-900 hover:text-white transition" title="Add to cart">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>

                        <h3 class="font-bold text-slate-900 text-sm truncate group-hover:text-primary transition">
                            <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $product->slug; ?>"><?php echo $product->name; ?></a>
                        </h3>
                        
                        <div class="flex items-center gap-2 mt-1">
                            <?php if($product->promo_price > 0): ?>
                                <span class="text-red-500 font-bold"><?php echo CURRENCY_SYMBOL . number_format($product->promo_price, 2); ?></span>
                                <span class="text-xs text-slate-400 line-through"><?php echo number_format($product->price, 2); ?></span>
                            <?php else: ?>
                                <span class="text-slate-900 font-bold"><?php echo CURRENCY_SYMBOL . number_format($product->price, 2); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if(!empty($data['promo_products'])): ?>
<section class="py-20 bg-orange-50 overflow-hidden relative">
    <div class="absolute -right-20 -top-20 w-64 h-64 bg-orange-200 rounded-full blur-3xl opacity-50"></div>
    <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-primary rounded-full blur-3xl opacity-20"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-center mb-10">
            <div>
                <span class="text-red-500 font-black uppercase tracking-widest text-xs mb-2 block animate-pulse">Limited Offers</span>
                <h2 class="text-3xl font-serif font-bold text-slate-900">Flash Sales ⚡</h2>
            </div>
            <a href="<?php echo URLROOT; ?>/shop?promo=true" class="mt-4 md:mt-0 px-6 py-2 border border-slate-900 rounded-full hover:bg-slate-900 hover:text-white transition font-bold text-sm">View all offers</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach($data['promo_products'] as $product): ?>
                <div class="bg-white p-4 rounded-2xl shadow-sm hover:shadow-md transition group">
                    <div class="relative overflow-hidden rounded-xl bg-slate-100 aspect-square mb-4">
                        <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm z-10">
                            -<?php echo round((($product->price - $product->promo_price) / $product->price) * 100); ?>%
                        </span>
                        
                        <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $product->slug; ?>">
                            <?php if(!empty($product->image)): ?>
                                <img src="<?php echo URLROOT . '/img/' . $product->image; ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-tag"></i></div>
                            <?php endif; ?>
                        </a>
                    </div>
                    <h3 class="font-bold text-sm truncate hover:text-primary transition">
                        <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $product->slug; ?>"><?php echo $product->name; ?></a>
                    </h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-red-600 font-bold"><?php echo CURRENCY_SYMBOL . number_format($product->promo_price, 2); ?></span>
                        <span class="text-xs text-slate-400 line-through"><?php echo number_format($product->price, 2); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="py-24 bg-primary text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <span class="text-4xl mb-6 block animate-bounce">💎</span>
        <h2 class="text-3xl md:text-5xl font-serif font-bold mb-6 italic">Join the Club</h2>
        <p class="text-white/90 mb-10 text-lg leading-relaxed max-w-2xl mx-auto">
            Get access to private sales, receive our style tips, and enjoy <strong>10% off</strong> your first order.
        </p>
        <form action="<?php echo URLROOT; ?>/pages/subscribe" method="POST" class="flex flex-col md:flex-row gap-4 max-w-lg mx-auto">
            <?php echo csrfField(); ?>    
            <input type="email" name="email" placeholder="Your email address" required class="flex-1 bg-white/20 border border-white/30 rounded-xl px-6 py-4 text-white placeholder-white/70 focus:ring-2 focus:ring-white focus:border-transparent outline-none transition backdrop-blur-sm">
            <button type="submit" class="bg-white text-primary px-8 py-4 rounded-xl font-bold hover:bg-slate-900 hover:text-white transition shadow-lg">Subscribe</button>
        </form>
    </div>
</section>

<section class="py-24 bg-slate-900 text-white border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-primary font-bold uppercase tracking-widest text-xs mb-2 block">Testimonials</span>
            <h2 class="text-3xl md:text-5xl font-serif font-bold relative inline-block">
                The Exotikha Community
                <svg class="absolute w-full h-3 -bottom-2 text-primary" viewBox="0 0 200 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.00025 6.99997C25.7501 5.50002 55.0002 3.00002 88.0002 2.50002C121 2.00002 165.75 2.50002 198 4.49997" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
            </h2>
        </div>

        <?php if(empty($data['reviews'])): ?>
            <div class="text-center py-16 border border-dashed border-slate-700 rounded-3xl bg-slate-800/30 max-w-2xl mx-auto">
                <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-500">
                    <i class="far fa-comment-dots text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No reviews yet</h3>
                <p class="text-slate-400 mb-6">Be the first to share your experience!</p>
                <a href="<?php echo URLROOT; ?>/shop" class="inline-block px-6 py-2 border border-slate-600 rounded-full hover:bg-white hover:text-slate-900 hover:border-white transition font-bold text-sm">
                    Start Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach($data['reviews'] as $review): ?>
                    <div class="bg-slate-800 p-8 rounded-3xl relative border border-slate-700 hover:border-primary/50 hover:bg-slate-800/80 transition duration-300 group">
                        <i class="fas fa-quote-right absolute top-6 right-6 text-4xl text-slate-600 group-hover:text-primary/20 transition"></i>
                        
                        <div class="flex text-yellow-400 text-sm mb-6">
                            <?php for($i=1; $i<=5; $i++) echo ($i<=$review->rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star text-slate-600"></i>'; ?>
                        </div>
                        
                        <p class="text-slate-300 italic mb-8 leading-relaxed text-lg">"<?php echo $review->comment; ?>"</p>
                        
                        <div class="flex items-center gap-4 pt-6 border-t border-slate-700">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-orange-600 flex items-center justify-center font-bold text-white uppercase shadow-lg">
                                <?php echo !empty($review->full_name) ? substr($review->full_name, 0, 1) : 'C'; ?>
                            </div>
                            <div>
                                <span class="block font-bold text-base text-white"><?php echo !empty($review->full_name) ? $review->full_name : 'Customer'; ?></span>
                                <span class="block text-[10px] uppercase font-bold text-primary flex items-center gap-1"><i class="fas fa-check-circle"></i> Verified Purchase</span>
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
                <span class="text-primary font-black uppercase tracking-widest text-xs mb-2 block">Lifestyle</span>
                <h2 class="text-3xl font-serif font-bold text-slate-900">Exotikha Journal</h2>
            </div>
            <a href="<?php echo URLROOT; ?>/pages/blog" class="hidden md:block text-sm font-bold uppercase tracking-widest border-b-2 border-slate-900 pb-1 hover:text-primary hover:border-primary transition">
                Read the Journal
            </a>
        </div>

        <?php if(empty($data['posts'])): ?>
            <div class="text-center py-12 border-2 border-dashed border-slate-200 rounded-2xl">
                <p class="text-slate-400 italic mb-2">Our journal is coming soon.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach($data['posts'] as $post): ?>
                    <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group h-full flex flex-col">
                        <div class="h-56 overflow-hidden bg-slate-200 relative">
                            <?php if(!empty($post->image)): ?>
                                <img src="<?php echo URLROOT . '/img/' . $post->image; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition"></div>
                        </div>
                        <div class="p-8 flex-1 flex flex-col">
                            <div class="mb-3 flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase text-primary tracking-widest bg-orange-50 px-2 py-1 rounded">
                                    <?php echo isset($post->category) ? $post->category : 'Fashion'; ?>
                                </span>
                                <span class="text-[10px] text-slate-400 font-bold">
                                    <?php echo date('d M Y', strtotime($post->created_at)); ?>
                                </span>
                            </div>
                            <h3 class="text-xl font-bold mb-3 text-slate-900 leading-tight group-hover:text-primary transition">
                                <a href="<?php echo URLROOT; ?>/pages/post/<?php echo $post->id; ?>"><?php echo $post->title; ?></a>
                            </h3>
                            <div class="mt-auto pt-4 border-t border-slate-50">
                                <a href="<?php echo URLROOT; ?>/pages/post/<?php echo $post->id; ?>" class="text-xs font-bold uppercase tracking-wider text-slate-500 hover:text-slate-900 flex items-center gap-2 group-hover:gap-3 transition-all">
                                    Read Article <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
    @keyframes slow-zoom {
        0% { transform: scale(1); }
        100% { transform: scale(1.1); }
    }
    .animate-slow-zoom { animation: slow-zoom 20s linear infinite alternate; }
</style>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>