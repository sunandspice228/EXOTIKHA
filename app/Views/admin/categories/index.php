<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-6xl mx-auto">
    
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Category Management</h1>
        <p class="text-slate-500 mt-1">Organize your products into logical sections.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm sticky top-24">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <i class="fas fa-plus text-xs"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Add New Category</h3>
                </div>

                <form action="<?php echo URLROOT; ?>/admin/categories" method="POST">
                    <?php echo csrfField(); ?>
                    <div class="mb-4">
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-2 ml-1">Name</label>
                        <input type="text" name="name" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition px-4 py-3 text-sm font-medium text-slate-800" placeholder="e.g. Accessories" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-2 ml-1">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition px-4 py-3 text-sm text-slate-600" placeholder="Short description for SEO..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-slate-900 text-white py-3.5 rounded-xl font-bold hover:bg-primary transition shadow-lg shadow-slate-900/20 text-sm flex justify-center items-center gap-2">
                        Create Category
                    </button>
                </form>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-[10px] font-black uppercase text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Name</th>
                                <th class="px-6 py-4">Description</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            <?php if(empty($data['categories'])): ?>
                                <tr>
                                    <td colspan="3" class="p-10 text-center">
                                        <div class="inline-block p-4 rounded-full bg-slate-50 mb-3 text-slate-300">
                                            <i class="fas fa-layer-group text-2xl"></i>
                                        </div>
                                        <p class="text-slate-400 italic">No categories found.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($data['categories'] as $cat): ?>
                                    <tr class="hover:bg-slate-50 transition group">
                                        <td class="px-6 py-4 font-bold text-slate-800">
                                            <?php echo $cat->name; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-slate-500 italic truncate max-w-xs text-xs">
                                                <?php echo empty($cat->description) ? 'No description' : $cat->description; ?>
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="<?php echo URLROOT; ?>/admin/categories_delete/<?php echo $cat->id; ?>" onclick="return confirm('Are you sure you want to delete this category? Products might be affected.');" class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-200 transition shadow-sm" title="Delete">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>