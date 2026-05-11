<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-6xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div class="flex items-center gap-4 w-full md:w-auto">
            <a href="<?php echo URLROOT; ?>/admin/products" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition shadow-sm group flex-shrink-0">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Product Details</h1>
                    
                    <?php if(isset($data['product']->status) && $data['product']->status == 'draft'): ?>
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-500 border border-slate-200">
                            Draft
                        </span>
                    <?php else: ?>
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-green-100 text-green-700 border border-green-200">
                            Active
                        </span>
                    <?php endif; ?>
                </div>
                <p class="text-xs text-slate-500 font-medium">Internal SKU: <span class="font-mono text-slate-600 bg-slate-100 px-1 rounded"><?php echo $data['product']->sku; ?></span></p>
            </div>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <a href="<?php echo URLROOT; ?>/shop/details/<?php echo $data['product']->slug; ?>" target="_blank" class="px-4 py-2 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-primary transition shadow-sm text-sm flex items-center gap-2 justify-center flex-1 md:flex-initial">
                <i class="fas fa-external-link-alt"></i> View in Store
            </a>
            <a href="<?php echo URLROOT; ?>/admin/products_edit/<?php echo $data['product']->id; ?>" class="px-4 py-2 bg-primary text-white font-bold rounded-xl hover:bg-indigo-600 transition shadow-lg shadow-primary/30 text-sm flex items-center gap-2 justify-center flex-1 md:flex-initial">
                <i class="fas fa-pen"></i> Edit Product
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="space-y-6">
            
            <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
                <div class="aspect-square rounded-xl overflow-hidden bg-slate-50 relative group">
                    <?php if(!empty($data['product']->image)): ?>
                        <img src="<?php echo URLROOT . '/public/img/' . $data['product']->image; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <i class="fas fa-image text-4xl opacity-50"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="absolute top-3 left-3 flex flex-col gap-2">
                        <?php if($data['product']->promo_price > 0): ?>
                            <span class="bg-red-500 text-white text-[10px] font-black uppercase px-2 py-1 rounded shadow-sm">Sale</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if(!empty($data['gallery'])): ?>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-[10px] font-bold uppercase text-slate-400 mb-3 tracking-widest border-b border-slate-50 pb-2">Gallery</h3>
                <div class="grid grid-cols-4 gap-2">
                    <?php foreach($data['gallery'] as $img): ?>
                        <div class="aspect-square rounded-lg overflow-hidden border border-slate-100 relative group cursor-pointer">
                            <img src="<?php echo URLROOT . '/public/img/' . $img->image; ?>" class="w-full h-full object-cover hover:opacity-90 transition">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-[10px] font-bold uppercase text-slate-400 mb-4 tracking-widest border-b border-slate-50 pb-2">Current Status</h3>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-50 border-dashed">
                    <span class="text-slate-600 text-sm font-medium">Global Stock</span>
                    <?php if($data['product']->stock > 5): ?>
                        <span class="bg-green-50 text-green-600 px-2.5 py-1 rounded text-xs font-bold border border-green-100"><?php echo $data['product']->stock; ?> Units</span>
                    <?php elseif($data['product']->stock > 0): ?>
                        <span class="bg-orange-50 text-orange-600 px-2.5 py-1 rounded text-xs font-bold border border-orange-100"><?php echo $data['product']->stock; ?> Low Stock</span>
                    <?php else: ?>
                        <span class="bg-red-50 text-red-600 px-2.5 py-1 rounded text-xs font-bold border border-red-100">Out of Stock</span>
                    <?php endif; ?>
                </div>

                <div class="flex justify-between items-center py-2 pt-3">
                    <span class="text-slate-600 text-sm font-medium">Selling Price</span>
                    <div class="text-right">
                        <?php if($data['product']->promo_price > 0): ?>
                            <span class="block font-black text-red-500 text-lg"><?php echo CURRENCY_SYMBOL . number_format($data['product']->promo_price, 2); ?></span>
                            <span class="text-xs line-through text-slate-400 font-medium"><?php echo CURRENCY_SYMBOL . number_format($data['product']->price, 2); ?></span>
                        <?php else: ?>
                            <span class="font-black text-slate-800 text-lg"><?php echo CURRENCY_SYMBOL . number_format($data['product']->price, 2); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute -top-4 -right-4 text-slate-50 opacity-50 rotate-12 pointer-events-none">
                    <i class="fas fa-file-alt text-9xl"></i>
                </div>

                <div class="relative z-10 mb-8">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest inline-block">
                            <?php echo $data['product']->category_name ?? 'Uncategorized'; ?>
                        </span>
                        <?php if(!empty($data['product']->type_name)): ?>
                            <span class="bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest inline-block">
                                <?php echo $data['product']->type_name; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-2 leading-tight"><?php echo $data['product']->name; ?></h2>
                </div>

                <div class="relative z-10 prose prose-sm text-slate-500 max-w-none">
                    <h3 class="text-slate-800 font-bold text-sm uppercase tracking-wide mb-2">Description</h3>
                    <p class="leading-relaxed"><?php echo nl2br($data['product']->description); ?></p>
                </div>

                <div class="relative z-10 grid grid-cols-2 gap-4 mt-8 pt-6 border-t border-slate-50">
                    <div>
                        <span class="block text-[10px] text-slate-400 font-bold uppercase mb-1">Gender</span>
                        <span class="font-bold text-slate-800 capitalize bg-slate-50 px-3 py-1.5 rounded-lg text-sm inline-block border border-slate-100">
                            <?php echo $data['product']->gender; ?>
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400 font-bold uppercase mb-1">Created At</span>
                        <span class="font-bold text-slate-800 bg-slate-50 px-3 py-1.5 rounded-lg text-sm inline-block border border-slate-100">
                            <?php echo date('M d, Y', strtotime($data['product']->created_at)); ?>
                        </span>
                    </div>
                </div>
            </div>

            <?php if(!empty($data['variants'])): ?>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-sm font-bold text-slate-800 mb-6 flex items-center gap-2 uppercase tracking-wide">
                    <i class="fas fa-cubes text-primary"></i> Product Variants
                </h3>
                
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold">
                            <tr>
                                <th class="p-4">Size</th>
                                <th class="p-4">Color</th>
                                <th class="p-4 text-center">Reference</th>
                                <th class="p-4 text-right">Stock Level</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php foreach($data['variants'] as $v): ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 font-bold text-slate-800"><?php echo $v->size; ?></td>
                                <td class="p-4 text-slate-600">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full border border-slate-200" style="background-color: <?php echo strtolower($v->color); ?>;"></span>
                                        <?php echo $v->color; ?>
                                    </span>
                                </td>
                                <td class="p-4 text-center font-mono text-xs text-slate-400">
                                    #VAR-<?php echo $v->id; ?>
                                </td>
                                <td class="p-4 text-right">
                                    <?php if($v->stock < 5): ?>
                                        <span class="text-orange-600 font-bold text-xs bg-orange-50 px-2 py-1 rounded border border-orange-100">
                                            <?php echo $v->stock; ?> (Low)
                                        </span>
                                    <?php else: ?>
                                        <span class="text-green-600 font-bold text-xs bg-green-50 px-2 py-1 rounded border border-green-100">
                                            <?php echo $v->stock; ?> Units
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>