<?php $title = "Inscription - Exotikha"; require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>

<div class="container-fluid p-0">
    <div class="row g-0" style="min-height: 80vh;">
        <div class="col-md-6 bg-white d-flex align-items-center justify-content-center order-2 order-md-1">
            <div class="w-100 p-5" style="max-width: 500px;">
                <div class="text-center mb-5">
                    <h3 class="fw-bold mb-3">Create Account</h3>
                    <p class="text-muted">Join the Exotikha community today.</p>
                </div>

                <?php if(isset($_SESSION['flash'])): foreach($_SESSION['flash'] as $type => $msg): ?>
                    <div class="alert alert-<?= $type == 'error'?'danger':'success' ?> rounded-0"><?= $msg ?></div>
                <?php endforeach; unset($_SESSION['flash']); endif; ?>

                <form action="<?= url('/register') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label text-uppercase x-small fw-bold">Full Name</label>
                        <input type="text" name="full_name" class="form-control rounded-0 p-3 bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-uppercase x-small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-0 p-3 bg-light border-0" required>
                    </div>
                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <label class="form-label text-uppercase x-small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control rounded-0 p-3 bg-light border-0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-uppercase x-small fw-bold">Confirm</label>
                            <input type="password" name="confirm_password" class="form-control rounded-0 p-3 bg-light border-0" required>
                        </div>
                    </div>
                    
                    <button class="btn btn-dark w-100 rounded-0 py-3 fw-bold text-uppercase">Register</button>

                    <div class="text-center mt-4">
                        <p class="small text-muted">Already a member? <a href="<?= url('/login') ?>" class="text-dark fw-bold">Sign in</a></p>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6 d-none d-md-block order-1 order-md-2" style="background: url('https://images.unsplash.com/photo-1496747611176-843222e1e57c?q=80&w=1000') center/cover;">
            <div class="h-100 w-100 bg-black bg-opacity-40 d-flex align-items-center justify-content-center">
                <div class="text-white text-center p-5">
                    <h2 class="display-4 fw-bold" style="font-family: 'Playfair Display', serif;">Join the Elite</h2>
                    <p class="lead">Exclusive offers, early access, and more.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>