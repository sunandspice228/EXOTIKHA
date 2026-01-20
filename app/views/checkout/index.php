<?php ob_start(); ?>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-7">
            <h4 class="fw-bold mb-4">Adresse de livraison</h4>
            <form action="<?= url('/checkout/process') ?>" method="POST" class="card p-4 border-0 shadow-sm">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nom Complet</label>
                    <input type="text" name="name" class="form-control" value="<?= e($user['name'] ?? '') ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= e($user['email'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Téléphone</label>
                        <input type="text" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Région</label>
                        <input type="text" name="region" class="form-control" value="<?= e($user['region'] ?? '') ?>" placeholder="ex: Maritime, Accra..." required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Ville</label>
                        <input type="text" name="city" class="form-control" value="<?= e($user['city'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Adresse précise</label>
                    <input type="text" name="address" class="form-control" value="<?= e($user['address'] ?? '') ?>" required>
                </div>

                <button class="btn btn-dark w-100 py-3 fw-bold mt-3">
                    Payer <?= format_price($total) ?> par Paystack
                </button>
            </form>
        </div>

        <div class="col-lg-5">
            <div class="card bg-light p-4 border-0">
                <h5 class="fw-bold mb-3">Votre commande</h5>
                <h2 class="text-success fw-bold"><?= format_price($total) ?></h2>
                <small class="text-muted">Incluant taxes et livraison</small>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>