<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="min-h-[60vh] flex items-center justify-center bg-slate-50 py-12 px-4">
    <div class="max-w-md w-full bg-white p-10 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-serif font-bold text-slate-900">Forgot Password?</h2>
            <p class="mt-2 text-sm text-slate-600">
                Enter your email address and we'll send you a link to reset your password.
            </p>
        </div>

        <?php flash('register_success'); ?>

        <form action="<?php echo URLROOT; ?>/users/forgot_password" method="POST" class="space-y-6">
            <?php if(!empty($data['error'])): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm text-center font-bold">
                    <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email address</label>
                <input type="email" name="email" value="<?php echo $data['email']; ?>" class="w-full rounded-lg border-slate-200 focus:ring-accent focus:border-accent" required placeholder="you@example.com">
            </div>

            <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl shadow-lg hover:bg-slate-800 transition">
                Send Reset Link
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="<?php echo URLROOT; ?>/users/login" class="text-sm font-bold text-accent hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Back to Login
            </a>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>