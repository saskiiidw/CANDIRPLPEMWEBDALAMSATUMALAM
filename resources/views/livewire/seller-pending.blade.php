<x-guest-layout>
    <div class="text-center py-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Akun Dalam Proses Verifikasi</h2>
        <p class="text-gray-600 mb-4">
            Halo, <strong>{{ auth()->user()->name }}</strong>! Akun penjual Anda sedang menunggu
            persetujuan dari Admin. Silakan tunggu konfirmasi melalui email atau hubungi pengelola kantin.
        </p>
        <p class="text-sm text-gray-500">Nama Toko: <strong>{{ auth()->user()->store_name }}</strong></p>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit"
                class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
