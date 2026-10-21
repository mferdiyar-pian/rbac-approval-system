<section class="space-y-4">
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-xs text-red-800 leading-relaxed">
        <p class="font-bold text-red-900 mb-1 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            Peringatan Keamanan
        </p>
        Setelah akun kamu dihapus, seluruh sumber daya dan data yang terhubung akan dihapus secara permanen. Sebelum menghapus akun, pastikan kamu telah mencadangkan data penting.
    </div>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-xl shadow-sm transition-all hover:-translate-y-0.5"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Hapus Akun Saya
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">
                        Apakah kamu yakin ingin menghapus akun?
                    </h3>
                    <p class="mt-1 text-xs text-gray-500 leading-relaxed">
                        Tindakan ini tidak dapat dibatalkan. Masukkan password akun kamu di bawah ini untuk mengonfirmasi penghapusan permanen.
                    </p>
                </div>
            </div>

            <div class="mt-5">
                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                    Konfirmasi Password Akun
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Masukkan password kamu..."
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 text-gray-800"
                />
                @if($errors->userDeletion->get('password'))
                    <p class="mt-1.5 text-xs text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition-colors">
                    Batal
                </button>

                <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                    Ya, Hapus Akun Secara Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
