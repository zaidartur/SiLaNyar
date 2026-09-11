<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Instansi {
    nama: string;
}

interface User {
    nama: string;
}

interface FormPengajuan {
    kode_pengajuan: string;
    instansi: Instansi;
    user: User;
}

interface Pembayaran {
    id: number;
    id_order: string;
    total_biaya: number;
    tanggal_pembayaran: string | null;
    metode_pembayaran: string;
    bukti_pembayaran: string | null;
    created_at: string;
    form_pengajuan: FormPengajuan;
    status_pembayaran: string;
    keterangan?: string | null;
}

const props = defineProps<{
    pembayaran: Pembayaran;
}>();

const keterangan = ref(props.pembayaran.keterangan ?? '');
const isSubmitting = ref(false);

function kembaliKeIndex() {
    router.visit('/pegawai/pembayaran');
}

function updateStatus(status: 'selesai' | 'gagal') {
    isSubmitting.value = true;
    router.put(
        `/pegawai/pembayaran/${props.pembayaran.id}/edit`,
        {
            status_pembayaran: status,
            keterangan: keterangan.value,
        },
        {
            onSuccess: () => {
                isSubmitting.value = false;
                kembaliKeIndex();
            },
            onError: () => {
                isSubmitting.value = false;
            },
        },
    );
}

const formatRupiah = (val: number) => {
    return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
};

const formatTanggal = (dateStr?: string | null) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
};
</script>

