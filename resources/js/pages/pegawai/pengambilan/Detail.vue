<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    nama: string;
    user: User;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    metode_pengambilan: string;
    lokasi: string;
    instansi: Instansi;
}

interface Jadwal {
    id: number;
    kode_pengambilan: string;
    form_pengajuan: Pengajuan;
    user: User;
    waktu_pengambilan: string;
    status: 'diproses' | 'diterima';
    keterangan: string;
}

const props = defineProps<{
    jadwal: Jadwal;
}>();

const formatTanggal = (tanggalStr: string) => {
    if (!tanggalStr) return '-';
    const date = new Date(tanggalStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};
</script>

<template>
    <Head title="Detail Jadwal Pengambilan" />
    <AdminLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            PPCU Lab
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            #{{ props.jadwal.kode_pengambilan }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Detail Jadwal Pengambilan Sampel
                    </h1>
                </div>

                <v-btn
                    component="a"
                    href="/pegawai/pengambilan"
                    variant="outlined"
                    rounded="lg"
                    size="small"
                    prepend-icon="mdi-arrow-left"
                    class="text-none font-semibold text-xs"
                >
                    Kembali
                </v-btn>
            </div>

            <!-- Card Detail -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Kode Pengambilan</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ props.jadwal.kode_pengambilan }}</p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Kode Pengajuan Sampel</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ props.jadwal.form_pengajuan?.kode_pengajuan || '-' }}</p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Instansi Pemohon</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ props.jadwal.form_pengajuan?.instansi?.nama || '-' }}</p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Nama Pemohon</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ props.jadwal.form_pengajuan?.instansi?.user?.nama || '-' }}</p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Petugas PPCU Bertugas</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ props.jadwal.user?.nama || '-' }}</p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Waktu Pengambilan</span>
                        <p class="font-bold text-emerald-700 dark:text-emerald-400 mt-0.5 text-sm">{{ formatTanggal(props.jadwal.waktu_pengambilan) }}</p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Metode Pengambilan</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm capitalize">{{ props.jadwal.form_pengajuan?.metode_pengambilan }}</p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Status Penugasan</span>
                        <div class="mt-1">
                            <v-chip
                                size="small"
                                variant="tonal"
                                :color="props.jadwal.status === 'diterima' ? 'success' : 'warning'"
                                class="font-bold uppercase"
                            >
                                {{ props.jadwal.status }}
                            </v-chip>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 sm:col-span-2">
                        <span class="text-slate-400 dark:text-slate-500">Lokasi Titik Sampel</span>
                        <p class="font-medium text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ props.jadwal.form_pengajuan?.lokasi || '-' }}</p>
                    </div>

                    <div v-if="props.jadwal.keterangan" class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 sm:col-span-2">
                        <span class="text-slate-400 dark:text-slate-500">Catatan Petugas</span>
                        <p class="text-slate-700 dark:text-slate-300 mt-0.5 text-sm">{{ props.jadwal.keterangan }}</p>
                    </div>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>