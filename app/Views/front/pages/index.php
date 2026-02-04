<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php 
// Helper local pour la langue
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>

<div class="fixed top-24 right-5 z-50 flex flex-col gap-2 pointer-events-none w-full max-w-sm">
    <?php if(function_exists('flash')) { flash('wishlist_msg'); flash('newsletter_msg'); flash('cart_msg'); } ?>
</div>

<section class="relative h-[90vh] min-h-[650px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo file_exists(PUBROOT . '/uploads/acceuil.jpg') ? URLROOT . '/uploads/acceuil.jpg' : 'https://images.unsplash.com/photo-1509319117193-518da72778cb?q=80&w=1889&auto=format&fit=crop'; ?>" 
             alt="Exotikha Collection" 
             class="w-full h-full object-cover opacity-50 animate-slow-zoom" style="object-position: center;">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-transparent to-slate-900/90"></div>
    </div>

    <div class="relative z-10 text-center text-white px-6 animate-fade-in-up max-w-5xl mx-auto">
        <div class="mb-8 flex justify-center">
            <span class="px-6 py-2 border border-white/20 bg-white/5 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-[0.3em] text-rose-200 shadow-lg">
                <?php echo lang('hero_new_collection'); ?> <?php echo date('Y'); ?>
            </span>
        </div>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-serif font-bold mb-8 leading-tight drop-shadow-2xl">
            <?php echo lang('hero_title'); ?>
        </h1>
        <p class="text-slate-200 text-lg md:text-2xl max-w-2xl mx-auto mb-12 font-light leading-relaxed drop-shadow-md">
            <?php echo lang('hero_subtitle'); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
            <a href="<?php echo URLROOT; ?>/shop" class="w-full sm:w-auto bg-primary text-white px-10 py-5 rounded-full font-bold text-sm uppercase tracking-widest hover:bg-rose-700 transition shadow-xl shadow-primary/40 transform hover:-translate-y-1">
                <?php echo lang('btn_shop_collection'); ?>
            </a>
            <a href="#universes" class="w-full sm:w-auto px-10 py-5 rounded-full font-bold text-sm uppercase tracking-widest text-white border border-white/30 backdrop-blur-md hover:bg-white/10 transition flex items-center justify-center gap-3 group">
                <?php echo lang('btn_explore'); ?> <i class="fas fa-arrow-down group-hover:translate-y-1 transition"></i>
            </a>
        </div>
    </div>
</section>

<section class="bg-white border-b border-slate-100 py-12 relative z-20 -mt-8 rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.05)] mx-4 md:mx-12">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-slate-50">
            <div class="flex flex-col items-center text-center gap-3 group px-4">
                <div class="text-primary text-2xl group-hover:scale-110 transition duration-300"><i class="fas fa-shipping-fast"></i></div>
                <div><h4 class="font-bold text-slate-900 uppercase text-xs tracking-widest mb-1"><?php echo lang('feat_delivery_title'); ?></h4> <p class="text-xs text-slate-500 font-medium"><?php echo lang('feat_delivery_text'); ?></p> </div>
            </div>
            <div class="flex flex-col items-center text-center gap-3 group px-4">
                <div class="text-primary text-2xl group-hover:scale-110 transition duration-300"><i class="fas fa-lock"></i></div>
                <div><h4 class="font-bold text-slate-900 uppercase text-xs tracking-widest mb-1"><?php echo lang('feat_payment_title'); ?></h4><p class="text-xs text-slate-500 font-medium"><?php echo lang('feat_payment_text'); ?></p></div>
            </div>
            <div class="flex flex-col items-center text-center gap-3 group px-4">
                <div class="text-primary text-2xl group-hover:scale-100 transition duration-300"><i class="fas fa-user-secret"></i></div>
                <div><h4 class="font-bold text-slate-900 uppercase text-xs tracking-widest mb-1"><?php echo lang('feat_discreet_title'); ?></h4><p class="text-xs text-slate-500 font-medium"><?php echo lang('feat_discreet_text'); ?></p></div>
            </div>
            <div class="flex flex-col items-center text-center gap-3 group px-4">
                <div class="text-primary text-2xl group-hover:scale-110 transition duration-300"><i class="fas fa-gem"></i></div>
                <div><h4 class="font-bold text-slate-900 uppercase text-xs tracking-widest mb-1"><?php echo lang('feat_quality_title'); ?></h4><p class="text-xs text-slate-500 font-medium"><?php echo lang('feat_quality_text'); ?></p></div>
            </div>
        </div>
    </div>
