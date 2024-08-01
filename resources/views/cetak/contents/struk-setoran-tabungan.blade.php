        <h3 class="text-center">Bukti Setoran Tabungan</h3>
        <table style="margin-top:15px;">
            <tr>
                <td>
                    {{ $data['keterangan'] }}
                </td>
            </tr>
        </table>
        <div class="divider"></div>
        <table>
            <tr class="font-bold">
                <td>
                    Setoran
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
