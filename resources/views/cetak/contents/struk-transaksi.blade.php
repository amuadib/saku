<h3 class="text-center">Bukti Transaksi</h3>
<table style="margin-top:15px;">
    @foreach ($data['transaksi'] as $b)
        <tr>
            <td>
                {{ $b['keterangan'] }}
            </td>
            <td class="text-right">
                {{ format_angka($b['jumlah']) }}
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
</table>

<div class="text-center" style="font-size: 12px; margin-top:25px;">
    Struk ini merupakan bukti transaksi yang sah. <br>
    Mohon disimpan dengan baik. <br>
    <spsn class="font-bold">Terima Kasih</spsn>
</div>
