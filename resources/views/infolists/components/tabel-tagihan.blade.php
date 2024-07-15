<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @livewire('tabel-tagihan-siswa', ['tagihan' => $getState(), 'siswa' => $getRecord()])
</x-dynamic-component>
