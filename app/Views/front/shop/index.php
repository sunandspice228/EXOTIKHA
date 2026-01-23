<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-slate-50 py-10 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-slate-900">The Collection</h1>
            <p class="text-slate-500 text-sm mt-1">Discover our premium selection tailored for you.</p>
        </div>
        
        <button @click="mobileFiltersOpen = true" x-data="{ mobileFiltersOpen: false }" class="md:hidden flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg">
            <i class="fas fa-sliders-h"></i> Filters & Sort
        </button>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-8" x-data="{ mobileFiltersOpen: false }">
    <div class="flex flex-col md:flex-row gap-10">
        
        <aside class="hidden md:block w-64 flex-shrink-0">
            <form action="<?php echo URLROOT; ?>/shop" method="GET" id="filterForm">
                
                <div class="mb-6 border-b border-slate-100 pb-6" x-data="{ open: true }">
                    <button type="button" @click="open = !open" class="flex justify-between items-center w-full font-bold text-slate-800 mb-3">
                        <span>Special Offers</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-red-50 transition border border-transparent hover:border-red-100">
                            <input type="checkbox" name="promo" value="1" class="rounded border-slate-300 text-red-500 focus:ring-red-500" 
                                   <?php echo (!empty($data['filters']['promo_only'])) ? 'checked' : ''; ?> 
                                   onchange="this.form.submit()">
                            <span class="flex items-center gap-2 text-sm font-bold text-slate-700 group-hover:text-red-600">
                                <i class="fas fa-tag text-red-500"></i> On Sale
                            </span>
                        </label>
                    </div>
                </div>

                <div class="mb-6 border-b border-slate-100 pb-6" x-data="{ open: true }">
                    <button type="button" @click="open = !open" class="flex justify-between items-center w-full font-bold text-slate-800 mb-3">
                        <span>Gender</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="gender" value="" class="hidden" <?php echo empty($data['filters']['gender']) ? 'checked' : ''; ?> onchange="this.form.submit()">
                            <span class="w-4 h-4 border border-slate-300 rounded-full flex items-center justify-center group-hover:border-accent">
                                <?php if(empty($data['filters']['gender'])): ?><div class="w-2 h-2 bg-accent rounded-full"></div><?php endif; ?>
                            </span>
                            <span class="text-sm text-slate-600 group-hover:text-accent <?php echo empty($data['filters']['gender']) ? 'font-bold' : ''; ?>">All Genders</span>
                        </label>
                        
                        <?php foreach(['women', 'men', 'kids'] as $g): ?>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="gender" value="<?php echo $g; ?>" class="hidden" <?php echo ($data['filters']['gender'] == $g) ? 'checked' : ''; ?> onchange="this.form.submit()">
                            <span class="w-4 h-4 border border-slate-300 rounded-full flex items-center justify-center group-hover:border-accent">
                                <?php if($data['filters']['gender'] == $g): ?><div class="w-2 h-2 bg-accent rounded-full"></div><?php endif; ?>
                            </span>
                            <span class="text-sm text-slate-600 group-hover:text-accent capitalize <?php echo ($data['filters']['gender'] == $g) ? 'font-bold' : ''; ?>"><?php echo $g; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-6 border-b border-slate-100 pb-6" x-data="{ open: true }">
                    <button type="button" @click="open = !open" class="flex justify-between items-center w-full font-bold text-slate-800 mb-3">
                        <span>Categories</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" class="space-y-2">
                         <label class="flex items-center gap-3 cursor-pointer group">
                             <input type="radio" name="category" value="" class="hidden" <?php echo empty($data['filters']['category_id']) ? 'checked' : ''; ?> onchange="this.form.submit()">
                             <span class="text-sm text-slate-600 group-hover:text-accent">All Categories</span>
                         </label>
                        <?php foreach($data['categories'] as $cat): ?>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="category" value="<?php echo $cat->id; ?>" class="hidden" <?php echo ($data['filters']['category_id'] == $cat->id) ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <span class="text-sm text-slate-600 group-hover:text-accent <?php echo ($data['filters']['category_id'] == $cat->id) ? 'font-bold text-accent' : ''; ?>">
                                    <?php echo $cat->name; ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-6 border-b border-slate-100 pb-6" x-data="{ open: true }">
                    <button type="button" @click="open = !open" class="flex justify-between items-center w-full font-bold text-slate-800 mb-3">
                        <span>Product Type</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" class="space-y-2 max-h-48 overflow-y-auto scrollbar-hide">
                        <?php foreach($data['types'] as $type): ?>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="types[]" value="<?php echo $type->id; ?>" 
                                       class="rounded border-slate-300 text-accent focus:ring-accent"
                                       <?php echo (in_array($type->id, $data['filters']['types'])) ? 'checked' : ''; ?>
                                       onchange="this.form.submit()">
                                <span class="text-sm text-slate-600 group-hover:text-accent"><?php echo $type->name; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-6 border-b border-slate-100 pb-6" x-data="{ min: <?php echo $data['filters']['price_min']; ?>, max: <?php echo $data['filters']['price_max']; ?> }">
                    <button type="button" class="flex justify-between items-center w-full font-bold text-slate-800 mb-3">
                        <span>Price Range</span>
                    </button>
                    <div class="px-2">
                        <div class="flex justify-between text-xs text-slate-500 mb-2">
                            <span><?php echo CURRENCY_SYMBOL; ?> <span x-text="min"></span></span>
                            <span><?php echo CURRENCY_SYMBOL; ?> <span x-text="max"></span></span>
                        </div>
                        <input type="range" name="max_price" min="0" max="5000" step="50" x-model="max" class="w-full accent-primary h-1 bg-slate-200 rounded-lg appearance-none cursor-pointer" onchange="this.form.submit()">
                    </div>
                </div>

                <a href="<?php echo URLROOT; ?>/shop" class="w-full block text-center py-2 text-xs font-bold text-slate-400 hover:text-red-500 transition border border-dashed border-slate-300 rounded hover:border-red-300">
                    Reset All Filters
                </a>

                <input type="hidden" name="sort" value="<?php echo $data['filters']['sort']; ?>">

            </form>
        </aside>

        <div class="flex-1">
            
            <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                <div class="text-sm text-slate-500">
                    Found <span class="font-bold text-slate-900"><?php echo count($data['products']); ?></span> items
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500 uppercase hidden sm:inline">Sort by:</span>
                    <select onchange="document.getElementById('filterForm').elements['sort'].value = this.value; document.getElementById('filterForm').submit();" class="border-none text-sm font-bold text-slate-700 focus:ring-0 bg-transparent cursor-pointer py-0 pl-2 pr-8">
                        <option value="newest" <?php echo ($data['filters']['sort'] == 'newest') ? 'selected' : ''; ?>>Newest First</option>
                        <option value="price_asc" <?php echo ($data['filters']['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo ($data['filters']['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <?php if(empty($data['products'])): ?>
                <div class="flex flex-col items-center justify-center py-20 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                        <i class="fas fa-search text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">No products found</h3>
                    <p class="text-slate-500 text-sm">Try removing some filters to see more results.</p>
                    <a href="<?php echo URLROOT; ?>/shop" class="mt-4 text-accent font-bold text-sm hover:underline">Clear all filters</a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach($data['products'] as $product): ?>
                        
                        <div class="group bg-white rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 border border-slate-100">
                            <div class="relative h-[320px] bg-slate-100 overflow-hidden">
                                
                                <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                                    <?php if(!empty($product->promo_price)): ?>
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">SALE</span>
                                    <?php endif; ?>
                                    <?php if($product->stock < 5): ?>
                                        <span class="bg-orange-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">LOW STOCK</span>
                                    <?php endif; ?>
                                </div>

                                <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>">
                                    <?php if(!empty($product->image)): ?>
                                        <img src="<?php echo URLROOT . '/public/' . $product->image; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-camera text-4xl"></i></div>
                                    <?php endif; ?>
                                </a>

                                <div class="absolute bottom-4 right-4 translate-y-full group-hover:translate-y-0 transition duration-300 z-20">
                                     <form action="<?php echo URLROOT; ?>/cart/add" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                        <button class="bg-white hover:bg-primary hover:text-white text-slate-900 w-10 h-10 rounded-full shadow-lg flex items-center justify-center transition-colors">
                                            <i class="fas fa-shopping-bag"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="text-[10px] text-slate-400 uppercase tracking-widest font-bold"><?php echo $product->category_name; ?></div>
                                    <?php if(!empty($product->type_name)): ?>
                                        <div class="text-[10px] text-slate-400 uppercase"><?php echo $product->type_name; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="font-bold text-slate-900 truncate mb-2">
                                    <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" class="group-hover:text-accent transition"><?php echo $product->name; ?></a>
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
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>