</section>

<section id="universes" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-primary font-bold uppercase tracking-[0.2em] text-xs mb-3 block"><?php echo lang('univ_subtitle'); ?></span>
            <h2 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6"><?php echo lang('univ_title'); ?></h2>
            <div class="h-1 w-24 bg-primary mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="<?php echo URLROOT; ?>/shop?gender=women" class="group relative h-[550px] rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-500">
                <img src="<?php echo file_exists(PUBROOT . '/uploads/cat-lingerie.jpg') ? URLROOT . '/uploads/cat-lingerie.jpg' : 'https://images.unsplash.com/photo-1596483756247-49f99d52b96e?q=80&w=1887&auto=format&fit=crop'; ?>" 
                     class="w-full h-full object-cover transition duration-1000 group-hover:scale-110 filter brightness-90 group-hover:brightness-100">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-90"></div>
                <div class="absolute bottom-0 left-0 w-full p-10 transform translate-y-4 group-hover:translate-y-0 transition duration-500">
                    <p class="text-rose-300 text-xs font-bold uppercase tracking-widest mb-2"><?php echo lang('univ_cat_badge'); ?></p>
                    <h3 class="text-4xl font-serif text-white mb-4 italic"><?php echo lang('univ_women_title'); ?></h3>
                    <span class="inline-flex items-center gap-2 text-white text-sm font-bold uppercase tracking-wider group-hover:gap-4 transition-all">
                        <?php echo lang('btn_shop_now'); ?> <i class="fas fa-arrow-right text-primary"></i>
                    </span>
                </div>
            </a>

            <a href="<?php echo URLROOT; ?>/shop?gender=men" class="group relative h-[550px] rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-500">
                <img src="<?php echo file_exists(PUBROOT . '/uploads/cat-men.jpg') ? URLROOT . '/uploads/cat-men.jpg' : 'https://images.unsplash.com/photo-1504194921103-f8b80cadd5e4?q=80&w=1887&auto=format&fit=crop'; ?>" 
                     class="w-full h-full object-cover transition duration-1000 group-hover:scale-110 filter brightness-90 group-hover:brightness-100">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-90"></div>
                <div class="absolute bottom-0 left-0 w-full p-10 transform translate-y-4 group-hover:translate-y-0 transition duration-500">
                    <p class="text-blue-300 text-xs font-bold uppercase tracking-widest mb-2"><?php echo lang('univ_cat_badge'); ?></p>
                    <h3 class="text-4xl font-serif text-white mb-4 italic"><?php echo lang('univ_men_title'); ?></h3>
                    <span class="inline-flex items-center gap-2 text-white text-sm font-bold uppercase tracking-wider group-hover:gap-4 transition-all">
                        <?php echo lang('btn_shop_now'); ?> <i class="fas fa-arrow-right text-primary"></i>
                    </span>
                </div>
            </a>

            <a href="<?php echo URLROOT; ?>/shop?category=Gift" class="group relative h-[550px] rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-500">
                <img src="<?php echo file_exists(PUBROOT . '/uploads/cat-gift.jpg') ? URLROOT . '/uploads/cat-gift.jpg' : 'https://images.unsplash.com/photo-1513201099705-a9746e1e201f?q=80&w=1974&auto=format&fit=crop'; ?>" 
                     class="w-full h-full object-cover transition duration-1000 group-hover:scale-110 filter brightness-90 group-hover:brightness-100">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-90"></div>
                <div class="absolute bottom-0 left-0 w-full p-10 transform translate-y-4 group-hover:translate-y-0 transition duration-500">
                    <p class="text-yellow-300 text-xs font-bold uppercase tracking-widest mb-2"><?php echo lang('univ_gift_badge'); ?></p>
                    <h3 class="text-4xl font-serif text-white mb-4 italic"><?php echo lang('univ_gift_title'); ?></h3>
                    <span class="inline-flex items-center gap-2 text-white text-sm font-bold uppercase tracking-wider group-hover:gap-4 transition-all">
                        <?php echo lang('btn_discover'); ?> <i class="fas fa-arrow-right text-primary"></i>
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>

