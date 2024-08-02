<?php
$config = [
    'app' => [
        'nama' => 'Sistem Administrasi Keuangan',
        'singkatan' => 'SAKU',
        'keterangan' => 'Aplikasi pengelolaan keuangan SDI & SMPI Miftahul Ulum Klemunan.'
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
    ]
];
