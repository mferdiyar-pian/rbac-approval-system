<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div x-data="{
        selectedEmail: '{{ old('email') }}',
        showPassword: false,
        justFilledRole: '',
        fillCredentials(email, roleLabel) {
            this.selectedEmail = email;
            this.justFilledRole = roleLabel;
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            if (emailInput) emailInput.value = email;
            if (passwordInput) passwordInput.value = 'BelajarRBAC123!';
            setTimeout(() => { this.justFilledRole = ''; }, 2000);
        }
    }">
        <!-- Form Header -->
        <div class="mb-5 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 mb-2.5 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Selamat Datang</h2>
            <p class="text-xs text-gray-500 mt-0.5">Masuk ke akun Anda untuk mengakses sistem</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address Field -->
            <div>
                <label for="email" class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Alamat Email
                </label>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="nama@contoh.com"
                        class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-gray-200 bg-gray-50/60 focus:bg-white text-gray-800 placeholder:text-gray-400 transition-all outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-sm {{ $errors->get('email') ? 'border-red-400 bg-red-50 focus:border-red-500' : '' }}"
                    >
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Password Field with Show/Hide Toggle -->
            <div>
                <label for="password" class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input
                        id="password"
                        name="password"
                        :type="showPassword ? 'text' : 'password'"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full pl-10 pr-11 py-2.5 text-sm rounded-xl border border-gray-200 bg-gray-50/60 focus:bg-white text-gray-800 placeholder:text-gray-400 transition-all outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-sm {{ $errors->get('password') ? 'border-red-400 bg-red-50 focus:border-red-500' : '' }}"
                    >
                    <!-- Show/Hide Toggle Button -->
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1 rounded-lg focus:outline-none transition-colors"
                        title="Tampilkan/Sembunyikan Password"
                    >
                        <template x-if="!showPassword">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </template>
                        <template x-if="showPassword">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </template>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Remember Me + Forgot Password -->
            <div class="flex items-center justify-between text-xs pt-0.5">
                <label for="remember_me" class="flex items-center gap-2 cursor-pointer text-gray-600 select-none">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500/30 cursor-pointer">
                    <span class="font-medium text-xs">Ingat Saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs hover:underline transition-colors">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full flex items-center justify-center gap-2.5 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-150 hover:-translate-y-0.5 active:translate-y-0 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Masuk ke Sistem
            </button>
        </form>

        <!-- Quick Demo Accounts Grid (1-Click Fill) -->
        <div class="mt-4 pt-3.5 border-t border-gray-100">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-[11px] font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                    </svg>
                    Uji Coba Akun Demo
                </span>
                <span class="text-[10px] text-gray-500 font-mono bg-gray-100 px-2 py-0.5 rounded-md border border-gray-200">
                    Sandi: BelajarRBAC123!
                </span>
            </div>

            <!-- Feedback Toast on Fill -->
            <div x-show="justFilledRole" x-transition.opacity class="mb-2 text-center text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-200 py-1 px-2 rounded-lg font-medium flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Akun <strong x-text="justFilledRole"></strong> berhasil diisi! Silakan klik Masuk.</span>
            </div>

            <!-- Symmetric 2x3 Grid of Demo Accounts -->
            <div class="grid grid-cols-2 gap-2 text-xs">
                <!-- Superadmin -->
                <button
                    type="button"
                    @click="fillCredentials('superadmin@example.com', 'Superadmin')"
                    :class="selectedEmail === 'superadmin@example.com' ? 'ring-2 ring-purple-500 bg-purple-100/90 text-purple-950 border-purple-400 font-bold' : 'bg-purple-50/70 text-purple-700 hover:bg-purple-100/80 border-purple-200'"
                    class="px-2.5 py-2 rounded-xl border text-left transition-all duration-150 flex items-center justify-between group shadow-sm hover:shadow">
                    <span class="flex items-center gap-1.5 font-semibold">
                        <span class="text-sm">👑</span>
                        <span>Superadmin</span>
                    </span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded font-medium"
                          :class="selectedEmail === 'superadmin@example.com' ? 'bg-purple-600 text-white' : 'bg-purple-200/60 text-purple-700 group-hover:bg-purple-200'">
                        <span x-text="selectedEmail === 'superadmin@example.com' ? 'Aktif' : 'Pilih'"></span>
                    </span>
                </button>

                <!-- Admin -->
                <button
                    type="button"
                    @click="fillCredentials('admin@example.com', 'Admin')"
                    :class="selectedEmail === 'admin@example.com' ? 'ring-2 ring-indigo-500 bg-indigo-100/90 text-indigo-950 border-indigo-400 font-bold' : 'bg-indigo-50/70 text-indigo-700 hover:bg-indigo-100/80 border-indigo-200'"
                    class="px-2.5 py-2 rounded-xl border text-left transition-all duration-150 flex items-center justify-between group shadow-sm hover:shadow">
                    <span class="flex items-center gap-1.5 font-semibold">
                        <span class="text-sm">⚙️</span>
                        <span>Admin</span>
                    </span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded font-medium"
                          :class="selectedEmail === 'admin@example.com' ? 'bg-indigo-600 text-white' : 'bg-indigo-200/60 text-indigo-700 group-hover:bg-indigo-200'">
                        <span x-text="selectedEmail === 'admin@example.com' ? 'Aktif' : 'Pilih'"></span>
                    </span>
                </button>

                <!-- Inputer -->
                <button
                    type="button"
                    @click="fillCredentials('inputer@example.com', '1. Inputer')"
                    :class="selectedEmail === 'inputer@example.com' ? 'ring-2 ring-blue-500 bg-blue-100/90 text-blue-950 border-blue-400 font-bold' : 'bg-blue-50/70 text-blue-700 hover:bg-blue-100/80 border-blue-200'"
                    class="px-2.5 py-2 rounded-xl border text-left transition-all duration-150 flex items-center justify-between group shadow-sm hover:shadow">
                    <span class="flex items-center gap-1.5 font-semibold">
                        <span class="text-sm">✍️</span>
                        <span>1. Inputer</span>
                    </span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded font-medium"
                          :class="selectedEmail === 'inputer@example.com' ? 'bg-blue-600 text-white' : 'bg-blue-200/60 text-blue-700 group-hover:bg-blue-200'">
                        <span x-text="selectedEmail === 'inputer@example.com' ? 'Aktif' : 'Pilih'"></span>
                    </span>
                </button>

                <!-- Checker -->
                <button
                    type="button"
                    @click="fillCredentials('checker@example.com', '2. Checker')"
                    :class="selectedEmail === 'checker@example.com' ? 'ring-2 ring-amber-500 bg-amber-100/90 text-amber-950 border-amber-400 font-bold' : 'bg-amber-50/70 text-amber-700 hover:bg-amber-100/80 border-amber-200'"
                    class="px-2.5 py-2 rounded-xl border text-left transition-all duration-150 flex items-center justify-between group shadow-sm hover:shadow">
                    <span class="flex items-center gap-1.5 font-semibold">
                        <span class="text-sm">🔍</span>
                        <span>2. Checker</span>
                    </span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded font-medium"
                          :class="selectedEmail === 'checker@example.com' ? 'bg-amber-600 text-white' : 'bg-amber-200/60 text-amber-700 group-hover:bg-amber-200'">
                        <span x-text="selectedEmail === 'checker@example.com' ? 'Aktif' : 'Pilih'"></span>
                    </span>
                </button>

                <!-- Approver -->
                <button
                    type="button"
                    @click="fillCredentials('approver@example.com', '3. Approver')"
                    :class="selectedEmail === 'approver@example.com' ? 'ring-2 ring-emerald-500 bg-emerald-100/90 text-emerald-950 border-emerald-400 font-bold' : 'bg-emerald-50/70 text-emerald-700 hover:bg-emerald-100/80 border-emerald-200'"
                    class="px-2.5 py-2 rounded-xl border text-left transition-all duration-150 flex items-center justify-between group shadow-sm hover:shadow">
                    <span class="flex items-center gap-1.5 font-semibold">
                        <span class="text-sm">✅</span>
                        <span>3. Approver</span>
                    </span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded font-medium"
                          :class="selectedEmail === 'approver@example.com' ? 'bg-emerald-600 text-white' : 'bg-emerald-200/60 text-emerald-700 group-hover:bg-emerald-200'">
                        <span x-text="selectedEmail === 'approver@example.com' ? 'Aktif' : 'Pilih'"></span>
                    </span>
                </button>

                <!-- Standard User -->
                <button
                    type="button"
                    @click="fillCredentials('user@example.com', 'User Biasa')"
                    :class="selectedEmail === 'user@example.com' ? 'ring-2 ring-slate-500 bg-slate-200 text-slate-950 border-slate-400 font-bold' : 'bg-slate-50 text-slate-700 hover:bg-slate-100/80 border-slate-200'"
                    class="px-2.5 py-2 rounded-xl border text-left transition-all duration-150 flex items-center justify-between group shadow-sm hover:shadow">
                    <span class="flex items-center gap-1.5 font-semibold">
                        <span class="text-sm">👤</span>
                        <span>User Biasa</span>
                    </span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded font-medium"
                          :class="selectedEmail === 'user@example.com' ? 'bg-slate-700 text-white' : 'bg-slate-200/70 text-slate-700 group-hover:bg-slate-200'">
                        <span x-text="selectedEmail === 'user@example.com' ? 'Aktif' : 'Pilih'"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</x-guest-layout>
