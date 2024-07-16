<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @livewire('tabel-tagihan-siswa', ['tagihan' => $getState(), 'siswa' => $getRecord()])
    @php
        $total = 0;
        if ($getState()) {
            foreach ($getState() as $t) {
                if ($t->jumlah - intval($t->bayar) > 0) {
                    $total += $t->jumlah;
                }
            }
        }
    @endphp
    @if ($total > 0)
        <div class="my-2">
            Total Tagihan: Rp {{ number_format($total, 0, ',', '.') }}
        </div>
    @endif
</x-dynamic-component>
