<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-slate-50 py-16 border-b border-slate-200">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <span class="text-primary font-black uppercase tracking-widest text-xs mb-4 block">Lifestyle & Inspiration</span>
        <h1 class="text-4xl md:text-6xl font-serif font-bold text-slate-900 mb-6">The Exotikha Journal</h1>
        <p class="text-slate-500 text-lg leading-relaxed max-w-2xl mx-auto">
            Dive into our universe. Discover our style tips, modern African inspirations, and the behind-the-scenes of our collections.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-16">
    
    <?php if(empty($data['posts'])): ?>
        <div class="text-center py-20 border-2 border-dashed border-slate-200 rounded-3xl">
            <p class="text-slate-400 italic mb-2">Our editors are crafting the next stories.</p>
            <p class="text-xs font-bold text-slate-300 uppercase">Check back soon!</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach($data['posts'] as $post): ?>
                <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition duration-300 group h-full flex flex-col border border-slate-100">
                    
                    <a href="<?php echo URLROOT; ?>/pages/post/<?php echo $post->id; ?>" class="block h-64 overflow-hidden bg-slate-200 relative">
                        <?php if(!empty($post->image)): ?>
                            <img src="<?php echo URLROOT . '/img/' . $post->image; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-newspaper text-3xl"></i></div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition"></div>
                    </a>

                    <div class="p-8 flex-1 flex flex-col">
                        <div class="mb-4 flex items-center justify-between">
                            <span class="text-[10px] font-black uppercase text-primary tracking-widest bg-orange-50 px-2 py-1 rounded">
                                <?php echo isset($post->category) ? $post->category : 'Lifestyle'; ?>
                            </span>
                            <span class="text-[10px] text-slate-400 font-bold flex items-center gap-1">
                                <i class="far fa-clock"></i> <?php echo date('M d, Y', strtotime($post->created_at)); ?>
                            </span>
                        </div>

                        <h3 class="text-2xl font-bold mb-3 text-slate-900 leading-tight group-hover:text-primary transition">
                            <a href="<?php echo URLROOT; ?>/pages/post/<?php echo $post->id; ?>">
                                <?php echo $post->title; ?>
                            </a>
                        </h3>
                        
                        <p class="text-slate-500 text-sm mb-6 line-clamp-3 leading-relaxed">
                            <?php echo substr(strip_tags($post->content), 0, 120); ?>...
                        </p>

                        <div class="mt-auto pt-6 border-t border-slate-50 flex justify-between items-center">
                            <a href="<?php echo URLROOT; ?>/pages/post/<?php echo $post->id; ?>" class="text-xs font-bold uppercase tracking-wider text-slate-900 group-hover:text-primary transition flex items-center gap-2">
                                Read more <i class="fas fa-arrow-right group-hover:translate-x-1 transition"></i>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<section class="bg-slate-900 text-white py-16">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-2xl font-serif font-bold mb-4">Never miss a story</h2>
        <p class="text-slate-400 mb-8">Receive our latest inspirations directly in your inbox.</p>
        <form action="<?php echo URLROOT; ?>/pages/subscribe" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
            <?php echo csrfField(); ?>    
            <input type="email" name="email" placeholder="Your email address" required class="flex-1 rounded-xl bg-white/10 border border-white/20 px-4 py-3 text-white focus:outline-none focus:border-primary placeholder-white/50">
            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-white hover:text-slate-900 transition shadow-lg">Subscribe</button>
        </form>
    </div>
</section>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>