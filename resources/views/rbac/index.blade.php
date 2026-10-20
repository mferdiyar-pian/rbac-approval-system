<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">Info Role & Akses</h2>
        <p class="text-sm text-gray-500 mt-0.5">Informasi role dan permission akun kamu saat ini</p>
    </x-slot>

    <div class="max-w-3xl space-y-5">

        <!-- User Info Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider">Informasi Pengguna</h3>
            </div>
            <div class="px-6 py-5 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Nama</p>
                    <p class="text-gray-800 font-semibold">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Email</p>
                    <p class="text-gray-800 font-semibold">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Roles Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider">Role yang Dimiliki</h3>
            </div>
            <div class="px-6 py-5">
                @forelse ($roles as $role)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-800 mr-2 mb-2">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $role }}
                    </span>
                @empty
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-sm italic">Belum memiliki role.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Permissions Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider">Permission yang Dimiliki</h3>
            </div>
            <div class="px-6 py-5">
                @forelse ($permissions as $permission)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 mr-2 mb-2">
                        <svg class="w-3 h-3 mr-1.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ $permission }}
                    </span>
                @empty
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-sm italic">Belum memiliki permission.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Back to Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Dashboard
        </a>

    </div>
</x-app-layout>
