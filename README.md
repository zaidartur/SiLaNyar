# SiLaNyar - Sistem Informasi Laboratorium Kabupaten Karanganyar

**SiLaNyar** adalah aplikasi web untuk manajemen laboratorium lingkungan di Kabupaten Karanganyar. Sistem ini dirancang untuk memudahkan proses pengajuan, pengujian, pelaporan, dan monitoring hasil laboratorium baik untuk pihak internal (pegawai, teknisi, admin) maupun eksternal (customer/instansi).

## Fitur Utama

### 1. Pengelolaan Pengajuan Laboratorium

- Customer dapat mengajukan permohonan pengujian sampel lingkungan secara online.
- Pengajuan dapat dipilih berdasarkan kategori, parameter uji, jenis cairan, dan metode pengambilan sampel (diantar/diambil).
- Terdapat validasi volume sampel sesuai jenis cairan.

### 2. Manajemen Jadwal Pengambilan & Pengujian

- Pegawai dapat mengatur jadwal pengambilan sampel dan pengujian laboratorium.
- Jadwal dapat difilter berdasarkan status, tanggal, dan metode pengambilan.
- Sistem otomatis menghasilkan kode unik untuk setiap jadwal dan pengujian.

### 3. Proses Pengujian & Input Hasil Uji

- Teknisi dapat menginput hasil pengujian berdasarkan parameter yang dipilih customer.
- Hasil uji memiliki alur status: draf, revisi, proses review, proses peresmian, selesai.
- Terdapat fitur histori perubahan hasil uji.

### 4. Pembayaran & Konfirmasi

- Sistem mendukung pembayaran biaya pengujian melalui transfer atau tunai (tergantung metode pengambilan).
- Customer dapat mengunggah bukti pembayaran.
- Pegawai dapat memverifikasi dan mengelola status pembayaran.

### 5. Pelaporan & Dashboard

- Tersedia dashboard statistik untuk admin, pegawai, teknisi, dan customer sesuai peran.
- Laporan keuangan dapat difilter berdasarkan periode (bulanan, tahunan, rentang tanggal).
- Data dapat divisualisasikan dalam bentuk diagram.

### 6. Manajemen Role & Permission

- Sistem menggunakan Spatie Laravel Permission untuk pengelolaan role dan permission.
- Hak akses fitur diatur berdasarkan role (superadmin, admin, teknisi, pegawai, customer).

### 7. Notifikasi & Aduan

- Customer dapat mengajukan aduan terkait hasil uji.
- Notifikasi email dikirim untuk konfirmasi pembayaran dan status hasil uji.

### 8. Otentikasi & SSO

- Mendukung login SSO (Single Sign-On) dengan akun SAKTI Karanganyar.
- Terdapat pembatasan akses berdasarkan role dan permission.

### 9. Dokumentasi & Kode Modular

- Struktur kode mengikuti standar Laravel (Controller, Model, Seeder, Service, Middleware).
- Tersedia unit test dan feature test untuk berbagai skenario aplikasi.

## Struktur Utama Project

- **app/Http/Controllers**: Berisi controller untuk fitur customer dan pegawai.
- **app/Models**: Model Eloquent untuk seluruh entitas (User, Pengajuan, Jadwal, Pengujian, HasilUji, Pembayaran, dsb).
- **app/Services**: Service logic seperti DashboardManager.
- **database/seeders**: Seeder data master (kategori, parameter, role, dsb).
- **resources/js/pages**: Halaman frontend berbasis Vue.js dan Inertia.js.
- **routes/web.php**: Routing utama aplikasi.
- **tests/**: Unit dan feature test untuk memastikan kualitas aplikasi.

## Ringkasan Alur Kerja

1. **Customer** mendaftar/melakukan login, mengisi data instansi, lalu mengajukan permohonan pengujian.
2. **Pegawai/Admin** memverifikasi pengajuan, mengatur jadwal pengambilan/pengujian, dan mengelola pembayaran.
3. **Teknisi** melakukan pengujian dan menginput hasil uji.
4. **Customer** dapat memantau status pengajuan, pembayaran, jadwal, dan hasil uji melalui dashboard.
5. **Semua proses** terdokumentasi dan dapat diaudit melalui histori dan laporan.

---

**SiLaNyar** membantu digitalisasi proses laboratorium lingkungan, meningkatkan transparansi, efisiensi, dan kemudahan layanan bagi masyarakat dan pemerintah Kabupaten Karanganyar.
