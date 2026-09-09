<div class="max-w-1/2 mx-auto rounded-lg bg-white p-8 shadow-lg">
    <div class="header mb-6 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Tagihan Pembayaran Siswa</h1>
    </div>
    <div class="container p-4">
        <div class="profile mb-6 rounded-lg bg-gray-100 p-4">
            <img src="{{ asset($siswa->foto && Storage::disk('public')->exists($siswa->foto) ? url('/storage/' . $siswa->foto) : asset('image.png')) }}"
                alt="Foto {{ $siswa->nama }}" class="mx-auto h-32 w-32 rounded-full">
            <div class="profile-info mt-4 text-center">
                <h2 class="text-2xl font-bold text-gray-800">{{ $siswa->nama }}</h2>
                <p class="text-gray-600">Kelas: {{ $siswa->kelas->nama }}</p>
                <p class="text-gray-600">NIS: {{ $siswa->nis }}</p>
            </div>
        </div>
        <h2 class="mb-4 text-2xl font-bold text-gray-800">Detail Tagihan</h2>
        <table class="table w-full border-collapse">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2">No.</th>
                    <th class="border px-4 py-2">Jenis Tagihan</th>
                    <th class="border px-4 py-2">Tanggal</th>
                    <th class="border px-4 py-2">Deskripsi</th>
                    <th class="border px-4 py-2">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $no = 0;
                @endphp
                @foreach ($siswa->tagihan as $t)
                    @if (!$t->isLunas())
                        @php
                            $total += $t->jumlah;
                        @endphp
                        <tr>
                            <td class="border px-4 py-2">{{ ++$no }}</td>
                            <td class="border px-4 py-2">{{ $t->kas->nama }}</td>
                            <td class="border px-4 py-2">{{ $t->created_at->format('d-m-Y') }}</td>
                            <td class="border px-4 py-2">{{ $t->keterangan }}</td>
                            <td class="border px-4 py-2">{{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach

                <tr>
                    <td colspan="4" class="total border px-4 py-2 text-right">Total</td>
                    <td class="total border px-4 py-2 text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
