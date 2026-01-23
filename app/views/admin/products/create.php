<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<a href="<?php echo URLROOT; ?>/admin/products" class="btn btn-light mb-3">&laquo; Retour</a>
<h1>Ajouter un produit</h1>

<div class="card card-body bg-light mt-3">
    <form action="<?php echo URLROOT; ?>/admin/products_add" method="post" enctype="multipart/form-data">
        <?php echo csrfField(); ?>
        
        <?php if(!empty($data['error'])): ?>
            <div class="alert alert-danger"><?php echo $data['error']; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nom du produit *</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
    <label>Genre / Cible *</label>
    <select name="gender" class="form-select" required>
        <option value="uni">Unisexe (Par défaut)</option>
        <option value="men">Homme</option>
        <option value="women">Femme</option>
        <option value="kids">Enfant</option>
    </select>
</div>
            <div class="col-md-3 mb-3">
                <label>Prix (€) *</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
                <label>Stock *</label>
                <input type="number" name="stock" class="form-control" value="1" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Catégorie</label>
                <select name="category_id" id="category_select" class="form-select" onchange="loadTypes(this.value)" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach($data['categories'] as $cat) : ?>
                        <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Type (Sous-catégorie)</label>
                <select name="type_id" id="type_select" class="form-select">
                    <option value="">Sélectionner une catégorie d'abord</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label>Images (Multiple)</label>
            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
            <small class="text-muted">Maintenez CTRL pour sélectionner plusieurs images.</small>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer le produit</button>
    </form>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>   