<template>
    <Head :title="`Detail Pembayaran #${pembayaran.id_order}`" />

    <AdminLayout title="Verifikasi Pembayaran">
        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Header Halaman & Tombol Kembali -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="kembaliKeIndex"
                            class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition"
                            title="Kembali ke daftar pembayaran"
                        >
                            <v-icon size="20">mdi-arrow-left</v-icon>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Detail & Verifikasi Pembayaran
                        </h1>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 ml-8">
                        Order ID: <span class="font-mono font-bold text-slate-700 dark:text-slate-300">#{{ pembayaran.id_order }}</span>
                    </p>
                </div>

                <!-- Status Badge -->
                <div class="ml-8 sm:ml-0 flex items-center gap-2">
                    <span
                        class="px-3.5 py-1 rounded-full text-xs font-bold border"
                        :class="{
                            'bg-amber-50 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-300 dark:border-amber-800': pembayaran.status_pembayaran === 'diproses' || pembayaran.status_pembayaran === 'belum_dibayar',
                            'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800': pembayaran.status_pembayaran === 'selesai' || pembayaran.status_pembayaran === 'lunas',
                            'bg-rose-50 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 border-rose-300 dark:border-rose-800': pembayaran.status_pembayaran === 'gagal' || pembayaran.status_pembayaran === 'ditolak',
                        }"
                    >
                        {{ pembayaran.status_pembayaran.replace('_', ' ').toUpperCase() }}
                    </span>
                </div>
            </div>

            <!-- Konten Utama 2 Kolom -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Kolom Kiri: Informasi Tagihan & Form Verifikasi (7 Kolom) -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Kartu Informasi Tagihan -->
                    <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-4">
                        <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                            <v-icon color="primary" size="20">mdi-receipt-text-outline</v-icon>
                            <h2 class="text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">
                                Informasi Tagihan & Pelanggan
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-500 dark:text-slate-400">Kode Pengajuan Sampel</span>
                                <p class="font-mono font-bold text-slate-900 dark:text-slate-100 text-sm">
                                    {{ pembayaran.form_pengajuan?.kode_pengajuan ?? '-' }}
                                </p>
                            </div>

                            <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-500 dark:text-slate-400">Instansi / Perusahaan</span>
                                <p class="font-bold text-slate-900 dark:text-slate-100 text-sm">
                                    {{ pembayaran.form_pengajuan?.instansi?.nama ?? '-' }}
                                </p>
                            </div>

                            <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-500 dark:text-slate-400">Pemohon (PIC)</span>
                                <p class="font-semibold text-slate-800 dark:text-slate-200">
                                    {{ pembayaran.form_pengajuan?.user?.nama ?? '-' }}
                                </p>
                            </div>

                            <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-500 dark:text-slate-400">Metode Pembayaran</span>
                                <p class="font-semibold capitalize text-slate-800 dark:text-slate-200">
                                    {{ pembayaran.metode_pembayaran }}
                                </p>
                            </div>

                            <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-500 dark:text-slate-400">Tanggal Pembayaran</span>
                                <p class="font-medium text-slate-800 dark:text-slate-200">
                                    {{ formatTanggal(pembayaran.tanggal_pembayaran) }}
                                </p>
                            </div>

                            <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-500 dark:text-slate-400">Tanggal Unggah Bukti</span>
                                <p class="font-medium text-slate-800 dark:text-slate-200">
                                    {{ formatTanggal(pembayaran.created_at) }}
                                </p>
                            </div>
                        </div>

                        <!-- Total Tagihan Callout -->
                        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-emerald-800 dark:text-emerald-300">Total Biaya Retribusi:</span>
                                <div class="text-2xl font-black text-emerald-900 dark:text-emerald-200 mt-0.5">
                                    {{ formatRupiah(pembayaran.total_biaya) }}
                                </div>
                            </div>
                            <v-icon size="36" color="success">mdi-cash-check</v-icon>
                        </div>
                    </v-card>

                    <!-- Kartu Tindakan Petugas / Form Keterangan -->
                    <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-4">
                        <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                            <v-icon color="primary" size="20">mdi-shield-check-outline</v-icon>
                            <h2 class="text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">
                                Catatan & Keputusan Verifikasi
                            </h2>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Keterangan / Catatan Tambahan (Opsional)
                            </label>
                            <textarea
                                v-model="keterangan"
                                rows="3"
                                placeholder="Tuliskan catatan verifikasi atau alasan penolakan jika diperlukan..."
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition resize-none"
                            ></textarea>
                        </div>

                        <div v-if="pembayaran.status_pembayaran === 'belum_dibayar'" class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-300">
                            Pelanggan belum melakukan pembayaran atau belum mengunggah bukti transfer.
                        </div>

                        <!-- Tombol Verifikasi -->
                        <div class="flex flex-wrap items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button
                                type="button"
                                @click="kembaliKeIndex"
                                class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                            >
                                Kembali
                            </button>

                            <button
                                type="button"
                                @click="updateStatus('gagal')"
                                :disabled="pembayaran.status_pembayaran === 'belum_dibayar' || pembayaran.status_pembayaran === 'gagal' || pembayaran.status_pembayaran === 'selesai' || isSubmitting"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white transition disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed"
                            >
                                <v-icon size="16">mdi-close-circle-outline</v-icon>
                                <span>Tolak Pembayaran</span>
                            </button>

                            <button
                                type="button"
                                @click="updateStatus('selesai')"
                                :disabled="pembayaran.status_pembayaran === 'belum_dibayar' || pembayaran.status_pembayaran === 'gagal' || pembayaran.status_pembayaran === 'selesai' || isSubmitting"
                                class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white transition disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed shadow-sm"
                            >
                                <v-icon size="16">mdi-check-circle-outline</v-icon>
                                <span>Verifikasi Lunas</span>
                            </button>
                        </div>
                    </v-card>
                </div>

                <!-- Kolom Kanan: Bukti Pembayaran Viewer (5 Kolom) -->
                <div class="lg:col-span-5">
                    <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <v-icon color="primary" size="20">mdi-image-check-outline</v-icon>
                                <h2 class="text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">
                                    Bukti Transfer / Pembayaran
                                </h2>
                            </div>

                            <a
                                v-if="pembayaran.bukti_pembayaran"
                                :href="`/storage/${pembayaran.bukti_pembayaran}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 hover:underline inline-flex items-center gap-1"
                            >
                                <span>Buka Penuh</span>
                                <v-icon size="14">mdi-open-in-new</v-icon>
                            </a>
                        </div>

                        <!-- Image Preview Container -->
                        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/40 min-h-[320px] flex items-center justify-center overflow-hidden p-2">
                            <template v-if="pembayaran.bukti_pembayaran">
                                <img
                                    :src="`/storage/${pembayaran.bukti_pembayaran}`"
                                    alt="Bukti Transfer Pembayaran"
                                    class="max-h-[460px] w-full object-contain rounded-lg shadow-sm"
                                />
                            </template>
                            <template v-else>
                                <div class="text-center p-6 space-y-2 text-slate-400 dark:text-slate-500">
                                    <v-icon size="48">mdi-image-off-outline</v-icon>
                                    <p class="text-xs font-medium">Belum ada bukti pembayaran yang diunggah</p>
                                </div>
                            </template>
                        </div>
                    </v-card>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>