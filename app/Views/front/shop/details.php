<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php
// SÉCURITÉ : Vérification si produit existe
if(empty($data['product'])){
    echo '<div class="max-w-7xl mx-auto px-6 py-24 text-center text-red-500 font-bold">Produit introuvable.</div>';
    require APPROOT . '/Views/front/layout/footer.php';
    exit;
}

// Helper langue
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

// --- DONNÉES PRODUIT ---
$pName = ($lang == 'fr' && !empty($data['product']->name_fr)) ? $data['product']->name_fr : $data['product']->name;
$pDesc = ($lang == 'fr' && !empty($data['product']->description_fr)) ? $data['product']->description_fr : $data['product']->description;
$mainImg = !empty($data['product']->image) ? URLROOT . '/uploads/' . $data['product']->image : URLROOT . '/img/no-image.jpg';

// Galerie (Si dispo dans $data['gallery'])
$gallery = isset($data['gallery']) ? $data['gallery'] : [];
?>

<div class="bg-white border-b border-slate-100 py-4">
    <div class="max-w-7xl mx-auto px-6 text-xs font-bold uppercase tracking-widest text-slate-400">
        <a href="<?php echo URLROOT; ?>" class="hover:text-primary"><?php echo lang('nav_home'); ?></a>
        <span class="mx-2">/</span>
        <a href="<?php echo URLROOT; ?>/shop" class="hover:text-primary"><?php echo lang('nav_shop'); ?></a>
        <span class="mx-2">/</span>
        <span class="text-slate-900 truncate max-w-[200px] inline-block align-top"><?php echo $pName; ?></span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
        
        <div class="space-y-4" x-data="{ mainImage: '<?php echo $mainImg; ?>' }">
            <div class="relative aspect-[3/4] bg-slate-50 rounded-2xl overflow-hidden shadow-sm group">
                
                <?php if($data['product']->promo_price > 0): ?>
                    <span class="absolute top-4 left-4 bg-red-600 text-white text-xs font-black uppercase px-3 py-1.5 rounded z-10 shadow-md">
                        <?php echo lang('badge_sale'); ?>
                    </span>
                <?php endif; ?>
                
                <img :src="mainImage" alt="<?php echo $pName; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 cursor-zoom-in">
            </div>
            
            <div class="grid grid-cols-4 gap-4">
                <div @click="mainImage = '<?php echo $mainImg; ?>'" class="aspect-square rounded-xl bg-slate-100 overflow-hidden border-2 cursor-pointer transition" :class="mainImage === '<?php echo $mainImg; ?>' ? 'border-slate-900' : 'border-transparent hover:border-slate-300'">
                    <img src="<?php echo $mainImg; ?>" class="w-full h-full object-cover">
                </div>

                <?php if(!empty($gallery)): ?>
                    <?php foreach($gallery as $img): ?>
                        <?php $gUrl = URLROOT . '/uploads/' . $img->image; ?>
                        <div @click="mainImage = '<?php echo $gUrl; ?>'" class="aspect-square rounded-xl bg-slate-100 overflow-hidden border-2 cursor-pointer transition" :class="mainImage === '<?php echo $gUrl; ?>' ? 'border-slate-900' : 'border-transparent hover:border-slate-300'">
                            <img src="<?php echo $gUrl; ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="lg:sticky lg:top-32 h-fit">
            
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-slate-900 mb-4"><?php echo $pName; ?></h1>
            
            <div class="flex items-center gap-4 mb-6">
                <?php if($data['product']->promo_price > 0): ?>
                    <span class="text-3xl font-bold text-red-600"><?php echo CURRENCY_SYMBOL . number_format($data['product']->promo_price, 2); ?></span>
                    <span class="text-xl text-slate-400 line-through"><?php echo number_format($data['product']->price, 2); ?></span>
                    <span class="bg-red-50 text-red-600 text-xs font-bold px-2 py-1 rounded">
                        -<?php echo round((($data['product']->price - $data['product']->promo_price)/$data['product']->price)*100); ?>%
                    </span>
                <?php else: ?>
                    <span class="text-3xl font-bold text-slate-900"><?php echo CURRENCY_SYMBOL . number_format($data['product']->price, 2); ?></span>
                <?php endif; ?>
            </div>

            <div class="flex items-center gap-2 mb-8">
                <div class="flex text-yellow-400 text-sm">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <span class="text-sm font-bold text-slate-500 underline cursor-pointer">4.8 (12 <?php echo lang('reviews_title'); ?>)</span>
            </div>

            <div class="prose prose-slate text-slate-600 leading-relaxed mb-8 text-lg">
                <?php echo nl2br($pDesc); ?>
            </div>

            <?php if($data['product']->stock > 0): ?>
            <form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="space-y-8">
                <?php if(function_exists('csrfField')) echo csrfField(); ?>    
                <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
                
                <?php if(!empty($data['variants'])): ?>
                    <div class="space-y-4">
                        <label class="text-sm font-bold uppercase tracking-wide text-slate-900"><?php echo lang('product_select_option'); ?></label>
                        <div class="flex flex-wrap gap-3">
                            <?php foreach($data['variants'] as $variant): ?>
                                <label class="cursor-pointer">
                                    <input type="radio" name="variant_id" value="<?php echo $variant->id; ?>" class="peer sr-only" <?php echo ($variant->stock <= 0) ? 'disabled' : ''; ?>>
                                    <div class="px-4 py-2 border-2 border-slate-200 rounded-lg peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white hover:border-slate-400 transition font-bold text-sm <?php echo ($variant->stock <= 0) ? 'opacity-50 cursor-not-allowed bg-slate-50' : ''; ?>">
                                        <?php echo $variant->size; ?> 
                                        <?php if(!empty($variant->color)) echo ' - ' . $variant->color; ?>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex items-center gap-6">
                    <span class="text-sm font-bold uppercase tracking-wide text-slate-900"><?php echo lang('product_qty'); ?></span>
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
                        <span class="w-2 h-2 rounded-full bg-green-500"></span> <?php echo lang('product_in_stock'); ?>
                    </span>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-slate-900 text-white py-4 rounded-xl font-bold uppercase tracking-widest hover:bg-primary transition shadow-xl shadow-slate-900/20 flex items-center justify-center gap-3 group">
                        <i class="fas fa-shopping-bag group-hover:-translate-y-1 transition"></i> <?php echo lang('btn_add_cart'); ?>
                    </button>
                    <a href="<?php echo URLROOT; ?>/wishlist/toggle/<?php echo $data['product']->id; ?>" class="w-16 h-16 border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition shadow-sm" title="Add to Wishlist">
                        <i class="far fa-heart text-2xl"></i>
                    </a>
                </div>
            </form>
            
            <?php else: ?>
                <div class="bg-red-50 border border-red-100 p-6 rounded-xl text-center">
                    <p class="text-red-600 font-bold uppercase tracking-widest text-sm"><?php echo lang('out_of_stock'); ?></p>
                    <p class="text-sm text-red-400 mt-2"><?php echo lang('product_unavailable'); ?></p>
                </div>
            <?php endif; ?>

            <div class="mt-12 border-t border-slate-100" x-data="{ active: 1 }">
                <div class="border-b border-slate-100">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full py-5 flex justify-between items-center text-left font-bold text-slate-900 hover:text-primary transition">
                        <span><?php echo lang('product_details_tab'); ?></span>
                        <i class="fas fa-chevron-down transition-transform" :class="active === 1 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="active === 1" x-collapse class="pb-6 text-sm text-slate-600 leading-relaxed">
                        <ul class="list-disc pl-5 space-y-1">
                            <li><?php echo lang('product_feature_1'); ?></li>
                            <li><?php echo lang('product_feature_2'); ?></li>
                            <li><?php echo lang('product_feature_3'); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="border-b border-slate-100">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full py-5 flex justify-between items-center text-left font-bold text-slate-900 hover:text-primary transition">
                        <span><?php echo lang('product_shipping_tab'); ?></span>
                        <i class="fas fa-chevron-down transition-transform" :class="active === 2 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="active === 2" x-collapse class="pb-6 text-sm text-slate-600 leading-relaxed">
                        <p class="mb-2"><?php echo lang('product_shipping_info'); ?></p>
                        <p><?php echo lang('product_return_info'); ?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php if(!empty($data['related'])): ?>
    <div class="mt-24 border-t border-slate-100 pt-16">
        <h2 class="text-2xl font-serif font-bold text-slate-900 mb-8"><?php echo lang('product_related_title'); ?></h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            <?php foreach($data['related'] as $item): ?>
                <?php 
                    if($item->id != $data['product']->id): 
                        // Traduction item lié
                        $rName = ($lang == 'fr' && !empty($item->name_fr)) ? $item->name_fr : $item->name;
                        $rImg = !empty($item->image) ? URLROOT . '/uploads/' . $item->image : URLROOT . '/img/no-image.jpg';
                ?>
                <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $item->slug; ?>" class="group block">
                    <div class="aspect-[3/4] bg-slate-100 rounded-xl overflow-hidden mb-4 relative">
                         <img src="<?php echo $rImg; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <?php if($item->promo_price > 0): ?>
                            <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded"><?php echo lang('badge_sale'); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="font-bold text-slate-900 text-sm truncate group-hover:text-primary transition"><?php echo $rName; ?></h3>
                    
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