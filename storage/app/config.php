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
    'kontak_lembaga' => [
        1 => [
            'singkatan' => 'SDI',
            'alamat' => 'Jl. Manggar Lingk. Jatikeplek RT 02 RW 06 Klemunan Wlingi Blitar',
            'kontak' => 'B. Latif',
            'telp' => '+62 857-0844-8279',
        ],
        2 => [
            'singkatan' => 'SMPI',
            'alamat' => 'Jl. Manggar Lingk. Jatikeplek RT 02 RW 06 Klemunan Wlingi Blitar',
            'kontak' => 'Bella',
            'telp' => '+62 857-0681-4780',
        ],
        99 => [
            'singkatan' => 'YPIB',
            'alamat' => 'Jl. Manggar Lingk. Jatikeplek RT 02 RW 06 Klemunan Wlingi Blitar',
            'kontak' => '',
            'telp' => '',
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
        'awal' => 'Assalamu\'alaikum Wr. Wb.
Bapak/Ibu Wali siswa *{siswa.nama}*. ',
        'akhir' => '

Wassalamu\'alaikum Wr. Wb.',
        'tagihan' => [
            'bayar' => 'Pembayaran atas tagihan *{tagihan.keterangan}* sejumlah *{tagihan.jumlah}* telah kami terima.',
            'bayar_banyak' => 'Pembayaran atas tagihan {tagihan.rincian} dengan total *{tagihan.total}* telah kami terima.',
            'daftar' => 'Berikut informasi resmi terkait tanggungan ananda.
Apabila terdapat kesalahan mohon konfirmasi ke Bagian TU {lembaga} ({kontak.nama}).
Selanjutnya tanda bukti pembayaran akan berupa Print Out (Kecuali Tahfidz dan Mobil).
{tagihan.rincian}Total tagihan *{tagihan.total}*.'
        ],
        'footer' => '
...
_Pesan ini dikirim secara otomatis dari Sistem Administrasi Keuangan (SAKU) SDI & SMPI Miftahul Ulum. Mohon tidak membalas pesan ke Nomor ini_'
    ]
];
