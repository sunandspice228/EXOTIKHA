<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6">
    <h1 class="text-2xl font-bold text-slate-800 mb-8">Modération des Avis</h1>
    <?php flash('admin_msg'); ?>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-xs font-black uppercase text-slate-400 border-b">
                <tr>
                    <th class="px-6 py-4">Client</th>
                    <th class="px-6 py-4">Note</th>
                    <th class="px-6 py-4">Commentaire</th>
                    <th class="px-6 py-4">Statut</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm">
                <?php foreach($data['reviews'] as $review): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800"><?php echo $review->full_name; ?></div>
                            <div class="text-xs text-slate-400"><?php echo $review->email; ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex text-yellow-400 text-xs">
                                <?php for($i=1;$i<=5;$i++) echo ($i<=$review->rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star text-slate-300"></i>'; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 italic text-slate-600 max-w-md truncate">"<?php echo $review->comment; ?>"</td>
                        <td class="px-6 py-4">
                            <?php if($review->status == 'pending'): ?>
                                <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-[10px] font-black uppercase">En attente</span>
                            <?php else: ?>
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-[10px] font-black uppercase">Publié</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if($review->status == 'pending'): ?>
                                <a href="<?php echo URLROOT; ?>/admin/approve_review/<?php echo $review->id; ?>" class="text-green-600 font-bold text-xs mr-3 hover:underline">Approuver</a>
                            <?php endif; ?>
                            <a href="<?php echo URLROOT; ?>/admin/delete_review/<?php echo $review->id; ?>" class="text-red-400 hover:text-red-600" onclick="return confirm('Supprimer cet avis ?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if(empty($data['reviews'])): ?>
            <div class="p-8 text-center text-slate-400 italic">Aucun avis client pour le moment.</div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>