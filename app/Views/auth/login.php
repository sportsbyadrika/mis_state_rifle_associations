<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSRA MIS - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif']
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/theme.css')); ?>">
</head>
<body class="theme-body min-h-screen flex items-center justify-center px-4 py-12">
    <div class="auth-card w-full max-w-md">
        <div class="text-center space-y-3 mb-6">
            <div class="brand-mark mx-auto h-16 w-16 text-2xl font-semibold">KS</div>
            <h1 class="text-2xl font-semibold section-heading">Welcome to KSRA MIS</h1>
            <p class="text-sm text-muted">Sign in with your registered email to access the management portal.</p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error mb-4 text-sm">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form action="<?= htmlspecialchars(url_to('login')); ?>" method="POST" class="space-y-4">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
            <div class="space-y-2">
                <label class="form-label text-sm" for="email">Email</label>
                <input type="email" id="email" name="email" class="input-control" required>
            </div>
            <div class="space-y-2">
                <label class="form-label text-sm" for="password">Password</label>
                <input type="password" id="password" name="password" class="input-control" required>
            </div>
            <button type="submit" class="button-primary w-full flex items-center justify-center">Login</button>
        </form>
        <p class="text-center text-sm text-muted mt-6">Need an account? <a class="auth-link" href="<?= htmlspecialchars(url_to('register')); ?>">Register</a></p>
    </div>
</body>
</html>
