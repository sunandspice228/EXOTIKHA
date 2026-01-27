<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Review Moderation</h1>
            <p class="text-slate-500 mt-1">Manage customer feedback and ensure quality standards.</p>
        </div>
        
        <div class="flex bg-white rounded-xl border border-slate-200 p-1 shadow-sm">
            <button class="px-4 py-2 text-xs font-bold bg-slate-100 text-slate-700 rounded-lg">All</button>
            <button class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-primary transition">Pending</button>
            <button class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-primary transition">Published</button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider">Customer</th>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider">Rating</th>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider w-1/3">Comment</th>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider">Status</th>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['reviews'])): ?>
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="far fa-comment-dots text-2xl text-slate-300"></i>
                                    </div>
                                    <p class="text-lg font-bold text-slate-600">No reviews yet.</p>
                                    <p class="text-sm text-slate-400">Customer feedback will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['reviews'] as $review): ?>
                        <tr class="hover:bg-slate-50 transition group">
                            
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 font-bold uppercase text-xs">
                                        <?php echo substr($review->full_name, 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm"><?php echo $review->full_name; ?></p>
                                        <p class="text-[10px] text-slate-400"><?php echo $review->email; ?></p>
                                    </div>
                                </div>
                            </td>

                            <td class="p-5">
                                <div class="flex text-amber-400 text-xs gap-0.5">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                        <?php if($i <= $review->rating): ?>
                                            <i class="fas fa-star drop-shadow-sm"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-slate-300"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium mt-1 block">
                                    <?php echo $review->rating; ?>/5
                                </span>
                            </td>

                            <td class="p-5">
                                <div class="relative">
                                    <i class="fas fa-quote-left absolute -left-3 -top-2 text-slate-200 text-xs"></i>
                                    <p class="text-sm text-slate-600 italic leading-relaxed line-clamp-2">
                                        <?php echo $review->comment; ?>
                                    </p>
                                </div>
                            </td>

                            <td class="p-5">
                                <?php if($review->status == 'pending'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-orange-50 text-orange-600 border border-orange-100">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-green-50 text-green-600 border border-green-100">
                                        <i class="fas fa-check-circle"></i> Published
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="p-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <?php if($review->status == 'pending'): ?>
                                        <a href="<?php echo URLROOT; ?>/admin/approve_review/<?php echo $review->id; ?>" 
                                           class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition shadow-sm"
                                           title="Approve Review">
                                            <i class="fas fa-check text-xs"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo URLROOT; ?>/admin/delete_review/<?php echo $review->id; ?>" 
                                       class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:bg-red-50 hover:text-red-500 hover:border-red-100 transition shadow-sm"
                                       onclick="return confirm('Are you sure you want to delete this review?')"
                                       title="Delete Review">
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