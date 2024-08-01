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
