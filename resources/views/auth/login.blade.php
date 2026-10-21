<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div x-data="{
        selectedEmail: '',
        fillCredentials(email) {
            this.selectedEmail = email;
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'BelajarRBAC123!';
        }
    }">
        <!-- Form Header -->
        <div class="mb-5 text-center">
            <div class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 mb-2.5 shadow-sm border border-indigo-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Selamat Datang</h2>
            <p class="text-xs text-gray-500 mt-0.5">Masuk ke akun Anda untuk mengakses sistem</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                    Alamat Email
                </label>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="nama@contoh.com"
                        class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none text-gray-800 placeholder:text-gray-400 transition-all bg-gray-50/50 focus:bg-white {{ $errors->get('email') ? 'border-red-400 bg-red-50' : '' }}"
                    >
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none text-gray-800 placeholder:text-gray-400 transition-all bg-gray-50/50 focus:bg-white {{ $errors->get('password') ? 'border-red-400 bg-red-50' : '' }}"
                    >
                </div>
                @error('password')
                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Remember Me + Forgot Password -->
            <div class="flex items-center justify-between text-xs pt-0.5">
                <label for="remember_me" class="flex items-center gap-1.5 cursor-pointer text-gray-600">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="w-3.5 h-3.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                    <span>Ingat Saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-indigo-600 hover:text-indigo-800 font-medium hover:underline transition-colors">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-500/20 transition-all duration-150 hover:-translate-y-0.5 active:translate-y-0 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Masuk ke Sistem
            </button>
        </form>

        <!-- Quick Demo Accounts Grid (1-Click Fill) -->
        <div class="mt-4 pt-3.5 border-t border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-gray-600 uppercase tracking-wider flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                    </svg>
                    Uji Coba Akun Demo:
                </span>
                <span class="text-[10px] text-gray-500 font-mono bg-gray-100 px-1.5 py-0.5 rounded">
                    Sandi: BelajarRBAC123!
                </span>
            </div>

            <!-- Symmetric 2x3 Grid of Demo Accounts -->
            <div class="grid grid-cols-2 gap-1.5 text-xs">
                <!-- Superadmin -->
                <button
                    type="button"
                    @click="fillCredentials('superadmin@example.com')"
                    :class="selectedEmail === 'superadmin@example.com' ? 'ring-2 ring-purple-500 bg-purple-100 text-purple-900 border-purple-400' : 'bg-purple-50 text-purple-700 hover:bg-purple-100/80 border-purple-200'"
                    class="px-2.5 py-1.5 rounded-lg border text-left transition-all flex items-center justify-between">
                    <span class="font-semibold flex items-center gap-1">
                        <span>👑</span> Superadmin
                    </span>
                    <span class="text-[10px] opacity-75 font-mono">Pilih</span>
                </button>

                <!-- Admin -->
                <button
                    type="button"
                    @click="fillCredentials('admin@example.com')"
                    :class="selectedEmail === 'admin@example.com' ? 'ring-2 ring-indigo-500 bg-indigo-100 text-indigo-900 border-indigo-400' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100/80 border-indigo-200'"
                    class="px-2.5 py-1.5 rounded-lg border text-left transition-all flex items-center justify-between">
                    <span class="font-semibold flex items-center gap-1">
                        <span>⚙️</span> Admin
                    </span>
                    <span class="text-[10px] opacity-75 font-mono">Pilih</span>
                </button>

                <!-- Inputer -->
                <button
                    type="button"
                    @click="fillCredentials('inputer@example.com')"
                    :class="selectedEmail === 'inputer@example.com' ? 'ring-2 ring-blue-500 bg-blue-100 text-blue-900 border-blue-400' : 'bg-blue-50 text-blue-700 hover:bg-blue-100/80 border-blue-200'"
                    class="px-2.5 py-1.5 rounded-lg border text-left transition-all flex items-center justify-between">
                    <span class="font-semibold flex items-center gap-1">
                        <span>✍️</span> 1. Inputer
                    </span>
                    <span class="text-[10px] opacity-75 font-mono">Pilih</span>
                </button>

                <!-- Checker -->
                <button
                    type="button"
                    @click="fillCredentials('checker@example.com')"
                    :class="selectedEmail === 'checker@example.com' ? 'ring-2 ring-amber-500 bg-amber-100 text-amber-900 border-amber-400' : 'bg-amber-50 text-amber-700 hover:bg-amber-100/80 border-amber-200'"
                    class="px-2.5 py-1.5 rounded-lg border text-left transition-all flex items-center justify-between">
                    <span class="font-semibold flex items-center gap-1">
                        <span>🔍</span> 2. Checker
                    </span>
                    <span class="text-[10px] opacity-75 font-mono">Pilih</span>
                </button>

                <!-- Approver -->
                <button
                    type="button"
                    @click="fillCredentials('approver@example.com')"
                    :class="selectedEmail === 'approver@example.com' ? 'ring-2 ring-emerald-500 bg-emerald-100 text-emerald-900 border-emerald-400' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100/80 border-emerald-200'"
                    class="px-2.5 py-1.5 rounded-lg border text-left transition-all flex items-center justify-between">
                    <span class="font-semibold flex items-center gap-1">
                        <span>✅</span> 3. Approver
                    </span>
                    <span class="text-[10px] opacity-75 font-mono">Pilih</span>
                </button>

                <!-- Standard User -->
                <button
                    type="button"
                    @click="fillCredentials('user@example.com')"
                    :class="selectedEmail === 'user@example.com' ? 'ring-2 ring-gray-500 bg-gray-100 text-gray-900 border-gray-400' : 'bg-gray-50 text-gray-700 hover:bg-gray-100/80 border-gray-200'"
                    class="px-2.5 py-1.5 rounded-lg border text-left transition-all flex items-center justify-between">
                    <span class="font-semibold flex items-center gap-1">
                        <span>👤</span> User Biasa
                    </span>
                    <span class="text-[10px] opacity-75 font-mono">Pilih</span>
                </button>
            </div>
        </div>
    </div>
</x-guest-layout>
