<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SORAS') }} — @yield('title', 'Sistem Rekomendasi Olahraga')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (function() {
            var saved = localStorage.getItem('soras-theme');
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

        .font-mono-custom {
            font-family: 'DM Mono', monospace;
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

        /* FIX #3 — Select/option dark mode: browser native dropdown tidak baca Tailwind class */
        .dark select,
        .dark select option {
            background-color: #111827 !important;
            color: #f9fafb !important;
            color-scheme: dark;
        }

        /* FIX #2 — Sidebar: pakai pure CSS karena @apply di <style> tag tidak reliable di Tailwind v4 */
        /* Dan w-4.5 tidak valid — Tailwind v4 pakai size-[18px] */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            color: #6b7280;
            transition: background 0.12s ease, color 0.12s ease;
            line-height: 1;
        }

        .dark .nav-link {
            color: #9ca3af;
        }

        .nav-link svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        .nav-link:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        .dark .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #f9fafb;
        }

        .nav-link.active {
            background-color: #f0fdf4;
            color: #15803d;
            font-weight: 600;
        }

        .dark .nav-link.active {
            background-color: rgba(34, 197, 94, 0.10);
            color: #4ade80;
        }

        .name-active {
            color: #15803d;
        }

        .dark .name-active {
            color: #4ade80;
        }

        /* Sidebar layout */
        .app-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
            width: 240px;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            border-right: 1px solid #e5e7eb;
            transition: transform 0.3s ease;
            transform: translateX(-100%);
        }

        .dark .app-sidebar {
            background-color: #111827;
            border-right-color: rgba(255, 255, 255, 0.06);
        }

        .app-sidebar.mobile-open {
            transform: translateX(0);
        }

        @media (min-width: 1024px) {
            .app-sidebar {
                transform: translateX(0) !important;
            }

            .app-main {
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
        localStorage.setItem('soras-theme', this.dark ? 'dark' : 'light');
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
        <aside class="app-sidebar" :class="{ 'mobile-open': mobileOpen }">

            {{-- Logo --}}
            <div class="flex items-center gap-1 px-5 border-b border-gray-100 dark:border-white/[0.06]"
                style="height:64px; flex-shrink:0;">
                <img src="{{ asset('assets/soras-logo.png') }}" alt="SORAS Logo" class="w-14 h-14" loading="lazy">
                <div>
                    <div class="font-extrabold dark name-active" style="font-size:15px;">
                        <p class="text-base">SORAS</p>
                    </div>
                    <div class="text-gray-400" style="font-size:10px;">
                        <p class="text-xs">Sport Recommendation</p>
                    </div>
                </div>
            </div>

            {{-- Nav links --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4" style="display:flex;flex-direction:column;gap:2px;">
                <div class="text-gray-400 dark:text-gray-600 uppercase px-3 mb-2"
                    style="font-size:10px;font-weight:600;letter-spacing:0.08em;">Menu</div>

                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('recommendation.create') }}"
                    class="nav-link {{ request()->routeIs('recommendation.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span>Rekomendasi</span>
                </a>

                <a href="{{ route('history.index') }}"
                    class="nav-link {{ request()->routeIs('history.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Riwayat</span>
                </a>

                <a href="{{ route('profile.index') }}"
                    class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profil Saya</span>
                </a>
            </nav>

            {{-- User footer --}}
            <div class="border-t border-gray-100 dark:border-white/[0.06] p-3" style="flex-shrink:0;">
                <div
                    class="flex items-center gap-3 rounded-xl px-2.5 py-2 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                    <div class="rounded-full text-white font-bold flex items-center justify-center"
                        style="width:32px;height:32px;flex-shrink:0;font-size:12px;background:linear-gradient(135deg,#4ade80,#16a34a);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold dark name-active truncate" style="font-size:13px;line-height:1.3;">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="text-gray-400 truncate" style="font-size:11px;line-height:1.3;">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar"
                            class="rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
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
        <div class="app-main flex-1 flex flex-col min-w-0">

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
                <span class="font-bold text-gray-900 dark:text-white text-sm">@yield('page_title', 'Dashboard')</span>

                {{-- Right actions --}}
                <div class="flex items-center gap-2">

                    {{-- FIX #1: Dark/light toggle --}}
                    <button @click="toggleDark()"
                        class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors"
                        :title="dark ? 'Ganti ke Mode Terang' : 'Ganti ke Mode Gelap'">
                        {{-- Matahari = sedang dark, klik → terang --}}
                        <svg x-show="dark" width="18" height="18" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        {{-- Bulan = sedang light, klik → gelap --}}
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
                        <button @click="show = false" class="hover:opacity-70"><svg width="15" height="15"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg></button>
                    </div>
                </div>
            @endif
            @if (session('warning'))
                <div class="mx-4 sm:mx-6 mt-4" x-data="{ show: true }" x-cloak x-show="show">
                    <div
                        class="flex items-center gap-3 p-4 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700/50 rounded-xl text-amber-800 dark:text-amber-300 text-sm">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"
                            style="flex-shrink:0;">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1">{{ session('warning') }}</span>
                        <button @click="show = false" class="hover:opacity-70"><svg width="15" height="15"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg></button>
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

            <footer class="px-6 py-6 border-t border-gray-100 dark:border-white/[0.06]" style="flex-shrink:0;">
                <p class="text-xs text-gray-400 dark:text-gray-600 text-center">
                    SORAS &copy; {{ date('Y') }} &middot; Sistem Olahraga Rekomendasi Adaptif &amp; Sehat
                </p>
            </footer>
        </div>

    </div>
</body>

</html>
