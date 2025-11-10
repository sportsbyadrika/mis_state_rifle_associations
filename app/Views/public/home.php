<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerala State Rifle Association - Public Information</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
    <header class="bg-white shadow">
        <div class="max-w-6xl mx-auto px-6 py-6 flex flex-wrap items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="h-12 w-12 bg-slate-900 text-white flex items-center justify-center rounded-full text-xl font-bold">KS</div>
                <div>
                    <h1 class="text-2xl font-semibold text-slate-800">Kerala State Rifle Association</h1>
                    <p class="text-sm text-slate-500">Official public portal</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 text-sm text-slate-600">
                <a href="/login" class="hover:text-slate-900">Member Login</a>
                <a href="/register" class="hover:text-slate-900">Join KSRA</a>
            </div>
        </div>
    </header>
    <main class="max-w-6xl mx-auto px-6 py-10 space-y-10">
        <section>
            <h2 class="text-xl font-semibold text-slate-800 mb-4">Latest Elections & Representatives</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($elections as $election): ?>
                    <article class="bg-white rounded-xl shadow overflow-hidden">
                        <div class="h-48 bg-slate-200">
                            <img src="<?= htmlspecialchars($election['photo_path'] ?? 'https://via.placeholder.com/600x400'); ?>" alt="Election" class="w-full h-full object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($election['title']); ?></h3>
                            <p class="text-sm text-slate-500">Organization: <?= htmlspecialchars($election['organization_name']); ?> • Held On: <?= htmlspecialchars($election['held_on']); ?></p>
                            <p class="mt-3 text-slate-600 leading-relaxed line-clamp-3"><?= nl2br(htmlspecialchars($election['description'] ?? '')); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <section>
            <h2 class="text-xl font-semibold text-slate-800 mb-4">Public News</h2>
            <div class="bg-white rounded-xl shadow divide-y divide-slate-200">
                <?php foreach ($newsItems as $item): ?>
                    <article class="px-6 py-4">
                        <h3 class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($item['title']); ?></h3>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Published <?= htmlspecialchars(date('d M Y', strtotime($item['published_at']))); ?></p>
                        <p class="mt-2 text-slate-600 leading-relaxed"><?= nl2br(htmlspecialchars($item['body'])); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <section>
            <h2 class="text-xl font-semibold text-slate-800 mb-4">Bylaws & Governance</h2>
            <div class="bg-white rounded-xl shadow divide-y divide-slate-200">
                <?php foreach ($bylaws as $bylaw): ?>
                    <article class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($bylaw['title']); ?></h3>
                            <p class="text-xs text-slate-500">Published <?= htmlspecialchars($bylaw['published_at'] ? date('d M Y', strtotime($bylaw['published_at'])) : 'Draft'); ?></p>
                        </div>
                        <a href="<?= htmlspecialchars($bylaw['document_path']); ?>" class="text-sm font-medium text-slate-900" target="_blank" rel="noopener">View Document &rarr;</a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <footer class="bg-white border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-6 py-4 text-sm text-slate-500">© <?= date('Y'); ?> Kerala State Rifle Association. All rights reserved.</div>
    </footer>
</body>
</html>
