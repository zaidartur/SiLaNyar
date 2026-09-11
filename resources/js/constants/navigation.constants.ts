import type { NavigationItem, BottomNavItem } from '@/types/navigation.types';

export const ADMIN_SIDEBAR_ITEMS: NavigationItem[] = [
    {
        title: 'Beranda',
        href: '/pegawai/dashboard',
        icon: 'mdi-view-dashboard-outline',
    },
    {
        title: 'Daftar Layanan',
        href: '#',
        icon: 'mdi-clipboard-text-outline',
        children: [
            {
                title: 'Pengajuan Uji Lab',
                href: '/pegawai/pengajuan',
                icon: 'mdi-file-document-outline',
                permission: 'lihat pengajuan',
            },
            {
                title: 'Jadwal Penjemputan / PPCU',
                href: '/pegawai/pengambilan',
                icon: 'mdi-calendar-clock-outline',
                permission: 'lihat pengambilan',
            },
            {
                title: 'Pengujian Parameter',
                href: '/pegawai/pengujian',
                icon: 'mdi-flask-outline',
                permission: 'lihat pengujian',
            },
        ],
    },
    {
        title: 'Master Data Baku Mutu',
        href: '#',
        icon: 'mdi-database-outline',
        children: [
            {
                title: 'Parameter Uji',
                href: '/pegawai/parameter',
                icon: 'mdi-tune-variant',
                permission: 'kelola parameter',
            },
            {
                title: 'Kategori',
                href: '/pegawai/kategori',
                icon: 'mdi-folder-outline',
                permission: 'kelola kategori',
            },
            {
                title: 'Sub Kategori',
                href: '/pegawai/subkategori',
                icon: 'mdi-folder-multiple-outline',
                permission: 'kelola subkategori',
            },
            {
                title: 'Jenis Cairan',
                href: '/pegawai/jenis-cairan',
                icon: 'mdi-water-outline',
                permission: 'kelola jenis cairan',
            },
        ],
    },
    {
        title: 'Verifikasi Aduan',
        href: '/pegawai/aduan',
        icon: 'mdi-message-alert-outline',
        permission: 'kelola aduan',
    },
    {
        title: 'Verifikasi Pembayaran',
        href: '/pegawai/pembayaran',
        icon: 'mdi-cash-check',
        permission: 'kelola pembayaran',
    },
    {
        title: 'Laporan Keuangan',
        href: '/pegawai/laporan-keuangan',
        icon: 'mdi-chart-areaspline',
        permission: 'laporan keuangan',
    },
    {
        title: 'Lembar Hasil Uji (Lhus / LHU)',
        href: '/pegawai/hasiluji',
        icon: 'mdi-file-certificate-outline',
        permission: 'lihat hasil uji',
    },
    {
        title: 'Manajemen Hak Akses',
        href: '#',
        icon: 'mdi-shield-account-outline',
        children: [
            {
                title: 'Daftar Pengguna',
                href: '/superadmin/users',
                icon: 'mdi-account-group-outline',
                permission: 'kelola user',
            },
            {
                title: 'Kelola Role',
                href: '/superadmin/role',
                icon: 'mdi-badge-account-outline',
                permission: 'kelola role',
            },
            {
                title: 'Kelola Permission',
                href: '/superadmin/permission',
                icon: 'mdi-key-outline',
                permission: 'kelola permission',
            },
        ],
    },
];

export const CUSTOMER_SIDEBAR_ITEMS: NavigationItem[] = [
    {
        title: 'Beranda',
        href: '/customer/dashboard',
        icon: 'mdi-view-dashboard-outline',
    },
    {
        title: 'Pengajuan Sampel',
        href: '/customer/pengajuan',
        icon: 'mdi-file-document-edit-outline',
    },
    {
        title: 'Jadwal Layanan',
        href: '#',
        icon: 'mdi-calendar-clock-outline',
        children: [
            {
                title: 'Penjemputan Sampel (PPCU)',
                href: '/customer/jadwal/penjemputan',
                icon: 'mdi-truck-delivery-outline',
            },
            {
                title: 'Pengantaran Mandiri',
                href: '/customer/jadwal/pengantaran',
                icon: 'mdi-map-marker-path',
            },
        ],
    },
    {
        title: 'Hasil Uji Lab (LHU)',
        href: '/customer/hasiluji',
        icon: 'mdi-file-certificate-outline',
    },
    {
        title: 'Profil & Instansi',
        href: '/customer/profile/show',
        icon: 'mdi-office-building-cog-outline',
    },
];

export const PPCU_BOTTOM_NAV_ITEMS: BottomNavItem[] = [
    {
        label: 'Tugas Hari Ini',
        href: '/pegawai/dashboard',
        icon: 'mdi-clipboard-list-outline',
        value: 'tugas',
    },
    {
        label: 'Jadwal Sampel',
        href: '/pegawai/pengambilan',
        icon: 'mdi-map-marker-distance',
        value: 'jadwal',
    },
    {
        label: 'Profil Saya',
        href: '/pegawai/profile/show',
        icon: 'mdi-account-circle-outline',
        value: 'profil',
    },
];

export const PELANGGAN_BOTTOM_NAV_ITEMS: BottomNavItem[] = [
    {
        label: 'Beranda',
        href: '/customer/dashboard',
        icon: 'mdi-home-outline',
        value: 'beranda',
    },
    {
        label: 'Pengajuan',
        href: '/customer/pengajuan',
        icon: 'mdi-file-plus-outline',
        value: 'pengajuan',
    },
    {
        label: 'Sertifikat LHU',
        href: '/customer/hasiluji',
        icon: 'mdi-file-check-outline',
        value: 'hasiluji',
    },
    {
        label: 'Profil',
        href: '/customer/profile/show',
        icon: 'mdi-account-circle-outline',
        value: 'profil',
    },
];

export const ANALIS_BOTTOM_NAV_ITEMS: BottomNavItem[] = [
    {
        label: 'Beranda Uji',
        href: '/pegawai/dashboard',
        icon: 'mdi-view-dashboard-outline',
        value: 'beranda',
    },
    {
        label: 'Antrian Analisis',
        href: '/pegawai/pengujian',
        icon: 'mdi-flask-outline',
        value: 'pengujian',
    },
    {
        label: 'Hasil Lab',
        href: '/pegawai/hasiluji',
        icon: 'mdi-file-document-edit-outline',
        value: 'hasiluji',
    },
    {
        label: 'Profil',
        href: '/pegawai/profile/show',
        icon: 'mdi-account-circle-outline',
        value: 'profil',
    },
];
