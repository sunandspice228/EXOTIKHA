<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/png" href="<?= url('/assets/img/favicon.ico') ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Exotikha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100 min-h-screen flex">

    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col fixed h-full">
        <div class="h-16 flex items-center justify-center font-bold text-white text-xl border-b border-slate-800">
            Exotikha Admin
        </div>
        <nav class="flex-1 px-2 py-4 space-y-2">
            <a href="<?= url('/admin/dashboard') ?>" class="block px-4 py-2 rounded hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-gauge mr-2"></i> Dashboard
            </a>
            <a href="<?= url('/admin/products') ?>" class="block px-4 py-2 rounded hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-shirt mr-2"></i> Produits
            </a>
            <a href="<?= url('/admin/attributes') ?>" class="block px-4 py-2 rounded hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-tags mr-2"></i> Attributs
            </a>
            <a href="<?= url('/admin/orders') ?>" class="block px-4 py-2 rounded hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-box mr-2"></i> Commandes
            </a>
        </nav>
        <div class="p-4 border-t border-slate-800">
            <a href="<?= url('/admin/logout') ?>" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white py-2 rounded">
                Déconnexion
            </a>
        </div>
    </aside>

    <main class="ml-64 flex-1 p-8">
        <?php display_flash_message(); ?>
        <?= $content ?>
    </main>

</body>
</html>