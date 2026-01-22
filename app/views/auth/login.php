<?php $title = "Connexion - Exotikha"; require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>

<div class="container-fluid p-0">
    <div class="row g-0" style="min-height: 80vh;">
        <div class="col-md-6 d-none d-md-block" style="background: url('https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=1000') center/cover;">
            <div class="h-100 w-100 bg-black bg-opacity-50 d-flex align-items-center justify-content-center">
                <div class="text-white text-center p-5">
                    <h2 class="display-4 fw-bold" style="font-family: 'Playfair Display', serif;">Welcome Back</h2>
                    <p class="lead">Reconnect with elegance and style.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 bg-white d-flex align-items-center justify-content-center">
            <div class="w-100 p-5" style="max-width: 500px;">
                <div class="text-center mb-5">
                    <h3 class="fw-bold mb-3">Sign In</h3>
                    <p class="text-muted">Enter your details to access your account</p>
                </div>

                <?php if(isset($_SESSION['flash'])): foreach($_SESSION['flash'] as $type => $msg): ?>
                    <div class="alert alert-<?= $type == 'error'?'danger':'success' ?> rounded-0"><?= $msg ?></div>
                <?php endforeach; unset($_SESSION['flash']); endif; ?>

                <form action="<?= url('/login') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="form-label text-uppercase x-small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-0 p-3 bg-light border-0" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-uppercase x-small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control rounded-0 p-3 bg-light border-0" placeholder="••••••••" required>
                    </div>
                    
                    <button class="btn btn-dark w-100 rounded-0 py-3 fw-bold text-uppercase">Sign In</button>
                    
                    <div class="text-center mt-4">
                        <p class="small text-muted">Don't have an account? <a href="<?= url('/register') ?>" class="text-dark fw-bold">Create one</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>