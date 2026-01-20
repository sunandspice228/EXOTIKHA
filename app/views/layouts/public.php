<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Exotikha Store' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="icon" type="image/png" href="<?= url('/assets/img/favicon.png') ?>">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        :root {
            --primary: #111;
            --accent: #c6a87c;
            --danger: #dc3545;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--primary);
            background-color: #fff;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; transition: 0.3s; }
        a:hover { color: var(--accent); }

        /* NAVBAR */
        .top-bar { background: #000; color: #fff; font-size: 11px; padding: 8px 0; text-transform: uppercase; letter-spacing: 1px; }
        .main-navbar { background: #fff; padding: 15px 0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); position: sticky; top: 0; z-index: 1000; }
        .nav-link { font-weight: 500; font-size: 13px; text-transform: uppercase; margin: 0 10px; color: #000 !important; }
        .nav-link:hover { color: var(--accent) !important; }
        .badge-count { position: absolute; top: -5px; right: -8px; background: #000; color: #fff; font-size: 9px; padding: 2px 5px; border-radius: 50%; }

        /* SLIDER */
        .hero-slider { position: relative; height: 550px; overflow: hidden; background: #000; }
        .slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease-in-out; background-size: cover; background-position: center; }
        .slide::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); }
        .slide.active { opacity: 1; }
        .slide-content { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; color: white; transform: translateY(30px); opacity: 0; transition: all 1s ease; }
        .slide.active .slide-content { transform: translateY(0); opacity: 1; }
        .btn-hero { background: #fff; color: #000; padding: 12px 30px; text-transform: uppercase; font-weight: 700; border: none; margin-top: 20px; transition: 0.3s; }
        .btn-hero:hover { background: var(--accent); color: #fff; }

        /* TABS */
        .product-tabs { display: flex; justify-content: center; gap: 20px; margin-bottom: 40px; border-bottom: 1px solid #eee; }
        .tab-btn { background: none; border: none; padding: 10px 20px; font-size: 14px; font-weight: 600; text-transform: uppercase; color: #888; cursor: pointer; border-bottom: 2px solid transparent; }
        .tab-btn.active { color: #000; border-bottom: 2px solid #000; }
        .tab-content { display: none; animation: fadeIn 0.5s; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from{opacity:0; transform:translateY(10px);} to{opacity:1; transform:translateY(0);} }

        /* PRODUCT CARD */
        .product-card { background: #fff; transition: 0.3s; }
        .product-img-wrap { position: relative; overflow: hidden; padding-top: 125%; background: #f4f4f4; }
        .product-img-wrap img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
        .product-card:hover img { transform: scale(1.08); }
        .product-actions { position: absolute; bottom: 15px; left: 0; right: 0; display: flex; justify-content: center; gap: 10px; opacity: 0; transform: translateY(20px); transition: 0.3s; }
        .product-card:hover .product-actions { opacity: 1; transform: translateY(0); }
        .action-btn { width: 35px; height: 35px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #000; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: 0.2s; }
        .action-btn:hover { background: #000; color: #fff; }

        /* FOOTER */
        footer { background: #111; color: #bbb; padding: 70px 0 30px; margin-top: 80px; }
        footer h5 { color: #fff; font-size: 14px; text-transform: uppercase; margin-bottom: 20px; letter-spacing: 1px; }
        footer a { color: #bbb; font-size: 13px; }
        footer a:hover { color: #fff; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-none d-md-block">
                <i class="fa-solid fa-truck-fast me-1"></i> LIVRAISON INTERNATIONALE
            </div>
            <div class="d-flex gap-3 ms-auto">
                <a href="<?= url('/lang/fr') ?>">FR</a>
                <a href="<?= url('/currency/GHS') ?>">GHS</a>
                <a href="<?= url('/currency/XOF') ?>">XOF</a>
                <a href="<?= url('/currency/USD') ?>">USD</a>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container">
            <a href="<?= url('/') ?>" class="navbar-brand fw-bold fs-3" style="letter-spacing: 1px;">EXOTIKHA<span style="color: var(--accent);">.</span></a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse justify-content-center" id="navMain">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="<?= url('/') ?>" class="nav-link">Accueil</a></li>
                    <li class="nav-item"><a href="<?= url('/?new=1') ?>" class="nav-link">Nouveautés</a></li>
                    <li class="nav-item"><a href="<?= url('/?is_promo=1') ?>" class="nav-link text-danger">Promotions</a></li>
                    <li class="nav-item"><a href="<?= url('/?category=Femme') ?>" class="nav-link">Femme</a></li>
                    <li class="nav-item"><a href="<?= url('/?category=Homme') ?>" class="nav-link">Homme</a></li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-3 d-none d-lg-flex">
                <a href="<?= isset($_SESSION['user_id']) ? url('/account') : url('/login') ?>" class="text-dark fs-5"><i class="fa-regular fa-user"></i></a>
                <a href="<?= url('/cart') ?>" class="text-dark fs-5 position-relative">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <?php if(cart_count() > 0): ?><span class="badge-count"><?= cart_count() ?></span><?php endif; ?>
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-3"><?php display_flash_message(); ?></div>

    <main class="flex-grow-1">
        <?= $content ?>
    </main>

    <footer>
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <h4 class="text-white fw-bold mb-4">EXOTIKHA.</h4>
                    <p class="small text-secondary">Mode africaine moderne.</p>
                </div>
                <div class="col-lg-2 col-6">
                    <h5>Explorer</h5>
                    <ul class="list-unstyled space-y-2"><li><a href="<?= url('/') ?>">Accueil</a></li><li><a href="<?= url('/cart') ?>">Panier</a></li></ul>
                </div>
                <div class="col-lg-4">
                    <h5>Newsletter</h5>
                    <form class="d-flex gap-2"><input type="email" class="form-control form-control-sm bg-dark border-secondary text-white" placeholder="Email..."><button class="btn btn-light btn-sm fw-bold">OK</button></form>
                </div>
            </div>
            <div class="text-center mt-5 pt-4 border-top border-secondary text-secondary small">&copy; <?= date('Y') ?> Exotikha.</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>