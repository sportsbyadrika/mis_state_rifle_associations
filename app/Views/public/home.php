<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerala State Rifle Association - Public Information</title>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="brand-mark h-12 w-12 text-xl font-semibold">KS</div>
                <div>
                    <h1 class="text-2xl font-semibold">Kerala State Rifle Association</h1>
                    <p class="brand-subtitle text-xs uppercase tracking-[0.32em]">Official public portal</p>
                </div>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <a href="<?= htmlspecialchars(url_to('login')); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/30 text-white/90 hover:bg-white/10">Member Login</a>
                <a href="<?= htmlspecialchars(url_to('register')); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white font-semibold hover:bg-white/20">Join KSRA</a>
            </div>
        </div>
    </header>
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
            <section>
                <h2 class="text-xl font-semibold section-heading mb-4">Latest Elections &amp; Representatives</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($elections as $election): ?>
                        <article class="card overflow-hidden">
                            <div class="h-48 bg-slate-200">
                                <img src="<?= htmlspecialchars($election['photo_path'] ?? 'https://via.placeholder.com/600x400'); ?>" alt="Election" class="w-full h-full object-cover">
                            </div>
                            <div class="p-6 space-y-3">
                                <h3 class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($election['title']); ?></h3>
                                <p class="text-xs uppercase tracking-wide text-muted">Organization: <?= htmlspecialchars($election['organization_name']); ?> • Held On: <?= htmlspecialchars($election['held_on']); ?></p>
                                <p class="text-sm text-muted leading-relaxed line-clamp-4"><?= nl2br(htmlspecialchars($election['description'] ?? '')); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <section>
                <h2 class="text-xl font-semibold section-heading mb-4">Public News</h2>
                <div class="card divide-y divide-slate-200">
                    <?php foreach ($newsItems as $item): ?>
                        <article class="px-6 py-5 space-y-2">
                            <h3 class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($item['title']); ?></h3>
                            <p class="text-xs uppercase tracking-wide text-muted">Published <?= htmlspecialchars(date('d M Y', strtotime($item['published_at']))); ?></p>
                            <p class="text-sm text-muted leading-relaxed"><?= nl2br(htmlspecialchars($item['body'])); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <section>
                <h2 class="text-xl font-semibold section-heading mb-4">Bylaws &amp; Governance</h2>
                <div class="card divide-y divide-slate-200">
                    <?php foreach ($bylaws as $bylaw): ?>
                        <article class="px-6 py-5 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($bylaw['title']); ?></h3>
                                <p class="text-xs text-muted">Published <?= htmlspecialchars($bylaw['published_at'] ? date('d M Y', strtotime($bylaw['published_at'])) : 'Draft'); ?></p>
                            </div>
                            <a href="<?= htmlspecialchars($bylaw['document_path']); ?>" class="auth-link text-sm font-semibold" target="_blank" rel="noopener">View Document &rarr;</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>
    <footer class="surface border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-muted">© <?= date('Y'); ?> Kerala State Rifle Association. All rights reserved.</div>
    </footer>
</body>
</html>
