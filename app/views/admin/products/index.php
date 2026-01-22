<?php ob_start(); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <h3 class="fw-bold m-0">Catalogue</h3>
    <div class="d-flex gap-2 flex-grow-1 justify-content-end">
        <form action="" method="GET" class="d-flex">
            <input type="text" name="q" class="form-control" placeholder="Rechercher..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button class="btn btn-dark ms-2"><i class="fa-solid fa-search"></i></button>
            <?php if(!empty($search)): ?>
                <a href="<?= url('/admin/products') ?>" class="btn btn-light border ms-1"><i class="fa-solid fa-times"></i></a>
            <?php endif; ?>
        </form>
        <a href="<?= url('/admin/products/create') ?>" class="btn btn-dark fw-bold shadow-sm"><i class="fa-solid fa-plus me-2"></i> Ajouter</a>
    </div>
</div>

<form action="<?= url('/admin/products/bulk') ?>" method="POST" id="bulkForm">
    <?= csrf_field() ?>

    <div class="card-custom p-3 mb-3 bg-dark text-white d-flex align-items-center justify-content-between" id="bulkBar" style="display:none;">
        <div><span class="fw-bold me-2"><span id="selectedCount">0</span> sélectionné(s)</span></div>
        <div class="d-flex gap-2">
            <button type="submit" name="bulk_action" value="delete" class="btn btn-danger btn-sm fw-bold" onclick="return confirm('Confirmer suppression ?')">
                <i class="fa-solid fa-trash me-1"></i> Supprimer
            </button>
            <button type="button" class="btn btn-warning btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#bulkPromoModal">
                <i class="fa-solid fa-tags me-1"></i> Promo de masse
            </button>
        </div>
    </div>

    <div class="card-custom p-0 overflow-hidden">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4" style="width: 40px;"><input type="checkbox" class="form-check-input" id="selectAll" onclick="toggleAll(this)"></th>
                    <th>Produit</th>
                    <th>Info</th>
                    <th>Prix & Promo</th>
                    <th>Stock</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($products)): ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">Aucun produit.</td></tr>
                <?php else: ?>
                    <?php foreach($products as $p): ?>
                    <tr>
                        <td class="ps-4">
                            <input type="checkbox" class="form-check-input product-select" name="selected_products[]" value="<?= $p['id'] ?>" onclick="updateBulkBar()">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= url('/uploads/' . $p['image']) ?>" class="rounded border me-3 object-fit-cover" width="50" height="50">
                                <div><div class="fw-bold text-dark"><?= $p['name'] ?></div><small class="text-muted">#<?= $p['id'] ?></small></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border"><?= $p['category'] ?></span>
                            <?php if($p['type']): ?><span class="badge bg-light text-muted border ms-1"><?= $p['type'] ?></span><?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold"><?= format_price($p['price']) ?></span>
                                <?php if($p['is_promo']): ?>
                                    <div class="mt-1">
                                        <span class="text-decoration-line-through text-muted small me-1"><?= format_price($p['price']) ?></span>
                                        <span class="fw-bold text-danger"><?= format_price($p['promo_price']) ?></span>
                                    </div>
                                    <?php if(!empty($p['promo_end_date'])): ?>
                                        <small class="text-muted x-small">Fin: <?= date('d/m', strtotime($p['promo_end_date'])) ?></small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><span class="badge bg-<?= $p['stock'] < 5 ? 'warning' : 'success' ?> bg-opacity-10 text-<?= $p['stock'] < 5 ? 'warning' : 'success' ?>"><?= $p['stock'] ?></span></td>
                        <td class="text-end pe-4">
                            <a href="<?= url('/admin/products/edit/' . $p['id']) ?>" class="btn btn-sm btn-light border"><i class="fa-solid fa-pen"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="bulkPromoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title fw-bold">Activer Promo</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <p class="text-muted small">Active la promo sur la sélection. <strong class="text-danger">Les prix promo doivent être définis individuellement.</strong></p>
                    <div class="mb-3"><label class="form-label fw-bold">Début</label><input type="datetime-local" name="bulk_start" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Fin</label><input type="datetime-local" name="bulk_end" class="form-control" required></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="bulk_action" value="promo" class="btn btn-warning fw-bold">Appliquer</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function toggleAll(source) {
        document.querySelectorAll('.product-select').forEach(cb => cb.checked = source.checked);
        updateBulkBar();
    }
    function updateBulkBar() {
        let count = document.querySelectorAll('.product-select:checked').length;
        document.getElementById('selectedCount').innerText = count;
        document.getElementById('bulkBar').style.display = count > 0 ? 'flex' : 'none';
    }
</script>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>