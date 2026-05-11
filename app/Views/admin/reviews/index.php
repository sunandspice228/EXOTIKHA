<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight"><?php echo lang('adm_reviews_title'); ?></h1>
            <p class="text-slate-500 mt-1"><?php echo lang('adm_reviews_subtitle'); ?></p>
        </div>
        </div>

    <?php flash('review_msg'); ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Produit</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Client</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Note & Avis</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Statut</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Date</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['reviews'])): ?>
                        <tr><td colspan="6" class="p-8 text-center text-slate-400"><?php echo lang('adm_no_reviews'); ?></td></tr>
                    <?php else: ?>
                        <?php foreach($data['reviews'] as $r): ?>
                        <tr class="hover:bg-slate-50 transition">
                            
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <?php $img = !empty($r->product_image) ? URLROOT . '/uploads/' . $r->product_image : URLROOT . '/img/no-image.jpg'; ?>
                                    <img src="<?php echo $img; ?>" class="w-10 h-10 rounded object-cover border border-slate-100">
                                    <span class="text-xs font-bold text-slate-700 line-clamp-1 w-32" title="<?php echo $r->product_name; ?>">
                                        <?php echo $r->product_name; ?>
                                    </span>
                                </div>
                            </td>

                            <td class="p-5">
                                <p class="text-sm font-bold text-slate-800"><?php echo $r->user_name; ?></p>
                                <p class="text-[10px] text-slate-400"><?php echo $r->user_email; ?></p>
                            </td>

                            <td class="p-5 max-w-xs">
                                <div class="flex text-yellow-400 text-xs mb-1">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                        <?php if($i <= $r->rating): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-slate-300"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="ml-2 text-slate-400 font-bold"><?php echo $r->rating; ?>/5</span>
                                </div>
                                <p class="text-sm text-slate-600 italic line-clamp-2" title="<?php echo $r->comment; ?>">
                                    "<?php echo $r->comment; ?>"
                                </p>
                            </td>

                            <td class="p-5">
                                <?php if($r->status == 'approved'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <i class="fas fa-check-circle mr-1"></i> Visible
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-orange-50 text-orange-600 border border-orange-100">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="p-5 text-xs text-slate-500 font-mono">
                                <?php echo date('d/m/Y', strtotime($r->created_at)); ?>
                            </td>

                            <td class="p-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <?php if($r->status == 'pending'): ?>
                                        <a href="<?php echo URLROOT; ?>/admin/reviews_approve/<?php echo $r->id; ?>" 
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-600 hover:bg-emerald-500 hover:text-white transition shadow-sm"
                                           title="Approve">
                                            <i class="fas fa-check text-xs"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo URLROOT; ?>/admin/reviews_pending/<?php echo $r->id; ?>" 
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-orange-50 border border-orange-200 text-orange-600 hover:bg-orange-500 hover:text-white transition shadow-sm"
                                           title="Set as Pending">
                                            <i class="fas fa-ban text-xs"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?php echo URLROOT; ?>/admin/reviews_delete/<?php echo $r->id; ?>" 
                                       onclick="return confirm('<?php echo lang('confirm_delete'); ?>');" 
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-red-400 hover:text-red-600 hover:border-red-300 transition shadow-sm"
                                       title="Delete">
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
    </div>
</div>
<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>