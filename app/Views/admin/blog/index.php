<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight"><?php echo lang('adm_blog_title'); ?></h1>
            <p class="text-slate-500 mt-1"><?php echo lang('adm_blog_subtitle'); ?></p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/blog_add" class="bg-slate-900 hover:bg-primary text-white px-6 py-3 rounded-xl font-bold transition shadow-lg flex items-center gap-2 transform hover:-translate-y-1">
            <i class="fas fa-pen-nib"></i> <span><?php echo lang('btn_add_post'); ?></span>
        </a>
    </div>

    <?php flash('blog_msg'); ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Image</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Article</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Statut</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Date</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['posts'])): ?>
                        <tr><td colspan="5" class="p-8 text-center text-slate-400"><?php echo lang('adm_no_posts'); ?></td></tr>
                    <?php else: ?>
                        <?php foreach($data['posts'] as $post): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-5">
                                <?php $img = !empty($post->image) ? URLROOT . '/uploads/' . $post->image : URLROOT . '/img/no-image.jpg'; ?>
                                <img src="<?php echo $img; ?>" class="w-16 h-10 rounded object-cover border border-slate-100">
                            </td>
                            <td class="p-5">
                                <span class="font-bold text-slate-800 block"><?php echo $post->title; ?></span>
                                <span class="text-xs text-slate-400 line-clamp-1"><?php echo substr(strip_tags($post->content), 0, 50); ?>...</span>
                            </td>
                            <td class="p-5">
                                <?php if($post->status == 'published'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">Published</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-500 border border-slate-200">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-5 text-xs text-slate-500 font-mono">
                                <?php echo date('d M Y', strtotime($post->created_at)); ?>
                            </td>
                            <td class="p-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo URLROOT; ?>/admin/blog_edit/<?php echo $post->id; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-blue-500 hover:text-blue-700 hover:border-blue-300 transition shadow-sm"><i class="fas fa-pen text-xs"></i></a>
                                    <a href="<?php echo URLROOT; ?>/admin/blog_delete/<?php echo $post->id; ?>" onclick="return confirm('<?php echo lang('confirm_delete'); ?>');" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-red-400 hover:text-red-600 hover:border-red-300 transition shadow-sm"><i class="fas fa-trash-alt text-xs"></i></a>
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