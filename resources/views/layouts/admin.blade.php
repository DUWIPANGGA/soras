<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin SORAS — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    {{-- Inject dark class BEFORE CSS to prevent flash --}}
    <script>
        (function() {
            var saved = localStorage.getItem('soras-admin-theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        *,
        *::before,
        *::after {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 99px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #374151;
        }

        .dark select,
        .dark select option {
            background-color: #111827 !important;
            color: #f9fafb !important;
            color-scheme: dark;
        }

        /* Admin nav links */
        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            color: #9ca3af;
            transition: background 0.12s ease, color 0.12s ease;
            line-height: 1;
        }

        .admin-nav-link svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        .admin-nav-link:hover {
            background-color: rgba(255, 255, 255, 0.07);
            color: #f9fafb;
        }

        .admin-nav-link.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.25), rgba(139, 92, 246, 0.15));
            color: #a78bfa;
            font-weight: 600;
        }

        .admin-name-active {
            color: #a78bfa;
        }

        /* Admin sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
            width: 240px;
            display: flex;
            flex-direction: column;
            background-color: #0f172a;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            transition: transform 0.3s ease;
            transform: translateX(-100%);
        }

        .dark .admin-sidebar {
            background-color: #0a0f1a;
            border-right-color: rgba(255, 255, 255, 0.05);
        }

        .admin-sidebar.mobile-open {
            transform: translateX(0);
        }

        @media (min-width: 1024px) {
            .admin-sidebar {
                transform: translateX(0) !important;
            }

            .admin-main {
                padding-left: 240px;
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100" x-data="{
    dark: document.documentElement.classList.contains('dark'),
    mobileOpen: false,
    toggleDark() {
        this.dark = !this.dark;
        document.documentElement.classList.toggle('dark', this.dark);
        localStorage.setItem('soras-admin-theme', this.dark ? 'dark' : 'light');
    }
}"
    @keydown.escape="mobileOpen = false">

    <div class="flex min-h-screen h-full">

        {{-- Mobile overlay --}}
        <div x-cloak x-show="mobileOpen" @click="mobileOpen = false"
            class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm lg:hidden"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>

        {{-- ═══ SIDEBAR ═══════════════════════════════════════════ --}}
        <aside class="admin-sidebar" :class="{ 'mobile-open': mobileOpen }">

            {{-- Logo --}}
            <div class="flex items-center gap-1 px-5 border-b border-white/[0.06]" style="height:64px;flex-shrink:0;">
                <img src="{{ asset('assets/soras-logo.png') }}" alt="SORAS Logo" class="w-14 h-14" loading="lazy">

                <div>
                    <p class="font-extrabold text-white admin-name-active" style="font-size:15px;">SORAS</p>
                    <p class="text-gray-500" style="font-size:10px;">Admin Panel</p>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4" style="display:flex;flex-direction:column;gap:2px;">

                <div class="text-gray-600 uppercase px-3 mb-2"
                    style="font-size:10px;font-weight:600;letter-spacing:0.08em;">Menu Utama</div>

                <a href="{{ route('admin.dashboard') }}"
                    class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Pengguna</span>
                </a>

                <div class="text-gray-600 uppercase px-3 mt-5 mb-2"
                    style="font-size:10px;font-weight:600;letter-spacing:0.08em;">Knowledge Base</div>

                <a href="{{ route('admin.exercises.index') }}"
                    class="admin-nav-link {{ request()->routeIs('admin.exercises.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                    </svg>
                    <span>Olahraga</span>
                </a>

                <a href="{{ route('admin.complaints.index') }}"
                    class="admin-nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Matriks Keluhan</span>
                </a>

                <a href="{{ route('admin.goals.index') }}"
                    class="admin-nav-link {{ request()->routeIs('admin.goals.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Matriks Tujuan</span>
                </a>

            </nav>

            {{-- User footer --}}
            <div class="border-t border-white/[0.06] p-3" style="flex-shrink:0;">
                <div class="flex items-center gap-3 rounded-xl px-2.5 py-2 hover:bg-white/5 transition-colors">
                    <div class="rounded-full text-white font-bold flex items-center justify-center"
                        style="width:32px;height:32px;flex-shrink:0;font-size:12px;background:linear-gradient(135deg,#818cf8,#6366f1);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-white truncate" style="font-size:13px;line-height:1.3;">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="text-gray-500 truncate" style="font-size:11px;line-height:1.3;">
                            Administrator
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar"
                            class="rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"
                            style="padding:6px;flex-shrink:0;">
                            <svg width="15" height="15" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        {{-- ═══ MAIN AREA ══════════════════════════════════════════ --}}
        <div class="admin-main flex-1 flex flex-col min-w-0">

            {{-- Topbar --}}
            <header
                class="sticky top-0 z-20 flex items-center justify-between px-4 sm:px-6
                bg-white/80 dark:bg-gray-950/80 backdrop-blur-md
                border-b border-gray-200 dark:border-white/[0.06]"
                style="height:64px;flex-shrink:0;">

                {{-- Mobile hamburger --}}
                <button @click="mobileOpen = !mobileOpen"
                    class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- Page title --}}
                <span class="font-bold text-gray-900 dark:text-white text-sm lg:ml-0 ml-2">@yield('title', 'Dashboard')</span>

                {{-- Right actions --}}
                <div class="flex items-center gap-2">
                    {{-- Dark/light toggle --}}
                    <button @click="toggleDark()"
                        class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors"
                        :title="dark ? 'Ganti ke Mode Terang' : 'Ganti ke Mode Gelap'">
                        <svg x-show="dark" width="18" height="18" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg x-show="!dark" width="18" height="18" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </header>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mx-4 sm:mx-6 mt-4" x-data="{ show: true }" x-cloak x-show="show"
                    x-init="setTimeout(() => show = false, 4000)">
                    <div
                        class="flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700/50 rounded-xl text-green-800 dark:text-green-300 text-sm">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"
                            style="flex-shrink:0;">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1">{{ session('success') }}</span>
                        <button @click="show = false" class="hover:opacity-70">
                            <svg width="15" height="15" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="mx-4 sm:mx-6 mt-4" x-data="{ show: true }" x-cloak x-show="show">
                    <div
                        class="flex items-center gap-3 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700/50 rounded-xl text-red-800 dark:text-red-300 text-sm">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"
                            style="flex-shrink:0;">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- Page content --}}
            <main class="flex-1 px-4 sm:px-6 py-6">
                @yield('content')
            </main>

            <footer class="px-6 py-4 border-t border-gray-100 dark:border-white/[0.06]" style="flex-shrink:0;">
                <p class="text-xs text-gray-400 dark:text-gray-600 text-center">
                    SORAS Admin &copy; {{ date('Y') }} &middot; Panel Administrasi Sistem Rekomendasi
                </p>
            </footer>

        </div>

    </div>

</body>

</html>
