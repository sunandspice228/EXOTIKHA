<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Commande #<?= $order['id'] ?></h3>
    <a href="<?= url('/admin/orders') ?>" class="btn btn-light border"><i class="fa-solid fa-arrow-left"></i> Retour</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-custom p-4 mb-4">
            <h5 class="fw-bold border-bottom pb-3 mb-3">Articles commandés</h5>
            <?php foreach($items as $item): ?>
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <img src="<?= url('/uploads/' . $item['image']) ?>" width="60" class="rounded border me-3">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1"><?= $item['name'] ?></h6>
                        <small class="text-muted">Quantité: <?= $item['quantity'] ?></small>
                    </div>
                    <div class="fw-bold"><?= format_price($item['price']) ?></div>
                </div>
            <?php endforeach; ?>
            <div class="d-flex justify-content-between mt-3">
                <span class="fw-bold h5">Total</span>
                <span class="fw-bold h5 text-primary"><?= format_price($order['total_amount']) ?></span>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-custom p-4 mb-4">
            <h5 class="fw-bold mb-3">Client</h5>
            <div class="d-flex align-items-center mb-3">
                <div class="avatar-circle bg-light text-dark fw-bold rounded-circle d-flex align-items-center justify-content-center me-3" style="width:40px; height:40px;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <div class="fw-bold"><?= $order['user_name'] ?></div>
                    <div class="text-muted small"><?= $order['email'] ?></div>
                </div>
            </div>
            <hr>
            <h6 class="fw-bold small text-uppercase">Adresse de livraison</h6>
            <p class="text-muted small mb-0"><?= nl2br($order['address'] ?? 'Non renseignée') ?></p>
        </div>

        <div class="card-custom p-4 bg-dark text-white">
            <h5 class="fw-bold mb-3">Traitement</h5>
            <form action="<?= url('/admin/orders/update') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                
                <label class="small text-muted mb-2">Statut actuel</label>
                <select name="status" class="form-select mb-3">
                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>En attente</option>
                    <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Expédié</option>
                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Livré / Terminé</option>
                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                </select>

                <button class="btn btn-warning w-100 fw-bold">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>