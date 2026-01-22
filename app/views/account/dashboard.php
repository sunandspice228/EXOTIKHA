<?php ob_start(); 
// On récupère l'onglet actif, sinon 'dashboard' par défaut
$activeTab = $tab ?? 'dashboard';
?>

<div class="container py-5 my-5">
    <div class="row">
        
        <div class="col-lg-3 mb-5">
            <div class="p-4 bg-light border-0 rounded-0">
                <div class="text-center mb-4 border-bottom pb-4">
                    <div class="avatar-circle bg-dark text-white d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 70px; height: 70px; font-size: 1.5rem; border-radius: 50%;">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <h5 class="fw-bold mb-1 font-serif"><?= e($_SESSION['user_name'] ?? 'Client') ?></h5>
                </div>

                <div class="list-group list-group-flush bg-transparent">
                    <a href="<?= url('/account') ?>" class="list-group-item list-group-item-action bg-transparent border-0 ps-0 <?= $activeTab === 'dashboard' ? 'fw-bold text-dark' : 'text-muted' ?>">
                        <i class="fa-solid fa-gauge me-2" style="width:25px"></i> <?= __('Tableau de bord', 'Dashboard') ?>
                    </a>
                    
                    <a href="<?= url('/account/orders') ?>" class="list-group-item list-group-item-action bg-transparent border-0 ps-0 <?= $activeTab === 'orders' ? 'fw-bold text-dark' : 'text-muted' ?>">
                        <i class="fa-solid fa-bag-shopping me-2" style="width:25px"></i> <?= __('Mes Commandes', 'My Orders') ?>
                    </a>
                    
                    <a href="<?= url('/account/profile') ?>" class="list-group-item list-group-item-action bg-transparent border-0 ps-0 <?= $activeTab === 'profile' ? 'fw-bold text-dark' : 'text-muted' ?>">
                        <i class="fa-regular fa-user me-2" style="width:25px"></i> <?= __('Mon Profil', 'My Profile') ?>
                    </a>
                    
                    <a href="<?= url('/account/addresses') ?>" class="list-group-item list-group-item-action bg-transparent border-0 ps-0 <?= $activeTab === 'addresses' ? 'fw-bold text-dark' : 'text-muted' ?>">
                        <i class="fa-solid fa-location-dot me-2" style="width:25px"></i> <?= __('Adresses', 'Addresses') ?>
                    </a>
                    
                    <a href="<?= url('/logout') ?>" class="list-group-item list-group-item-action bg-transparent border-0 ps-0 text-danger mt-3 border-top pt-3">
                        <i class="fa-solid fa-right-from-bracket me-2" style="width:25px"></i> <?= __('Déconnexion', 'Logout') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            
            <?php if($activeTab === 'dashboard'): ?>
                <h2 class="fw-bold font-serif mb-4"><?= __('Mon Espace', 'My Dashboard') ?></h2>
                <div class="alert alert-light border rounded-0 shadow-sm mb-5 p-4">
                    <?= __('Bonjour', 'Hello') ?> <strong><?= e($_SESSION['user_name']) ?></strong> !
                    <p class="mb-0 text-muted small mt-2">
                        <?= __('Bienvenue sur votre espace personnel. Utilisez le menu pour naviguer.', 'Welcome to your account dashboard.') ?>
                    </p>
                </div>
                <h5 class="fw-bold mb-3"><?= __('Dernières Commandes', 'Last Orders') ?></h5>
                <?php include ROOT_PATH . '/app/views/partials/orders_table.php'; ?>


            <?php elseif($activeTab === 'orders'): ?>
                <h2 class="fw-bold font-serif mb-4"><?= __('Historique des commandes', 'Order History') ?></h2>
                <?php include ROOT_PATH . '/app/views/partials/orders_table.php'; ?>


            <?php elseif($activeTab === 'profile'): ?>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold font-serif mb-0"><?= __('Mon Profil', 'My Profile') ?></h2>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Ghana.svg/320px-Flag_of_Ghana.svg.png" width="30" class="border shadow-sm" alt="Ghana">
                </div>

                <form action="<?= url('/account/profile') ?>" method="POST" class="p-4 border bg-white shadow-sm">
                    <?= csrf_field() ?>
                    
                    <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2 mb-3"><?= __('Informations Personnelles', 'Personal Information') ?></h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= __('Nom Complet', 'Full Name') ?> <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-0 p-2" value="<?= e($user['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" class="form-control rounded-0 bg-light p-2" value="<?= e($user['email'] ?? '') ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= __('Téléphone (Mobile Money)', 'Phone Number / MoMo') ?></label>
                            <div class="input-group">
                                <span class="input-group-text rounded-0 bg-light border-end-0"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Ghana.svg/320px-Flag_of_Ghana.svg.png" width="16" class="me-1"> +233</span>
                                <input type="text" name="phone" class="form-control rounded-0 border-start-0" value="<?= e($user['phone'] ?? '') ?>" placeholder="24 123 4567">
                            </div>
                            <div class="form-text x-small text-muted">Utilisé pour les paiements MTN/Telecel/AT.</div>
                        </div>
                    </div>

                    <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2 mb-3 mt-4">
                        <?= __('Adresse de Livraison', 'Delivery Details') ?>
                    </h6>

                    <div class="row g-3">
                        
                        <div class="col-12">
                            <label class="form-label small fw-bold text-success">
                                <i class="fa-solid fa-location-crosshairs me-1"></i> Ghana Post GPS (Digital Address)
                            </label>
                            <input type="text" name="address" class="form-control rounded-0 p-2 border-success" value="<?= e($user['address'] ?? '') ?>" placeholder="Ex: GA-183-8164">
                            <div class="form-text x-small">L'adresse numérique est essentielle pour une livraison rapide à Accra et Kumasi.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= __('Ville / Quartier', 'City / Area') ?></label>
                            <input type="text" name="city" class="form-control rounded-0 p-2" value="<?= e($user['city'] ?? '') ?>" placeholder="Ex: East Legon, Accra">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= __('Région', 'Region') ?></label>
                            <select name="region" class="form-select rounded-0 p-2">
                                <option value="" disabled selected>Select Region...</option>
                                <option value="Greater Accra" <?= ($user['region'] ?? '') == 'Greater Accra' ? 'selected' : '' ?>>Greater Accra</option>
                                <option value="Ashanti" <?= ($user['region'] ?? '') == 'Ashanti' ? 'selected' : '' ?>>Ashanti (Kumasi)</option>
                                <option value="Central" <?= ($user['region'] ?? '') == 'Central' ? 'selected' : '' ?>>Central</option>
                                <option value="Eastern" <?= ($user['region'] ?? '') == 'Eastern' ? 'selected' : '' ?>>Eastern</option>
                                <option value="Western" <?= ($user['region'] ?? '') == 'Western' ? 'selected' : '' ?>>Western</option>
                                <option value="Volta" <?= ($user['region'] ?? '') == 'Volta' ? 'selected' : '' ?>>Volta</option>
                                <option value="Northern" <?= ($user['region'] ?? '') == 'Northern' ? 'selected' : '' ?>>Northern</option>
                                <option value="Other" <?= ($user['region'] ?? '') == 'Other' ? 'selected' : '' ?>>Other Region</option>
                            </select>
                        </div>

                        <input type="hidden" name="country" value="Ghana">
                    </div>

                    <div class="row mt-5">
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-dark rounded-0 px-5 py-3 text-uppercase fw-bold ls-1">
                                <i class="fa-regular fa-floppy-disk me-2"></i> <?= __('Enregistrer', 'Save Changes') ?>
                            </button>
                        </div>
                    </div>
                </form>


            <?php elseif($activeTab === 'addresses'): ?>
                <h2 class="fw-bold font-serif mb-4"><?= __('Adresse de livraison', 'Shipping Address') ?></h2>
                <form action="<?= url('/account/profile') ?>" method="POST" class="p-4 border bg-white">
                    <?= csrf_field() ?>
                    <input type="hidden" name="name" value="<?= e($user['name'] ?? '') ?>"> <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-uppercase"><?= __('Adresse', 'Address') ?></label>
                            <input type="text" name="address" class="form-control rounded-0" value="<?= e($user['address'] ?? '') ?>" placeholder="Quartier, Rue...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase"><?= __('Ville', 'City') ?></label>
                            <input type="text" name="city" class="form-control rounded-0" value="<?= e($user['city'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase"><?= __('Région', 'Region') ?></label>
                            <select name="region" class="form-select rounded-0">
                                <option value="Maritime" <?= ($user['region'] ?? '') == 'Maritime' ? 'selected' : '' ?>>Maritime (Lomé)</option>
                                <option value="Plateaux" <?= ($user['region'] ?? '') == 'Plateaux' ? 'selected' : '' ?>>Plateaux</option>
                                <option value="Centrale" <?= ($user['region'] ?? '') == 'Centrale' ? 'selected' : '' ?>>Centrale</option>
                                <option value="Kara" <?= ($user['region'] ?? '') == 'Kara' ? 'selected' : '' ?>>Kara</option>
                                <option value="Savanes" <?= ($user['region'] ?? '') == 'Savanes' ? 'selected' : '' ?>>Savanes</option>
                            </select>
                        </div>
                        <div class="col-12 mt-4">
                            <button class="btn btn-dark rounded-0 px-4 py-2 text-uppercase fw-bold"><?= __('Mettre à jour', 'Update Address') ?></button>
                        </div>
                    </div>
                </form>

            <?php endif; ?>

        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>