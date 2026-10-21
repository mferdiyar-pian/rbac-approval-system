<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Profil Saya</h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelola identitas akun dan pengaturan keamanan kata sandi kamu</p>
            </div>
            <div class="flex items-center gap-2">
                @foreach (Auth::user()->getRoleNames() as $role)
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-semibold rounded-full">
                        Peran: {{ $role }}
                    </span>
                @endforeach
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl space-y-6">

        <!-- Update Info Profil -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 flex items-center gap-3 text-white">
                <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Informasi Profil</h3>
                    <p class="text-indigo-200 text-xs mt-0.5">Perbarui nama tampilan dan alamat email akun kamu</p>
                </div>
            </div>
            <div class="p-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 flex items-center gap-3 text-white">
                <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Perbarui Password</h3>
                    <p class="text-indigo-200 text-xs mt-0.5">Pastikan akun menggunakan password yang kuat dan aman</p>
                </div>
            </div>
            <div class="p-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Hapus Akun -->
        <div class="bg-white rounded-2xl border border-red-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 flex items-center gap-3 text-white">
                <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Hapus Akun Pengguna</h3>
                    <p class="text-red-200 text-xs mt-0.5">Tindakan ini akan menghapus akun dan data terkait secara permanen</p>
                </div>
            </div>
            <div class="p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</x-app-layout>
