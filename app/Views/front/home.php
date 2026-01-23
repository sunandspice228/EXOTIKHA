<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=1920&auto=format&fit=crop');">
      <div class="carousel-caption d-none d-md-block text-start container">
        <h5 class="display-3 fw-bold text-white">Nouvelle Collection <span style="color:var(--primary-color)">2026</span></h5>
        <p class="lead text-white mb-4">L'élégance africaine revisitée pour le quotidien.</p>
        <a href="<?php echo URLROOT; ?>/shop" class="btn btn-primary btn-lg shadow-lg">Découvrir la boutique</a>
      </div>
    </div>
    <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1920&auto=format&fit=crop');">
      <div class="carousel-caption d-none d-md-block text-center">
        <h5 class="display-3 fw-bold text-white">Accessoires Uniques</h5>
        <p class="lead text-white mb-4">La touche finale pour parfaire votre style.</p>
        <a href="<?php echo URLROOT; ?>/shop" class="btn btn-light btn-lg shadow-lg">Voir les accessoires</a>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Nos Univers</h2>
        <div style="width: 50px; height: 3px; background: var(--primary-color); margin: 10px auto;"></div>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card bg-dark text-white border-0 overflow-hidden" style="height: 250px;">
                <img src="https://images.unsplash.com/photo-1605763240004-7e93b172d754?q=80&w=600&auto=format&fit=crop" class="card-img opacity-50 h-100 w-100" style="object-fit:cover">
                <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center">
                    <h3 class="card-title fw-bold">HOMME</h3>
                    <a href="<?php echo URLROOT; ?>/shop" class="btn btn-outline-light btn-sm mt-2">Explorer</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white border-0 overflow-hidden" style="height: 250px;">
                <img src="https://images.unsplash.com/photo-1550614000-4b9519e099a9?q=80&w=600&auto=format&fit=crop" class="card-img opacity-50 h-100 w-100" style="object-fit:cover">
                <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center">
                    <h3 class="card-title fw-bold">FEMME</h3>
                    <a href="<?php echo URLROOT; ?>/shop" class="btn btn-outline-light btn-sm mt-2">Explorer</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold mb-0">Derniers Arrivages</h2>
                <div style="width: 50px; height: 3px; background: var(--primary-color); margin-top: 10px;"></div>
            </div>
            <a href="<?php echo URLROOT; ?>/shop" class="btn btn-outline-dark rounded-pill">Tout voir <i class="fas fa-arrow-right ms-2"></i></a>
        </div>

        <div class="row g-4">
            <?php if(empty($data['products'])): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Aucun produit pour le moment. Connectez-vous à l'admin pour remplir la boutique !</p>
                </div>
            <?php else: ?>
                
                <?php foreach($data['products'] as $product): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card product-card h-100">
                        <div class="position-relative overflow-hidden">
                            <?php if($product->main_image): ?>
                                <img src="<?php echo URLROOT . '/' . $product->main_image; ?>" class="card-img-top" alt="<?php echo $product->name; ?>">
                            <?php else: ?>
                                <img src="https://dummyimage.com/400x400/dee2e6/6c757d.jpg" class="card-img-top">
                            <?php endif; ?>
                            
                            <?php if($product->stock < 5 && $product->stock > 0): ?>
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">Bientôt épuisé</span>
                            <?php elseif($product->stock == 0): ?>
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">Rupture</span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <small class="text-muted mb-1"><?php echo $product->category_name; ?></small>
                            <h5 class="card-title mb-2 text-truncate"><?php echo $product->name; ?></h5>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="price-tag"><?php echo number_format($product->price, 2); ?> €</span>
                                <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-primary rounded-circle" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row text-center g-4">
        <div class="col-md-4">
            <i class="fas fa-shipping-fast fa-3x text-muted mb-3"></i>
            <h5>Livraison Rapide</h5>
            <p class="text-muted small">Partout au Togo et Ghana en 48h.</p>
        </div>
        <div class="col-md-4">
            <i class="fas fa-lock fa-3x text-muted mb-3"></i>
            <h5>Paiement Sécurisé</h5>
            <p class="text-muted small">Flooz, T-Money et Cartes Bancaires.</p>
        </div>
        <div class="col-md-4">
            <i class="fas fa-headset fa-3x text-muted mb-3"></i>
            <h5>Service Client 24/7</h5>
            <p class="text-muted small">Une question ? Nous sommes là.</p>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>