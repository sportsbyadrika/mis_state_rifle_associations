<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSRA MIS - Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-xl rounded-lg p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <div class="mx-auto h-16 w-16 bg-slate-900 text-white flex items-center justify-center rounded-full text-2xl font-bold">KS</div>
            <h1 class="text-2xl font-semibold text-slate-800 mt-4">Verify Your Email</h1>
            <p class="text-slate-500 text-sm">Enter the 6-digit OTP sent to <?= htmlspecialchars($email); ?> to complete registration.</p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="mb-4 p-3 rounded bg-red-100 text-red-600 text-sm">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php elseif (!empty($message)): ?>
            <div class="mb-4 p-3 rounded bg-emerald-100 text-emerald-700 text-sm">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="/verify" method="POST" class="space-y-4">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email); ?>">
            <div>
                <label class="block text-sm font-medium text-slate-600">One-Time Password</label>
                <input type="text" name="code" maxlength="6" pattern="[0-9]{6}" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500" required>
            </div>
            <button type="submit" class="w-full py-2 bg-slate-900 text-white rounded-md hover:bg-slate-800">Verify & Continue</button>
        </form>
        <p class="text-center text-sm text-slate-500 mt-4">Wrong email? <a class="text-slate-900 font-medium" href="/register">Start over</a></p>
    </div>
</body>
</html>
