<div id="cek_tagihan" class="flex min-h-screen w-full flex-col items-center justify-center bg-gray-800">
    <div class="w-full self-center px-4 text-center sm:px-0 xl:w-1/3">
        <h2 class="mb-4 text-4xl font-bold tracking-widest text-gray-200">Cek Tagihan</h2>
        <div class="text-xl font-light text-gray-400">
            <p>
                Dapatkan informasi Tagihan putra-putri Anda disini. <br>
                Rincian Tagihan akan dikirimkan ke
                Nomor <span class="rounded bg-green-600 px-2 py-1 font-bold text-white">WhatsApp</span>
                yang telah didaftarkan
            </p>
        </div>
        <div class="mx-auto my-20 w-full max-w-lg">
            @if ($send)
                @if ($tagihan)
                    @if ($success)
                        <x-alert type="success">
                            {{ $pesan }}
                            <div class="mt-3 flex">
                                <button type="button" wire:click="resetForm()"
                                    class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-700 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                                    Kembali
                                </button>
                            </div>
                        </x-alert>
                    @else
                        <x-alert type="danger">
                            {{ $pesan }}
                            <div class="mt-3 flex">
                                <button type="button" wire:click="resetForm()"
                                    class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-700 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                                    Kembali
                                </button>
                            </div>
                        </x-alert>
                    @endif
                @else
                    <x-alert type="success">
                        Semua tagihan siswa sudah <span class="font-bold">Lunas</span>. Terima kasih
                        <div class="mt-3 flex">
                            <button type="button" wire:click="resetForm()"
                                class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-700 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                                Kembali
                            </button>
                        </div>
                    </x-alert>
                @endif
            @else
                <div class="flex items-center border-b border-pink-500 py-2">
                    <input
                        class="mr-3 w-full appearance-none border border-transparent bg-transparent px-3 py-2 text-gray-200 focus:border-gray-400 focus:bg-gray-300 focus:text-gray-800 focus:outline-none"
                        type="text" wire:model="nik" placeholder="Masukkan NIK / NISN Siswa" aria-label="NIK/NISN"
                        required=""><button
                        class="flex-shrink-0 border border-pink-500 bg-pink-500 px-4 py-2 text-gray-200 hover:bg-gray-800 hover:text-pink-500"
                        type="button" wire:click="cek()">Cek!</button>
                </div>
                @error('nik')
                    <div class="text-red-800">{{ $message }}</div>
                @enderror
            @endif
        </div>
    </div>
</div>
