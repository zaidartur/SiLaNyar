<script setup lang="ts">
/* eslint-disable */
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface JenisCairan {
    id: number;
    nama: string;
}

interface Parameter {
    id: number;
    nama_parameter: string;
    satuan: string;
    harga: number;
}

interface Kategori {
    id: number;
    nama: string;
    harga: number;
    parameter: Parameter[];
    subkategori: any[];
}

interface Pengajuan {
    id: number;
    status_pengajuan: string;
    metode_pengambilan: string;
    volume_sampel: string;
    lokasi: string;
    jenis_cairan: JenisCairan;
}

const props = defineProps<{
    pengajuan: Pengajuan;
    pembayaran: { status_pembayaran: string; total_biaya: number; metode_pembayaran: string } | null;
    metodePembayaran: string[];
    detailPembayaran: { kategori: Kategori; parameter: Parameter[] };
    pengajuanBerhasil?: boolean;
}>();

const form = useForm({
    metode_pembayaran: '',
    bukti_pembayaran: null as File | null,
});

const metode = ref(form.metode_pembayaran);
const buktiPembayaran = ref<File | null>(null);
const previewUrl = ref<string | null>(null);
const syarat = ref(false);
const error = ref('');
const loading = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);

watch(buktiPembayaran, (file) => {
    if (file && file.type.startsWith('image/')) {
        previewUrl.value = URL.createObjectURL(file);
    } else {
        previewUrl.value = null;
    }
});

function handleFileChange(e: Event) {
    const files = (e.target as HTMLInputElement).files;
    if (files && files.length > 0) {
        buktiPembayaran.value = files[0];
        form.bukti_pembayaran = files[0];
    }
}

function handleDrop(e: DragEvent) {
    e.preventDefault();
    if (e.dataTransfer && e.dataTransfer.files.length > 0) {
        buktiPembayaran.value = e.dataTransfer.files[0];
        form.bukti_pembayaran = e.dataTransfer.files[0];
    }
}

function handleDragOver(e: DragEvent) {
    e.preventDefault();
}

function submitPembayaran() {
    error.value = '';
    if (!syarat.value) {
        error.value = 'Anda harus menyetujui syarat dan ketentuan sebelum melanjutkan.';
        return;
    }
    if (!metode.value) {
        error.value = 'Silakan pilih salah satu metode pembayaran.';
        return;
    }
    if (metode.value === 'transfer' && !buktiPembayaran.value) {
        error.value = 'Silakan unggah foto/berkas bukti transfer pembayaran.';
        return;
    }
    loading.value = true;
    form.post(route('customer.pembayaran.process', props.pengajuan.uuid || props.pengajuan.id), {
        forceFormData: true,
        onFinish: () => (loading.value = false),
        onError: (err) => {
            error.value = Object.values(err).join(', ');
        },
    });
}

function formatRupiah(val: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val);
}
</script>

