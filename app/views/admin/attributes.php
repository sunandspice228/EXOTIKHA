<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Attributs (Tailles & Couleurs)</h3>
</div>

<div class="row g-4">
    
    <div class="col-md-4">
        <div class="card-custom p-4 sticky-top" style="top: 20px;">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Ajouter</h5>
            
            <form action="<?= url('/admin/attributes/store') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase text-muted">Type</label>
                    <select name="type" class="form-select bg-light border-0">
                        <option value="size">Taille (S, M, L...)</option>
                        <option value="color">Couleur (Rouge, Bleu...)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase text-muted">Nom</label>
                    <input type="text" name="name" class="form-control bg-light border-0" placeholder="Ex: XL ou Rouge" required>
                </div>

                <button class="btn btn-dark w-100 fw-bold py-2 mt-2">
                    <i class="fa-solid fa-plus me-2"></i> AJOUTER
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card-custom p-0 overflow-hidden">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Type</th>
                        <th>Nom</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($attributes)): ?>
                        <tr><td colspan="3" class="text-center py-4 text-muted">Aucun attribut.</td></tr>
                    <?php else: ?>
                        <?php foreach($attributes as $attr): ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if($attr['type'] == 'size'): ?>
                                        <span class="badge bg-light text-dark border fw-normal">TAILLE</span>
                                    <?php else: ?>
                                        <span class="badge bg-dark fw-normal">COULEUR</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold"><?= $attr['name'] ?></td>
                                <td class="text-end pe-4">
                                    <a href="<?= url('/admin/attributes/delete/' . $attr['id']) ?>" 
                                       class="text-danger"
                                       onclick="return confirm('Supprimer ?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>