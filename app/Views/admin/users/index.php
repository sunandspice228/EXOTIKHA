<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-6xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight"><?php echo lang('adm_staff_title'); ?></h1>
            <p class="text-slate-500 mt-1"><?php echo lang('adm_staff_subtitle'); ?></p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/users_add" class="bg-slate-900 hover:bg-primary text-white px-6 py-3 rounded-xl font-bold transition shadow-lg flex items-center gap-2 transform hover:-translate-y-1">
            <i class="fas fa-user-plus"></i> <span><?php echo lang('btn_add_staff'); ?></span>
        </a>
    </div>

    <?php flash('admin_msg'); ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Utilisateur</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Rôle</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Email</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Date Ajout</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach($data['admins'] as $u): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden flex-shrink-0">
                                    <?php if(!empty($u->image)): ?>
                                        <img src="<?php echo URLROOT . '/uploads/' . $u->image; ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center font-bold text-slate-500">
                                            <?php echo strtoupper(substr($u->name, 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <span class="font-bold text-slate-800"><?php echo $u->name; ?></span>
                            </div>
                        </td>
                        <td class="p-5">
                            <?php if($u->role == 'super_admin'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-purple-100 text-purple-700 border border-purple-200">
                                    Super Admin
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-600 border border-blue-100">
                                    <?php echo ucfirst($u->role); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="p-5 text-sm text-slate-600"><?php echo $u->email; ?></td>
                        <td class="p-5 text-xs text-slate-500 font-mono"><?php echo date('d/m/Y', strtotime($u->created_at)); ?></td>
                        <td class="p-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?php echo URLROOT; ?>/admin/users_edit/<?php echo $u->id; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-blue-500 hover:text-blue-700 transition shadow-sm"><i class="fas fa-pen text-xs"></i></a>
                                
                                <?php if($u->id != $_SESSION['user_id']): ?>
                                    <a href="<?php echo URLROOT; ?>/admin/users_delete/<?php echo $u->id; ?>" onclick="return confirm('<?php echo lang('confirm_delete'); ?>');" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-red-400 hover:text-red-600 transition shadow-sm"><i class="fas fa-trash-alt text-xs"></i></a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>