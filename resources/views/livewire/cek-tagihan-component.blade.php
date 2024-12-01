<div class="w-full self-center px-4 text-center sm:px-0 lg:w-1/2" id="cek_tagihan">
    <div class="mx-2 my-5 rounded-lg bg-indigo-100 p-10 text-gray-700 xl:m-20">
        <h2 class="mb-4 text-4xl font-bold tracking-widest">Cek <span class="border-b-8 border-red-500">Tagihan</span>
        </h2>
        <div class="text-xl font-light">
            <p>
                Dapatkan informasi Tagihan putra-putri Anda disini. <br>
                Rincian Tagihan akan dikirimkan ke
                Nomor <span class="rounded bg-green-600 px-1 text-sm font-bold text-white">Whatsapp</span>
                yang telah didaftarkan
            </p>
        </div>
        <div class="mx-auto my-20 w-full max-w-lg">
            @if ($cek)
                @if ($punya_tagihan)
                    @if ($success)
                        <x-alert type="success">
                            {!! $pesan !!}
                            <div class="mt-3 flex">
                                <button type="button" wire:click="resetForm()"
                                    class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-700 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                                    Kembali
                                </button>
                            </div>
                        </x-alert>
                    @else
                        <x-alert type="danger">
                            {!! $pesan !!}
                            <div class="mt-3 flex">
                                <button type="button" wire:click="resetForm()"
                                    class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-700 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                                    Kembali
                                </button>
                            </div>
                        </x-alert>
                    @endif
                @else
                    <x-alert type="warning">
                        {!! $pesan !!}
                        <div class="mt-3 flex">
                            <button type="button" wire:click="resetForm()"
                                class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-700 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                                Kembali
                            </button>
                        </div>
                    </x-alert>
                @endif
            @else
                <div class="flex items-center border-b border-red-500 py-2">
                    <input
                        class="mr-3 w-full appearance-none border border-transparent bg-transparent px-3 py-2 text-gray-800 focus:border-gray-300 focus:bg-gray-800 focus:text-gray-200 focus:outline-none"
                        type="text" wire:model="nik" placeholder="Masukkan NIK / NISN Siswa" aria-label="NIK/NISN"
                        required=""><button
                        class="flex-shrink-0 border border-red-500 bg-red-400 px-4 py-2 text-red-500 text-white hover:bg-red-500"
                        type="button" wire:click="cekTagihan()">Cek!</button>
                </div>
                @error('nik')
                    <div class="text-red-800">{{ $message }}</div>
                @enderror
            @endif
        </div>
    </div>
</div>
