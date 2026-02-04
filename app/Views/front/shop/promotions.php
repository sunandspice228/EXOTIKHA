<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php
// Helper langue
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>

<div class="bg-red-600 text-white relative overflow-hidden py-16 md:py-20">
    <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <span class="inline-block py-1 px-3 border border-white/30 rounded-full text-xs font-black uppercase tracking-[0.2em] mb-4 animate-pulse">
            <?php echo lang('promo_badge_limited'); ?>
        </span>
        <h1 class="text-5xl md:text-7xl font-serif font-bold mb-6 italic">
            <?php echo lang('promo_hero_title'); ?>
        </h1>
        <p class="text-red-100 text-lg mb-8 max-w-2xl mx-auto">
            <?php echo lang('promo_hero_text'); ?>
        </p>
        
        <div class="flex justify-center gap-4 md:gap-8 mb-8" id="countdown">
            <div class="text-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur rounded-xl flex items-center justify-center text-2xl md:text-4xl font-black shadow-lg border border-white/20" id="hours">00</div>
                <span class="text-[10px] uppercase font-bold mt-2 block opacity-80"><?php echo lang('time_h'); ?></span>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur rounded-xl flex items-center justify-center text-2xl md:text-4xl font-black shadow-lg border border-white/20" id="minutes">00</div>
                <span class="text-[10px] uppercase font-bold mt-2 block opacity-80"><?php echo lang('time_m'); ?></span>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white text-red-600 rounded-xl flex items-center justify-center text-2xl md:text-4xl font-black shadow-lg" id="seconds">00</div>
                <span class="text-[10px] uppercase font-bold mt-2 block opacity-80"><?php echo lang('time_s'); ?></span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-12 -mt-10 relative z-20">
    
    <?php if($data['star_product']): ?>
        <?php 
            $sp = $data['star_product'];
            $spName = ($lang == 'fr' && !empty($sp->name_fr)) ? $sp->name_fr : $sp->name;
            $spDesc = ($lang == 'fr' && !empty($sp->description_fr)) ? $sp->description_fr : $sp->description;
            $spImg = !empty($sp->image) ? URLROOT . '/uploads/' . $sp->image : URLROOT . '/img/no-image.jpg';
        ?>
        <div class="bg-white rounded-3xl p-6 md:p-12 shadow-2xl border border-slate-100 mb-16 flex flex-col md:flex-row items-center gap-12 relative overflow-hidden group">
            
            <div class="absolute -top-12 -left-12 w-40 h-40 bg-red-600 text-white flex items-center justify-center transform -rotate-12 shadow-lg z-20">
                <span class="text-3xl font-black mt-8 ml-4">-<?php echo $data['max_percent']; ?>%</span>
            </div>

            <div class="w-full md:w-1/2 relative z-10">
                <div class="aspect-square bg-slate-50 rounded-2xl overflow-hidden relative">
                    <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $sp->slug; ?>">
                        <img src="<?php echo $spImg; ?>" alt="<?php echo $spName; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    </a>
                </div>
            </div>

            <div class="w-full md:w-1/2 text-center md:text-left">
                <h3 class="text-red-600 font-bold uppercase tracking-widest text-sm mb-2"><?php echo lang('promo_deal_day'); ?></h3>
                <h2 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 mb-4">
                    <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $sp->slug; ?>" class="hover:text-red-600 transition"><?php echo $spName; ?></a>
                </h2>
                <p class="text-slate-500 mb-8 leading-relaxed line-clamp-3">
                    <?php echo $spDesc; ?>
                </p>
                
                <div class="flex items-center justify-center md:justify-start gap-6 mb-8">
                    <div class="text-center">
                        <span class="block text-xs text-slate-400 line-through"><?php echo lang('promo_original_price'); ?></span>
                        <span class="text-2xl text-slate-400 line-through decoration-red-500"><?php echo CURRENCY_SYMBOL . number_format($sp->price, 2); ?></span>
                    </div>
                    <div class="text-center">
                        <span class="block text-xs text-red-600 font-bold uppercase"><?php echo lang('promo_flash_price'); ?></span>
                        <span class="text-5xl font-black text-slate-900"><?php echo CURRENCY_SYMBOL . number_format($sp->promo_price, 2); ?></span>
                    </div>
                </div>

                <form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="flex gap-4 max-w-md mx-auto md:mx-0">
                    <?php echo isset($_SESSION['csrf_token']) ? csrfField() : ''; ?>    
                    <input type="hidden" name="product_id" value="<?php echo $sp->id; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="flex-1 bg-red-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-red-700 transition shadow-lg shadow-red-500/30 flex items-center justify-center gap-2 animate-bounce-subtle">
                        <i class="fas fa-shopping-bag"></i> <?php echo lang('btn_grab_deal'); ?>
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex items-end justify-between mb-8">
        <h2 class="text-2xl font-bold text-slate-900"><?php echo lang('promo_all_title'); ?></h2>
        <span class="text-sm text-slate-500 font-bold"><?php echo count($data['products']); ?> <?php echo lang('promo_items_count'); ?></span>
    </div>

    <?php if(empty($data['products'])): ?>
        <p class="text-center py-20 text-slate-400 italic"><?php echo lang('promo_empty'); ?></p>
    <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach($data['products'] as $product): ?>
                <?php 
                    $pName = ($lang == 'fr' && !empty($product->name_fr)) ? $product->name_fr : $product->name;
                    $pImg = !empty($product->image) ? URLROOT . '/uploads/' . $product->image : URLROOT . '/img/no-image.jpg';
                ?>
                <div class="group bg-white rounded-2xl p-4 border border-slate-100 hover:border-red-200 hover:shadow-xl transition duration-300 relative">
                    <div class="relative overflow-hidden rounded-xl bg-slate-50 aspect-[3/4] mb-4">
                        
                        <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md z-10">
                            -<?php echo round((($product->price - $product->promo_price)/$product->price)*100); ?>%
                        </span>
                        
                        <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $product->slug; ?>">
                            <img src="<?php echo $pImg; ?>" alt="<?php echo $pName; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </a>

                        <?php if($product->stock > 0): ?>
                        <form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="absolute bottom-3 right-3 translate-y-12 group-hover:translate-y-0 transition duration-300 z-20">
                            <?php echo isset($_SESSION['csrf_token']) ? csrfField() : ''; ?>    
                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button class="w-10 h-10 bg-white text-red-600 rounded-full flex items-center justify-center shadow-md hover:bg-red-600 hover:text-white transition" title="<?php echo lang('btn_add_cart'); ?>">
                                <i class="fas fa-plus"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>

                    <h3 class="font-bold text-slate-900 text-sm truncate mb-1">
                        <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $product->slug; ?>"><?php echo $pName; ?></a>
                    </h3>
                    
                    <div class="flex items-center gap-2">
                        <span class="text-red-600 font-bold text-lg"><?php echo CURRENCY_SYMBOL . number_format($product->promo_price, 2); ?></span>
                        <span class="text-xs text-slate-400 line-through"><?php echo number_format($product->price, 2); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
</div>

<script>
    // Sets promo end time to next midnight (Example logic)
    const now = new Date();
    const endTime = new Date(now);
    endTime.setHours(24, 0, 0, 0); 

    function updateCountdown() {
        const currentTime = new Date();
        const diff = endTime - currentTime;

        if (diff <= 0) return;

        const h = Math.floor((diff / (1000 * 60 * 60)) % 24);
        const m = Math.floor((diff / (1000 * 60)) % 60);
        const s = Math.floor((diff / 1000) % 60);

        document.getElementById('hours').innerText = h < 10 ? '0' + h : h;
        document.getElementById('minutes').innerText = m < 10 ? '0' + m : m;
        document.getElementById('seconds').innerText = s < 10 ? '0' + s : s;
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();
</script>

<style>
    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }
    .animate-bounce-subtle { animation: bounce-subtle 2s infinite; }
</style>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>