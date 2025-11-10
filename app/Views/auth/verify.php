<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSRA MIS - Verify OTP</title>
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
            <h1 class="text-2xl font-semibold section-heading">Verify Your Email</h1>
            <p class="text-sm text-muted">Enter the 6-digit OTP sent to <?= htmlspecialchars($email); ?> to complete your registration.</p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error mb-4 text-sm">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php elseif (!empty($message)): ?>
            <div class="alert alert-info mb-4 text-sm">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="<?= htmlspecialchars(url_to('verify')); ?>" method="POST" class="space-y-4">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email); ?>">
            <div class="space-y-2">
                <label class="form-label text-sm" for="code">One-Time Password</label>
                <input type="text" id="code" name="code" maxlength="6" pattern="[0-9]{6}" class="input-control" required>
                <p class="text-xs text-muted">Check your inbox or the OTP logs if email delivery is unavailable.</p>
            </div>
            <button type="submit" class="button-primary w-full flex items-center justify-center">Verify &amp; Continue</button>
        </form>
        <p class="text-center text-sm text-muted mt-6">Wrong email? <a class="auth-link" href="<?= htmlspecialchars(url_to('register')); ?>">Start over</a></p>
    </div>
</body>
</html>