<template>
    <Head title="Pembayaran Retribusi Laboratorium" />
    <CustomerLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Tagihan Retribusi
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            #{{ props.pengajuan.id }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Pembayaran Retribusi Pengujian
                    </h1>
                </div>

                <v-btn
                    component="a"
                    :href="route('customer.dashboard')"
                    variant="outlined"
                    rounded="lg"
                    prepend-icon="mdi-arrow-left"
                    class="text-none font-semibold text-xs self-start sm:self-auto"
                >
                    Kembali
                </v-btn>
            </div>

            <!-- Notifikasi Pengajuan Sukses -->
            <v-alert
                v-if="pengajuanBerhasil"
                type="success"
                variant="tonal"
                rounded="lg"
                class="text-xs font-semibold"
            >
                Pengajuan sampel Anda telah berhasil tersimpan di sistem. Silakan selesaikan pembayaran retribusi di bawah ini.
            </v-alert>

            <!-- Ringkasan Sampel -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800 text-slate-900 dark:text-slate-100">
                    <v-icon icon="mdi-flask-outline" color="primary" size="20" />
                    <h2 class="text-sm font-bold">Ringkasan Sampel Lingkungan</h2>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">Jenis Cairan</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ pengajuan.jenis_cairan?.nama || '-' }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">Volume Sampel</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ pengajuan.volume_sampel }} ml</p>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">Metode</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 capitalize">{{ pengajuan.metode_pengambilan }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">Status Validasi</span>
                        <p class="font-bold text-emerald-700 dark:text-emerald-400 mt-0.5 capitalize">{{ pengajuan.status_pengajuan }}</p>
                    </div>
                </div>
            </v-card>

            <!-- Rincian Parameter & Tagihan -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800 text-slate-900 dark:text-slate-100">
                    <div class="flex items-center gap-2">
                        <v-icon icon="mdi-receipt-text-outline" color="primary" size="20" />
                        <h2 class="text-sm font-bold">Rincian Retribusi Uji Parameter</h2>
                    </div>
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        {{ detailPembayaran.parameter?.length || 0 }} parameter
                    </span>
                </div>

                <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                <th class="px-4 py-2.5 text-left font-bold">Parameter Uji</th>
                                <th class="px-4 py-2.5 text-right font-bold">Tarif Retribusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr
                                v-for="param in detailPembayaran.parameter"
                                :key="param.id"
                                class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"
                            >
                                <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">
                                    {{ param.nama_parameter }} <span v-if="param.satuan" class="text-slate-400">({{ param.satuan }})</span>
                                </td>
                                <td class="px-4 py-2.5 text-right font-semibold text-slate-700 dark:text-slate-300">
                                    {{ formatRupiah(param.harga || 0) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-bold text-slate-500 dark:text-slate-400">Total Tagihan Retribusi</span>
                        <p class="text-xs text-slate-600 dark:text-slate-400">Tarif resmi sesuai Perda Retribusi Laboratorium</p>
                    </div>
                    <span class="text-2xl font-black text-emerald-800 dark:text-emerald-300">
                        {{
                            formatRupiah(
                                pembayaran?.total_biaya ||
                                detailPembayaran.parameter.reduce((sum, p) => sum + (p.harga || 0), 0)
                            )
                        }}
                    </span>
                </div>
            </v-card>

            <!-- Metode Pembayaran Card -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 space-y-5">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800 text-slate-900 dark:text-slate-100">
                    <v-icon icon="mdi-credit-card-outline" color="primary" size="20" />
                    <h2 class="text-sm font-bold">Pilih Cara Pembayaran</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Tunai (hanya jika diantar) -->
                    <div
                        v-if="pengajuan.metode_pengambilan === 'diantar'"
                        @click="metode = 'tunai'; form.metode_pembayaran = 'tunai'"
                        class="cursor-pointer rounded-xl border p-4 transition-all flex items-start gap-3"
                        :class="[
                            metode === 'tunai'
                                ? 'border-emerald-600 bg-emerald-50/60 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-100 ring-2 ring-emerald-500'
                                : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 text-slate-800 dark:text-slate-200 hover:border-emerald-400'
                        ]"
                    >
                        <v-icon icon="mdi-cash-multiple" color="primary" size="28" />
                        <div>
                            <p class="text-sm font-bold">Tunai di Loket</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Pembayaran langsung ke kasir laboratorium saat mengantarkan sampel.
                            </p>
                        </div>
                    </div>

                    <!-- Transfer Bank Jateng -->
                    <div
                        @click="metode = 'transfer'; form.metode_pembayaran = 'transfer'"
                        class="cursor-pointer rounded-xl border p-4 transition-all flex items-start gap-3"
                        :class="[
                            metode === 'transfer'
                                ? 'border-emerald-600 bg-emerald-50/60 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-100 ring-2 ring-emerald-500'
                                : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 text-slate-800 dark:text-slate-200 hover:border-emerald-400'
                        ]"
                    >
                        <v-icon icon="mdi-bank-transfer" color="primary" size="28" />
                        <div>
                            <p class="text-sm font-bold">Transfer Bank Jateng</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Transfer rekening resmi Pemkab Karanganyar via ATM/Mobile Banking.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Petunjuk Transfer & Dropzone Upload -->
                <div v-if="metode === 'transfer'" class="space-y-4 pt-2">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-xs space-y-2">
                        <p class="font-bold text-slate-900 dark:text-slate-100 text-sm">Instruksi Transfer Rekening Resmi:</p>
                        <div class="p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                            <div>
                                <span class="text-slate-400">Nomor Rekening Bank Jateng:</span>
                                <p class="text-base font-extrabold text-emerald-700 dark:text-emerald-400 tracking-wider">3-001-12345-6</p>
                                <p class="text-[11px] text-slate-500">a.n. DINAS LINGKUNGAN HIDUP KAB. KARANGANYAR</p>
                            </div>
                            <v-btn
                                variant="text"
                                size="small"
                                color="primary"
                                prepend-icon="mdi-content-copy"
                                class="text-none"
                                @click="navigator.clipboard?.writeText('3001123456')"
                            >
                                Salin Rekening
                            </v-btn>
                        </div>
                        <ul class="list-disc pl-5 space-y-1 text-slate-600 dark:text-slate-400">
                            <li>Masukkan nomor ID Pengajuan <b>#{{ pengajuan.id }}</b> pada berita transfer.</li>
                            <li>Foto atau tangkap layar struk/bukti transfer yang jelas dan unggah pada kotak di bawah.</li>
                        </ul>
                    </div>

                    <!-- Dropzone Bukti Transfer -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Unggah Bukti Transfer <span class="text-rose-500">*</span>
                        </label>
                        <div
                            class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer transition-all"
                            :class="[
                                buktiPembayaran
                                    ? 'border-emerald-500 bg-emerald-50/40 dark:bg-emerald-950/20'
                                    : 'border-slate-300 dark:border-slate-700 bg-slate-50/40 dark:bg-slate-800/40 hover:border-emerald-400'
                            ]"
                            @drop="handleDrop"
                            @dragover="handleDragOver"
                            @click="fileInput?.click()"
                        >
                            <template v-if="!buktiPembayaran">
                                <v-icon icon="mdi-cloud-upload-outline" size="36" color="primary" class="mb-2" />
                                <p class="text-xs font-bold text-slate-900 dark:text-slate-100">Klik atau seret foto bukti transfer di sini</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Format JPG, PNG (Maksimal 5 MB)</p>
                            </template>
                            <template v-else>
                                <div class="flex flex-col items-center">
                                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 mb-2">
                                        <v-icon icon="mdi-check-circle" size="16" start /> Berkas terpilih: {{ buktiPembayaran.name }}
                                    </p>
                                    <img v-if="previewUrl" :src="previewUrl" alt="Preview" class="max-h-40 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700" />
                                </div>
                            </template>
                            <input type="file" ref="fileInput" class="hidden" accept="image/jpeg,image/png,application/pdf" @change="handleFileChange" />
                        </div>
                    </div>
                </div>

                <!-- Checkbox Syarat & Ketentuan -->
                <div class="pt-2">
                    <label class="flex items-start gap-2.5 cursor-pointer text-xs text-slate-700 dark:text-slate-300">
                        <input type="checkbox" v-model="syarat" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 mt-0.5" />
                        <span>
                            Saya menyatakan bahwa data yang diisi adalah benar, dan saya bersedia mematuhi jadwal pengujian serta ketentuan retribusi laboratorium DLH Kabupaten Karanganyar.
                        </span>
                    </label>
                </div>

                <v-alert v-if="error" type="error" variant="tonal" rounded="lg" density="compact" class="text-xs">
                    {{ error }}
                </v-alert>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
                    <v-btn
                        component="a"
                        :href="route('customer.dashboard')"
                        variant="outlined"
                        rounded="lg"
                        class="text-none font-semibold text-xs"
                    >
                        Batal
                    </v-btn>

                    <v-btn
                        color="primary"
                        rounded="lg"
                        prepend-icon="mdi-check-all"
                        :loading="loading"
                        @click="submitPembayaran"
                        class="text-none font-semibold text-xs px-6"
                    >
                        Konfirmasi Pembayaran
                    </v-btn>
                </div>
            </v-card>
        </div>
    </CustomerLayout>
</template>
