<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'KSRA MIS'); ?></title>
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
<body class="theme-body min-h-screen">
<div class="min-h-screen flex flex-col">
    <header class="navbar shadow-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-6 py-4">
                <div class="flex items-center gap-4">
                    <a href="<?= htmlspecialchars(url_to('dashboard')); ?>" class="flex items-center gap-3">
                        <div class="brand-mark h-12 w-12 text-lg font-semibold">KS</div>
                        <div class="leading-tight">
                            <p class="text-lg font-semibold">KSRA MIS</p>
                            <p class="brand-subtitle text-[11px] uppercase tracking-[0.32em]">Secure Management Portal</p>
                        </div>
                    </a>
                    <button type="button" id="nav-toggle" class="nav-toggle md:hidden">Menu</button>
                </div>
                <nav id="desktop-nav" class="hidden md:flex items-center gap-6" aria-label="Primary navigation">
                    <a href="<?= htmlspecialchars(url_to('dashboard')); ?>" class="nav-link">Dashboard</a>
                    <div class="nav-dropdown" data-dropdown>
                        <button type="button" class="nav-link" data-dropdown-trigger aria-haspopup="true" aria-expanded="false">
                            Organizations
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-4 w-4">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 10.94l3.71-3.71a.75.75 0 0 1 1.08 1.04l-4.25 4.25a.75.75 0 0 1-1.06 0L5.21 8.27a.75.75 0 0 1 .02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="nav-dropdown-menu" data-dropdown-menu>
                            <a href="<?= htmlspecialchars(url_to('organizations/dra')); ?>">District Rifle Associations</a>
                            <a href="<?= htmlspecialchars(url_to('organizations/ai')); ?>">Affiliated Institutions</a>
                            <a href="<?= htmlspecialchars(url_to('organizations/club')); ?>">Clubs</a>
                        </div>
                    </div>
                    <a href="<?= htmlspecialchars(url_to('memberships')); ?>" class="nav-link">Memberships</a>
                    <a href="<?= htmlspecialchars(url_to('finance')); ?>" class="nav-link">Finance</a>
                    <a href="<?= htmlspecialchars(url_to('elections')); ?>" class="nav-link">Elections</a>
                </nav>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-3">
                        <img src="https://via.placeholder.com/48" alt="profile" class="h-11 w-11 rounded-full border-2 border-white/40 object-cover" />
                        <div class="profile-summary">
                            <span class="name text-sm"><?= htmlspecialchars($user['name'] ?? ''); ?></span>
                            <span class="role"><?= htmlspecialchars($user['role'] ?? ''); ?></span>
                        </div>
                    </div>
                    <div class="relative">
                        <button type="button" id="profile-menu-button" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/30 text-sm font-semibold text-white/90 hover:bg-white/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/60">
                            <span>Account</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-4 w-4">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 10.94l3.71-3.71a.75.75 0 0 1 1.08 1.04l-4.25 4.25a.75.75 0 0 1-1.06 0L5.21 8.27a.75.75 0 0 1 .02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="profile-menu" class="profile-dropdown hidden absolute right-0 mt-3">
                            <a href="<?= htmlspecialchars(url_to('profile')); ?>">Profile</a>
                            <form action="<?= htmlspecialchars(url_to('logout')); ?>" method="POST">
                                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf ?? ''); ?>">
                                <button type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav id="mobile-nav" class="mobile-nav md:hidden hidden">
            <div class="max-w-7xl mx-auto px-4 pb-4 space-y-4">
                <a href="<?= htmlspecialchars(url_to('dashboard')); ?>" class="mobile-nav-link">Dashboard</a>
                <details class="mobile-nav-group" data-mobile-group>
                    <summary class="mobile-nav-link cursor-pointer">
                        <span>Organizations</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-4 w-4">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 10.94l3.71-3.71a.75.75 0 0 1 1.08 1.04l-4.25 4.25a.75.75 0 0 1-1.06 0L5.21 8.27a.75.75 0 0 1 .02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </summary>
                    <div class="pl-4 flex flex-col gap-2 text-sm text-white/90">
                        <a href="<?= htmlspecialchars(url_to('organizations/dra')); ?>" class="hover:text-white">District Rifle Associations</a>
                        <a href="<?= htmlspecialchars(url_to('organizations/ai')); ?>" class="hover:text-white">Affiliated Institutions</a>
                        <a href="<?= htmlspecialchars(url_to('organizations/club')); ?>" class="hover:text-white">Clubs</a>
                    </div>
                </details>
                <a href="<?= htmlspecialchars(url_to('memberships')); ?>" class="mobile-nav-link">Memberships</a>
                <a href="<?= htmlspecialchars(url_to('finance')); ?>" class="mobile-nav-link">Finance</a>
                <a href="<?= htmlspecialchars(url_to('elections')); ?>" class="mobile-nav-link">Elections</a>
                <div class="mobile-nav-group text-white/90 text-sm space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="https://via.placeholder.com/40" alt="profile" class="h-10 w-10 rounded-full border border-white/30 object-cover" />
                        <div>
                            <p class="font-semibold"><?= htmlspecialchars($user['name'] ?? ''); ?></p>
                            <p class="text-xs uppercase tracking-wide text-white/70"><?= htmlspecialchars($user['role'] ?? ''); ?></p>
                        </div>
                    </div>
                    <a href="<?= htmlspecialchars(url_to('profile')); ?>" class="mobile-nav-link">Profile</a>
                    <form action="<?= htmlspecialchars(url_to('logout')); ?>" method="POST">
                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf ?? ''); ?>">
                        <button type="submit" class="mobile-nav-link justify-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15" />
                                <path d="M12 9l3-3m0 0 3 3m-3-3v12" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </header>
    <main class="flex-1">
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-semibold section-heading leading-tight"><?= htmlspecialchars($heading ?? 'Dashboard'); ?></h1>
                    <p class="text-sm text-muted mt-2">Welcome back, <?= htmlspecialchars($user['name'] ?? ''); ?>.</p>
                </div>
            </div>
            <div class="space-y-8">
                <?= $slot ?? '' ?>
            </div>
        </div>
    </main>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navToggle = document.getElementById('nav-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        const profileButton = document.getElementById('profile-menu-button');
        const profileMenu = document.getElementById('profile-menu');
        const dropdowns = document.querySelectorAll('[data-dropdown]');

        function closeProfileMenu(event) {
            if (!profileMenu || !profileButton) {
                return;
            }
            if (event && (profileMenu.contains(event.target) || profileButton.contains(event.target))) {
                return;
            }
            profileMenu.classList.add('hidden');
            profileButton.setAttribute('aria-expanded', 'false');
        }

        if (navToggle && mobileNav) {
            navToggle.addEventListener('click', function () {
                mobileNav.classList.toggle('hidden');
                navToggle.setAttribute('aria-expanded', mobileNav.classList.contains('hidden') ? 'false' : 'true');
            });
        }

        if (profileButton && profileMenu) {
            profileButton.addEventListener('click', function () {
                profileMenu.classList.toggle('hidden');
                profileButton.setAttribute('aria-expanded', profileMenu.classList.contains('hidden') ? 'false' : 'true');
            });
        }

        dropdowns.forEach(function (dropdown) {
            const trigger = dropdown.querySelector('[data-dropdown-trigger]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');
            if (!trigger || !menu) {
                return;
            }

            trigger.addEventListener('click', function (event) {
                event.preventDefault();
                const isOpen = dropdown.classList.toggle('open');
                trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        });

        document.addEventListener('click', function (event) {
            if (mobileNav && !mobileNav.classList.contains('hidden') && navToggle && !navToggle.contains(event.target) && !mobileNav.contains(event.target)) {
                mobileNav.classList.add('hidden');
                navToggle.setAttribute('aria-expanded', 'false');
            }

            dropdowns.forEach(function (dropdown) {
                const trigger = dropdown.querySelector('[data-dropdown-trigger]');
                if (trigger && !dropdown.contains(event.target)) {
                    dropdown.classList.remove('open');
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });

            if (profileMenu && !profileMenu.classList.contains('hidden')) {
                closeProfileMenu(event);
            }
        });

        window.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                if (mobileNav && !mobileNav.classList.contains('hidden')) {
                    mobileNav.classList.add('hidden');
                    if (navToggle) {
                        navToggle.setAttribute('aria-expanded', 'false');
                    }
                }
                if (profileMenu && !profileMenu.classList.contains('hidden')) {
                    profileMenu.classList.add('hidden');
                    if (profileButton) {
                        profileButton.setAttribute('aria-expanded', 'false');
                    }
                }
                dropdowns.forEach(function (dropdown) {
                    const trigger = dropdown.querySelector('[data-dropdown-trigger]');
                    if (trigger) {
                        dropdown.classList.remove('open');
                        trigger.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    });
</script>
</body>
</html>
