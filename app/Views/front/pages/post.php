<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php
// Helper local pour la langue
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

// --- PRÉPARATION DES DONNÉES DE L'ARTICLE PRINCIPAL ---
// 1. Titre traduit
$mainTitle = ($lang == 'fr' && !empty($data['post']->title_fr)) ? $data['post']->title_fr : $data['post']->title;

// 2. Contenu traduit (Décodage HTML pour WYSIWYG)
$rawContent = ($lang == 'fr' && !empty($data['post']->content_fr)) ? $data['post']->content_fr : $data['post']->content;
$mainContent = htmlspecialchars_decode($rawContent); 

// 3. Image
$mainImg = !empty($data['post']->image) ? URLROOT . '/uploads/' . $data['post']->image : '';
?>

<div class="relative h-[400px] md:h-[500px] flex items-center justify-center overflow-hidden bg-slate-900">
    <div class="absolute inset-0 z-0">
        <?php if($mainImg): ?>
            <img src="<?php echo $mainImg; ?>" class="w-full h-full object-cover opacity-50 blur-sm scale-105">
        <?php else: ?>
            <div class="w-full h-full bg-slate-800 opacity-50"></div>
        <?php endif; ?>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-slate-900/30"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center text-white animate-fade-in-up">
        <span class="inline-block px-3 py-1 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded mb-4 border border-white/30 backdrop-blur-sm">
            <?php echo isset($data['post']->category) ? $data['post']->category : 'Journal'; ?>
        </span>
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4 leading-tight drop-shadow-lg">
            <?php echo $mainTitle; ?>
        </h1>
        <div class="flex items-center justify-center gap-4 text-sm text-slate-300 font-bold">
            <span class="flex items-center gap-2"><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($data['post']->created_at)); ?></span>
            <span class="w-1 h-1 bg-slate-500 rounded-full"></span>
            <span class="flex items-center gap-2"><i class="far fa-user"></i> Exotikha Team</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        
        <div class="lg:col-span-2">
            <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-100">
                <?php if($mainImg): ?>
                    <img src="<?php echo $mainImg; ?>" class="w-full rounded-xl mb-8 shadow-sm">
                <?php endif; ?>

                <div class="prose prose-lg prose-slate max-w-none font-serif leading-relaxed text-slate-600 first-letter:text-5xl first-letter:font-bold first-letter:text-primary first-letter:mr-1 first-letter:float-left">
                    <?php echo $mainContent; ?>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-900 text-sm"><?php echo lang('post_share'); ?></span>
                    <div class="flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(URLROOT . '/pages/post/' . $data['post']->id); ?>" target="_blank" class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center hover:scale-110 transition"><i class="fab fa-facebook-f text-xs"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(URLROOT . '/pages/post/' . $data['post']->id); ?>&text=<?php echo urlencode($mainTitle); ?>" target="_blank" class="w-8 h-8 rounded-full bg-sky-400 text-white flex items-center justify-center hover:scale-110 transition"><i class="fab fa-twitter text-xs"></i></a>
                        <a href="whatsapp://send?text=<?php echo urlencode($mainTitle . ' ' . URLROOT . '/pages/post/' . $data['post']->id); ?>" data-action="share/whatsapp/share" class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center hover:scale-110 transition"><i class="fab fa-whatsapp text-xs"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="<?php echo URLROOT; ?>/pages/blog" class="inline-flex items-center text-slate-500 hover:text-primary font-bold transition gap-2">
                    <i class="fas fa-arrow-left"></i> <?php echo lang('post_back'); ?>
                </a>
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h3 class="font-serif font-bold text-slate-900 mb-4 text-lg"><?php echo lang('sidebar_about_title'); ?></h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-4">
                    <?php echo lang('sidebar_about_text'); ?>
                </p>
                <a href="<?php echo URLROOT; ?>/shop" class="block w-full text-center bg-slate-900 text-white py-3 rounded-xl font-bold text-sm hover:bg-primary transition">
                    <?php echo lang('btn_visit_shop'); ?>
                </a>
            </div>

            <div>
                <h3 class="font-serif font-bold text-slate-900 mb-4 text-lg"><?php echo lang('sidebar_read_also'); ?></h3>
                <div class="space-y-4">
                    <?php if(!empty($data['recent'])): ?>
                        <?php foreach($data['recent'] as $recent): ?>
                            <?php 
                                if($recent->id != $data['post']->id): // Don't show current post
                                    // Traduction du titre récent
                                    $recentTitle = ($lang == 'fr' && !empty($recent->title_fr)) ? $recent->title_fr : $recent->title;
                                    $recentImg = !empty($recent->image) ? URLROOT . '/uploads/' . $recent->image : '';
                            ?>
                            <a href="<?php echo URLROOT; ?>/pages/post/<?php echo $recent->id; ?>" class="flex gap-4 group">
                                <div class="w-20 h-20 rounded-lg overflow-hidden bg-slate-200 flex-shrink-0">
                                    <?php if($recentImg): ?>
                                        <img src="<?php echo $recentImg; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase text-primary tracking-widest bg-orange-50 px-1 rounded">
                                        <?php echo isset($recent->category) ? $recent->category : 'Post'; ?>
                                    </span>
                                    <h4 class="font-bold text-slate-800 text-sm leading-tight group-hover:text-primary transition mt-1 line-clamp-2">
                                        <?php echo $recentTitle; ?>
                                    </h4>
                                </div>
                            </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-sm text-slate-400">No other posts available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>