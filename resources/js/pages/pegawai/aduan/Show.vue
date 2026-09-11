<script lang="ts" setup>
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

interface Aduan {
    id: number;
    status: string;
    terkait: string;
    masalah: string;
    perbaikan: string;
    created_at: string;
    user: {
        nama: string;
    };
    hasil_uji?: {
        id: number;
        kode?: string;
        pengujian?: {
            form_pengajuan?: {
                instansi?: {
                    nama: string;
                    user?: {
                        nama: string;
                    };
                };
            };
        };
    };
}

const props = defineProps<{ aduan: Aduan }>();

const form = useForm({
    status: props.aduan.status,
    diverifikasi_oleh: '',
});

function submit(status: string) {
    form.status = status;
    form.put(`/pegawai/aduan/verifikasi/${props.aduan.uuid || props.aduan.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            router.visit('/pegawai/aduan');
        },
    });
}

const formatTanggal = (dateStr?: string) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
};
</script>

<template>
    <Head :title="`Detail Aduan #${aduan.id}`" />

    <AdminLayout title="Detail Aduan">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Halaman & Tombol Kembali -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="router.visit('/pegawai/aduan')"
                            class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition"
                            title="Kembali ke daftar aduan"
                        >
                            <v-icon size="20">mdi-arrow-left</v-icon>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Detail Pengaduan Pelanggan
                        </h1>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 ml-8">
                        ID Tiket Aduan: <span class="font-mono font-bold text-slate-700 dark:text-slate-300">#{{ aduan.id }}</span>
                    </p>
                </div>

                <!-- Status Badge -->
                <div class="ml-8 sm:ml-0 flex items-center gap-2">
                    <span
                        class="px-3 py-1 rounded-full text-xs font-bold border"
                        :class="{
                            'bg-amber-50 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-300 dark:border-amber-800': aduan.status === 'diproses' || aduan.status === 'menunggu',
                            'bg-blue-50 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300 border-blue-300 dark:border-blue-800': aduan.status === 'diterima_administrasi',
                            'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800': aduan.status === 'diterima_pengujian',
                            'bg-rose-50 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 border-rose-300 dark:border-rose-800': aduan.status === 'ditolak',
                        }"
                    >
                        {{ aduan.status.replaceAll('_', ' ').toUpperCase() }}
                    </span>
                </div>
            </div>

            <!-- Kartu Detail Aduan -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 sm:p-8 space-y-6">
                <!-- Metadata Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs pb-6 border-b border-slate-100 dark:border-slate-800">
                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Nama Pelapor:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 text-sm">
                            {{ aduan.user?.nama || '-' }}
                        </p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Instansi Pelapor:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 text-sm">
                            {{ aduan.hasil_uji?.pengujian?.form_pengajuan?.instansi?.nama || '-' }}
                        </p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Tanggal Pengaduan:</span>
                        <p class="font-semibold text-slate-800 dark:text-slate-200">
                            {{ formatTanggal(aduan.created_at) }}
                        </p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Kategori Terkait:</span>
                        <div class="mt-0.5">
                            <span
                                class="inline-block px-2 py-0.5 rounded-full text-xs font-bold"
                                :class="aduan.terkait === 'administrasi'
                                    ? 'bg-blue-100 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300'
                                    : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300'"
                            >
                                {{ aduan.terkait.toUpperCase() }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 sm:col-span-2">
                        <span class="text-slate-500 dark:text-slate-400">Referensi Hasil Uji (LHU):</span>
                        <p class="font-mono font-bold text-slate-800 dark:text-slate-200 mt-0.5">
                            <template v-if="aduan.hasil_uji">
                                #{{ aduan.hasil_uji.id }} {{ aduan.hasil_uji.kode ? `(${aduan.hasil_uji.kode})` : '' }}
                            </template>
                            <template v-else>
                                Tidak ada dokumen LHU tertaut
                            </template>
                        </p>
                    </div>
                </div>

                <!-- Subjek Keluhan -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        Subjek Keluhan / Masalah
                    </label>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 text-sm font-semibold text-slate-900 dark:text-slate-100">
                        {{ aduan.masalah }}
                    </div>
                </div>

                <!-- Uraian Masalah & Permintaan Perbaikan -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        Uraian & Usulan Tindak Lanjut Pemohon
                    </label>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 text-sm text-slate-800 dark:text-slate-200 whitespace-pre-line leading-relaxed">
                        {{ aduan.perbaikan || '-' }}
                    </div>
                </div>

                <!-- Tombol Keputusan Tindak Lanjut -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                    <button
                        type="button"
                        @click="router.visit('/pegawai/aduan')"
                        class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                    >
                        Kembali
                    </button>

                    <div v-if="aduan.status === 'diproses' || aduan.status === 'menunggu'" class="flex flex-wrap items-center gap-2.5">
                        <button
                            v-if="aduan.terkait === 'administrasi'"
                            type="button"
                            @click="submit('diterima_administrasi')"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white transition shadow-sm cursor-pointer disabled:opacity-50"
                        >
                            <v-icon size="16">mdi-check-all</v-icon>
                            <span>Terima Administrasi</span>
                        </button>

                        <button
                            v-if="aduan.terkait === 'pengujian'"
                            type="button"
                            @click="submit('diterima_pengujian')"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-700 hover:bg-emerald-800 text-white transition shadow-sm cursor-pointer disabled:opacity-50"
                        >
                            <v-icon size="16">mdi-test-tube</v-icon>
                            <span>Terima Pengujian Ulang</span>
                        </button>

                        <button
                            type="button"
                            @click="submit('ditolak')"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white transition cursor-pointer disabled:opacity-50"
                        >
                            <v-icon size="16">mdi-close-circle-outline</v-icon>
                            <span>Tolak Aduan</span>
                        </button>
                    </div>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>
