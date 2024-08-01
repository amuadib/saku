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
        <title>Cetak</title>
        <link href="{{ asset('/css/cetak.css') }}" rel="stylesheet" />
    </head>

    <body>
        @include('cetak.header')
        @include('cetak.sub-header')

        @include('cetak.contents.' . $view)

        <script>
            window.print();
        </script>
    </body>

    </html>
@else
    Maaf, Halaman tidak ditemukan. <br><a href="/">Kembali</a>
@endif
