<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="h-[60vh] relative bg-slate-900 overflow-hidden">
    <img src="<?php echo URLROOT . '/public/' . $data['post']->image; ?>" class="w-full h-full object-cover opacity-70">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-full p-6 md:p-12">
        <div class="max-w-4xl mx-auto text-white">
            <span class="bg-accent px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-4 inline-block"><?php echo $data['post']->category; ?></span>
            <h1 class="text-4xl md:text-6xl font-serif font-bold leading-tight mb-4"><?php echo $data['post']->title; ?></h1>
            <p class="text-slate-300 text-sm">Published on <?php echo date('F d, Y', strtotime($data['post']->created_at)); ?></p>
        </div>
    </div>
</div>

<div class="bg-white py-16">
    <div class="max-w-3xl mx-auto px-6">
        <div class="prose prose-lg prose-slate max-w-none first-letter:text-5xl first-letter:font-serif first-letter:font-bold first-letter:mr-2 first-letter:float-left first-letter:text-slate-900">
            <?php echo $data['post']->content; ?>
        </div>

        <div class="mt-16 pt-8 border-t border-slate-100 flex justify-between items-center">
            <a href="<?php echo URLROOT; ?>" class="text-sm font-bold text-slate-500 hover:text-accent flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
            <div class="flex gap-4">
                <span class="text-sm font-bold text-slate-900">Share:</span>
                <a href="#" class="text-slate-400 hover:text-[#1877F2]"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-slate-400 hover:text-[#1DA1F2]"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-slate-400 hover:text-[#25D366]"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>