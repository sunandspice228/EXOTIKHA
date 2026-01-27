<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-2xl mx-auto">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/attributes" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Create New Attribute</h1>
            <p class="text-slate-500 text-sm">Define characteristics for your products (e.g. Size, Color).</p>
        </div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <form action="<?php echo URLROOT; ?>/admin/attributes_add" method="POST">
            <?php echo csrfField(); ?>
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Attribute Name <span class="text-red-500">*</span></label>
                <div class="relative">
                    <i class="fas fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="name" placeholder="e.g. Size, Color, Fabric" required 
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-medium text-slate-800">
                </div>
            </div>

            <div class="mb-8">
                <div class="flex justify-between items-end mb-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Possible Values <span class="text-red-500">*</span></label>
                    <span class="text-[10px] font-bold text-primary bg-primary/10 px-2 py-1 rounded">Comma Separated</span>
                </div>
                
                <textarea name="values" rows="4" placeholder="e.g. Small, Medium, Large, X-Large" required
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition text-slate-800"></textarea>
                
                <div class="flex gap-2 mt-3 text-slate-400 text-xs">
                    <i class="fas fa-info-circle mt-0.5"></i>
                    <p>These values will appear as selectable options when adding a new product. You can add more values later if needed.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 border-t border-slate-50 pt-6">
                <a href="<?php echo URLROOT; ?>/admin/attributes" class="text-slate-500 font-bold text-sm hover:text-slate-800 px-4 transition">Cancel</a>
                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-indigo-600 transition shadow-lg shadow-primary/30 flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Attribute
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>