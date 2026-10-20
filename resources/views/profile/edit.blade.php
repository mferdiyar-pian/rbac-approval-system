<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">Profil Saya</h2>
        <p class="text-sm text-gray-500 mt-0.5">Kelola informasi akun dan keamanan kamu</p>
    </x-slot>

    <div class="max-w-2xl space-y-5">

        <!-- Update Info Profil -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-3">
                <h3 class="text-white font-semibold text-sm">Informasi Profil</h3>
            </div>
            <div class="p-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-3">
                <h3 class="text-white font-semibold text-sm">Ubah Password</h3>
            </div>
            <div class="p-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Hapus Akun -->
        <div class="bg-white rounded-xl border border-red-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-3">
                <h3 class="text-white font-semibold text-sm">Hapus Akun</h3>
            </div>
            <div class="p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</x-app-layout>
