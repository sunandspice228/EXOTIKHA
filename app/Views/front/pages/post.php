<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="relative h-[400px] md:h-[500px] flex items-center justify-center overflow-hidden bg-slate-900">
    <div class="absolute inset-0 z-0">
        <?php if(!empty($data['post']->image)): ?>
            <img src="<?php echo URLROOT . '/public/img/' . $data['post']->image; ?>" class="w-full h-full object-cover opacity-50 blur-sm scale-105">
        <?php else: ?>
            <div class="w-full h-full bg-slate-800 opacity-50"></div>
        <?php endif; ?>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-slate-900/30"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center text-white animate-fade-in-up">
        <span class="inline-block px-3 py-1 bg-accent text-white text-[10px] font-black uppercase tracking-widest rounded mb-4">
            <?php echo $data['post']->category; ?>
        </span>
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4 leading-tight">
            <?php echo $data['post']->title; ?>
        </h1>
        <div class="flex items-center justify-center gap-4 text-sm text-slate-300 font-bold">
            <span class="flex items-center gap-2"><i class="far fa-calendar"></i> <?php echo date('d M Y', strtotime($data['post']->created_at)); ?></span>
            <span class="w-1 h-1 bg-slate-500 rounded-full"></span>
            <span class="flex items-center gap-2"><i class="far fa-user"></i> Exotikha Team</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        
        <div class="lg:col-span-2">
            <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-100">
                <?php if(!empty($data['post']->image)): ?>
                    <img src="<?php echo URLROOT . '/public/img/' . $data['post']->image; ?>" class="w-full rounded-xl mb-8 shadow-sm">
                <?php endif; ?>

                <div class="prose prose-lg prose-slate max-w-none font-serif leading-relaxed text-slate-600 first-letter:text-5xl first-letter:font-bold first-letter:text-accent first-letter:mr-1 first-letter:float-left">
                    <?php echo nl2br($data['post']->content); ?>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-900 text-sm">Partager cet article</span>
                    <div class="flex gap-2">
                        <button class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center hover:scale-110 transition"><i class="fab fa-facebook-f text-xs"></i></button>
                        <button class="w-8 h-8 rounded-full bg-sky-400 text-white flex items-center justify-center hover:scale-110 transition"><i class="fab fa-twitter text-xs"></i></button>
                        <button class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center hover:scale-110 transition"><i class="fab fa-whatsapp text-xs"></i></button>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="<?php echo URLROOT; ?>#blog" class="inline-flex items-center text-slate-500 hover:text-accent font-bold transition gap-2">
                    <i class="fas fa-arrow-left"></i> Retour aux articles
                </a>
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h3 class="font-serif font-bold text-slate-900 mb-4 text-lg">À propos d'Exotikha</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-4">
                    Nous célébrons la mode africaine moderne avec une touche d'audace et d'élégance. Découvrez nos collections exclusives.
                </p>
                <a href="<?php echo URLROOT; ?>/shop" class="block w-full text-center bg-slate-900 text-white py-3 rounded-xl font-bold text-sm hover:bg-accent transition">Visiter la boutique</a>
            </div>

            <div>
                <h3 class="font-serif font-bold text-slate-900 mb-4 text-lg">Lire aussi</h3>
                <div class="space-y-4">
                    <?php foreach($data['recent'] as $recent): ?>
                        <?php if($recent->id != $data['post']->id): // Ne pas afficher l'article courant ?>
                        <a href="<?php echo URLROOT; ?>/pages/post/<?php echo $recent->id; ?>" class="flex gap-4 group">
                            <div class="w-20 h-20 rounded-lg overflow-hidden bg-slate-200 flex-shrink-0">
                                <?php if($recent->image): ?>
                                    <img src="<?php echo URLROOT . '/public/img/' . $recent->image; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                <?php endif; ?>
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase text-accent"><?php echo $recent->category; ?></span>
                                <h4 class="font-bold text-slate-800 text-sm leading-tight group-hover:text-accent transition mt-1">
                                    <?php echo $recent->title; ?>
                                </h4>
                            </div>
                        </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>