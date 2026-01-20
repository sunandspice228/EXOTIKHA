<?php ob_start(); ?>

<?php if(!$isSearching): ?>
<div class="hero-slider">
    <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1600&q=80');">
        <div class="slide-content">
            <h1 class="display-3 fw-bold mb-3">Collection Été 2026</h1>
            <p class="lead mb-4 fw-light">L'élégance naturelle, conçue pour vous.</p>
            <a href="#shop" class="btn-hero">Découvrir</a>
        </div>
    </div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1600&q=80');">
        <div class="slide-content">
            <h1 class="display-3 fw-bold mb-3">Nouvelle Vague</h1>
            <p class="lead mb-4 fw-light">Des styles audacieux pour une saison unique.</p>
            <a href="#shop" class="btn-hero">Voir les Nouveautés</a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="container py-5">
    
    <?php if(!$isSearching): ?>
    <div class="row text-center mb-5 pb-4 border-bottom g-4">
        <div class="col-6 col-md-3">
            <i class="fa-solid fa-truck-fast fa-2x mb-3 text-secondary"></i>
            <h6 class="fw-bold text-uppercase small ls-1">Livraison Rapide</h6>
        </div>
        <div class="col-6 col-md-3">
            <i class="fa-regular fa-credit-card fa-2x mb-3 text-secondary"></i>
            <h6 class="fw-bold text-uppercase small ls-1">Paiement Sécurisé</h6>
        </div>
        <div class="col-6 col-md-3">
            <i class="fa-solid fa-arrow-rotate-left fa-2x mb-3 text-secondary"></i>
            <h6 class="fw-bold text-uppercase small ls-1">Retours Gratuits</h6>
        </div>
        <div class="col-6 col-md-3">
            <i class="fa-solid fa-headset fa-2x mb-3 text-secondary"></i>
            <h6 class="fw-bold text-uppercase small ls-1">Support 24/7</h6>
        </div>
    </div>
    <?php endif; ?>

    <div id="shop">
        <?php if($isSearching): ?>
            <h4 class="fw-bold mb-4">Résultats de votre recherche</h4>
            <div class="row g-4">
                <?php if(empty($products)): ?>
                    <div class="col-12 py-5 text-center text-muted">Aucun produit ne correspond à vos critères.</div>
                <?php else: ?>
                    <?php foreach($products as $p): include ROOT_PATH . '/app/views/partials/product_card_dynamic.php'; endforeach; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-4">Nos Collections</h2>
                <div class="product-tabs">
                    <button class="tab-btn active" onclick="openTab(event, 'new')">Nouveautés</button>
                    <button class="tab-btn" onclick="openTab(event, 'promo')">Promotions</button>
                    <button class="tab-btn" onclick="openTab(event, 'all')">Tout voir</button>
                </div>
            </div>

            <div id="new" class="tab-content active">
                <div class="row g-4">
                    <?php if(empty($new_arrivals)): ?><div class="col-12 text-center text-muted">Bientôt disponible.</div><?php endif; ?>
                    <?php foreach($new_arrivals as $p): include ROOT_PATH . '/app/views/partials/product_card_dynamic.php'; endforeach; ?>
                </div>
            </div>

            <div id="promo" class="tab-content">
                <div class="row g-4">
                    <?php if(empty($promotions)): ?><div class="col-12 text-center text-muted">Pas de promotions en cours.</div><?php endif; ?>
                    <?php foreach($promotions as $p): include ROOT_PATH . '/app/views/partials/product_card_dynamic.php'; endforeach; ?>
                </div>
            </div>

            <div id="all" class="tab-content">
                <div class="row g-4">
                    <?php foreach(array_slice($products ?? [], 0, 12) as $p): include ROOT_PATH . '/app/views/partials/product_card_dynamic.php'; endforeach; ?>
                </div>
                <div class="text-center mt-5">
                    <a href="<?= url('/?category=all') ?>" class="btn btn-outline-dark rounded-0 px-5 py-2">VOIR TOUT LE CATALOGUE</a>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<script>
    // Slider
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    if(slides.length > 0) {
        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 5000);
    }

    // Tabs
    function openTab(evt, tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabName).classList.add('active');
        evt.currentTarget.classList.add('active');
    }
</script>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>