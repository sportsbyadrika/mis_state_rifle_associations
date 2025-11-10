<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSRA Elections & Representatives</title>
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
<body class="theme-body min-h-screen flex flex-col">
    <header class="navbar shadow-soft">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="brand-mark h-12 w-12 text-xl font-semibold">KS</div>
                <div>
                    <h1 class="text-2xl font-semibold">Kerala State Rifle Association</h1>
                    <p class="brand-subtitle text-xs uppercase tracking-[0.32em]">Elections &amp; Representatives</p>
                </div>
            </div>
            <a href="<?= htmlspecialchars(url_to('')); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/30 text-white/90 hover:bg-white/10">Back to Portal</a>
        </div>
    </header>
    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
            <?php foreach ($elections as $election): ?>
                <article class="card overflow-hidden">
                    <div class="md:flex">
                        <div class="md:w-1/3 bg-slate-200 h-56 md:h-full">
                            <img src="<?= htmlspecialchars($election['photo_path'] ?? 'https://via.placeholder.com/400x300'); ?>" alt="Election" class="w-full h-full object-cover">
                        </div>
                        <div class="md:w-2/3 p-6 space-y-3">
                            <h2 class="text-xl font-semibold text-slate-800"><?= htmlspecialchars($election['title']); ?></h2>
                            <p class="text-xs uppercase tracking-wide text-muted">Organization: <?= htmlspecialchars($election['organization_name']); ?> • Held On: <?= htmlspecialchars($election['held_on']); ?></p>
                            <p class="text-sm text-muted leading-relaxed"><?= nl2br(htmlspecialchars($election['description'] ?? '')); ?></p>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
    <footer class="surface border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-muted">© <?= date('Y'); ?> Kerala State Rifle Association. All rights reserved.</div>
    </footer>
</body>
</html>
