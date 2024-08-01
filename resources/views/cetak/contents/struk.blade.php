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