<section id="new-arrivals" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-4">
            <div>
                <span class="text-primary font-black uppercase tracking-widest text-xs mb-2 block"><?php echo lang('new_subtitle'); ?></span>
                <h2 class="text-3xl md:text-5xl font-serif font-bold text-slate-900"><?php echo lang('new_title'); ?></h2>
            </div>
            <a href="<?php echo URLROOT; ?>/shop" class="hidden md:flex items-center gap-2 text-sm font-bold uppercase tracking-widest border-b-2 border-slate-900 pb-1 hover:text-primary hover:border-primary transition group">
                <?php echo lang('btn_view_all'); ?> <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition"></i>
            </a>
        </div>

        <?php if(empty($data['new_arrivals'])): ?>
            <div class="bg-slate-50 p-16 rounded-3xl text-center border-2 border-dashed border-slate-200">
                <p class="text-slate-400 italic text-lg"><?php echo lang('msg_no_products'); ?></p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-10">
                <?php foreach($data['new_arrivals'] as $product): ?>
                    <?php 
                        // DONNÉES DYNAMIQUES
                        $pName = ($lang == 'fr' && !empty($product->name_fr)) ? $product->name_fr : $product->name;
                        $imgUrl = !empty($product->image) ? URLROOT . '/uploads/' . $product->image : URLROOT . '/img/no-image.jpg';
                    ?>
                    
                    <div class="group">
                        <div class="relative overflow-hidden rounded-2xl bg-slate-100 aspect-[3/4] mb-5 shadow-sm group-hover:shadow-xl transition duration-500">
                            
                            <div class="absolute top-3 left-3 z-10 flex flex-col gap-2">
                                <span class="bg-white/90 backdrop-blur text-slate-900 text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm"><?php echo lang('badge_new'); ?></span>
                                <?php if($product->promo_price > 0): ?>
                                    <span class="bg-primary text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm"><?php echo lang('badge_sale'); ?></span>
                                <?php endif; ?>
                            </div>

                            <a href="<?php echo URLROOT; ?>/wishlist/toggle/<?php echo $product->id; ?>" class="absolute top-3 right-3 z-20 w-9 h-9 rounded-full bg-white/90 flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 transition shadow-sm" title="Add to wishlist">
                                <i class="far fa-heart"></i>
                            </a>

                            <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $product->slug; ?>" class="block w-full h-full">
                                <img src="<?php echo $imgUrl; ?>" alt="<?php echo $pName; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                            </a>
                            
                            <?php if($product->stock > 0): ?>
                            <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover:opacity-100 transition duration-300 z-20">
                                <form action="<?php echo URLROOT; ?>/cart/add" method="POST">
                                    <?php echo isset($_SESSION['csrf_token']) ? csrfField() : ''; ?>    
                                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="w-full bg-white text-slate-900 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-slate-900 hover:text-white transition shadow-lg flex items-center justify-center gap-2">
                                        <i class="fas fa-shopping-bag"></i> <?php echo lang('btn_add_cart'); ?>
                                    </button>
                                </form>
                            </div>
                            <?php else: ?>
                                 <div class="absolute inset-0 bg-white/60 flex items-center justify-center pointer-events-none">
                                    <span class="bg-dark text-white px-3 py-1 text-xs font-bold uppercase"><?php echo lang('out_of_stock'); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <h3 class="font-serif font-bold text-slate-900 text-lg truncate group-hover:text-primary transition">
                            <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $product->slug; ?>"><?php echo $pName; ?></a>
                        </h3>
                        <div class="flex items-center gap-3 mt-2">
                            <?php if($product->promo_price > 0): ?>
                                <span class="text-primary font-bold text-lg"><?php echo CURRENCY_SYMBOL . number_format($product->promo_price, 2); ?></span>
                                <span class="text-sm text-slate-400 line-through decoration-slate-400/50"><?php echo number_format($product->price, 2); ?></span>
                            <?php else: ?>
                                <span class="text-slate-900 font-bold text-lg"><?php echo CURRENCY_SYMBOL . number_format($product->price, 2); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="py-24 bg-slate-50 text-slate-900 relative">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <span class="text-4xl mb-4 block">💎</span>
        <h2 class="text-3xl md:text-5xl font-serif font-bold mb-4 italic"><?php echo lang('club_title'); ?></h2>
        <p class="text-slate-500 mb-10 text-lg font-light leading-relaxed">
            <?php echo lang('club_text'); ?>
        </p>
        <form action="<?php echo URLROOT; ?>/pages/subscribe" method="POST" class="flex flex-col md:flex-row gap-4">
            <?php echo isset($_SESSION['csrf_token']) ? csrfField() : ''; ?>    
            <input type="email" name="email" placeholder="<?php echo lang('club_placeholder'); ?>" required 
                   class="flex-1 bg-white border border-slate-200 rounded-full px-6 py-4 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition">
            <button type="submit" class="bg-slate-900 text-white px-8 py-4 rounded-full font-bold uppercase tracking-widest hover:bg-primary transition shadow-xl shadow-slate-200">
                <?php echo lang('btn_subscribe'); ?>
            </button>
        </form>
        <p class="mt-6 text-xs text-slate-400"><?php echo lang('club_terms'); ?></p>
    </div>
