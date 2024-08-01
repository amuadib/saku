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
