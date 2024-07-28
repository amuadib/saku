<x-filament-panels::page>
    @php
        $data = $this->getRecord();
    @endphp
    {{ $this->infolist }}
    {{ $this->table }}

    @if ($total > 0)
        <div class="mt-10 lg:mt-0">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Total Belanja</h2>
            <div
                class="mt-4 rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
                <dl class="space-y-6 px-4 py-6 sm:px-6">
                    <div class="flex items-center justify-between">
                        <dt class="text-lg">Total</dt>
                        <dd class="text-lg font-bold text-gray-900 dark:text-white">Rp
                            {{ number_format($total, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                        <dt class="text-base font-medium">Pembayaran</dt>
                        <dd class="text-base font-medium text-gray-900">
                            <div class="grid gap-y-2">
                                <div style="--cols-default: 1;"
                                    class="fi-fo-radio flex columns-[--cols-default] flex-wrap gap-4">
                                    <div class="">
                                        <label class="flex gap-x-3">
                                            <input type="radio"
                                                class="fi-radio-input text-primary-600 focus:ring-primary-600 checked:focus:ring-primary-500/50 dark:text-primary-500 dark:checked:bg-primary-500 dark:focus:ring-primary-500 dark:checked:focus:ring-primary-400/50 mt-1 border-none bg-white shadow-sm ring-1 ring-gray-950/10 transition duration-75 checked:ring-0 focus:ring-2 focus:ring-offset-0 disabled:bg-gray-50 disabled:text-gray-50 disabled:checked:bg-current disabled:checked:text-gray-400 dark:bg-white/5 dark:ring-white/20 dark:disabled:bg-transparent dark:disabled:ring-white/10 dark:disabled:checked:bg-gray-600"
                                                id="pembayaran-tun" name="pembayaran" value="tun"
                                                wire:loading.attr="disabled" wire:model.live="pembayaran">
                                            <div class="grid text-sm leading-6">
                                                <span class="font-medium text-gray-950 dark:text-white">
                                                    Tunai
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="">
                                        <label class="flex gap-x-3">
                                            <input type="radio"
                                                class="fi-radio-input text-primary-600 focus:ring-primary-600 checked:focus:ring-primary-500/50 dark:text-primary-500 dark:checked:bg-primary-500 dark:focus:ring-primary-500 dark:checked:focus:ring-primary-400/50 mt-1 border-none bg-white shadow-sm ring-1 ring-gray-950/10 transition duration-75 checked:ring-0 focus:ring-2 focus:ring-offset-0 disabled:bg-gray-50 disabled:text-gray-50 disabled:checked:bg-current disabled:checked:text-gray-400 dark:bg-white/5 dark:ring-white/20 dark:disabled:bg-transparent dark:disabled:ring-white/10 dark:disabled:checked:bg-gray-600"
                                                id="pembayaran-tag" name="pembayaran" value="tag"
                                                wire:loading.attr="disabled" wire:model.live="pembayaran">
                                            <div class="grid text-sm leading-6">
                                                <span class="font-medium text-gray-950 dark:text-white">
                                                    Masukkan Tagihan
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                    {{--
                                    @if ($saldoCukup)
                                        <div class="">
                                            <label class="flex gap-x-3">
                                                <input type="radio"
                                                    class="fi-radio-input text-primary-600 focus:ring-primary-600 checked:focus:ring-primary-500/50 dark:text-primary-500 dark:checked:bg-primary-500 dark:focus:ring-primary-500 dark:checked:focus:ring-primary-400/50 mt-1 border-none bg-white shadow-sm ring-1 ring-gray-950/10 transition duration-75 checked:ring-0 focus:ring-2 focus:ring-offset-0 disabled:bg-gray-50 disabled:text-gray-50 disabled:checked:bg-current disabled:checked:text-gray-400 dark:bg-white/5 dark:ring-white/20 dark:disabled:bg-transparent dark:disabled:ring-white/10 dark:disabled:checked:bg-gray-600"
                                                    id="pembayaran-tab" name="pembayaran" value="tab"
                                                    wire:loading.attr="disabled" wire:model.live="pembayaran">
                                                <div class="grid text-sm leading-6">
                                                    <span class="font-medium text-gray-950 dark:text-white">
                                                        Ambil dari Tabungan
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                    @endif
                                     --}}
                                </div>
                            </div>
                            <div>
                                @error('pembayaran')
                                    <span class="text-red-600">Pilih jenis pembayaran</span>
                                @enderror
                            </div>
                        </dd>
                    </div>
                    @if ($pembayaran == 'tun')
                        <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                            <dt class="text-base font-medium">Bayar</dt>
                            <dd class="text-base font-medium text-gray-900">
                                <div class="grid gap-y-2">
                                    <div
                                        class="fi-input-wrp [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 fi-fo-text-input flex overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-950/10 transition duration-75 dark:bg-white/5 dark:ring-white/20">
                                        <div
                                            class="flex items-center gap-x-3 border-e border-gray-200 pe-3 ps-3 ps-3 dark:border-white/10">
                                            <span
                                                class="fi-input-wrp-label whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                Rp
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <input wire:model="bayar"
                                                class="fi-input block w-full border-none bg-white/0 py-1.5 pe-3 ps-3 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6"
                                                id="bayar" type="text" x-data="{
                                                    input: $wire.$entangle('bayar', false),
                                                    masked: '',
                                                    init() {
                                                        $nextTick(this.updateMasked());
                                                        $watch('masked', () => this.updateInput());
                                                        $watch('input', () => this.updateMasked());
                                                    },
                                                    updateMasked(value, oldValue) {
                                                        if (this.input !== undefined & amp; & amp; typeof Number(this.input) === 'number') {
                                                            if (this.masked?.replaceAll('.', '').replaceAll(',', '.') != this.input) {
                                                                this.masked = this.input?.toString().replaceAll('.', ',');
                                                            }
                                                        }
                                                    },
                                                    updateInput() {
                                                        this.input = this.masked?.replaceAll('.', '').replaceAll(',', '.');
                                                    }
                                                }" x-model="masked"
                                                x-mask:dynamic="$money($input,',','.',0)">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    @error('bayar')
                                        <span class="text-red-600">Masukkan nominal pembayaran</span>
                                    @enderror
                                    @error('kurang')
                                        <span class="text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </dd>
                        </div>
                    @elseif($pembayaran == 'tab')
                        <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                            <dt class="text-base font-medium">Tabungan</dt>
                            <dd class="text-base font-medium text-gray-900">
                                <div class="grid gap-y-2">
                                    <div style="--cols-default: 1;"
                                        class="fi-fo-radio flex columns-[--cols-default] flex-wrap gap-4">
                                        @foreach ($tabungan[$data->id] as $tb => $ket)
                                            <div class="">
                                                <label class="flex gap-x-3">
                                                    <input type="radio"
                                                        class="fi-radio-input text-primary-600 focus:ring-primary-600 checked:focus:ring-primary-500/50 dark:text-primary-500 dark:checked:bg-primary-500 dark:focus:ring-primary-500 dark:checked:focus:ring-primary-400/50 mt-1 border-none bg-white shadow-sm ring-1 ring-gray-950/10 transition duration-75 checked:ring-0 focus:ring-2 focus:ring-offset-0 disabled:bg-gray-50 disabled:text-gray-50 disabled:checked:bg-current disabled:checked:text-gray-400 dark:bg-white/5 dark:ring-white/20 dark:disabled:bg-transparent dark:disabled:ring-white/10 dark:disabled:checked:bg-gray-600"
                                                        id="pembayaran-{{ $tb }}"
                                                        name="tabungan-{{ $tb }}" value="{{ $tb }}"
                                                        wire:loading.attr="disabled" wire:model="tabungan_id">
                                                    <div class="grid text-sm leading-6">
                                                        <span class="font-medium text-gray-950 dark:text-white">
                                                            {{ $ket['nama'] }}
                                                        </span>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    @error('tabungan_id')
                                        <span class="text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </dd>
                        </div>
                    @endif
                    {{-- <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                    <dt class="text-base font-medium">Kembali</dt>
                    <dd class="text-base font-medium text-gray-900">Rp {{ number_format($kembali, 0, ',', '.') }}</dd>
                </div> --}}
                </dl>

                <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                    <button type="button" wire:click="prosesPembayaran"
                        class="w-full rounded-lg border border-transparent bg-blue-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50">Proses</button>
                </div>
            </div>
        </div>
    @endif

    @if ($transaksi_selesai)
        <div id="success"
            class="rounded-lg bg-white ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
            <div class="p-6 md:mx-auto">
                <svg viewBox="0 0 24 24" class="mx-auto my-6 h-16 w-16 text-green-600">
                    <path fill="currentColor"
                        d="M12,0A12,12,0,1,0,24,12,12.014,12.014,0,0,0,12,0Zm6.927,8.2-6.845,9.289a1.011,1.011,0,0,1-1.43.188L5.764,13.769a1,1,0,1,1,1.25-1.562l4.076,3.261,6.227-8.451A1,1,0,1,1,18.927,8.2Z">
                    </path>
                </svg>
                <div class="text-center">
                    <h3 class="text-center text-base font-semibold text-gray-900 dark:text-white md:text-2xl">Transaksi
                        Berhasil !</h3>
                    @if ($pembayaran == 'tun')
                        <p class="my-2 text-gray-600">
                            Pembayaran tunai.
                            @if ($kembali > 0)
                                Kembali <span class="rounded-md bg-green-600 px-2 py-1 font-bold text-white">Rp
                                    {{ number_format(num: $kembali, thousands_separator: '.') }}</span>
                            @endif
                        </p>
                    @elseif($pembayaran == 'tag')
                        <p class="my-2 text-gray-600">Tagihan telah ditambahkan.</p>
                    @elseif($pembayaran == 'tab')
                        <p class="my-2 text-gray-600">Pembayaran diambil dari Tabungan.</p>
                    @endif
                    <div class="py-10 text-center">
                        <a href="{{ url('/siswas') }}"
                            class="mr-1 rounded-lg bg-blue-600 px-12 py-3 font-semibold text-white hover:bg-blue-700">
                            Kembali
                        </a>
                        @if ($id != null)
                            <a href="#success" wire:click="cetak('{{ $id }}')"
                                class="ml-1 rounded-lg bg-green-600 px-12 py-3 font-semibold text-white hover:bg-green-700">
                                Cetak
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
