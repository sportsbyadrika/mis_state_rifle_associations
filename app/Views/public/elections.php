<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSRA Elections & Representatives</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
    <header class="bg-white shadow">
        <div class="max-w-5xl mx-auto px-6 py-6 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="h-12 w-12 bg-slate-900 text-white flex items-center justify-center rounded-full text-xl font-bold">KS</div>
                <div>
                    <h1 class="text-2xl font-semibold text-slate-800">Kerala State Rifle Association</h1>
                    <p class="text-sm text-slate-500">Elections & Public Representatives</p>
                </div>
            </div>
            <a href="<?= htmlspecialchars(url_to('')); ?>" class="text-sm font-medium text-slate-600">Back to Portal</a>
        </div>
    </header>
    <main class="max-w-5xl mx-auto px-6 py-10 space-y-6">
        <?php foreach ($elections as $election): ?>
            <article class="bg-white rounded-xl shadow overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-1/3 bg-slate-200 h-56 md:h-auto">
                        <img src="<?= htmlspecialchars($election['photo_path'] ?? 'https://via.placeholder.com/400x300'); ?>" alt="Election" class="w-full h-full object-cover">
                    </div>
                    <div class="md:w-2/3 p-6">
                        <h2 class="text-xl font-semibold text-slate-800"><?= htmlspecialchars($election['title']); ?></h2>
                        <p class="text-sm text-slate-500 mt-1">Organization: <?= htmlspecialchars($election['organization_name']); ?> • Held On: <?= htmlspecialchars($election['held_on']); ?></p>
                        <p class="text-slate-600 mt-4 leading-relaxed"><?= nl2br(htmlspecialchars($election['description'] ?? '')); ?></p>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </main>
    <footer class="bg-white border-t border-slate-200">
        <div class="max-w-5xl mx-auto px-6 py-4 text-sm text-slate-500">© <?= date('Y'); ?> Kerala State Rifle Association. All rights reserved.</div>
    </footer>
</body>
</html>
