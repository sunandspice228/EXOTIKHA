<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exotikha Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --sidebar-bg: #111827; /* Noir profond */
            --sidebar-text: #9ca3af;
            --sidebar-active: #ffffff;
            --gold: #d4af37;
            --bg-light: #f3f4f6;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: #1f2937;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .brand-logo {
            font-family: 'Playfair Display', serif;
            color: white;
            font-size: 1.5rem;
            letter-spacing: 1px;
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
        }

        .nav-category {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #4b5563;
            padding: 25px 25px 10px;
            font-weight: 700;
        }

        .nav-link {
            color: var(--sidebar-text);
            padding: 10px 25px;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-link i { width: 24px; font-size: 1rem; opacity: 0.8; }

        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.03);
        }

        .nav-link.active {
            color: var(--sidebar-active);
            background: rgba(212, 175, 55, 0.1); /* Gold léger */
            border-left-color: var(--gold);
        }

        /* CONTENT */
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        /* UTILS */
        .card-custom {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }
        
        .avatar-circle {
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <a href="<?= url('/admin/dashboard') ?>" class="brand-logo text-decoration-none">
        <i class="fa-solid fa-gem text-warning me-2"></i> EXOTIKHA
    </a>

    <div class="d-flex flex-column flex-grow-1 overflow-auto">
        
        <div class="mt-2">
            <a href="<?= url('/admin/dashboard') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i> Vue d'ensemble
            </a>
        </div>

        <span class="nav-category">Commerce</span>
        <a href="<?= url('/admin/orders') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'orders') ? 'active' : '' ?>">
            <i class="fa-solid fa-cart-shopping"></i> Commandes
        </a>
        <a href="<?= url('/admin/customers') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'customers') ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i> Clients
        </a>

        <span class="nav-category">Catalogue</span>
        <a href="<?= url('/admin/products') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'products') ? 'active' : '' ?>">
            <i class="fa-solid fa-shirt"></i> Produits
        </a>
        <a href="<?= url('/admin/categories') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'categories') ? 'active' : '' ?>">
            <i class="fa-solid fa-layer-group"></i> Catégories
        </a>
        <a href="<?= url('/admin/types') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'types') ? 'active' : '' ?>">
            <i class="fa-solid fa-tags"></i> Types
        </a>
        <a href="<?= url('/admin/attributes') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'attributes') ? 'active' : '' ?>">
            <i class="fa-solid fa-palette"></i> Attributs
        </a>

        <div class="mt-auto mb-4">
            <span class="nav-category">Système</span>
            <a href="<?= url('/') ?>" target="_blank" class="nav-link">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Voir le site
            </a>
            <a href="<?= url('/logout') ?>" class="nav-link text-danger">
                <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
            </a>
        </div>
    </div>
</nav>

<main class="main-content">
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h4 class="fw-bold m-0 text-dark">Espace Administration</h4>
            <small class="text-muted">Bienvenue, Admin.</small>
        </div>
        <div class="d-flex align-items-center bg-white px-4 py-2 rounded-pill shadow-sm">
            <div class="avatar-circle bg-dark text-white me-3">A</div>
            <div class="line-height-sm">
                <span class="d-block fw-bold small text-dark">Administrateur</span>
                <span class="d-block x-small text-muted">Superviseur</span>
            </div>
        </div>
    </div>

    <?php if(isset($_SESSION['flash'])): ?>
        <?php foreach($_SESSION['flash'] as $type => $message): ?>
            <div class="alert alert-<?= $type == 'error' ? 'danger' : 'success' ?> border-0 shadow-sm mb-4 fade show">
                <i class="fa-solid fa-circle-info me-2"></i> <?= $message ?>
            </div>
        <?php endforeach; unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?= $content ?>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>