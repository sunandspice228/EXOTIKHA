<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Product Catalog</h1>
            <p class="text-slate-500 mt-1">Manage inventory, pricing, and visual assets.</p>
        </div>
        
        <a href="<?php echo URLROOT; ?>/admin/products_add" 
           class="bg-primary hover:bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold transition shadow-lg shadow-primary/30 flex items-center gap-2 transform hover:-translate-y-1">
            <i class="fas fa-plus"></i> 
            <span>Add New Product</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Product</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Category</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Price</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Stock Status</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['products'])): ?>
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-box-open text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="text-lg font-bold text-slate-600">Your catalog is empty.</p>
                                    <p class="text-sm text-slate-400 mb-4">Start by adding your first product to the store.</p>
                                    <a href="<?php echo URLROOT; ?>/admin/products_add" class="text-primary font-bold hover:underline text-sm">Add Product Now</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['products'] as $p): ?>
                        <tr class="hover:bg-slate-50 transition group">
                            
                            <td class="p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-200 bg-white flex-shrink-0 relative group-hover:shadow-md transition">
                                        <?php if(!empty($p->image)): ?>
                                            <img src="<?php echo URLROOT . '/public/img/' . $p->image; ?>" 
                                                 alt="<?php echo $p->name; ?>" 
                                                 class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-300">
                                                <i class="fas fa-image text-xl"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <h3 class="font-bold text-slate-800 text-sm md:text-base leading-tight hover:text-primary transition cursor-pointer" onclick="window.location='<?php echo URLROOT; ?>/admin/products_show/<?php echo $p->id; ?>'">
                                            <?php echo $p->name; ?>
                                        </h3>
                                        <p class="text-[10px] text-slate-400 font-mono mt-1 font-bold uppercase tracking-wide">
                                            SKU: <span class="select-all text-slate-500"><?php echo $p->sku; ?></span>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="p-5">
                                <div class="flex flex-col items-start gap-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide bg-slate-100 text-slate-600 border border-slate-200">
                                        <?php echo $p->category_name ?? 'Uncategorized'; ?>
                                    </span>
                                    <?php if(!empty($p->type_name)): ?>
                                        <span class="text-[10px] text-slate-400 ml-1 font-medium">
                                            <?php echo $p->type_name; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="p-5 font-bold text-slate-800">
                                <?php if($p->promo_price > 0): ?>
                                    <div class="flex flex-col">
                                        <span class="text-red-500"><?php echo CURRENCY_SYMBOL . number_format($p->promo_price, 2); ?></span>
                                        <span class="text-[10px] text-slate-400 line-through font-medium">
                                            <?php echo CURRENCY_SYMBOL . number_format($p->price, 2); ?>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <span><?php echo CURRENCY_SYMBOL . number_format($p->price, 2); ?></span>
                                <?php endif; ?>
                            </td>

                            <td class="p-5">
                                <?php if($p->stock <= 0): ?>
                                    <div class="flex items-center gap-2 text-red-600 bg-red-50 px-3 py-1.5 rounded-lg w-fit border border-red-100">
                                        <i class="fas fa-times-circle text-xs"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wide">Out of Stock</span>
                                    </div>
                                <?php elseif($p->stock < 5): ?>
                                    <div class="flex items-center gap-2 text-orange-600 bg-orange-50 px-3 py-1.5 rounded-lg w-fit border border-orange-100">
                                        <i class="fas fa-exclamation-triangle text-xs"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wide">Low (<?php echo $p->stock; ?>)</span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center gap-2 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg w-fit border border-emerald-100">
                                        <i class="fas fa-check-circle text-xs"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wide"><?php echo $p->stock; ?> In Stock</span>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td class="p-5 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition duration-200">
                                    
                                    <a href="<?php echo URLROOT; ?>/admin/products_show/<?php echo $p->id; ?>" 
                                       class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-800 transition" 
                                       title="View Details">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    <a href="<?php echo URLROOT; ?>/admin/products_edit/<?php echo $p->id; ?>" 
                                       class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-primary transition"
                                       title="Edit Product">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>

                                    <a href="<?php echo URLROOT; ?>/admin/products_delete/<?php echo $p->id; ?>" 
                                       class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-red-500 transition"
                                       onclick="return confirm('Warning: Deleting this product will remove all variants and images permanently. Continue?');"
                                       title="Delete Product">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </a>

                                </div>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center text-xs text-slate-500 font-medium">
            <span>Showing <strong><?php echo count($data['products']); ?></strong> items</span>
            <div class="flex gap-2">
                <button class="px-3 py-1 bg-white border border-slate-200 rounded hover:bg-slate-100 disabled:opacity-50" disabled>Previous</button>
                <button class="px-3 py-1 bg-white border border-slate-200 rounded hover:bg-slate-100 disabled:opacity-50" disabled>Next</button>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>