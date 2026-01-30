<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-red-600 text-white relative overflow-hidden py-16 md:py-20">
    <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <span class="inline-block py-1 px-3 border border-white/30 rounded-full text-xs font-black uppercase tracking-[0.2em] mb-4 animate-pulse">Limited Offer</span>
        <h1 class="text-5xl md:text-7xl font-serif font-bold mb-6 italic">Flash Sales</h1>
        <p class="text-red-100 text-lg mb-8 max-w-2xl mx-auto">Enjoy exceptional discounts on our exclusive collections. Hurry, stocks are running out fast!</p>
        
        <div class="flex justify-center gap-4 md:gap-8 mb-8" id="countdown">
            <div class="text-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur rounded-xl flex items-center justify-center text-2xl md:text-4xl font-black shadow-lg border border-white/20" id="hours">00</div>
                <span class="text-[10px] uppercase font-bold mt-2 block opacity-80">Hours</span>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur rounded-xl flex items-center justify-center text-2xl md:text-4xl font-black shadow-lg border border-white/20" id="minutes">00</div>
                <span class="text-[10px] uppercase font-bold mt-2 block opacity-80">Minutes</span>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white text-red-600 rounded-xl flex items-center justify-center text-2xl md:text-4xl font-black shadow-lg" id="seconds">00</div>
                <span class="text-[10px] uppercase font-bold mt-2 block opacity-80">Seconds</span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-12 -mt-10 relative z-20">
    
    <?php if($data['star_product']): ?>
    <div class="bg-white rounded-3xl p-6 md:p-12 shadow-2xl border border-slate-100 mb-16 flex flex-col md:flex-row items-center gap-12 relative overflow-hidden group">
        <div class="absolute -top-12 -left-12 w-40 h-40 bg-red-600 text-white flex items-center justify-center transform -rotate-12 shadow-lg z-20">
            <span class="text-3xl font-black mt-8 ml-4">-<?php echo $data['max_percent']; ?>%</span>
        </div>

        <div class="w-full md:w-1/2 relative z-10">
            <div class="aspect-square bg-slate-50 rounded-2xl overflow-hidden relative">
                <?php if(!empty($data['star_product']->image)): ?>
                    <img src="<?php echo URLROOT . '/img/' . $data['star_product']->image; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                <?php endif; ?>
            </div>
        </div>

        <div class="w-full md:w-1/2 text-center md:text-left">
            <h3 class="text-red-600 font-bold uppercase tracking-widest text-sm mb-2">Deal of the Day</h3>
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 mb-4"><?php echo $data['star_product']->name; ?></h2>
            <p class="text-slate-500 mb-8 leading-relaxed line-clamp-3">
                <?php echo $data['star_product']->description; ?>
            </p>
            
            <div class="flex items-center justify-center md:justify-start gap-6 mb-8">
                <div class="text-center">
                    <span class="block text-xs text-slate-400 line-through">Original Price</span>
                    <span class="text-2xl text-slate-400 line-through decoration-red-500"><?php echo CURRENCY_SYMBOL . number_format($data['star_product']->price, 2); ?></span>
                </div>
                <div class="text-center">
                    <span class="block text-xs text-red-600 font-bold uppercase">Flash Price</span>
                    <span class="text-5xl font-black text-slate-900"><?php echo CURRENCY_SYMBOL . number_format($data['star_product']->promo_price, 2); ?></span>
                </div>
            </div>

            <form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="flex gap-4 max-w-md mx-auto md:mx-0">
                <?php echo csrfField(); ?>    
                <input type="hidden" name="product_id" value="<?php echo $data['star_product']->id; ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="flex-1 bg-red-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-red-700 transition shadow-lg shadow-red-500/30 flex items-center justify-center gap-2 animate-bounce-subtle">
                    <i class="fas fa-shopping-bag"></i> Grab Deal
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="flex items-end justify-between mb-8">
        <h2 class="text-2xl font-bold text-slate-900">All Special Offers</h2>
        <span class="text-sm text-slate-500 font-bold"><?php echo count($data['products']); ?> items on sale</span>
    </div>

    <?php if(empty($data['products'])): ?>
        <p class="text-center py-20 text-slate-400 italic">No active promotions at the moment. Please come back soon!</p>
    <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach($data['products'] as $product): ?>
                <div class="group bg-white rounded-2xl p-4 border border-slate-100 hover:border-red-200 hover:shadow-xl transition duration-300">
                    <div class="relative overflow-hidden rounded-xl bg-slate-50 aspect-[3/4] mb-4">
                        
                        <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md z-10">
                            -<?php echo round((($product->price - $product->promo_price)/$product->price)*100); ?>%
                        </span>
                        
                        <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $product->slug; ?>">
                            <?php if(!empty($product->image)): ?>
                                <img src="<?php echo URLROOT . '/img/' . $product->image; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-image text-2xl"></i></div>
                            <?php endif; ?>
                        </a>

                        <?php if($product->stock > 0): ?>
                        <form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="absolute bottom-3 right-3 translate-y-12 group-hover:translate-y-0 transition duration-300">
                            <?php echo csrfField(); ?>    
                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button class="w-10 h-10 bg-white text-red-600 rounded-full flex items-center justify-center shadow-md hover:bg-red-600 hover:text-white transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>

                    <h3 class="font-bold text-slate-900 text-sm truncate mb-1"><?php echo $product->name; ?></h3>
                    
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
    // Sets promo end time to midnight tonight
    const now = new Date();
    const endTime = new Date(now);
    endTime.setHours(24, 0, 0, 0); // Next Midnight

    function updateCountdown() {
        const currentTime = new Date();
        const diff = endTime - currentTime;

        if (diff <= 0) {
            // End of counter -> Reset or hide
            return; 
        }

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