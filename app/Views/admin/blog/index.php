<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Gestion du Blog</h1>
            <p class="text-sm text-slate-500">Gérez vos histoires et inspirations lifestyle.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/add_post" class="bg-primary text-white px-6 py-3 rounded-xl font-bold shadow hover:bg-slate-800 transition">
            <i class="fas fa-plus mr-2"></i> Nouvel Article
        </a>
    </div>

    <?php flash('admin_msg'); ?>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-black uppercase text-slate-400">Article</th>
                    <th class="px-6 py-4 text-xs font-black uppercase text-slate-400">Catégorie</th>
                    <th class="px-6 py-4 text-xs font-black uppercase text-slate-400">Date</th>
                    <th class="px-6 py-4 text-xs font-black uppercase text-slate-400 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach($data['posts'] as $post): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <img src="<?php echo URLROOT . '/public/' . $post->image; ?>" class="w-12 h-12 rounded-lg object-cover">
                                <span class="font-bold text-slate-700"><?php echo $post->title; ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-[10px] font-bold uppercase text-slate-500"><?php echo $post->category; ?></span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            <?php echo date('M d, Y', strtotime($post->created_at)); ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?php echo URLROOT; ?>/admin/delete_post/<?php echo $post->id; ?>" class="text-red-400 hover:text-red-600 ml-4" onclick="return confirm('Supprimer définitivement ?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if(empty($data['posts'])): ?>
            <div class="p-8 text-center text-slate-400 italic">Aucun article publié pour le moment.</div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>