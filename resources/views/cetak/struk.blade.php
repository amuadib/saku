@if (count($data))
    @php
        $identitas = config('custom.identitas')[$data['lembaga_id']];

        function format_angka(int $num)
        {
            return number_format(num: $num, thousands_separator: '.');
        }
    @endphp
    <!DOCTYPE html>
    <html>

    <head>
        <title>Cetak Struk Belanja</title>
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
                {{ strtoupper($identitas['nama']) }}
            </span>
        </div>
        <div class="text-center">
            {{ $identitas['alamat'] }}
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

        <h3 class="text-center">Bukti Pembayaran</h3>
        <table style="font-size: 1.2em; margin-top:15px;">
            @foreach ($data['barang'] as $b)
                <tr>
                    <td colspan="3">
                        {{ $b['nama'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $b['jumlah'] }} {{ $b['satuan'] }} x
                    </td>
                    <td>
                        {{ format_angka($b['harga']) }} =
                    </td>
                    <td class="text-right">
                        {{ format_angka($b['total']) }}
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="divider"></div>
        <table>
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
            <tr>
                <td>
                    Bayar
                </td>
                <td class="text-right">
                    Rp
                </td>
                <td class="text-right">
                    {{ format_angka($data['bayar']) }}
                </td>
            </tr>
        </table>
        <div class="divider"></div>

        <table>
            <tr>
                <td width="65%">
                    Kembali
                </td>
                <td class="text-right" width="20px">
                    Rp
                </td>
                <td class="text-right">
                    {{ format_angka($data['kembali']) }}
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