</section>

<section class="py-24 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-primary font-bold uppercase tracking-widest text-xs mb-3 block">Testimonials</span>
            <h2 class="text-3xl md:text-5xl font-serif font-bold relative inline-block text-slate-900"><?php echo lang('reviews_title'); ?></h2>
        </div>
        <?php if(!empty($data['reviews'])): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach($data['reviews'] as $review): ?>
                    <div class="bg-white p-8 rounded-2xl relative border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 group">
                        <i class="fas fa-quote-right absolute top-8 right-8 text-3xl text-slate-100 group-hover:text-rose-100 transition"></i>
                        <div class="flex text-primary text-xs mb-6">
                            <?php for($i=1; $i<=5; $i++) echo ($i<=$review->rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star text-slate-300"></i>'; ?>
                        </div>
                        <p class="text-slate-600 italic mb-8 leading-relaxed text-lg">"<?php echo $review->comment; ?>"</p>
                        <div class="flex items-center gap-4 pt-6 border-t border-slate-50">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 uppercase">
                                <?php echo !empty($review->full_name) ? substr($review->full_name, 0, 1) : 'C'; ?>
                            </div>
                            <div>
                                <span class="block font-bold text-sm text-slate-900"><?php echo !empty($review->full_name) ? $review->full_name : lang('reviews_customer'); ?></span>
                                <span class="block text-[10px] uppercase font-bold text-slate-400 flex items-center gap-1"><i class="fas fa-check-circle text-primary"></i> <?php echo lang('reviews_verified'); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center text-slate-400 italic"><?php echo lang('reviews_empty'); ?></div>
        <?php endif; ?>
    </div>
</section>

<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-end mb-16">
            <div>
                <span class="text-primary font-black uppercase tracking-widest text-xs mb-2 block"><?php echo lang('blog_subtitle'); ?></span>
                <h2 class="text-3xl md:text-5xl font-serif font-bold text-slate-900"><?php echo lang('blog_title'); ?></h2>
            </div>
            <a href="<?php echo URLROOT; ?>/pages/blog" class="hidden md:block text-sm font-bold uppercase tracking-widest border-b-2 border-slate-900 pb-1 hover:text-primary hover:border-primary transition">
                <?php echo lang('blog_read'); ?>
            </a>
        </div>

        <?php if(empty($data['posts'])): ?>
            <div class="text-center py-12 border-2 border-dashed border-slate-200 rounded-2xl bg-white">
                <p class="text-slate-400 italic mb-2"><?php echo lang('blog_empty'); ?></p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <?php foreach($data['posts'] as $post): ?>
                    <?php 
                        $postTitle = ($lang == 'fr' && !empty($post->title_fr)) ? $post->title_fr : $post->title;
                        $postImg = !empty($post->image) ? URLROOT . '/uploads/' . $post->image : '';
                    ?>
                    <article class="bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition group h-full flex flex-col">
                        <div class="h-64 overflow-hidden bg-slate-200 relative">
                            <?php if($postImg): ?>
                                <img src="<?php echo $postImg; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-300"><i class="fas fa-pen-nib fa-2x"></i></div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition"></div>
                        </div>
                        <div class="p-8 flex-1 flex flex-col">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase text-primary tracking-widest bg-rose-50 px-3 py-1 rounded-full">
                                    <?php echo isset($post->category) ? $post->category : 'Lifestyle'; ?>
                                </span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">
                                    <?php echo date('d M Y', strtotime($post->created_at)); ?>
                                </span>
                            </div>
                            <h3 class="text-xl font-bold mb-4 text-slate-900 leading-tight group-hover:text-primary transition font-serif">
                                <a href="<?php echo URLROOT; ?>/pages/post/<?php echo $post->id; ?>"><?php echo $postTitle; ?></a>
                            </h3>
                            <div class="mt-auto pt-6 border-t border-slate-50">
                                <a href="<?php echo URLROOT; ?>/pages/post/<?php echo $post->id; ?>" class="text-xs font-bold uppercase tracking-wider text-slate-500 hover:text-slate-900 flex items-center gap-2 group-hover:gap-3 transition-all">
                                    <?php echo lang('blog_read_article'); ?> <i class="fas fa-arrow-right"></i>
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