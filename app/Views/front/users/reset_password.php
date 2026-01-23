<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="min-h-[60vh] flex items-center justify-center bg-slate-50 py-12 px-4">
    <div class="max-w-md w-full bg-white p-10 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-serif font-bold text-slate-900">Set New Password</h2>
            <p class="mt-2 text-sm text-slate-600">
                Please create a secure password for your account.
            </p>
        </div>

        <form action="<?php echo URLROOT; ?>/users/reset_password/<?php echo $data['token']; ?>" method="POST" class="space-y-6">
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">New Password</label>
                <input type="password" name="password" class="w-full rounded-lg border-slate-200 focus:ring-accent focus:border-accent <?php echo (!empty($data['password_err'])) ? 'border-red-500' : ''; ?>" required placeholder="Min 6 characters">
                <span class="text-xs text-red-500"><?php echo $data['password_err']; ?></span>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Confirm Password</label>
                <input type="password" name="confirm_password" class="w-full rounded-lg border-slate-200 focus:ring-accent focus:border-accent <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500' : ''; ?>" required placeholder="Repeat password">
                <span class="text-xs text-red-500"><?php echo $data['confirm_password_err']; ?></span>
            </div>

            <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl shadow-lg hover:bg-slate-800 transition">
                Reset Password
            </button>
        </form>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>