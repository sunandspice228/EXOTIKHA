<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/png" href="<?= url('/assets/img/favicon.ico') ?>">
    <meta charset="UTF-8">
    <div class="text-center mb-6">
    <img src="<?= url('/assets/img/logo.png') ?>" alt="Exotikha" class="h-16 mx-auto">
</div>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-xl w-96">
        <h1 class="text-2xl font-bold text-center mb-6 text-slate-800">Admin Exotikha</h1>
        
        <?php display_flash_message(); ?>

        <form action="<?= url('/admin/login') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-sm font-bold text-slate-600 mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-slate-300 p-2 rounded focus:outline-none focus:border-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-600 mb-1">Mot de passe</label>
                <input type="password" name="password" class="w-full border border-slate-300 p-2 rounded focus:outline-none focus:border-indigo-500" required>
            </div>

            <button class="w-full bg-indigo-600 text-white font-bold py-2 rounded hover:bg-indigo-700 transition">
                Se connecter
            </button>
        </form>
        
        <div class="mt-4 text-center">
            <a href="<?= url('/') ?>" class="text-sm text-slate-500 hover:text-indigo-500">← Retour au site</a>
        </div>
    </div>

</body>
</html>