<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="min-h-[70vh] flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center">
            <h2 class="mt-2 text-3xl font-serif font-bold text-slate-900">Welcome Back</h2>
            <p class="mt-2 text-sm text-slate-600">
                Or <a href="<?php echo URLROOT; ?>/users/register" class="font-medium text-accent hover:text-indigo-500">create a new account</a>
            </p>
        </div>
        
        <?php flash('register_success'); ?>
        <?php flash('product_message'); ?>

        <form class="mt-8 space-y-6" action="<?php echo URLROOT; ?>/users/login" method="POST">
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email address</label>
                    <input name="email" type="email" required class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-accent focus:border-accent focus:z-10 sm:text-sm <?php echo (!empty($data['email_err'])) ? 'border-red-500' : ''; ?>" placeholder="you@example.com" value="<?php echo $data['email']; ?>">
                    <span class="text-xs text-red-500"><?php echo $data['email_err']; ?></span>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <input name="password" type="password" required class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-accent focus:border-accent focus:z-10 sm:text-sm <?php echo (!empty($data['password_err'])) ? 'border-red-500' : ''; ?>" placeholder="••••••••">
                    <span class="text-xs text-red-500"><?php echo $data['password_err']; ?></span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-accent focus:ring-accent border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-slate-900">Remember me</label>
                </div>

                <div class="text-sm">
                    <a href="<?php echo URLROOT; ?>/users/forgot_password" class="font-medium text-accent hover:text-indigo-500">Forgot your password?</a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-primary hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition transform hover:-translate-y-1 shadow-lg">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>