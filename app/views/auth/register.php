<?php ob_start(); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4 shadow-sm border-0">
                <h3 class="fw-bold text-center mb-4"><?= lang('register') ?></h3>
                
                <form action="<?= url('/register') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('password') ?></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('confirm_password') ?></label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button class="btn btn-success w-100 py-2 fw-bold"><?= lang('register') ?></button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="<?= url('/login') ?>" class="text-decoration-none small">Déjà un compte ? Connectez-vous</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>