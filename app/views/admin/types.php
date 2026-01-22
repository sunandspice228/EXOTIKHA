<?php ob_start(); ?>
<h3 class="fw-bold mb-4">Types de Produits</h3>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card-custom p-4">
            <h5 class="fw-bold mb-3">Nouveau Type</h5>
            <form action="<?= url('/admin/types/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="fw-bold small text-muted">Nom</label>
                    <input type="text" name="name" class="form-control" placeholder="Ex: Robe, T-shirt..." required>
                </div>
                <button class="btn btn-dark w-100 fw-bold">AJOUTER</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card-custom p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light"><tr><th class="ps-4">Nom</th><th class="text-end pe-4">Action</th></tr></thead>
                <tbody>
                    <?php foreach($types as $t): ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?= $t['name'] ?></td>
                        <td class="text-end pe-4">
                            <a href="<?= url('/admin/types/delete/' . $t['id']) ?>" class="text-danger" onclick="return confirm('Supprimer ?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>