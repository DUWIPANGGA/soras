<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — SORAS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-primary-50 to-white flex items-center justify-center p-4">

    <div class="w-full max-w-md py-6">

        {{-- Logo --}}
        <div class="text-center">
            <img src="{{ asset('assets/soras-logo.png') }}" alt="SORAS Logo" class="w-25 h-25 md:w-35 md:h-35 mx-auto" loading="lazy">
            <h1 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h1>
            <p class="mt-2 text-sm text-gray-500">Gratis, tanpa kartu kredit</p>
        </div>

        <div class="card mt-4">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Nama kamu" class="form-input @error('name') border-red-400 @enderror" required
                        autofocus>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="nama@email.com" class="form-input @error('email') border-red-400 @enderror"
                        required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                        class="form-input @error('password') border-red-400 @enderror" required>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Ulangi password" class="form-input" required>
                </div>

                <button type="submit" class="btn-primary w-full py-3">
                    Buat Akun
                </button>

            </form>
        </div>

        <p class="text-center mt-6 text-sm text-gray-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary-600 font-medium hover:underline">
                Masuk di sini
            </a>
        </p>

    </div>

</body>

</html>
