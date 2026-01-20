<div class="col-6 col-md-4">
    <div class="product-card h-100 position-relative group">
        
        <?php if(is_on_promotion($p)): ?>
            <?php $percent = round((($p['price'] - $p['promo_price']) / $p['price']) * 100); ?>
            <span class="badge-custom bg-promo">-<?= $percent ?>%</span>
        <?php elseif(strtotime($p['created_at']) > strtotime('-7 days')): ?>
            <span class="badge-custom bg-new">NEW</span>
        <?php endif; ?>

        <a href="<?= url('/product/'.$p['id']) ?>" class="d-block overflow-hidden rounded-1">
            <div class="product-img-wrap">
                <img src="<?= $p['image'] ? url('/uploads/'.$p['image']) : 'https://dummyimage.com/600x800/f5f5f5/999' ?>" alt="<?= e($p['name']) ?>">
                
                <div class="overlay-btn">
                    Voir le produit
                </div>
            </div>
        </a>
        
        <div class="product-details">
            <div class="product-cat"><?= e($p['category']) ?></div>
            <h3 class="product-title text-truncate">
                <a href="<?= url('/product/'.$p['id']) ?>" class="text-dark text-decoration-none">
                    <?= get_tr($p, 'name') ?>
                </a>
            </h3>
            <div class="product-price">
                <?= format_product_price_html($p) ?>
            </div>
        </div>
    </div>
</div>