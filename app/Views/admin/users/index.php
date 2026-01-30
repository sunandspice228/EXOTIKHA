<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-6xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Staff Management</h1>
            <p class="text-slate-500 mt-1">Manage administrative access and team roles.</p>
        </div>
        
        <a href="<?php echo URLROOT; ?>/admin/users_add" 
           class="bg-slate-900 hover:bg-primary text-white px-6 py-3 rounded-xl font-bold transition shadow-lg shadow-slate-900/20 flex items-center gap-2 transform hover:-translate-y-1">
            <i class="fas fa-user-plus"></i> 
            <span>Add New Admin</span>
        </a>
    </div>

    <?php flash('admin_msg'); ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Administrator</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Email</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Role</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Joined Date</th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['admins'])): ?>
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-user-shield text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="text-lg font-bold text-slate-600">No administrators found.</p>
                                    <p class="text-sm text-slate-400">Add staff members to help manage the store.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['admins'] as $admin): ?>
                        <tr class="hover:bg-slate-50 transition group">
                            
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-sm uppercase shadow-md shadow-slate-900/20">
                                        <?php echo substr($admin->name, 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">
                                            <?php echo $admin->name; ?>
                                            <?php if($admin->id == $_SESSION['user_id']): ?>
                                                <span class="text-[10px] text-primary bg-indigo-50 px-1.5 py-0.5 rounded ml-1 border border-indigo-100 font-bold">YOU</span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-mono">ID: #<?php echo $admin->id; ?></p>
                                    </div>
                                </div>
                            </td>

                            <td class="p-5">
                                <a href="mailto:<?php echo $admin->email; ?>" class="text-sm text-slate-600 hover:text-primary transition font-medium flex items-center gap-2">
                                    <i class="far fa-envelope text-xs text-slate-400"></i> <?php echo $admin->email; ?>
                                </a>
                            </td>

                            <td class="p-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-indigo-50 text-indigo-600 border border-indigo-100">
                                    <i class="fas fa-shield-alt"></i> Admin
                                </span>
                            </td>

                            <td class="p-5 text-sm text-slate-500 font-medium">
                                <?php echo date('M d, Y', strtotime($admin->created_at)); ?>
                            </td>

                            <td class="p-5 text-right">
                                <?php if($admin->id != $_SESSION['user_id']): ?>
                                    <a href="<?php echo URLROOT; ?>/admin/users_delete/<?php echo $admin->id; ?>" 
                                       class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:bg-red-50 hover:text-red-500 hover:border-red-100 transition shadow-sm"
                                       onclick="return confirm('Are you sure you want to revoke access for this user?')"
                                       title="Revoke Access">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-300 italic font-medium cursor-not-allowed">Current Session</span>
                                <?php endif; ?>
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