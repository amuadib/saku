<div class="w-full self-center px-4 text-center sm:px-0 lg:w-1/2" id="cek_tabungan">
    <div class="mx-2 my-5 rounded-lg bg-gray-800 p-10 xl:m-20">
        <h2 class="mb-4 text-4xl font-bold tracking-widest text-gray-200">Cek <span
                class="border-b-8 border-green-500">Tabungan</span></h2>
        <div class="text-xl font-light text-gray-400">
            <p>
                Dapatkan informasi Tabungan putra-putri Anda disini. <br>
                Rincian Tabungan akan dikirimkan ke
                Nomor <span class="rounded bg-green-600 px-1 text-sm font-bold text-white">Whatsapp</span>
                yang telah didaftarkan
            </p>
        </div>
        <div class="mx-auto my-20 w-full max-w-lg">
            @if ($cek)
                @if ($punya_tabungan)
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
                <div class="flex items-center border-b border-green-500 py-2">
                    <input
                        class="mr-3 w-full appearance-none border border-transparent bg-transparent px-3 py-2 text-gray-200 focus:border-gray-400 focus:bg-gray-300 focus:text-gray-800 focus:outline-none"
                        type="text" wire:model="nik" placeholder="Masukkan NIK / NISN Siswa" aria-label="NIK/NISN"
                        required=""><button
                        class="flex-shrink-0 border border-green-500 bg-green-500 px-4 py-2 text-white" type="button"
                        wire:click="cekTabungan()">Cek!</button>
                </div>
                @error('nik')
                    <div class="text-red-800">{{ $message }}</div>
                @enderror
            @endif
        </div>
    </div>
</div>
