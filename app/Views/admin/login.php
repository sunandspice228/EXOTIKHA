<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration | EXOTIKHA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: '#0f172a', // Slate 900
                        accent: '#6366f1',  // Indigo 500
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
        
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-slate-900 tracking-tighter">
                EXOTIKHA<span class="text-accent">.</span>
            </h1>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-2">Accès Back-Office</p>
        </div>

        <form action="<?php echo URLROOT; ?>/admin/login" method="POST" class="space-y-5">
            
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Administrateur</label>
                <input type="email" name="email" 
                       class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition <?php echo (!empty($data['email_err'])) ? 'border-red-500' : ''; ?>"
                       value="<?php echo $data['email']; ?>"
                       placeholder="admin@exotikha.com"
                       autofocus>
                <span class="text-red-500 text-xs mt-1 block"><?php echo $data['email_err']; ?></span>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Mot de passe</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition <?php echo (!empty($data['password_err'])) ? 'border-red-500' : ''; ?>"
                       value="<?php echo $data['password']; ?>"
                       placeholder="••••••••">
                <span class="text-red-500 text-xs mt-1 block"><?php echo $data['password_err']; ?></span>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white font-bold py-3 rounded-lg hover:bg-accent transition shadow-lg shadow-slate-900/20 transform active:scale-95 duration-200">
                Se connecter
            </button>

        </form>

        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <a href="<?php echo URLROOT; ?>" class="text-xs text-slate-400 hover:text-slate-600 font-medium transition flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour au site
            </a>
        </div>

    </div>

</body>
</html>