<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php
// Helper local pour la langue
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>

<div class="bg-slate-50 border-b border-slate-200 py-12 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-2 animate-fade-in-down">Exotikha Store</p>
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-4 animate-fade-in-up">
            <?php 
                if(!empty($data['filters']['promo_only'])) echo lang('title_promo');
                elseif(!empty($data['filters']['search'])) echo lang('title_search') . ': "' . htmlspecialchars($data['filters']['search']) . '"';
                elseif(!empty($data['filters']['gender'])) echo ucfirst($data['filters']['gender']) . "'s Collection";
                else echo lang('title_all_collections');
            ?>
        </h1>
        
        <?php if(!empty($data['filters']['gender']) || !empty($data['filters']['category_id']) || !empty($data['filters']['type_id']) || !empty($data['filters']['search'])): ?>
        <div class="flex flex-wrap justify-center gap-2 mt-6 animate-fade-in-up delay-100">
            
            <?php if(!empty($data['filters']['gender'])): ?>
                <a href="<?php echo URLROOT; ?>/shop" class="inline-flex items-center gap-2 px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-600 hover:border-red-300 hover:text-red-500 transition group">
                    <?php echo ucfirst($data['filters']['gender']); ?> <i class="fas fa-times group-hover:rotate-90 transition"></i>
                </a>
            <?php endif; ?>

            <?php if(!empty($data['filters']['category_id'])): ?>
                <?php 
                    $catName = 'Category';
                    foreach($data['categories'] as $c) { if($c->id == $data['filters']['category_id']) $catName = $c->name; }
                ?>
                <a href="<?php echo URLROOT; ?>/shop" class="inline-flex items-center gap-2 px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-600 hover:border-red-300 hover:text-red-500 transition group">
                    <?php echo $catName; ?> <i class="fas fa-times group-hover:rotate-90 transition"></i>
                </a>
            <?php endif; ?>

            <?php if(!empty($data['filters']['type_id'])): ?>
                <?php 
                    $typeName = 'Type';
                    if(isset($data['types'])) {
                        foreach($data['types'] as $t) { if($t->id == $data['filters']['type_id']) $typeName = $t->name; }
                    }
                ?>
                <a href="<?php echo URLROOT; ?>/shop" class="inline-flex items-center gap-2 px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-600 hover:border-red-300 hover:text-red-500 transition group">
                    <?php echo $typeName; ?> <i class="fas fa-times group-hover:rotate-90 transition"></i>
                </a>
            <?php endif; ?>

            <a href="<?php echo URLROOT; ?>/shop" class="inline-flex items-center gap-2 px-3 py-1 bg-slate-900 text-white rounded-full text-xs font-bold hover:bg-slate-700 transition">
                <?php echo lang('btn_clear_all'); ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-12"> 
    
    <div class="lg:hidden mb-6 flex justify-between items-center">
        <button id="mobileFilterBtn" class="flex items-center gap-2 bg-slate-900 text-white px-4 py-3 rounded-xl font-bold text-sm shadow-lg">
            <i class="fas fa-filter"></i> <?php echo lang('btn_filters'); ?>
        </button>
        <span class="text-xs font-bold text-slate-500"><?php echo count($data['products']); ?> items</span>
    </div>

    <div class="flex flex-col lg:flex-row gap-12">
        
        <div id="filterOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>
        
        <aside id="shopSidebar" class="fixed inset-y-0 left-0 w-[280px] bg-white z-50 transform -translate-x-full transition-transform duration-300 lg:translate-x-0 lg:static lg:w-64 lg:block lg:z-auto lg:bg-transparent shadow-2xl lg:shadow-none overflow-y-auto lg:overflow-visible p-6 lg:p-0">
            
            <div class="flex justify-between items-center lg:hidden mb-6">
                <h3 class="font-bold text-lg"><?php echo lang('sidebar_filters'); ?></h3>
                <button id="closeFilterBtn" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500"><i class="fas fa-times"></i></button>
            </div>

            <div class="space-y-8 lg:sticky lg:top-24">
                
                <div class="bg-white lg:bg-transparent rounded-2xl">
                    <form action="<?php echo URLROOT; ?>/shop" method="GET" class="relative group">
                        <?php foreach(['category_id', 'gender', 'type_id'] as $f): ?>
                            <?php if(!empty($data['filters'][$f])): ?>
                                <input type="hidden" name="<?php echo $f; ?>" value="<?php echo $data['filters'][$f]; ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <input type="text" name="search" value="<?php echo htmlspecialchars($data['filters']['search'] ?? ''); ?>" placeholder="<?php echo lang('search_placeholder'); ?>" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-sm group-hover:shadow-md">
                        <i class="fas fa-search absolute left-4 top-3.5 text-slate-400 group-focus-within:text-primary transition"></i>
                    </form>
                </div>

                <?php if(isset($data['genres']) && !empty($data['genres'])): ?>
                <div class="border-t border-slate-200 pt-6 lg:border-none lg:pt-0">
                    <h3 class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-widest flex items-center gap-2">
                        <?php echo lang('sidebar_collections'); ?>
                    </h3>
                    <div class="space-y-2">
                        <a href="<?php echo URLROOT; ?>/shop" class="flex items-center justify-between p-3 rounded-lg transition <?php echo empty($data['filters']['gender']) ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'hover:bg-slate-100 text-slate-600'; ?>">
                            <span class="font-bold text-sm"><?php echo lang('sidebar_view_all'); ?></span>
                        </a>
                        
                        <?php foreach($data['genres'] as $genre): ?>
                            <?php 
                                $url = URLROOT . '/shop?gender=' . urlencode($genre->name); 
                                if(!empty($data['filters']['category_id'])) $url .= '&category_id=' . $data['filters']['category_id'];
                                
                                $isActive = (isset($data['filters']['gender']) && strtolower($data['filters']['gender']) == strtolower($genre->name));
                            ?>
                            <a href="<?php echo $url; ?>" class="flex items-center justify-between p-3 rounded-lg transition <?php echo $isActive ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'hover:bg-slate-100 text-slate-600'; ?>">
                                <span class="font-bold text-sm"><?php echo ucfirst($genre->name); ?></span>
                                <?php if($isActive): ?><i class="fas fa-check text-xs"></i><?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(isset($data['types']) && !empty($data['types'])): ?>
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-widest"><?php echo lang('sidebar_type'); ?></h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach($data['types'] as $type): ?>
                            <?php 
                                $isActive = (isset($data['filters']['type_id']) && $data['filters']['type_id'] == $type->id);
                                $url = URLROOT . '/shop?type_id=' . $type->id;
                                if(!empty($data['filters']['gender'])) $url .= '&gender=' . $data['filters']['gender'];
                            ?>
                            <a href="<?php echo $url; ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition <?php echo $isActive ? 'bg-slate-800 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-800 hover:text-slate-800'; ?>">
                                <?php echo $type->name; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="border-t border-slate-200 pt-6">
                    <h3 class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-widest"><?php echo lang('sidebar_categories'); ?></h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach($data['categories'] as $cat): ?>
                            <?php 
                                $isActive = (isset($data['filters']['category_id']) && $data['filters']['category_id'] == $cat->id);
                                $url = URLROOT . '/shop?category_id=' . $cat->id;
                                if(!empty($data['filters']['gender'])) $url .= '&gender=' . $data['filters']['gender'];
                                if(!empty($data['filters']['type_id'])) $url .= '&type_id=' . $data['filters']['type_id'];
                            ?>
                            <a href="<?php echo $url; ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition <?php echo $isActive ? 'bg-primary border-primary text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-primary hover:text-primary'; ?>">
                                <?php echo $cat->name; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-6">
                    <a href="<?php echo URLROOT; ?>/shop?promo_only=1" class="relative block overflow-hidden rounded-xl bg-gradient-to-r from-red-500 to-pink-600 p-5 text-white shadow-lg hover:shadow-xl hover:scale-[1.02] transition duration-300">
                        <div class="relative z-10 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase opacity-80"><?php echo lang('promo_special_offer'); ?></p>
                                <p class="font-bold text-lg"><?php echo lang('promo_title'); ?></p>
                            </div>
                            <i class="fas fa-percent text-2xl opacity-50"></i>
                        </div>
                        <div class="absolute -right-4 -bottom-8 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                    </a>
                </div>

            </div>
        </aside>

        <div class="flex-1">
            
            <div class="hidden lg:flex justify-between items-center mb-8 pb-4 border-b border-slate-100">
                <span class="text-sm font-bold text-slate-500"><strong><?php echo count($data['products']); ?></strong> results</span>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <form action="<?php echo URLROOT; ?>/shop" method="get" class="flex items-center gap-2">
                        <?php foreach($_GET as $key => $val): ?>
                            <?php if($key != 'sort' && !empty($val)): ?>
                                <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($val); ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                        
                        <span><?php echo lang('sort_by'); ?>:</span>
                        <select name="sort" onchange="this.form.submit()" class="border-none bg-slate-50 rounded-lg text-sm font-bold text-slate-800 focus:ring-0 cursor-pointer py-1 pl-3 pr-8">
                            <option value="newest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : ''; ?>><?php echo lang('sort_newest'); ?></option>
                            <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>><?php echo lang('sort_price_asc'); ?></option>
                            <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>><?php echo lang('sort_price_desc'); ?></option>
                            <option value="popularity" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'popularity') ? 'selected' : ''; ?>><?php echo lang('sort_popular'); ?></option>
                        </select>
                    </form>
                </div>
            </div>

            <?php if(empty($data['products'])): ?>
                <div class="flex flex-col items-center justify-center py-24 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-6 text-slate-300">
                        <i class="fas fa-search text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-serif font-bold text-slate-800 mb-2"><?php echo lang('shop_no_results_title'); ?></h3>
                    <p class="text-slate-500 max-w-sm mb-8"><?php echo lang('shop_no_results_text'); ?></p>
                    <a href="<?php echo URLROOT; ?>/shop" class="bg-slate-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-primary transition shadow-lg shadow-slate-900/20">
                        <?php echo lang('btn_clear_filters'); ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
                    <?php foreach($data['products'] as $product): ?>
                        <?php 
                            // Traduction Produit
                            $pName = ($lang == 'fr' && !empty($product->name_fr)) ? $product->name_fr : $product->name;
                            $imgUrl = !empty($product->image) ? URLROOT . '/uploads/' . $product->image : URLROOT . '/img/no-image.jpg';
                        ?>
                        <div class="group relative">
                            
                            <div class="relative overflow-hidden rounded-2xl bg-slate-100 aspect-[3/4] mb-4 shadow-sm group-hover:shadow-xl transition-all duration-500">
                                
                                <?php if($product->promo_price > 0): ?>
                                    <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-black uppercase px-2 py-1 rounded z-20 shadow-md">
                                        -<?php echo round((($product->price - $product->promo_price)/$product->price)*100); ?>%
                                    </span>
                                <?php endif; ?>

                                <?php if($product->stock > 0 && $product->stock < 5): ?>
                                    <span class="absolute top-3 right-3 bg-white/90 backdrop-blur text-orange-600 text-[10px] font-bold px-2 py-1 rounded z-20 border border-orange-100">
                                        <?php echo lang('stock_low'); ?>
                                    </span>
                                <?php endif; ?>

                                <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $product->slug; ?>" class="block w-full h-full">
                                    <img src="<?php echo $imgUrl; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="<?php echo $pName; ?>">
                                </a>

                                <?php if($product->stock > 0): ?>
                                    <div class="absolute bottom-4 left-4 right-4 translate-y-20 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 z-20">
                                        <form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="flex gap-2">
                                            <?php echo csrfField(); ?>
                                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            
                                            <button type="submit" class="flex-1 bg-slate-900 text-white py-3 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-black shadow-lg flex items-center justify-center gap-2">
                                                <i class="fas fa-shopping-bag"></i> <?php echo lang('btn_add'); ?>
                                            </button>
                                            
                                            <a href="<?php echo URLROOT; ?>/wishlist/toggle/<?php echo $product->id; ?>" class="w-10 bg-white text-slate-900 rounded-xl flex items-center justify-center hover:text-red-500 hover:bg-red-50 transition shadow-lg" title="Add to Wishlist">
                                                <i class="far fa-heart"></i>
                                            </a>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <div class="absolute inset-0 bg-white/60 flex items-center justify-center z-10">
                                        <span class="bg-slate-900 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-widest shadow-lg"><?php echo lang('out_of_stock'); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="relative z-0">
                                <p class="text-[10px] uppercase text-slate-400 font-bold tracking-wider mb-1">
                                    <?php echo $product->category_name ?? 'Collection'; ?>
                                </p>
                                
                                <h3 class="font-bold text-slate-900 text-sm md:text-base leading-tight truncate mb-1 group-hover:text-primary transition">
                                    <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $product->slug; ?>"><?php echo $pName; ?></a>
                                </h3>
                                
                                <div class="flex items-center gap-3">
                                    <?php if($product->promo_price > 0): ?>
                                        <span class="text-red-600 font-black text-base"><?php echo CURRENCY_SYMBOL . number_format($product->promo_price, 2); ?></span>
                                        <span class="text-xs text-slate-400 line-through font-medium"><?php echo number_format($product->price, 2); ?></span>
                                    <?php else: ?>
                                        <span class="text-slate-900 font-bold text-base"><?php echo CURRENCY_SYMBOL . number_format($product->price, 2); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
    const mobileBtn = document.getElementById('mobileFilterBtn');
    const closeBtn = document.getElementById('closeFilterBtn');
    const sidebar = document.getElementById('shopSidebar');
    const overlay = document.getElementById('filterOverlay');

    function toggleFilters() {
        if(!sidebar) return; 
        const isClosed = sidebar.classList.contains('-translate-x-full');
        if(isClosed) {
            sidebar.classList.remove('-translate-x-full');
            if(overlay) overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            if(overlay) overlay.classList.add('hidden');
        }
    }

    if(mobileBtn) mobileBtn.addEventListener('click', toggleFilters);
    if(closeBtn) closeBtn.addEventListener('click', toggleFilters);
    if(overlay) overlay.addEventListener('click', toggleFilters);
</script>

<style>
    /* Smooth animation for cards */
    .group:hover .group-hover\:translate-y-0 { transform: translateY(0); }
    .delay-100 { animation-delay: 0.1s; }
</style>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>