<?php
$config = [
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
        99 => 'Default',
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
            'PKT' => 'Paket',
            'BKS' => 'Bungkus',
        ]
    ]
];
