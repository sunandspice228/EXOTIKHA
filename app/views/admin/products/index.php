<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight"><?php echo lang('adm_products_title'); ?></h1>
            <p class="text-slate-500 mt-1"><?php echo lang('adm_products_subtitle'); ?></p>
        </div>
        
        <a href="<?php echo URLROOT; ?>/admin/products_add" 
           class="bg-slate-900 hover:bg-primary text-white px-6 py-3 rounded-xl font-bold transition shadow-lg shadow-slate-900/20 flex items-center gap-2 transform hover:-translate-y-1">
            <i class="fas fa-plus"></i> 
            <span><?php echo lang('btn_add_product'); ?></span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider"><?php echo lang('col_product'); ?></th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider"><?php echo lang('col_status'); ?></th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider"><?php echo lang('col_category'); ?></th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider"><?php echo lang('col_price'); ?></th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider"><?php echo lang('col_stock'); ?></th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right"><?php echo lang('col_action'); ?></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['products'])): ?>
                        <tr>
                            <td colspan="6" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-box-open text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="text-lg font-bold text-slate-600"><?php echo lang('adm_no_products'); ?></p>
                                    <p class="text-sm text-slate-400 mb-4"><?php echo lang('adm_start_adding'); ?></p>
                                    <a href="<?php echo URLROOT; ?>/admin/products_add" class="text-primary font-bold hover:underline text-sm"><?php echo lang('btn_add_product_now'); ?></a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['products'] as $p): ?>
                        <tr class="hover:bg-slate-50 transition group">
                            
                            <td class="p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-200 bg-white flex-shrink-0 relative group-hover:shadow-md transition">
                                        <?php 
                                            // Gestion image fallback
                                            $imgUrl = !empty($p->image) ? URLROOT . '/uploads/' . $p->image : URLROOT . '/img/no-image.jpg';
                                        ?>
                                        <img src="<?php echo $imgUrl; ?>" alt="<?php echo $p->name; ?>" class="w-full h-full object-cover">
                                    </div>
                                    
                                    <div>
                                        <a href="<?php echo URLROOT; ?>/admin/products_edit/<?php echo $p->id; ?>" 
                                           class="font-bold text-slate-800 text-sm md:text-base leading-tight hover:text-primary transition block mb-1">
                                            <?php echo $p->name; ?>
                                        </a>
                                        <p class="text-[10px] text-slate-400 font-mono font-bold uppercase tracking-wide">
                                            SKU: <span class="select-all text-slate-500"><?php echo $p->sku; ?></span>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="p-5">
                                <?php if(isset($p->status) && $p->status == 'draft'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-500 border border-slate-200">
                                        <?php echo lang('status_draft'); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <?php echo lang('status_active'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="p-5">
                                <div class="flex flex-col items-start gap-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        <?php echo $p->category_name ?? lang('uncategorized'); ?>
                                    </span>
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
                                        <span class="text-[10px] font-bold uppercase tracking-wide"><?php echo lang('stock_out'); ?></span>
                                    </div>
                                <?php elseif($p->stock < 5): ?>
                                    <div class="flex items-center gap-2 text-orange-600 bg-orange-50 px-3 py-1.5 rounded-lg w-fit border border-orange-100">
                                        <i class="fas fa-exclamation-triangle text-xs"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wide"><?php echo lang('stock_low'); ?> (<?php echo $p->stock; ?>)</span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center gap-2 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg w-fit border border-emerald-100">
                                        <i class="fas fa-check-circle text-xs"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wide"><?php echo $p->stock; ?></span>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td class="p-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <a href="<?php echo URLROOT; ?>/admin/products_details/<?php echo $p->id; ?>"
                                       class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-primary hover:border-primary transition shadow-sm"
                                       title="<?php echo lang('btn_view_live'); ?>">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    <a href="<?php echo URLROOT; ?>/admin/products_edit/<?php echo $p->id; ?>" 
                                       class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-600 transition shadow-sm"
                                       title="<?php echo lang('btn_edit'); ?>">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>

                                    <a href="<?php echo URLROOT; ?>/admin/products_delete/<?php echo $p->id; ?>" 
                                       class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-red-600 hover:border-red-600 transition shadow-sm"
                                       onclick="return confirm('<?php echo lang('confirm_delete'); ?>');"
                                       title="<?php echo lang('btn_delete'); ?>">
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
            <span><?php echo lang('adm_showing'); ?> <strong><?php echo isset($data['products']) ? count($data['products']) : 0; ?></strong> <?php echo lang('adm_items'); ?></span>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>