<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    kode_instansi: string;
    nama: string;
    tipe?: 'swasta' | 'pemerintahan' | 'pribadi';
    alamat: string;
    wilayah?: string;
    desa_kelurahan?: string;
    email: string;
    no_telepon: string;
    posisi_jabatan: string;
    departemen_divisi: string;
    status_verifikasi?: 'diproses' | 'diterima' | 'ditolak';
    created_at: string;
    diverifikasi_oleh: string | null;
    user: User;
}

const props = defineProps<{
    instansi: Instansi;
}>();

const form = useForm({
    status_verifikasi: '' as 'diterima' | 'ditolak',
});

const verifikasi = (status: 'diterima' | 'ditolak') => {
    form.status_verifikasi = status;
    form.put(`/pegawai/instansi/${props.instansi.id}/edit`, {
        onSuccess: () => {
            form.reset();
        },
    });
};

const formatTanggal = (dateStr: string) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
};
</script>

<template>
    <Head title="Detail & Verifikasi Instansi" />

    <AdminLayout title="Detail Instansi">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Halaman & Tombol Kembali -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="router.visit('/pegawai/instansi')"
                            class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition"
                            title="Kembali ke daftar instansi"
                        >
                            <v-icon size="20">mdi-arrow-left</v-icon>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Detail Instansi Pelanggan
                        </h1>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 ml-8">
                        Verifikasi keabsahan data profil instansi / pemohon uji sampel
                    </p>
                </div>

                <!-- Status Badge -->
                <div class="ml-8 sm:ml-0 flex items-center gap-2">
                    <span
                        class="px-3.5 py-1 rounded-full text-xs font-bold border"
                        :class="{
                            'bg-amber-50 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-300 dark:border-amber-800': props.instansi.status_verifikasi === 'diproses',
                            'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800': props.instansi.status_verifikasi === 'diterima',
                            'bg-rose-50 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 border-rose-300 dark:border-rose-800': props.instansi.status_verifikasi === 'ditolak',
                        }"
                    >
                        {{ props.instansi.status_verifikasi ? props.instansi.status_verifikasi.toUpperCase() : 'MENUNGGU' }}
                    </span>
                </div>
            </div>

            <!-- Kartu Aksi Verifikasi -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100">
                            Tindakan Verifikasi Petugas
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Status saat ini: <strong class="capitalize">{{ props.instansi.status_verifikasi || 'Diproses' }}</strong>
                            <span v-if="props.instansi.diverifikasi_oleh" class="ml-1">
                                &bull; Diverifikasi oleh: <strong>{{ props.instansi.diverifikasi_oleh }}</strong>
                            </span>
                        </p>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <button
                            type="button"
                            @click="verifikasi('diterima')"
                            :disabled="form.processing || props.instansi.status_verifikasi === 'diterima'"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-700 hover:bg-emerald-800 text-white transition disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed"
                        >
                            <v-icon size="16">mdi-check-circle-outline</v-icon>
                            <span>Terima Verifikasi</span>
                        </button>
                        <button
                            type="button"
                            @click="verifikasi('ditolak')"
                            :disabled="form.processing || props.instansi.status_verifikasi === 'ditolak'"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white transition disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed"
                        >
                            <v-icon size="16">mdi-close-circle-outline</v-icon>
                            <span>Tolak Verifikasi</span>
                        </button>
                    </div>
                </div>
            </v-card>

            <!-- Informasi Profil Instansi Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Profil Instansi -->
                <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <v-icon color="primary" size="20">mdi-office-building</v-icon>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">
                            Informasi Legalitas Instansi
                        </h3>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Kode Registrasi Instansi</span>
                            <p class="font-mono font-bold text-slate-900 dark:text-slate-100 text-sm mt-0.5">
                                {{ props.instansi.kode_instansi }}
                            </p>
                        </div>

                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Nama Instansi / Badan Usaha</span>
                            <p class="font-bold text-slate-900 dark:text-slate-100 text-sm mt-0.5">
                                {{ props.instansi.nama }}
                            </p>
                        </div>

                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Tipe Lembaga</span>
                            <p class="capitalize font-semibold text-slate-800 dark:text-slate-200 mt-0.5">
                                {{ props.instansi.tipe || '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Departemen / Divisi Terkait</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">
                                {{ props.instansi.departemen_divisi || '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Tanggal Terdaftar</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">
                                {{ formatTanggal(props.instansi.created_at) }}
                            </p>
                        </div>
                    </div>
                </v-card>

                <!-- Kontak & PIC -->
                <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <v-icon color="primary" size="20">mdi-account-tie</v-icon>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">
                            Penanggung Jawab (PIC) & Kontak
                        </h3>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Nama Penanggung Jawab</span>
                            <p class="font-bold text-slate-900 dark:text-slate-100 text-sm mt-0.5">
                                {{ props.instansi.user?.nama || '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Posisi / Jabatan PIC</span>
                            <p class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">
                                {{ props.instansi.posisi_jabatan || '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Alamat Email</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">
                                {{ props.instansi.email }}
                            </p>
                        </div>

                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Nomor Telepon / WhatsApp</span>
                            <p class="font-medium text-emerald-700 dark:text-emerald-400 mt-0.5">
                                {{ props.instansi.no_telepon }}
                            </p>
                        </div>
                    </div>
                </v-card>
            </div>

            <!-- Lokasi & Alamat Instansi -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <v-icon color="primary" size="20">mdi-map-marker-radius</v-icon>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">
                        Lokasi & Domisili
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div class="md:col-span-3">
                        <span class="text-slate-500 dark:text-slate-400">Alamat Lengkap</span>
                        <p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">
                            {{ props.instansi.alamat || '-' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-slate-500 dark:text-slate-400">Wilayah / Kabupaten / Kota</span>
                        <p class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">
                            {{ props.instansi.wilayah || '-' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-slate-500 dark:text-slate-400">Desa / Kelurahan</span>
                        <p class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">
                            {{ props.instansi.desa_kelurahan || '-' }}
                        </p>
                    </div>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>
