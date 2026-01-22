<?php ob_start(); ?>

<div class="container py-5">
    
    <nav aria-label="breadcrumb" class="mb-4 d-none d-md-block">
        <ol class="breadcrumb text-uppercase small" style="font-size: 0.8rem; letter-spacing: 1px;">
            <li class="breadcrumb-item"><a href="<?= url('/') ?>" class="text-muted text-decoration-none">Accueil</a></li>
            <?php if(!empty($product['category'])): ?>
            <li class="breadcrumb-item"><a href="<?= url('/?category='.urlencode($product['category'])) ?>" class="text-muted text-decoration-none"><?= e($product['category']) ?></a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active text-dark fw-bold" aria-current="page"><?= get_tr($product, 'name') ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        
        <div class="col-lg-7">
            <div class="d-flex gap-3 h-100">
                
                <div class="d-none d-md-flex flex-column gap-3" style="width: 90px; min-width: 90px;">
                    <img src="<?= url('/uploads/'.$product['image']) ?>" 
                         class="gallery-thumb active w-100 rounded cursor-pointer border border-2 border-dark object-fit-cover" 
                         style="height: 110px;" 
                         onclick="changeMainImage(this.src, this)">
                    
                    <?php if(!empty($gallery)): ?>
                        <?php foreach($gallery as $img): ?>
                            <img src="<?= url('/uploads/'.$img['image']) ?>" 
                                 class="gallery-thumb w-100 rounded cursor-pointer border object-fit-cover opacity-75 hover-opacity-100 transition" 
                                 style="height: 110px;" 
                                 onclick="changeMainImage(this.src, this)">
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="flex-grow-1 position-relative bg-light rounded overflow-hidden shadow-sm" style="min-height: 500px; max-height: 700px;">
                    <img id="mainImage" src="<?= url('/uploads/'.$product['image']) ?>" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0 transition-opacity">
                    
                    <?php if(is_on_promotion($product)): ?>
                        <?php $pct = round((($product['price'] - $product['promo_price']) / $product['price']) * 100); ?>
                        <span class="position-absolute top-0 end-0 m-4 badge bg-danger px-3 py-2 fs-6 shadow">-<?= $pct ?>%</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex d-md-none gap-2 overflow-auto mt-3 pb-2 no-scrollbar">
                <img src="<?= url('/uploads/'.$product['image']) ?>" class="rounded border object-fit-cover" style="width: 70px; height: 70px;" onclick="changeMainImage(this.src)">
                <?php if(!empty($gallery)): ?>
                    <?php foreach($gallery as $img): ?>
                        <img src="<?= url('/uploads/'.$img['image']) ?>" class="rounded border object-fit-cover" style="width: 70px; height: 70px;" onclick="changeMainImage(this.src)">
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="h-100 d-flex flex-column">
                
                <div class="mb-4 border-bottom pb-4">
                    <div class="text-uppercase text-muted small letter-spacing-1 mb-2">
                        <?= e($product['category']) ?> <?= !empty($product['type']) ? '• '.e($product['type']) : '' ?>
                    </div>
                    <h1 class="fw-bold mb-3 display-6 lh-sm"><?= get_tr($product, 'name') ?></h1>
                    
                    <div class="fs-3">
                        <?= format_product_price_html($product) ?>
                    </div>
                </div>

                <div class="mb-5">
                    <p class="text-secondary" style="line-height: 1.8; font-size: 0.95rem;">
                        <?= nl2br(get_tr($product, 'description')) ?>
                    </p>
                </div>

                <form action="<?= url('/cart/add') ?>" method="POST" class="mt-auto">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <div class="row g-3 mb-4">
                        <?php if(!empty($product['sizes'])): ?>
                        <div class="col-6">
                            <label class="fw-bold small mb-2 text-uppercase text-muted">Taille</label>
                            <select name="size" class="form-select bg-light border-0 rounded-0 py-2 focus-ring-dark">
                                <?php foreach(explode(',', $product['sizes']) as $s): ?>
                                    <option value="<?= trim($s) ?>"><?= trim($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <?php if(!empty($product['colors'])): ?>
                        <div class="col-6">
                            <label class="fw-bold small mb-2 text-uppercase text-muted">Couleur</label>
                            <select name="color" class="form-select bg-light border-0 rounded-0 py-2 focus-ring-dark">
                                <?php foreach(explode(',', $product['colors']) as $c): ?>
                                    <option value="<?= trim($c) ?>"><?= trim($c) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="position-relative" style="width: 70px;">
                            <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?>" class="form-control text-center bg-light border-0 rounded-0 h-100 fw-bold">
                        </div>
                        
                        <?php if($product['stock'] > 0): ?>
                            <button class="btn btn-dark w-100 py-3 fw-bold text-uppercase rounded-0 shadow hover-lift transition">
                                <i class="fa-solid fa-bag-shopping me-2"></i> Ajouter au panier
                            </button>
                        <?php else: ?>
                            <button type="button" disabled class="btn btn-secondary w-100 py-3 fw-bold text-uppercase rounded-0 opacity-50 cursor-not-allowed">
                                <i class="fa-solid fa-ban me-2"></i> Rupture de stock
                            </button>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="mt-5 pt-4 border-top">
                    <div class="row g-2 text-muted small">
                        <div class="col-6 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-truck-fast text-dark"></i> Expédition 24/48h
                        </div>
                        <div class="col-6 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-lock text-dark"></i> Paiement Sécurisé
                        </div>
                        <div class="col-6 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-rotate-left text-dark"></i> Retours sous 14j
                        </div>
                        <div class="col-6 d-flex align-items-center gap-2">
                            <i class="fa-regular fa-circle-check text-dark"></i> Garantie Qualité
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <?php if(!empty($similar)): ?>
    <div class="mt-5 pt-5 border-top">
        <h3 class="fw-bold mb-5 text-center position-relative">
            <span class="bg-white px-3 z-1 relative">Vous aimerez aussi</span>
            <span class="position-absolute top-50 start-0 w-100 border-top z-0"></span>
        </h3>
        <div class="row g-4">
            <?php foreach($similar as $p): include ROOT_PATH . '/app/views/partials/product_card_dynamic.php'; endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<style>
    .hover-opacity-100:hover { opacity: 1 !important; }
    .focus-ring-dark:focus { box-shadow: 0 0 0 0.25rem rgba(0,0,0,0.1); }
    .hover-lift:hover { transform: translateY(-2px); }
    .transition { transition: all 0.2s ease; }
    .transition-opacity { transition: opacity 0.2s ease-in-out; }
    /* Cache scrollbar mobile */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
function changeMainImage(src, thumbElement) {
    const main = document.getElementById('mainImage');
    
    // Animation de fondu
    main.style.opacity = 0.6;
    setTimeout(() => {
        main.src = src;
        main.style.opacity = 1;
    }, 150);

    // Gestion de la bordure active (seulement sur Desktop où on a les pouces verticaux)
    if(thumbElement && thumbElement.classList.contains('gallery-thumb')) {
        document.querySelectorAll('.gallery-thumb').forEach(el => {
            el.classList.remove('border-dark', 'border-2', 'active', 'opacity-100');
            el.classList.add('opacity-75');
            el.classList.add('border');
        });
        thumbElement.classList.remove('border', 'opacity-75');
        thumbElement.classList.add('border-dark', 'border-2', 'active', 'opacity-100');
    }
}
</script>
<div class="mt-5 pt-5 border-top" id="reviews">
        <h3 class="fw-bold mb-4">Avis Clients</h3>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="bg-light p-4 rounded text-center">
                    <h1 class="display-4 fw-bold mb-0"><?= number_format($stats['average'] ?? 0, 1) ?></h1>
                    <div class="text-warning mb-2">
                        <?php 
                        $avg = round($stats['average'] ?? 0);
                        for($i=1; $i<=5; $i++) {
                            echo $i <= $avg ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                        }
                        ?>
                    </div>
                    <p class="text-muted small"><?= $stats['count'] ?? 0 ?> avis vérifiés</p>
                </div>

                <?php if($canReview): ?>
                    <div class="mt-4">
                        <h6 class="fw-bold">Donnez votre avis</h6>
                        <form action="<?= url('/reviews/store') ?>" method="POST" class="mt-2">
                            <?= csrf_field() ?>
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            
                            <div class="mb-2">
                                <label class="small text-muted mb-1">Note</label>
                                <select name="rating" class="form-select form-select-sm">
                                    <option value="5">★★★★★ (Excellent)</option>
                                    <option value="4">★★★★☆ (Très bon)</option>
                                    <option value="3">★★★☆☆ (Correct)</option>
                                    <option value="2">★★☆☆☆ (Moyen)</option>
                                    <option value="1">★☆☆☆☆ (Mauvais)</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <textarea name="comment" rows="3" class="form-control form-control-sm" placeholder="Votre commentaire..." required></textarea>
                            </div>
                            <button class="btn btn-dark btn-sm w-100">Publier l'avis</button>
                        </form>
                    </div>
                <?php elseif(!isset($_SESSION['user_id'])): ?>
                    <div class="mt-3 text-center small">
                        <a href="<?= url('/login') ?>" class="text-dark fw-bold">Connectez-vous</a> pour donner votre avis.
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-8">
                <?php if(empty($reviews)): ?>
                    <p class="text-muted fst-italic">Aucun avis pour le moment. Soyez le premier !</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach($reviews as $r): ?>
                            <div class="border-bottom pb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="fw-bold"><?= e($r['user_name']) ?></span>
                                        <span class="badge bg-success text-white" style="font-size: 0.6rem;">ACHAT VÉRIFIÉ</span>
                                    </div>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($r['created_at'])) ?></small>
                                </div>
                                <div class="text-warning my-1" style="font-size: 0.8rem;">
                                    <?php for($i=1; $i<=5; $i++) echo $i <= $r['rating'] ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>'; ?>
                                </div>
                                <p class="mb-0 small text-secondary"><?= nl2br(e($r['comment'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>