<?php
$config = [
    'app' => [
        'nama' => 'Sistem Administrasi Keuangan',
        'singkatan' => 'SAKU',
        'keterangan' => 'Sistem Administrasi Keuangan (SAKU) SDI & SMPI Miftahul Ulum'
    ],
    'roles' => [
        1 => 'Admin',
        2 => 'Yayasan',
        3 => 'Kepala Sekolah',
        4 => 'Bendahara',
        5 => 'Siswa',
        6 => 'Orang Tua',
        99 => 'Default'
    ],
    'jam_kerja' => [
        'Senin - Sabtu: 07:00 - 13:30',
        'Jum\'at: 07:00 - 10:00',
        'Hari Ahad & Libur Nasional Tutup'
    ],
    'lembaga' => [
        1 => 'SDI Miftahul Ulum Klemunan',
        2 => 'SMPI Miftahul Ulum',
        99 => 'Yayasan Bastomiyah Rahman',
    ],
    'kelas' => [
        1 => [1, 2, 3, 4, 5, 6],
        2 => [7, 8, 9],
        99 => [],
    ],
    'kontak_lembaga' => [
        1 => [
            'singkatan' => 'SDI',
            'alamat' => 'Jl. Manggar Lingk. Jatikeplek',
            'kontak' => '',
            'telp' => '',
            'lat' => 0,
            'lon' => 0,
        ],
        2 => [
            'singkatan' => 'SMPI',
            'alamat' => 'Jl. Manggar Lingk. Jatikeplek',
            'kontak' => '',
            'telp' => '',
            'lat' => 0,
            'lon' => 0,
        ],
        99 => [
            'singkatan' => 'YPIB',
            'alamat' => 'Jl. Manggar Lingk. Jatikeplek',
            'kontak' => '',
            'telp' => '',
            'lat' => 0,
            'lon' => 0,
        ]
    ],
    'siswa' => [
        'status' => [
            1 => 'Aktif',
            2 => 'Mutasi',
            3 => 'Lulus',
            99 => 'Non Aktif',
        ],
        'label' => [
            1 => 'Yatim',
            2 => 'Piatu',
            11 => 'Ikut Tahfid',
            12 => 'Pondok',
            21 => 'Keluarga Pegawai Yayasan'
        ]
    ],
    'barang' => [
        'jenis' => [
            'SRG' => 'Seragam',
            'AKS' => 'Aksesoris',
            'LKS' => 'Lembar Kerja Siswa',
            'USM' => 'Buku Usmani',
            'BKU' => 'Buku lain',
            'LLN' => 'Lain-lain'
        ],
        'satuan' => [
            'PCS' => 'Pcs',
            'STL' => 'Setel',
            'PKT' => 'Paket',
            'BKS' => 'Bungkus',
        ]
    ],
    'pembayaran' => [
        'tun' => 'Tunai',
        'tag' => 'Tagihan',
        'tab' => 'Tabungan',
    ],
    'template' => [
        'awal' => '*Sistem Administrasi Keuangan (SAKU) SDI & SMPI Miftahul Ulum*

🗒️ Yth. Bapak/Ibu Wali siswa *{siswa.nama}*. ' . PHP_EOL,
        'akhir' => '
Terima Kasih
        ',
        'akhir_bayar' => '
Terima kasih kami sampaikan.
Semoga Bapak/Ibu diberi rizki yang Lancar dan Barokah.
        ',
        'akhir_daftar' => '
Apabila terdapat *kesalahan* mohon konfirmasi ke Bagian TU {lembaga} ({kontak.nama}).
Selanjutnya tanda bukti pembayaran akan berupa Print Out (Kecuali Tahfid dan mobil).
Terima Kasih
        ',
        'awal_alumni' => '
Assalaamu\'alaikum Wr. Wb.
Semoga dalam lindungan Allah SWT serta diberikan kesehatan selalu🤲

🏫Berikut merupakan WA resmi sistem otomatis {lembaga} untuk para alumni.

Kami menginformasikan bahwasanya;

Ananda yang bernama *{siswa.nama}*

📋Memiliki *daftar pembayaran yang belum dilunasi (tanggungan pembayaran)* selama masih bersekolah di {lembaga}.
',
        'akhir_alumni' => '

🙏Mohon maaf apabila masih ada tanggungan maka *ijazah masih kami tangguhkan* (belum bisa kami berikan)

🖋️Apabila terdapat kesalahan dalam jumlah ataupun hal² lain bisa segera konfirmasi di kantor {lembaga}*

Atas perhatiannya kami sampaikan terima kasih dan mohon maaf.

Wassalaamu\'alaikum Wr. Wb
',
        'tagihan' => [
            'bayar' => '
Telah kami terima & *LUNAS* pembayaran atas tagihan *{tagihan.keterangan}* sejumlah *{tagihan.jumlah}*.',
            'bayar_banyak' => '
Telah kami terima & *LUNAS* pembayaran atas tagihan {tagihan.rincian} dengan total *{tagihan.total}*.',
            'daftar' => '🏫 Berikut informasi resmi terkait tanggungan ananda.' . PHP_EOL . '
{tagihan.rincian}Dengan total tagihan *{tagihan.total}*.',
            'tabungan' => PHP_EOL . '
🗳️ Ananda mempunyai tabungan sebanyak *{tabungan.total}*' . PHP_EOL,
            'daftar_alumni' => '
Berikut informasi tanggungan ananda.
{tagihan.rincian}Total tagihan *{tagihan.total}*.'
        ],
        'tabungan' => [
            'daftar' => '🏫 Berikut rincian Tabungan ananda.' . PHP_EOL . '
{tabungan.rincian}Dengan Saldo total *{tabungan.total}*.',
        ],
        'footer' => '
...
_Pesan ini dikirim otomatis oleh sistem, mohon tidak membalas pesan ke nomor ini_
        '
    ],
    'tabungan' => [
        'potongan' =>
        [
            'lembaga' => [1, 2],
            'kas_admin_id' => '',
            'min_saldo_tidak_kena_admin' => 5000,
            'jumlah_per_tahun' => 1000,
            'tanggal' => '2024-01-01'
        ]
    ],

];
$local_config = [];
@include storage_path() . '/app/local_config.php';
return array_merge($config, $local_config);
