@extends('layouts.admin')
@section('title', 'Data Pengguna')

@section('content')
    <div class="space-y-5">

        <div class="flex justify-between items-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Total: <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $users->total() }}</span> pengguna
            </p>
        </div>

        <div
            class="bg-white dark:bg-white/[0.03] rounded-2xl border border-gray-100 dark:border-white/[0.07] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[640px]">
                    <thead class="bg-gray-50 dark:bg-white/[0.03] border-b border-gray-100 dark:border-white/[0.07]">
                        <tr>
                            <th class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">#</th>
                            <th class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">Nama</th>
                            <th class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">Email</th>
                            <th class="text-center px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">Profil</th>
                            <th class="text-center px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">Rekomendasi
                            </th>
                            <th class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">Bergabung</th>
                            <th class="text-center px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-white/[0.05]">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-5 py-3.5 text-gray-400 dark:text-gray-600 text-xs">
                                    {{ $users->firstItem() + $loop->index }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0"
                                            style="font-size:12px;background:linear-gradient(135deg,#818cf8,#6366f1);">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($user->profile)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold
                                        bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400
                                        border border-green-100 dark:border-green-500/20">
                                            Lengkap
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold
                                        bg-gray-50 dark:bg-white/[0.05] text-gray-500 dark:text-gray-500
                                        border border-gray-100 dark:border-white/[0.08]">
                                            Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($user->recommendations_count > 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold"
                                            style="background:rgba(99,102,241,0.1);color:#818cf8;">
                                            {{ $user->recommendations_count }}x
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-600">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-xs">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                        class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                                        style="background:rgba(99,102,241,0.1);color:#818cf8;"
                                        onmouseover="this.style.background='rgba(99,102,241,0.2)'"
                                        onmouseout="this.style.background='rgba(99,102,241,0.1)'">
                                        <svg width="12" height="12" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-gray-400 dark:text-gray-600 text-sm">
                                    Belum ada pengguna terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="flex justify-center">
                {{ $users->links() }}
            </div>
        @endif

    </div>
@endsection
