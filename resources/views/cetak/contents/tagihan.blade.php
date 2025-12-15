<h3 class="text-center">Daftar Tagihan</h3>

<table style=" margin-top:15px;">
    @foreach ($data['tagihan'] as $b)
        <tr>
            <td>
                {{ $b['keterangan'] ?? '-' }} ({{ $b['tanggal'] ?? '-' }})
            </td>
            <td class="text-right">
                Rp {{ format_angka($b['sisa']) }}
            </td>
        </tr>
    @endforeach
</table>
<div class="divider"></div>
<table>
    <tr class="font-bold">
        <td width="65%">
            Total Tagihan
        </td>
        <td class="text-right" width="20px">
            Rp
        </td>
        <td class="text-right">
            {{ format_angka($data['total']) }}
        </td>
    </tr>
</table>
<div class="text-center" style="font-size: 12px; margin-top:25px;">
    Struk ini merupakan bukti tagihan yang harus dibayar. <br>
    Mohon bayar tagihan tepat waktu. <br>
    <spsn class="font-bold">Terima Kasih</spsn>
</div>
