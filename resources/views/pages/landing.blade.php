<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SORAS — Sistem Rekomendasi Olahraga Adaptif dan Sehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeUp 560ms cubic-bezier(.2, .9, .2, 1) both;
        }

        .animate-fade-up-delay-1 {
            animation-delay: 120ms;
        }

        .animate-fade-up-delay-2 {
            animation-delay: 240ms;
        }

        .card {
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .card:hover {
            transform: translateY(-6px);
        }
    </style>
</head>

<body class="bg-white">

    {{-- Navbar --}}
    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md border-b border-gray-100 z-50">
        <div class="max-w-6xl mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/soras-logo.png') }}" alt="SORAS Logo" class="w-20 h-20">
                {{-- <span class="font-bold text-gray-900">SORAS</span> --}}
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary text-sm py-2">
                        Ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary text-sm py-2">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm py-2">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="pt-32 pb-20 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <span
                class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary-50 text-primary-700 rounded-full text-sm font-medium mb-6">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                Berbasis Knowledge-Based & MCDM
            </span>

            <h1
                class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6 animate-fade-up animate-fade-up-delay-1">
                Temukan Olahraga yang Tepat<br>
                <span class="text-primary-600">untuk Kondisi Kamu</span>
            </h1>

            <p
                class="text-xl text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed animate-fade-up animate-fade-up-delay-2">
                SORAS menganalisis kondisi fisik, keluhan kesehatan, dan tujuan latihan kamu untuk memberikan
                rekomendasi olahraga yang aman dan personal.
            </p>

            <div class="flex items-center justify-center gap-4 animate-fade-up animate-fade-up-delay-2">
                <a href="{{ route('register') }}"
                    class="btn-primary text-base px-8 py-3 rounded-full shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition">
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-12 bg-gray-50 border-y border-gray-100">
        <div class="max-w-4xl mx-auto px-6 grid grid-cols-3 gap-8 text-center">
            <div>
                <p class="text-3xl font-bold text-primary-600">8+</p>
                <p class="text-sm text-gray-500 mt-1">Jenis Olahraga</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-primary-600">10+</p>
                <p class="text-sm text-gray-500 mt-1">Keluhan Ditangani</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-primary-600">7</p>
                <p class="text-sm text-gray-500 mt-1">Tujuan Latihan</p>
            </div>
        </div>
    </section>

    {{-- Cara Kerja --}}
    <section id="cara-kerja" class="py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Bagaimana SORAS Bekerja?</h2>
                <p class="text-gray-500 max-w-xl mx-auto">
                    4 langkah sederhana untuk mendapatkan rekomendasi olahraga yang tepat
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                @foreach ([['01', 'Isi Data Fisik', 'Masukkan usia, tinggi, dan berat badan kamu.', 'text-sky-600 bg-sky-50'], ['02', 'Pilih Keluhan', 'Ceritakan keluhan utama dan keluhan tambahan kamu.', 'text-teal-600 bg-teal-50'], ['03', 'Tentukan Tujuan', 'Pilih apa yang ingin kamu capai dari olahraga.', 'text-orange-600 bg-orange-50'], ['04', 'Dapatkan Rekomendasi', 'Sistem memberikan top-3 olahraga terbaik untuk kamu.', 'text-primary-600 bg-primary-50']] as [$num, $title, $desc, $color])
                    <div class="text-center">
                        <div
                            class="w-12 h-12 {{ $color }} rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="font-semibold text-lg">{{ $num }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $title }}</h3>
                        <p class="text-sm text-gray-500">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Fitur --}}
    <section class="py-20 px-6 bg-gray-50">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Kenapa SORAS?</h2>
            </div>

            @php
                $features = [
                    [
                        'icon' => 'science',
                        'title' => 'Berbasis Ilmu',
                        'desc' =>
                            'Menggunakan pendekatan Knowledge-Based Reasoning dan Weighted MCDM yang transparan dan dapat dipertanggungjawabkan.',
                    ],
                    [
                        'icon' => 'shield',
                        'title' => 'Aman & Terpercaya',
                        'desc' =>
                            'Hard filtering otomatis mencegah rekomendasi olahraga yang berbahaya untuk kondisi medis tertentu.',
                    ],
                    [
                        'icon' => 'chart',
                        'title' => 'Transparan',
                        'desc' =>
                            'Setiap rekomendasi dilengkapi breakdown skor per faktor — kamu tahu persis mengapa olahraga itu direkomendasikan.',
                    ],
                    [
                        'icon' => 'bolt',
                        'title' => 'Instan',
                        'desc' =>
                            'Hasil rekomendasi langsung tersedia tanpa menunggu — tidak butuh waktu pemrosesan lama.',
                    ],
                    [
                        'icon' => 'device',
                        'title' => 'Multi Platform',
                        'desc' => 'Tersedia sebagai web app dan mobile app — akses kapan saja dari mana saja.',
                    ],
                    [
                        'icon' => 'target',
                        'title' => 'Personal',
                        'desc' =>
                            'Rekomendasi disesuaikan dengan kondisi unik setiap pengguna, bukan rekomendasi generik.',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($features as $feature)
                    <div class="card p-6 bg-white rounded-xl shadow-sm">
                        <div
                            class="w-10 h-10 bg-primary-50 text-primary-600 rounded-md flex items-center justify-center mb-3">
                            @switch($feature['icon'])
                                @case('science')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 7V3h8v4m-1 4h.01M6 21h12" />
                                    </svg>
                                @break

                                @case('shield')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 2l7 4v6c0 5-3.58 9.74-7 11-3.42-1.26-7-6-7-11V6l7-4z" />
                                    </svg>
                                @break

                                @case('chart')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M3 3v18h18" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M7 13v5M12 9v9M17 5v13" />
                                    </svg>
                                @break

                                @case('bolt')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                @break

                                @case('device')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="7" y="3" width="10" height="18" rx="2" ry="2"
                                            stroke-width="1.5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M11 18h2" />
                                    </svg>
                                @break

                                @case('target')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="3" stroke-width="1.5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19.4 15a7.98 7.98 0 00.6-3 8 8 0 10-8 8 7.98 7.98 0 003-.6" />
                                    </svg>
                                @break
                            @endswitch
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 px-6">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Siap Mulai Olahraga dengan Benar?
            </h2>
            <p class="text-gray-500 mb-8">
                Daftar gratis dan dapatkan rekomendasi olahraga pertama kamu sekarang.
            </p>
            <a href="{{ route('register') }}" class="btn-primary text-base px-10 py-3">
                Daftar Sekarang
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 py-8 px-6">
        <div class="max-w-6xl mx-auto flex items-center justify-center text-sm text-gray-400">
            <p class="text-center w-full">© {{ date('Y') }} SORAS. Sistem Rekomendasi Olahraga Adaptif dan Sehat.
            </p>
        </div>
    </footer>

</body>

</html>
