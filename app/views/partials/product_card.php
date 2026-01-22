<div class="col">
    <div class="card h-100 border-0 shadow-sm product-card position-relative">
        
        <?php if(!empty($product['is_promo'])): ?>
            <span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 small fw-bold z-1 m-2">
                PROMO
            </span>
        <?php endif; ?>

        <div class="position-relative overflow-hidden bg-light" style="padding-top: 120%;"> <?php 
                // Gestion du chemin image
                $img = $product['image'];
                if (!preg_match("~^(?:f|ht)tps?://~i", $img)) {
                    $img = url('/uploads/' . $img);
                }
            ?>
            <a href="<?= url('/product/' . $product['id']) ?>">
                <img src="<?= htmlspecialchars($img) ?>" 
                     class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" 
                     alt="<?= htmlspecialchars($product['name']) ?>"
                     onerror="this.src='https://via.placeholder.com/300x400?text=Exotikha'">
            </a>
            
            <div class="position-absolute bottom-0 w-100 p-2 text-center bg-white bg-opacity-75">
                <a href="<?= url('/product/' . $product['id']) ?>" class="btn btn-sm btn-dark w-100 rounded-0 text-uppercase" style="font-size: 0.7rem;">
                    <?= __('Voir', 'View') ?>
                </a>
            </div>
        </div>

        <div class="card-body text-center p-3">
            <div class="text-muted x-small text-uppercase mb-1"><?= $product['category'] ?? 'Mode' ?></div>
            <h6 class="card-title fw-bold mb-2 text-truncate">
                <a href="<?= url('/product/' . $product['id']) ?>" class="text-dark text-decoration-none">
                    <?= htmlspecialchars($product['name']) ?>
                </a>
            </h6>
            
            <div class="price">
                <?php if(!empty($product['is_promo'])): ?>
                    <span class="text-muted text-decoration-line-through me-2 small">
                        <?= format_price($product['old_price'] ?? $product['promo_price'] ?? 0) ?>
                    </span>
                    <span class="text-danger fw-bold"><?= format_price($product['price']) ?></span>
                <?php else: ?>
                    <span class="fw-bold"><?= format_price($product['price']) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>