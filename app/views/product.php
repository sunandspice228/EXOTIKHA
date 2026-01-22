<?php ob_start(); ?>

<div class="bg-light py-3 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-uppercase">
                <li class="breadcrumb-item"><a href="<?= url('/') ?>" class="text-muted text-decoration-none"><?= __('Accueil', 'Home') ?></a></li>
                <li class="breadcrumb-item"><a href="<?= url('/?category=' . $product['category']) ?>" class="text-muted text-decoration-none"><?= ucfirst($product['category']) ?></a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page"><?= e($product['name']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        
        <div class="col-lg-6">
            <div class="position-relative mb-3 bg-white border">
                <?php if(!empty($product['is_promo'])): ?>
                    <span class="position-absolute top-0 start-0 bg-danger text-white px-3 py-2 fw-bold small">
                        PROMO
                    </span>
                <?php endif; ?>

                <?php 
                    // LOGIQUE INTELLIGENTE POUR L'IMAGE PRINCIPALE
                    $mainImgPath = $product['image'];
                    // Si ce n'est pas un lien http (internet), on ajoute le dossier uploads
                    if (!preg_match("~^(?:f|ht)tps?://~i", $mainImgPath)) {
                        $mainImgPath = url('/uploads/' . $mainImgPath);
                    }
                ?>
                
                <img id="main-product-image" 
                     src="<?= $mainImgPath ?>" 
                     class="img-fluid w-100 object-fit-cover" 
                     style="min-height: 500px;" 
                     alt="<?= e($product['name']) ?>"
                     onerror="this.src='https://via.placeholder.com/500x500?text=No+Image'">
            </div>

            <div class="row g-2">
                <div class="col-3">
                     <img src="<?= $mainImgPath ?>" 
                          onclick="changeImage(this.src)" 
                          class="img-fluid border opacity-75 hover-opacity-100 cursor-pointer" 
                          style="cursor: pointer; height: 100px; object-fit: cover; width: 100%;" 
                          alt="Principal">
                </div>

                <?php if(!empty($gallery)): ?>
                    <?php foreach($gallery as $img): ?>
                        <?php 
                            $galleryPath = $img['image'];
                            if (!preg_match("~^(?:f|ht)tps?://~i", $galleryPath)) {
                                $galleryPath = url('/uploads/' . $galleryPath);
                            }
                        ?>
                        <div class="col-3">
                            <img src="<?= $galleryPath ?>" 
                                 onclick="changeImage(this.src)" 
                                 class="img-fluid border opacity-75 hover-opacity-100 cursor-pointer" 
                                 style="cursor: pointer; height: 100px; object-fit: cover; width: 100%;" 
                                 alt="Vue galerie">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-6">
            <h6 class="text-muted text-uppercase ls-2 mb-2"><?= e($product['category']) ?></h6>
            <h1 class="fw-bold font-serif display-6 mb-3"><?= e($product['name']) ?></h1>

            <div class="d-flex align-items-center mb-4">
                <div class="me-4">
                    <?php if(!empty($product['is_promo'])): ?>
                        <span class="text-decoration-line-through text-muted me-2">
                            <?= format_price($product['old_price'] ?? $product['promo_price'] ?? 0) ?>
                        </span>
                        <span class="fs-3 fw-bold text-danger"><?= format_price($product['price']) ?></span>
                    <?php else: ?>
                        <span class="fs-3 fw-bold text-dark"><?= format_price($product['price']) ?></span>
                    <?php endif; ?>
                </div>
                
                <?php if(isset($stats) && $stats['count'] > 0): ?>
                    <div class="border-start ps-4">
                        <div class="text-warning small">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="<?= $i <= round($stats['average']) ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <small class="text-muted ms-2"><?= $stats['count'] ?> <?= __('avis', 'reviews') ?></small>
                    </div>
                <?php endif; ?>
            </div>

            <p class="text-muted mb-5 leading-relaxed">
                <?= nl2br(e($product['description'])) ?>
            </p>

            <form action="<?= url('/cart/add') ?>" method="POST" class="mb-5">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                <div class="row g-3">
                    <div class="col-3">
                        <input type="number" name="quantity" value="1" min="1" max="10" class="form-control rounded-0 text-center py-3 fw-bold">
                    </div>
                    <div class="col-9">
                        <button type="submit" class="btn btn-dark w-100 rounded-0 py-3 text-uppercase fw-bold ls-1">
                            <i class="fa-solid fa-bag-shopping me-2"></i> <?= __('Ajouter au panier', 'Add to Cart') ?>
                        </button>
                    </div>
                </div>
            </form>

            <div class="bg-light p-4 small border">
                <div class="d-flex mb-3">
                    <i class="fa-solid fa-truck-fast me-3 fs-5 text-muted"></i>
                    <div>
                        <strong><?= __('Livraison Rapide', 'Fast Delivery') ?></strong><br>
                        <span class="text-muted">Accra, Lomé & Régions (24-48h)</span>
                    </div>
                </div>
                <div class="d-flex">
                    <i class="fa-solid fa-rotate-left me-3 fs-5 text-muted"></i>
                    <div>
                        <strong><?= __('Retours Faciles', 'Easy Returns') ?></strong><br>
                        <span class="text-muted">7 jours pour changer d'avis.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5 pt-5 border-top" id="reviews">
        <div class="col-lg-8 mx-auto">
            <h3 class="fw-bold font-serif text-center mb-5"><?= __('Avis Clients', 'Customer Reviews') ?></h3>

            <?php if(empty($reviews)): ?>
                <div class="text-center text-muted mb-5 p-5 bg-light rounded">
                    <i class="fa-regular fa-comment-dots fs-1 mb-3 opacity-50"></i>
                    <p><?= __('Aucun avis pour le moment. Soyez le premier !', 'No reviews yet. Be the first!') ?></p>
                </div>
            <?php else: ?>
                <?php foreach($reviews as $review): ?>
                    <div class="mb-4 pb-4 border-bottom">
                        <div class="d-flex justify-content-between mb-2">
                            <h6 class="fw-bold mb-0"><?= e($review['user_name']) ?></h6>
                            <small class="text-muted"><?= date('d/m/Y', strtotime($review['created_at'])) ?></small>
                        </div>
                        <div class="text-warning small mb-2">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="<?= $i <= $review['rating'] ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-muted mb-0"><?= nl2br(e($review['comment'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if(isset($canReview) && $canReview): ?>
                <div class="bg-white border p-4 mt-5 shadow-sm">
                    <h5 class="fw-bold mb-3"><?= __('Laisser un avis', 'Write a Review') ?></h5>
                    <form action="<?= url('/reviews/store') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Note</label>
                            <select name="rating" class="form-select rounded-0" required>
                                <option value="5">★★★★★ (Excellent)</option>
                                <option value="4">★★★★☆ (Très bien)</option>
                                <option value="3">★★★☆☆ (Bien)</option>
                                <option value="2">★★☆☆☆ (Moyen)</option>
                                <option value="1">★☆☆☆☆ (Mauvais)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Commentaire</label>
                            <textarea name="comment" class="form-control rounded-0" rows="3" required placeholder="Dites-nous ce que vous en pensez..."></textarea>
                        </div>
                        <button class="btn btn-dark rounded-0"><?= __('Publier', 'Submit Review') ?></button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if(!empty($related)): ?>
    <div class="mt-5 pt-5 border-top">
        <h3 class="fw-bold font-serif mb-4 text-center"><?= __('Vous aimerez aussi', 'You may also like') ?></h3>
        <div class="row row-cols-2 row-cols-md-4 g-4">
            <?php foreach($related as $rel): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <div class="position-relative overflow-hidden">
                            <?php 
                                $relImg = $rel['image'];
                                if (!preg_match("~^(?:f|ht)tps?://~i", $relImg)) {
                                    $relImg = url('/uploads/' . $relImg);
                                }
                            ?>
                            <a href="<?= url('/product/' . $rel['id']) ?>">
                                <img src="<?= $relImg ?>" class="card-img-top rounded-0" alt="<?= e($rel['name']) ?>" style="height: 300px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="card-body text-center p-3">
                            <h6 class="card-title fw-bold mb-1 text-truncate">
                                <a href="<?= url('/product/' . $rel['id']) ?>" class="text-dark text-decoration-none">
                                    <?= e($rel['name']) ?>
                                </a>
                            </h6>
                            <p class="card-text text-muted small mb-0">
                                <?= format_price($rel['price']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
    function changeImage(src) {
        var mainImage = document.getElementById('main-product-image');
        // Effet visuel
        mainImage.style.opacity = 0.5;
        setTimeout(function() {
            mainImage.src = src;
            mainImage.style.opacity = 1;
        }, 150);
    }
</script>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>