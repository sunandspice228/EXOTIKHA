<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-2xl mx-auto">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/attributes" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Create Attribute</h1>
            <p class="text-slate-500 text-sm">Define product options like Size or Color.</p>
        </div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <form action="<?php echo URLROOT; ?>/admin/attributes_add" method="POST">
            <?php echo csrfField(); ?>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Attribute Name <span class="text-red-500">*</span></label>
                <div class="relative">
                    <i class="fas fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="name" placeholder="e.g. Size" required 
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-medium text-slate-800 placeholder-slate-400">
                </div>
            </div>

            <div class="mb-8">
                <div class="flex justify-between items-end mb-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Values <span class="text-red-500">*</span></label>
                    <span class="text-[10px] font-bold text-primary bg-indigo-50 px-2 py-1 rounded border border-indigo-100">Comma Separated</span>
                </div>
                
                <textarea name="values" rows="4" placeholder="e.g. Small, Medium, Large, XL" required
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition text-slate-800 leading-relaxed"></textarea>
                
                <div class="flex gap-2 mt-3 text-slate-400 text-xs bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <i class="fas fa-info-circle mt-0.5 text-primary"></i>
                    <p>Enter values separated by commas. These will be selectable when creating products.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 border-t border-slate-50 pt-6">
                <a href="<?php echo URLROOT; ?>/admin/attributes" class="text-slate-500 font-bold text-sm hover:text-slate-800 px-4 transition">Cancel</a>
                <button type="submit" class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-primary transition shadow-lg shadow-slate-900/20 flex items-center gap-2 transform hover:-translate-y-1">
                    <i class="fas fa-check-circle"></i> Save Attribute
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>