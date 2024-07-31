@if (count($data))
    @php
        $lembaga = config('custom.lembaga')[$data['lembaga_id']];
        $kontak = config('custom.kontak_lembaga')[$data['lembaga_id']];

        function format_angka(int $num)
        {
            return number_format(num: $num, thousands_separator: '.');
        }
    @endphp
    <!DOCTYPE html>
    <html>

    <head>
        <title>Cetak Struk Pembayaran Tagihan</title>
        <link href="{{ asset('/css/cetak.css') }}" rel="stylesheet" />
    </head>

    <body>
        <div class="text-center font-bold" style="margin-bottom:5px;">
            <span
                style="display: inline-block;
        transform: scale(1, 2);
        -webkit-transform: scale(1, 2);
        -moz-transform: scale(1, 2);
        -o-transform: scale(1, 2);
        transform-origin: 0% 70%;">
                {{ strtoupper($lembaga) }}
            </span>
        </div>
        <div class="text-center">
            {{ $kontak['alamat'] }}
        </div>
        <table style="width: 100%; margin-top:15px;">
            <tr>
                <td>No.</td>
                <td>:</td>
                <td>{{ $data['transaksi_id'] }}</td>
                <td class="text-right">{{ $data['tanggal'] }}</td>
            </tr>
            <tr>
                <td>Petugas</td>
                <td>:</td>
                <td>{{ $data['petugas'] }}</td>
                <td class="text-right">{{ $data['waktu'] }}</td>
            </tr>
            <tr>
                <td>Siswa</td>
                <td>:</td>
                <td colspan="2">{{ $data['siswa'] }}</td>
            </tr>
        </table>

        <h3 class="text-center">Bukti Pembayaran Tagihan</h3>
        <table style="margin-top:15px;">
            @if (isset($data['tagihan']))
                @foreach ($data['tagihan'] as $b)
                    <tr>
                        <td>
                            {{ $b['keterangan'] }}
                        </td>
                        <td class="text-right">
                            {{ format_angka($b['jumlah']) }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        {{ $data['keterangan'] }}
                    </td>
                    <td class="text-right">
                        {{ format_angka($data['jumlah']) }}
                    </td>
                </tr>
            @endif
        </table>
        <div class="divider"></div>
        <table>
            @if (isset($data['tagihan']))
                <tr class="font-bold">
                    <td width="65%">
                        Total
                    </td>
                    <td class="text-right" width="20px">
                        Rp
                    </td>
                    <td class="text-right">
                        {{ format_angka($data['total']) }}
                    </td>
                </tr>
            @endif
            <tr class="font-bold">
                <td>
                    Bayar
                </td>
                <td class="text-right">
                    Rp
                </td>
                <td class="text-right">
                    {{ format_angka($data['jumlah']) }}
                </td>
            </tr>
        </table>

        <div class="text-center" style="font-size: 12px; margin-top:25px;">
            Struk ini merupakan bukti pembayaran yang sah. <br>
            Mohon disimpan dengan baik. <br>
            <spsn class="font-bold">Terima Kasih</spsn>
        </div>
        <script>
            window.print();
        </script>
    </body>

    </html>
@else
    Maaf, Halaman tidak ditemukan. <br><a href="/">Kembali</a>
@endif
