<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SORAS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-primary-50 to-white flex items-center justify-center p-4">

    <div class="w-full max-w-md py-6">

        {{-- Logo --}}
        <div class="text-center">
            <img src="{{ asset('assets/soras-logo.png') }}" alt="SORAS Logo" class="w-25 h-25 md:w-35 md:h-35 mx-auto" loading="lazy">
            <h1 class="text-2xl font-bold text-gray-900">Selamat Datang Kembali</h1>
            <p class="mt-2 text-sm text-gray-500">Masuk ke akun kamu untuk melanjutkan</p>
        </div>

        {{-- Form Card --}}
        <div class="card">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        class="form-input @error('email') border-red-400 focus:ring-red-400 focus:border-red-400 @enderror"
                        required autofocus>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••"
                        class="form-input @error('password') border-red-400 @enderror" required>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                <button type="submit" class="btn-primary w-full py-3">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center mt-6 text-sm text-gray-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary-600 font-medium hover:underline">
                Daftar sekarang
            </a>
        </p>

    </div>

</body>

</html>
