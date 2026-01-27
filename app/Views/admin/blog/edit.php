<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/blog" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Post</h1>
            <p class="text-sm text-slate-500">Updating: <span class="font-bold text-primary bg-primary/10 px-2 py-0.5 rounded"><?php echo $data['post']->title; ?></span></p>
        </div>
    </div>

    <form action="<?php echo URLROOT; ?>/admin/blog_edit/<?php echo $data['post']->id; ?>" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <?php echo csrfField(); ?>
        <div class="mb-8">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Post Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="<?php echo $data['post']->title; ?>" required 
                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-4 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition text-lg font-serif font-bold text-slate-800 placeholder-slate-300">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Category <span class="text-red-500">*</span></label>
                <div class="relative">
                    <i class="fas fa-folder absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <select name="category" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition appearance-none font-medium text-slate-700 cursor-pointer">
                        <?php $cats = ['Lifestyle', 'Mode', 'Conseils', 'Événements']; ?>
                        <?php foreach($cats as $c): ?>
                            <option value="<?php echo $c; ?>" <?php echo ($data['post']->category == $c) ? 'selected' : ''; ?>><?php echo $c; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Update Cover Image</label>
                <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:uppercase file:bg-slate-100 file:text-slate-600 hover:file:bg-primary hover:file:text-white transition cursor-pointer border border-slate-200 rounded-xl">
                <?php if(!empty($data['post']->image)): ?>
                    <p class="text-[10px] text-slate-400 mt-2 flex items-center gap-1">
                        <i class="fas fa-image"></i> Current image: <span class="font-mono text-slate-500"><?php echo $data['post']->image; ?></span>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Content <span class="text-red-500">*</span></label>
            <textarea name="content" rows="15" required 
                      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-4 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition text-slate-700 leading-relaxed"><?php echo $data['post']->content; ?></textarea>
        </div>

        <div class="flex items-center justify-end gap-4 border-t border-slate-50 pt-6">
            <a href="<?php echo URLROOT; ?>/admin/blog" class="text-slate-500 font-bold text-sm hover:text-slate-800 px-4 transition">Cancel</a>
            <button type="submit" class="bg-primary text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-indigo-600 transition shadow-lg shadow-primary/30 flex items-center gap-2">
                <i class="fas fa-check"></i> Update Post
            </button>
        </div>

    </form>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>