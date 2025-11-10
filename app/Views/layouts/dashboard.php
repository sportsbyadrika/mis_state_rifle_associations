<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'KSRA MIS'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
<div class="flex">
    <aside class="w-72 bg-slate-900 text-white min-h-screen flex flex-col">
        <div class="px-6 py-6 border-b border-slate-700 flex items-center space-x-3">
            <div class="h-12 w-12 bg-slate-700 rounded-full flex items-center justify-center">
                <span class="text-xl font-semibold">KS</span>
            </div>
            <div>
                <p class="text-lg font-bold">KSRA MIS</p>
                <p class="text-xs text-slate-300">Secure Management Portal</p>
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto">
            <ul class="py-4 space-y-2">
                <li><a href="/dashboard" class="flex items-center px-6 py-2 hover:bg-slate-800 transition">Dashboard</a></li>
                <li>
                    <details class="group" open>
                        <summary class="flex items-center px-6 py-2 cursor-pointer hover:bg-slate-800 transition">Organizations</summary>
                        <ul class="pl-10 space-y-1 mt-1 text-sm text-slate-200">
                            <li><a href="/organizations/dra" class="block py-1 hover:text-white">District Rifle Associations</a></li>
                            <li><a href="/organizations/ai" class="block py-1 hover:text-white">Affiliated Institutions</a></li>
                            <li><a href="/organizations/club" class="block py-1 hover:text-white">Clubs</a></li>
                        </ul>
                    </details>
                </li>
                <li><a href="/memberships" class="flex items-center px-6 py-2 hover:bg-slate-800 transition">Memberships</a></li>
                <li><a href="/finance" class="flex items-center px-6 py-2 hover:bg-slate-800 transition">Finance</a></li>
                <li><a href="/elections" class="flex items-center px-6 py-2 hover:bg-slate-800 transition">Elections</a></li>
            </ul>
        </nav>
    </aside>
    <main class="flex-1">
        <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800"><?= htmlspecialchars($heading ?? 'Dashboard'); ?></h1>
                <p class="text-sm text-slate-500">Welcome back, <?= htmlspecialchars($user['name'] ?? ''); ?>!</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <img src="https://via.placeholder.com/40" alt="profile" class="h-10 w-10 rounded-full object-cover border border-slate-200" />
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-700"><?= htmlspecialchars($user['name'] ?? ''); ?></p>
                        <p class="text-xs text-slate-400 uppercase tracking-wide"><?= htmlspecialchars($user['role'] ?? ''); ?></p>
                    </div>
                </div>
                <div class="relative">
                    <button class="px-3 py-1.5 bg-slate-900 text-white rounded-md" onclick="document.getElementById('profile-menu').classList.toggle('hidden')">Profile</button>
                    <div id="profile-menu" class="hidden absolute right-0 mt-2 w-40 bg-white border border-slate-200 rounded-md shadow-lg">
                        <a href="/profile" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">Profile</a>
                        <form action="/logout" method="POST">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf ?? ''); ?>">
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-slate-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <section class="p-8">
            <?= $slot ?? '' ?>
        </section>
    </main>
</div>
<script>
    document.addEventListener('click', function (event) {
        const menu = document.getElementById('profile-menu');
        if (!menu) return;
        if (!event.target.closest('#profile-menu') && !event.target.closest('button')) {
            menu.classList.add('hidden');
        }
    });
</script>
</body>
</html>
