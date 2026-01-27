<form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="mt-8 space-y-8">
<?php echo csrfField(); ?>    
<input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
    
    <?php if(!empty($data['variants'])): ?>
        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Select Option</h3>
                <a href="#" class="text-xs text-slate-400 underline hover:text-accent">Size Guide</a>
            </div>
            
            <div class="grid grid-cols-3 sm:grid-cols-4 gap-4">
                <?php foreach($data['variants'] as $variant): ?>
                    <label class="cursor-pointer relative group">
                        <input type="radio" name="variant_id" value="<?php echo $variant->id; ?>" class="peer sr-only" required>
                        <div class="rounded-xl border-2 border-slate-100 p-3 text-center transition-all duration-200 hover:border-slate-300 peer-checked:border-accent peer-checked:bg-accent/5 peer-checked:text-accent shadow-sm">
                            <span class="block font-bold text-sm mb-1"><?php echo $variant->size; ?></span>
                            <span class="block text-[10px] text-slate-500 uppercase tracking-wider peer-checked:text-accent/80"><?php echo $variant->color; ?></span>
                        </div>
                        <div class="absolute top-2 right-2 text-accent opacity-0 peer-checked:opacity-100 transition-opacity">
                            <i class="fas fa-check-circle text-xs"></i>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

    <?php else: ?>
        <div>
             <span class="inline-flex items-center gap-2 bg-green-50 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full border border-green-100">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                In Stock (<?php echo $data['product']->stock; ?> available)
             </span>
        </div>
    <?php endif; ?>

    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-100">
        
        <div class="w-full sm:w-36">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Quantity</label>
            <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden h-12 bg-slate-50">
                <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="w-10 h-full flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition">
                    <i class="fas fa-minus text-xs"></i>
                </button>
                <input type="number" name="quantity" value="1" min="1" max="<?php echo $data['product']->stock; ?>" class="w-full h-full text-center border-none focus:ring-0 text-slate-900 font-bold bg-transparent text-lg appearance-none">
                <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="w-10 h-full flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition">
                    <i class="fas fa-plus text-xs"></i>
                </button>
            </div>
        </div>
        
        <button type="submit" class="flex-1 bg-slate-900 text-white h-12 sm:h-auto mt-auto rounded-xl font-bold shadow-xl shadow-slate-900/20 hover:bg-accent hover:shadow-accent/30 transition-all duration-300 flex justify-center items-center gap-3 uppercase tracking-widest text-sm group">
            <i class="fas fa-shopping-bag group-hover:-translate-y-0.5 transition-transform"></i> Add to Cart
        </button>

        <a href="<?php echo URLROOT; ?>/wishlist/toggle/<?php echo $data['product']->id; ?>" class="w-12 h-12 sm:h-auto mt-auto border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition shadow-sm" title="Add to WishlistModel">
            <i class="far fa-heart text-xl"></i>
        </a>
    </div>
</form>