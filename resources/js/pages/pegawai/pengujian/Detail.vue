<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Kategori {
    id: number;
    nama: string;
}

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
    instansi: Instansi;
}

interface Pengujian {
    id: number;
    kode_pengujian: string;
    form_pengajuan: Pengajuan;
    user: User;
    kategori: Kategori;
    tanggal_uji: string;
    jam_mulai: string;
    jam_selesai: string;
    status: 'diproses' | 'selesai';
}

const props = defineProps<{
    pengujian: Pengujian;
}>();

const page = usePage();
const permissions =
    (page.props.auth && Array.isArray((page.props.auth as any).permissions))
        ? ((page.props.auth as any).permissions as string[])
        : [];

const can = (permission: string): boolean => {
    return permissions.includes(permission);
};

const formatTanggal = (tanggalStr: string) => {
    if (!tanggalStr) return '-';
    const date = new Date(tanggalStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

const statusLabels: Record<string, string> = {
    diproses: 'Diproses',
    selesai: 'Selesai',
};

const statusFlow: Record<string, string[]> = {
    diproses: ['selesai'],
    selesai: [],
};

const availableStatus = computed(() => statusFlow[props.pengujian.status] || []);

function updateStatus(newStatus: string) {
    router.put(`/pegawai/pengujian/verifikasi/${props.pengujian.uuid || props.pengujian.id}`, {
        status: newStatus,
    });
}
</script>

<template>
    <Head title="Detail Pengujian Sampel" />
    <AdminLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Laboratorium Uji
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            #{{ props.pengujian.kode_pengujian }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Detail Penugasan Pengujian
                    </h1>
                </div>

                <div class="flex items-center gap-2">
                    <v-btn
                        component="a"
                        href="/pegawai/pengujian"
                        variant="outlined"
                        rounded="lg"
                        size="small"
                        prepend-icon="mdi-arrow-left"
                        class="text-none font-semibold text-xs"
                    >
                        Kembali
                    </v-btn>
                </div>
            </div>

            <!-- Card Informasi Utama -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Kode Pengujian</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ props.pengujian.kode_pengujian }}</p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Kode Pengajuan</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ props.pengujian.form_pengajuan?.kode_pengajuan || '-' }}</p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Status Progres</span>
                        <div class="mt-1">
                            <v-chip
                                size="small"
                                variant="tonal"
                                :color="props.pengujian.status === 'selesai' ? 'success' : 'warning'"
                                class="font-bold uppercase"
                            >
                                {{ statusLabels[props.pengujian.status] ?? props.pengujian.status }}
                            </v-chip>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-2 border-t border-slate-100 dark:border-slate-800">
                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Instansi Pemohon:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ props.pengujian.form_pengajuan?.instansi?.nama || '-' }}</p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Nama Pemohon:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ props.pengujian.form_pengajuan?.instansi?.user?.nama || '-' }}</p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Teknisi Analis:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ props.pengujian.user?.nama || '-' }}</p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Kategori Baku Mutu:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ props.pengujian.kategori?.nama || '-' }}</p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Tanggal Pengujian:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ formatTanggal(props.pengujian.tanggal_uji) }}</p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Waktu Jam Pelaksanaan:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ props.pengujian.jam_mulai }} s/d {{ props.pengujian.jam_selesai }} WIB</p>
                    </div>
                </div>

                <!-- Update Status Verifikasi (jika memiliki izin) -->
                <div v-if="can('edit status pengujian') && availableStatus.length > 0" class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                        Tindakan Verifikasi Status
                    </p>
                    <div class="flex items-center gap-2">
                        <v-btn
                            v-for="status in availableStatus"
                            :key="status"
                            color="success"
                            rounded="lg"
                            size="small"
                            prepend-icon="mdi-check-circle-outline"
                            @click="updateStatus(status)"
                            class="text-none font-semibold text-xs"
                        >
                            Tandai Sebagai {{ statusLabels[status] }}
                        </v-btn>
                    </div>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>
