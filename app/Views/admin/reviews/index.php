<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Review Moderation</h1>
            <p class="text-slate-500 mt-1">Manage customer feedback and ensure quality standards.</p>
        </div>
        
        <div class="flex bg-white rounded-xl border border-slate-200 p-1 shadow-sm">
            <?php $status = $_GET['status'] ?? 'all'; ?>
            
            <a href="<?php echo URLROOT; ?>/admin/reviews" 
               class="px-4 py-2 text-xs font-bold rounded-lg transition <?php echo ($status == 'all') ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-900'; ?>">
               All
            </a>
            <a href="<?php echo URLROOT; ?>/admin/reviews?status=pending" 
               class="px-4 py-2 text-xs font-bold rounded-lg transition <?php echo ($status == 'pending') ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-900'; ?>">
               Pending
            </a>
            <a href="<?php echo URLROOT; ?>/admin/reviews?status=approved" 
               class="px-4 py-2 text-xs font-bold rounded-lg transition <?php echo ($status == 'approved') ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-900'; ?>">
               Published
            </a>
        </div>
    </div>

    <?php flash('review_msg'); ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Product</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Customer</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Rating</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider w-1/3">Comment</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Status</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['reviews'])): ?>
                        <tr>
                            <td colspan="6" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="far fa-comment-dots text-2xl text-slate-300"></i>
                                    </div>
                                    <p class="font-bold">No reviews found.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['reviews'] as $review): ?>
                        <?php if($status != 'all' && $review->status != $status) continue; ?>
                        
                        <tr class="hover:bg-slate-50 transition group">
                            
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                                        <?php if(!empty($review->product_image)): ?>
                                            <img src="<?php echo URLROOT; ?>/public/img/<?php echo $review->product_image; ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-image"></i></div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="font-bold text-slate-700 text-sm line-clamp-1 w-32" title="<?php echo $review->product_name; ?>">
                                        <?php echo $review->product_name; ?>
                                    </span>
                                </div>
                            </td>

                            <td class="p-5">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-black uppercase text-xs">
                                        <?php echo substr($review->full_name ?? 'U', 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">
                                            <?php echo !empty($review->full_name) ? $review->full_name : 'Unknown'; ?>
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-medium">
                                            <?php echo date('M d, Y', strtotime($review->created_at)); ?>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="p-5">
                                <div class="flex text-amber-400 text-xs gap-0.5">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                        <?php if($i <= $review->rating): ?>
                                            <i class="fas fa-star drop-shadow-sm"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-slate-200"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-[10px] text-slate-400 font-bold mt-1 block">
                                    <?php echo $review->rating; ?> / 5.0
                                </span>
                            </td>

                            <td class="p-5">
                                <div class="relative pl-3 border-l-2 border-slate-100">
                                    <p class="text-sm text-slate-600 leading-relaxed italic line-clamp-2 group-hover:line-clamp-none transition-all">
                                        "<?php echo $review->comment; ?>"
                                    </p>
                                </div>
                            </td>

                            <td class="p-5">
                                <?php if($review->status == 'pending'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-orange-50 text-orange-600 border border-orange-100">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <i class="fas fa-check-circle"></i> Published
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="p-5 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition duration-200">
                                    
                                    <?php if($review->status == 'pending'): ?>
                                        <form action="<?php echo URLROOT; ?>/admin/reviews_approve/<?php echo $review->id; ?>" method="POST">
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-100 hover:bg-emerald-600 hover:text-white transition shadow-sm" title="Approve">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form action="<?php echo URLROOT; ?>/admin/reviews_delete/<?php echo $review->id; ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition shadow-sm" title="Delete">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center text-xs text-slate-500 font-medium">
            <span>Showing <strong><?php echo count($data['reviews']); ?></strong> reviews</span>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>