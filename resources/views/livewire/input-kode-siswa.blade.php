{{-- <div>
    <h1>Masukkan Kode Siswa</h1>
    @if ($error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endif
    <form wire:submit.prevent="verifikasiKodeSiswa">
        <div class="mb-4">
            <label for="kodeSiswa" class="mb-2 block text-sm font-bold text-gray-700">Kode Siswa</label>
            <input type="text" wire:model="kodeSiswa"
                class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
                id="kodeSiswa" required>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit"
                class="focus:shadow-outline rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none"
                type="button">
                Verifikasi
            </button>
        </div>
    </form>
</div> --}}

<div class="mx-auto max-w-md rounded-lg bg-gray-800 p-8 shadow-lg">
    <h2 class="mb-6 text-center text-2xl font-bold text-white">Cek Tagihan Siswa</h2>
    <div class="mb-6 text-center text-gray-200">Hubungi TU untuk mendapatkan Kode Akses</div>
    @if ($error)
        <div class="relative mb-2 rounded border border-red-400 bg-red-100 px-2 py-1 text-red-700" role="alert">
            <span class="block sm:inline">{{ $error }}</span>
        </div>
    @endif
    <form wire:submit.prevent="verifikasiKodeSiswa" class="space-y-4">
        <div class="form-group">
            <input type="text" id="access-code" name="access-code" wire:model="kode_akses" required
                placeholder="Masukkan Kode Akses"
                class="w-full rounded border border-gray-700 bg-gray-700 px-2 py-1 text-center text-lg font-bold text-white focus:border-blue-500 focus:outline-none">
        </div>
        <button type="submit"
            class="w-full cursor-pointer rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 focus:outline-none"
            wire:loading.attr="disabled">
            <span wire:loading.remove>Kirim</span>
            <span wire:loading class="spinner-border spinner-border-sm" role="status"></span>
            <span wire:loading>Memproses...</span>
        </button>
    </form>
</div>
