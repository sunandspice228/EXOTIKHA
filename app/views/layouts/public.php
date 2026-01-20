<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exotikha - Mode & Beauté</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .product-card img { height: 250px; object-fit: cover; }
        .bg-black { background-color: #000; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <header class="sticky-top bg-white border-bottom py-3 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            
            <a href="<?= url('/') ?>" class="text-decoration-none text-dark fs-3 fw-bold">
                Exotikha<span class="text-danger">.</span>
            </a>

            <form action="<?= url('/') ?>" class="d-none d-md-flex w-50">
                <div class="input-group">
                    <input type="text" name="q" class="form-control border-end-0 rounded-start-pill ps-4" placeholder="<?= lang('search_placeholder') ?>">
                    <button class="btn btn-outline-secondary border-start-0 rounded-end-pill pe-4"><i class="fa fa-search"></i></button>
                </div>
            </form>

            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <a href="#" class="text-dark fw-bold text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <?= strtoupper($_SESSION['lang'] ?? 'FR') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= url('/lang/fr') ?>">Français</a></li>
                        <li><a class="dropdown-item" href="<?= url('/lang/en') ?>">English</a></li>
                    </ul>
                </div>

                <a href="<?= isset($_SESSION['user_id']) ? url('/account') : url('/login') ?>" class="text-dark">
                    <i class="fa-regular fa-user fa-lg"></i>
                </a>

                <a href="<?= url('/cart') ?>" class="position-relative text-dark">
                    <i class="fa-solid fa-bag-shopping fa-lg"></i>
                    <?php if (cart_count() > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= cart_count() ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </header>

    <div class="container mt-3">
        <?php display_flash_message(); ?>
    </div>

    <main class="flex-grow-1">
        <?= $content ?>
    </main>

    <footer class="bg-black text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">Exotikha.</h5>
                    <p class="text-white-50 small">L'élégance africaine livrée chez vous.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold text-uppercase">Liens</h6>
                    <ul class="list-unstyled small">
                        <li><a href="<?= url('/') ?>" class="text-white-50 text-decoration-none">Accueil</a></li>
                        <li><a href="<?= url('/account') ?>" class="text-white-50 text-decoration-none">Mon Compte</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold text-uppercase">Paiement</h6>
                    <div class="fs-4 text-white-50">
                        <i class="fa-brands fa-cc-visa"></i>
                        <i class="fa-brands fa-cc-mastercard"></i>
                    </div>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center text-white-50 small">&copy; <?= date('Y') ?> Exotikha - Tous droits réservés.</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>