<?php ob_start(); 
$prodModel = new \App\Models\Product();
?>

<div class="container py-5">
    <h1 class="fw-bold mb-5 text-center">Votre Panier</h1>

    <?php if(empty($cart)): ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-bag-shopping fa-3x text-muted mb-3"></i>
            <h3 class="fw-bold text-muted">Votre panier est vide</h3>
            <p class="mb-4">Il semblerait que vous n'ayez pas encore craqué !</p>
            <a href="<?= url('/') ?>" class="btn btn-dark px-5 py-3 rounded-0 text-uppercase fw-bold">Découvrir la collection</a>
        </div>
    <?php else: ?>

        <div class="row g-5">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <?php 
                        $total = 0;
                        foreach($cart as $id => $qty): 
                            $p = $prodModel->findById($id);
                            if(!$p) continue;
                            
                            $price = is_on_promotion($p) ? $p['promo_price'] : $p['price'];
                            $subtotal = $price * $qty;
                            $total += $subtotal;
                        ?>
                        <div class="d-flex align-items-center gap-4 p-4 border-bottom">
                            <a href="<?= url('/product/'.$p['id']) ?>">
                                <img src="<?= url('/uploads/'.$p['image']) ?>" class="rounded object-fit-cover" style="width: 80px; height: 100px;">
                            </a>
                            
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">
                                    <a href="<?= url('/product/'.$p['id']) ?>" class="text-dark text-decoration-none">
                                        <?= get_tr($p, 'name') ?>
                                    </a>
                                </h6>
                                <div class="text-muted small mb-2"><?= e($p['category']) ?></div>
                                <div class="text-dark fw-bold"><?= format_price($price) ?></div>
                            </div>

                            <form action="<?= url('/cart/update') ?>" method="POST" class="d-flex align-items-center">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $id ?>">
                                <input type="number" name="qty" value="<?= $qty ?>" min="1" max="<?= $p['stock'] ?>" class="form-control form-control-sm text-center border-secondary" style="width: 60px;" onchange="this.form.submit()">
                            </form>

                            <a href="<?= url('/cart/remove/'.$id) ?>" class="text-muted hover-text-danger" title="Retirer">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="<?= url('/') ?>" class="text-dark text-decoration-none fw-bold">
                        <i class="fa-solid fa-arrow-left me-2"></i> Continuer vos achats
                    </a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-light p-4 rounded">
                    <h5 class="fw-bold mb-4">Résumé de la commande</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total</span>
                        <span><?= format_price($total) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Livraison</span>
                        <span class="text-success">Gratuite</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4 fs-5 fw-bold">
                        <span>Total</span>
                        <span><?= format_price($total) ?></span>
                    </div>

                    <a href="<?= url('/checkout') ?>" class="btn btn-dark w-100 py-3 text-uppercase fw-bold rounded-0 shadow-sm">
                        Procéder au paiement
                    </a>
                    
                    <div class="mt-3 text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/MasterCard_Logo.svg/1280px-MasterCard_Logo.svg.png" style="height: 20px;" class="mx-1 opacity-50">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png" style="height: 15px;" class="mx-1 opacity-50">
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<style>
    .hover-text-danger:hover { color: #dc3545 !important; }
</style>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>