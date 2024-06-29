<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @livewire('tabel-tabungan-siswa', ['tabungan' => $getState(), 'siswa' => $getRecord()])
    @php
        $total = 0;
        if ($getState()) {
            foreach ($getState() as $t) {
                $total += $t->saldo;
            }
        }
    @endphp
    @if ($total > 0)
        <div class="my-2">
            Saldo Tabungan: Rp {{ number_format($total, 0, ',', '.') }}
        </div>
    @endif
</x-dynamic-component>
