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
            @if ($nomor != null)
                <div class="m-4 rounded-md border border-green-200 bg-green-100 p-2 text-left text-green-900">
                    <div class="flex flex-wrap justify-between">
                        <div class="flex w-0 flex-1">
                            <div class="mr-3 pt-1">
                                <svg width="26" height="26" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                                    <path
                                        d="M8.445 12.6675A.9.9 0 0 0 7.1424 13.91l2.5726 2.7448c.3679.3856.9884.3689 1.335-.036l5.591-7.0366a.9.9 0 0 0-1.3674-1.1705l-4.6548 5.9132a.4.4 0 0 1-.607.0252l-1.567-1.6826zM1.9995 12c0-5.5 4.5-10 10-10s10 4.5 10 10-4.5 10-10 10-10-4.5-10-10z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-md font-bold leading-6">
                                    Sukses
                                </h4>
                                <p class="text-sm">
                                    Rincian Tagihan telah dikirimkan ke Nomor WhatsApp {{ $nomor }}
                                </p>
                                <div class="mt-3 flex">
                                    <button type="button" wire:click="resetForm()"
                                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-700 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                                        Kembali
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="button" wire:click="resetForm()"
                                class="rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <svg width="24" height="24" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                                    <path
                                        d="M17.6555 6.3331a.9.9 0 0 1 .001 1.2728l-4.1032 4.1085a.4.4 0 0 0 0 .5653l4.1031 4.1088a.9002.9002 0 0 1 .0797 1.1807l-.0806.092a.9.9 0 0 1-1.2728-.0009l-4.1006-4.1068a.4.4 0 0 0-.5662 0l-4.099 4.1068a.9.9 0 1 1-1.2738-1.2718l4.1027-4.1089a.4.4 0 0 0 0-.5652L6.343 7.6059a.9002.9002 0 0 1-.0796-1.1807l.0806-.092a.9.9 0 0 1 1.2728.0009l4.099 4.1055a.4.4 0 0 0 .5662 0l4.1006-4.1055a.9.9 0 0 1 1.2728-.001z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
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
