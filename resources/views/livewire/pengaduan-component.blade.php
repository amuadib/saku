<section class="min-h-screen bg-blue-50" id="kontak">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
        <div class="mb-4">
            <div class="mb-6 max-w-3xl text-center sm:text-center md:mx-auto md:mb-12">
                <p class="my-5 text-base font-medium uppercase tracking-tight text-indigo-500">
                    Narahubung
                </p>
                <h2 class="font-heading text-gray-900sm:text-5xl mb-4 text-3xl font-bold tracking-tight">
                    Laporkan Kepada Kami
                </h2>
                <p class="mx-auto mt-4 max-w-3xl text-xl text-gray-600">
                    Jika terdapat Kesalahan tentang Tagihan atau Pembayaran
                </p>
            </div>
        </div>
        <div class="flex items-stretch justify-center">
            <div class="grid md:grid-cols-2">
                <div class="card h-fit max-w-6xl p-5" id="form">
                    <h2 class="text-2xl font-bold">Ada kesalahan tagihan atau pembayaran?</h2>
                    <p class="mb-4">Silahkan laporkan disini</p>
                    @if ($terkirim)
                        <x-alert type="success">
                            Laporan telah kami terima dan akan segera kami tindak lanjuti. Terima kasih
                            <div class="mt-3 flex">
                                <button type="button" wire:click="$set('terkirim', false)"
                                    class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-700 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                                    Kembali
                                </button>
                            </div>
                        </x-alert>
                    @else
                        <form id="contactForm" wire:submit="kirim">
                            <div class="mb-6">
                                <div class="mx-0 mb-1 sm:mb-4">
                                    <div class="mx-0 mb-1 sm:mb-4">
                                        <label for="nik" class="pb-1 text-xs uppercase tracking-wider"></label>
                                        <input type="text" wire:model="nik" autocomplete="nik" required
                                            placeholder="NIK/NISN Siswa"
                                            class="mb-2 w-full rounded-md border border-gray-400 py-2 pl-2 pr-4 sm:mb-0"
                                            name="nik">
                                        @error('nik')
                                            <span class="text-xs italic text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mx-0 mb-1 sm:mb-4">
                                    <label for="textarea" class="pb-1 text-xs uppercase tracking-wider"></label>
                                    <textarea wire:model="laporan" name="textarea" cols="30" rows="5" placeholder="Laporan Anda" required
                                        class="mb-2 w-full rounded-md border border-gray-400 py-2 pl-2 pr-4 sm:mb-0"></textarea>
                                    @error('laporan')
                                        <span class="text-xs italic text-red-500">Laporan belum diisi</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-center">
                                <div style="display:none !important">
                                    <input type="text" wire:model.defer="website" autocomplete="off">
                                    <input type="file" wire:model.defer="lampiran">
                                </div>
                                <button type="submit"
                                    class="font-xl w-full rounded-md bg-indigo-600 px-6 py-3 text-white sm:mb-0">
                                    Kirim
                                </button>

                            </div>
                        </form>
                    @endif
                </div>
                <div class="h-full pl-6">
                    <p class="mb-12 mt-3 text-lg text-gray-600">
                        Untuk mendapatkan Informasi lebih lanjut yang berkaitan dengan SAKU, Silahkan hubungi Kami di
                        Alamat berikut ini:
                    </p>
                    <ul class="mb-6 md:mb-0">
                        <li class="flex">
                            <div class="flex h-10 w-10 items-center justify-center rounded bg-indigo-600 text-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                    <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                    <path
                                        d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="mb-4 ml-4">
                                <h3 class="mb-2 text-lg font-medium leading-6 text-gray-900">Alamat
                                </h3>
                                <p class="text-gray-600">
                                    {{ config('custom.kontak_lembaga.99.alamat') }}
                                </p>
                            </div>
                        </li>
                        <li class="flex">
                            <div class="flex h-10 w-10 items-center justify-center rounded bg-indigo-600 text-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                    <path
                                        d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2">
                                    </path>
                                    <path d="M15 7a2 2 0 0 1 2 2"></path>
                                    <path d="M15 3a6 6 0 0 1 6 6"></path>
                                </svg>
                            </div>
                            <div class="mb-4 ml-4">
                                <h3 class="mb-2 text-lg font-medium leading-6 text-gray-900">
                                    Nomor WhatsApp
                                </h3>
                                @foreach (config('custom.kontak_lembaga') as $l)
                                    @if ($l['kontak'] != '')
                                        <p class="text-gray-600">{{ $l['kontak'] }} ({{ $l['singkatan'] }}):
                                            {{ $l['telp'] }}</p>
                                    @endif
                                @endforeach
                            </div>
                        </li>
                        <li class="flex">
                            <div class="flex h-10 w-10 items-center justify-center rounded bg-indigo-600 text-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                    <path d="M12 7v5l3 3"></path>
                                </svg>
                            </div>
                            <div class="mb-4 ml-4">
                                <h3 class="mb-2 text-lg font-medium leading-6 text-gray-900">
                                    Jam Kerja
                                </h3>
                                @foreach (config('custom.jam_kerja') as $j)
                                    <p class="text-gray-600">{{ $j }}</p>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
