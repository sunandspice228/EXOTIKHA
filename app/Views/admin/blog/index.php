<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Blog & News</h1>
            <p class="text-slate-500 mt-1">Manage articles, style tips, and updates.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/blog_add" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg shadow-primary/30 flex items-center gap-2">
            <i class="fas fa-pen-nib"></i> Write New Post
        </a>
    </div>

    <?php if(empty($data['posts'])): ?>
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                <i class="fas fa-newspaper text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">No posts yet</h3>
            <p class="text-slate-500 text-sm mb-6">Start sharing content with your customers.</p>
            <a href="<?php echo URLROOT; ?>/admin/blog_add" class="text-primary font-bold hover:underline text-sm">Create first post</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($data['posts'] as $post): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden group hover:shadow-xl transition-all duration-300 flex flex-col h-full">
                    
                    <div class="h-56 overflow-hidden relative bg-slate-200">
                        <?php if($post->image): ?>
                            <img src="<?php echo URLROOT . '/public/img/' . $post->image; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-image text-4xl opacity-50"></i></div>
                        <?php endif; ?>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>

                        <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-y-[-10px] group-hover:translate-y-0">
                            <a href="<?php echo URLROOT; ?>/admin/blog_edit/<?php echo $post->id; ?>" class="w-9 h-9 bg-white/90 backdrop-blur rounded-xl flex items-center justify-center text-slate-600 hover:text-primary transition shadow-md" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <a href="<?php echo URLROOT; ?>/admin/blog_delete/<?php echo $post->id; ?>" onclick="return confirm('Are you sure you want to delete this post?')" class="w-9 h-9 bg-white/90 backdrop-blur rounded-xl flex items-center justify-center text-slate-600 hover:text-red-500 transition shadow-md" title="Delete">
                                <i class="fas fa-trash text-xs"></i>
                            </a>
                        </div>

                        <span class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest text-slate-900 shadow-sm">
                            <?php echo $post->category; ?>
                        </span>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="font-bold text-slate-900 text-lg mb-2 leading-tight group-hover:text-primary transition line-clamp-2">
                            <?php echo $post->title; ?>
                        </h3>
                        
                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-3 mb-6 flex-1">
                            <?php echo substr(strip_tags($post->content), 0, 120); ?>...
                        </p>
                        
                        <div class="flex items-center justify-between border-t border-slate-50 pt-4 mt-auto">
                            <div class="flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-wide">
                                <i class="far fa-calendar"></i>
                                <?php echo date('M d, Y', strtotime($post->created_at)); ?>
                            </div>
                            <span class="text-xs font-bold text-primary hover:underline cursor-pointer">Read More</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>