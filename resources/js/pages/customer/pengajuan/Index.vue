<script setup lang="ts">
/* eslint-disable */
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

interface JenisCairan {
    id: number;
    nama: string;
    batas_minimum: number;
    batas_maksimum: number | null;
}
interface Parameter {
    id: number;
    nama_parameter: string;
    harga?: number;
}
interface SubKategori {
    id: number;
    nama: string;
    parameter: Parameter[];
}
interface Kategori {
    id: number;
    nama: string;
    parameter: Parameter[];
    subkategori: SubKategori[];
}
interface Instansi {
    id: number;
    nama: string;
}

const page = usePage();
const instansiList = (page.props.instansi as Instansi[]) || [];
const jenisCairan = (page.props.jenis_cairan as JenisCairan[]) || [];
const kategori = (page.props.kategori as Kategori[]) || [];
const semuaParameter = (page.props.parameter as Parameter[]) || [];

const step = ref(1);
const pengajuanBerhasil = ref(false);

const form = useForm({
    id_instansi: '',
    id_jenis_cairan: null as number | null,
    volume_sampel: null as number | null,
    metode_pengambilan: '',
    lokasi: '',
    waktu_pengambilan: null as string | null,
    id_kategori: null as number | null,
    parameter: [] as number[],
    keterangan: '',
});

const validationErrors = ref<Record<string, string>>({});

const selectedJenisCairan = computed(() => jenisCairan.find((j) => j.id === form.id_jenis_cairan));

const volumePlaceholder = computed(() => {
    if (selectedJenisCairan.value) {
        return `Min: ${selectedJenisCairan.value.batas_minimum} ml`;
    }
    return 'Pilih jenis cairan terlebih dahulu';
});

const isVolumeValid = computed(() => {
    if (!form.volume_sampel || !selectedJenisCairan.value) return false;
    return (
        form.volume_sampel >= selectedJenisCairan.value.batas_minimum &&
        (selectedJenisCairan.value.batas_maksimum === null ||
            form.volume_sampel <= selectedJenisCairan.value.batas_maksimum)
    );
});

function validateStep1() {
    const errors: Record<string, string> = {};

    if (!form.id_jenis_cairan) {
        errors.id_jenis_cairan = 'Jenis cairan harus dipilih';
    }

    if (!form.volume_sampel) {
        errors.volume_sampel = 'Volume sampel harus diisi';
    } else if (selectedJenisCairan.value) {
        if (form.volume_sampel < selectedJenisCairan.value.batas_minimum) {
            errors.volume_sampel = `Volume sampel minimal ${selectedJenisCairan.value.batas_minimum} ml`;
        } else if (
            selectedJenisCairan.value.batas_maksimum !== null &&
            form.volume_sampel > selectedJenisCairan.value.batas_maksimum
        ) {
            errors.volume_sampel = `Volume sampel maksimal ${selectedJenisCairan.value.batas_maksimum} ml`;
        }
    }

    if (!form.id_instansi) {
        errors.id_instansi = 'Instansi harus dipilih';
    }

    if (!form.metode_pengambilan) {
        errors.metode_pengambilan = 'Metode pengambilan harus dipilih';
    }

    if (form.metode_pengambilan === 'diambil' && !form.lokasi?.trim()) {
        errors.lokasi = 'Lokasi pengambilan harus diisi';
    }

    if (form.metode_pengambilan === 'diantar') {
        if (!form.waktu_pengambilan) {
            errors.waktu_pengambilan = 'Waktu pengambilan harus diisi';
        } else {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const selectedDate = new Date(form.waktu_pengambilan);

            if (selectedDate < today) {
                errors.waktu_pengambilan = 'Tanggal pengambilan tidak boleh sebelum hari ini';
            }
        }
    }

    validationErrors.value = errors;
    return Object.keys(errors).length === 0;
}

function validateStep2() {
    const errors: Record<string, string> = {};

    if (!form.id_kategori) {
        errors.id_kategori = 'Kategori harus dipilih';
    }

    if (form.parameter.length === 0) {
        errors.parameter = 'Minimal satu parameter harus dipilih';
    }

    validationErrors.value = errors;
    return Object.keys(errors).length === 0;
}

function clearError(field: string) {
    if (validationErrors.value[field]) {
        delete validationErrors.value[field];
    }
}

watch(() => form.id_jenis_cairan, () => {
    clearError('id_jenis_cairan');
    if (form.volume_sampel && selectedJenisCairan.value) {
        clearError('volume_sampel');
    }
});

watch(() => form.volume_sampel, () => {
    if (form.volume_sampel && isVolumeValid.value) {
        clearError('volume_sampel');
    }
});

watch(() => form.id_instansi, () => clearError('id_instansi'));
watch(() => form.metode_pengambilan, (val) => {
    clearError('metode_pengambilan');
    clearError('lokasi');
    clearError('waktu_pengambilan');
    clearError('keterangan');
    if (val === 'diantar') {
        form.lokasi = 'Jl. Lawu No.204, Tegalasri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57716 (DLH Kabupaten Karanganyar)';
    } else {
        form.lokasi = '';
    }
    form.parameter = [];
    form.id_kategori = null;
});

watch(() => form.lokasi, () => clearError('lokasi'));
watch(() => form.waktu_pengambilan, () => {
    if (form.waktu_pengambilan) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const selectedDate = new Date(form.waktu_pengambilan);
        if (selectedDate >= today) {
            clearError('waktu_pengambilan');
        }
    }
});
watch(() => form.id_kategori, (kategoriId) => {
    clearError('id_kategori');
    const kat = kategori.find((k) => k.id === kategoriId);
    if (!kat) return;
    const allowedParamIds =
        kat.subkategori.length > 0 ? kat.subkategori.flatMap((s) => s.parameter.map((p) => p.id)) : kat.parameter.map((p) => p.id);
    form.parameter = [...new Set(allowedParamIds)];
});
watch(() => form.parameter, () => clearError('parameter'));
watch(() => form.keterangan, () => clearError('keterangan'));

function parameterIsInKategori(id: number): boolean {
    const kat = kategori.find((k) => k.id === form.id_kategori);
    if (!kat) return true;
    const allowedParamIds =
        kat.subkategori.length > 0 ? kat.subkategori.flatMap((s) => s.parameter.map((p) => p.id)) : kat.parameter.map((p) => p.id);
    return allowedParamIds.includes(id);
}

function nextStep() {
    if (step.value === 1 && validateStep1()) {
        step.value = 2;
    } else if (step.value === 2 && validateStep2()) {
        step.value = 3;
    }
}

function prevStep() {
    if (step.value > 1) step.value -= 1;
}

function submit() {
    form.post(route('customer.pengajuan.store'), {
        onSuccess: () => {
            pengajuanBerhasil.value = true;
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
}

function getNamaJenisCairan() {
    return jenisCairan.find((j) => j.id === form.id_jenis_cairan)?.nama || '-';
}
function getNamaInstansi() {
    return instansiList.find((i) => i.id === Number(form.id_instansi))?.nama || '-';
}
function getNamaKategori() {
    return kategori.find((k) => k.id === form.id_kategori)?.nama || '-';
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
    <Head title="Pengajuan Sampel" />
    <CustomerLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Pendaftaran Sampel
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Formulir Pengajuan Sampel Laboratorium
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-0.5">
                        Lengkapi informasi sampel, metode pengambilan, dan parameter uji lingkungan yang dibutuhkan.
                    </p>
                </div>
            </div>

            <!-- Pesan Error Flash -->
            <v-alert
                v-if="page.props.errors && Object.keys(page.props.errors).length > 0"
                type="error"
                variant="tonal"
                rounded="lg"
                class="mb-4"
            >
                <div class="font-semibold text-sm">Terdapat kesalahan pengisian formulir:</div>
                <ul class="list-disc pl-5 text-xs mt-1">
                    <li v-for="(err, key) in page.props.errors" :key="key">{{ err }}</li>
                </ul>
            </v-alert>

            <!-- Stepper Progres Langkah Modern -->
            <div class="grid grid-cols-3 gap-2 sm:gap-4">
                <div
                    class="flex items-center gap-3 p-3 rounded-xl border transition-all"
                    :class="[
                        step === 1
                            ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 font-bold shadow-sm'
                            : step > 1
                                ? 'border-emerald-200 dark:border-emerald-900 bg-slate-50 dark:bg-slate-900 text-emerald-700 dark:text-emerald-400'
                                : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500'
                    ]"
                >
                    <div
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs sm:text-sm font-extrabold shrink-0"
                        :class="[
                            step > 1
                                ? 'bg-emerald-600 text-white'
                                : step === 1
                                    ? 'bg-emerald-700 text-white'
                                    : 'bg-slate-200 dark:bg-slate-800 text-slate-500'
                        ]"
                    >
                        <v-icon v-if="step > 1" icon="mdi-check" size="16" />
                        <span v-else>1</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs uppercase tracking-wider font-semibold opacity-80">Langkah 1</p>
                        <p class="text-xs sm:text-sm truncate">Detail Sampel</p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-3 p-3 rounded-xl border transition-all"
                    :class="[
                        step === 2
                            ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 font-bold shadow-sm'
                            : step > 2
                                ? 'border-emerald-200 dark:border-emerald-900 bg-slate-50 dark:bg-slate-900 text-emerald-700 dark:text-emerald-400'
                                : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500'
                    ]"
                >
                    <div
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs sm:text-sm font-extrabold shrink-0"
                        :class="[
                            step > 2
                                ? 'bg-emerald-600 text-white'
                                : step === 2
                                    ? 'bg-emerald-700 text-white'
                                    : 'bg-slate-200 dark:bg-slate-800 text-slate-500'
                        ]"
                    >
                        <v-icon v-if="step > 2" icon="mdi-check" size="16" />
                        <span v-else>2</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs uppercase tracking-wider font-semibold opacity-80">Langkah 2</p>
                        <p class="text-xs sm:text-sm truncate">Parameter Uji</p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-3 p-3 rounded-xl border transition-all"
                    :class="[
                        step === 3
                            ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 font-bold shadow-sm'
                            : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500'
                    ]"
                >
                    <div
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs sm:text-sm font-extrabold shrink-0"
                        :class="[
                            step === 3
                                ? 'bg-emerald-700 text-white'
                                : 'bg-slate-200 dark:bg-slate-800 text-slate-500'
                        ]"
                    >
                        <span>3</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs uppercase tracking-wider font-semibold opacity-80">Langkah 3</p>
                        <p class="text-xs sm:text-sm truncate">Review & Kirim</p>
                    </div>
                </div>
            </div>

            <!-- Step 1: Informasi Dasar Sampel -->
            <form v-if="step === 1" @submit.prevent="nextStep" class="space-y-6">
                <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100 dark:border-slate-800 text-slate-900 dark:text-slate-100">
                        <v-icon icon="mdi-flask-outline" color="primary" />
                        <h2 class="text-base font-bold">1. Pilihan Jenis Sampel / Cairan</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        <div
                            v-for="jenis in jenisCairan"
                            :key="jenis.id"
                            @click="form.id_jenis_cairan = jenis.id"
                            class="cursor-pointer rounded-xl border p-4 transition-all"
                            :class="[
                                form.id_jenis_cairan === jenis.id
                                    ? 'border-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-100 shadow-sm ring-2 ring-emerald-500'
                                    : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-slate-800 dark:text-slate-200 hover:border-emerald-400'
                            ]"
                        >
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-base">{{ jenis.nama }}</span>
                                <v-icon v-if="form.id_jenis_cairan === jenis.id" icon="mdi-check-circle" color="primary" size="20" />
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 space-y-0.5">
                                <div>Batas Min: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ jenis.batas_minimum }} ml</span></div>
                                <div>Batas Maks: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ jenis.batas_maksimum ?? 'Tidak terbatas' }}</span></div>
                            </div>
                        </div>
                    </div>
                    <p v-if="validationErrors.id_jenis_cairan" class="text-xs text-rose-600 dark:text-rose-400 font-medium mt-2">
                        {{ validationErrors.id_jenis_cairan }}
                    </p>
                </v-card>

                <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100 dark:border-slate-800 text-slate-900 dark:text-slate-100">
                        <v-icon icon="mdi-office-building-cog-outline" color="primary" />
                        <h2 class="text-base font-bold">2. Parameter Volume, Instansi, & Pengambilan</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Volume Sampel -->
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Volume Sampel (ml) <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="number"
                                step="0.1"
                                min="0"
                                v-model.number="form.volume_sampel"
                                :disabled="!selectedJenisCairan"
                                :placeholder="volumePlaceholder"
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:opacity-50"
                                :class="[validationErrors.volume_sampel ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                            />
                            <p v-if="validationErrors.volume_sampel" class="text-xs text-rose-600 dark:text-rose-400 mt-1 font-medium">
                                {{ validationErrors.volume_sampel }}
                            </p>
                            <p v-else-if="selectedJenisCairan" class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Batas: {{ selectedJenisCairan.batas_minimum }} ml {{ selectedJenisCairan.batas_maksimum ? '- ' + selectedJenisCairan.batas_maksimum + ' ml' : '' }}
                            </p>
                        </div>

                        <!-- Pilihan Instansi -->
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Instansi Terdaftar <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.id_instansi"
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                :class="[validationErrors.id_instansi ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                            >
                                <option value="">-- Pilih Instansi --</option>
                                <option v-for="ins in instansiList" :key="ins.id" :value="ins.id">{{ ins.nama }}</option>
                            </select>
                            <p v-if="validationErrors.id_instansi" class="text-xs text-rose-600 dark:text-rose-400 mt-1 font-medium">
                                {{ validationErrors.id_instansi }}
                            </p>
                        </div>

                        <!-- Metode Pengambilan -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold mb-2 text-slate-700 dark:text-slate-300">
                                Metode Pengantaran / Pengambilan Sampel <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div
                                    @click="form.metode_pengambilan = 'diantar'"
                                    class="cursor-pointer rounded-xl border p-3.5 transition-all flex items-center gap-3"
                                    :class="[
                                        form.metode_pengambilan === 'diantar'
                                            ? 'border-emerald-600 bg-emerald-50/80 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-100 ring-2 ring-emerald-500 font-semibold'
                                            : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 text-slate-700 dark:text-slate-300 hover:border-emerald-400'
                                    ]"
                                >
                                    <v-icon icon="mdi-truck-delivery-outline" color="primary" />
                                    <div>
                                        <p class="text-sm font-bold">Diantar oleh Pemohon</p>
                                        <p class="text-xs opacity-75">Anda membawa langsung sampel ke Laboratorium DLH</p>
                                    </div>
                                </div>

                                <div
                                    @click="form.metode_pengambilan = 'diambil'"
                                    class="cursor-pointer rounded-xl border p-3.5 transition-all flex items-center gap-3"
                                    :class="[
                                        form.metode_pengambilan === 'diambil'
                                            ? 'border-emerald-600 bg-emerald-50/80 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-100 ring-2 ring-emerald-500 font-semibold'
                                            : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 text-slate-700 dark:text-slate-300 hover:border-emerald-400'
                                    ]"
                                >
                                    <v-icon icon="mdi-car-pickup" color="primary" />
                                    <div>
                                        <p class="text-sm font-bold">Diambil oleh Petugas PPCU</p>
                                        <p class="text-xs opacity-75">Tim petugas pengambil sampel DLH datang ke lokasi Anda</p>
                                    </div>
                                </div>
                            </div>
                            <p v-if="validationErrors.metode_pengambilan" class="text-xs text-rose-600 dark:text-rose-400 mt-1 font-medium">
                                {{ validationErrors.metode_pengambilan }}
                            </p>
                        </div>

                        <!-- Lokasi Penjemputan jika diambil -->
                        <div v-if="form.metode_pengambilan === 'diambil'" class="sm:col-span-2">
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Alamat Lokasi Pengambilan Sampel <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                v-model="form.lokasi"
                                placeholder="Masukkan alamat lengkap lokasi pengambilan sampel"
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                :class="[validationErrors.lokasi ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                            />
                            <p v-if="validationErrors.lokasi" class="text-xs text-rose-600 dark:text-rose-400 mt-1 font-medium">
                                {{ validationErrors.lokasi }}
                            </p>
                        </div>

                        <!-- Jadwal & Syarat Pengantaran jika diantar -->
                        <div v-if="form.metode_pengambilan === 'diantar'" class="sm:col-span-2 space-y-3">
                            <v-alert
                                type="info"
                                variant="tonal"
                                rounded="lg"
                                density="compact"
                                class="text-xs"
                            >
                                <span class="font-bold">Persyaratan Wadah Sampel Diantar:</span>
                                Wajib menggunakan wadah kaca/gelas bersih steril bertutup rapat guna mencegah kontaminasi dan menjamin validitas hasil pengujian.
                            </v-alert>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                        Rencana Tanggal Pengantaran <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        v-model="form.waktu_pengambilan"
                                        :min="new Date().toISOString().split('T')[0]"
                                        class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                        :class="[validationErrors.waktu_pengambilan ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                                    />
                                    <p v-if="validationErrors.waktu_pengambilan" class="text-xs text-rose-600 dark:text-rose-400 mt-1 font-medium">
                                        {{ validationErrors.waktu_pengambilan }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                        Lokasi Penerimaan Laboratorium
                                    </label>
                                    <input
                                        type="text"
                                        :value="form.lokasi"
                                        readonly
                                        class="w-full rounded-lg border px-3 py-2 text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-300 dark:border-slate-700"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                    Catatan / Keterangan Tambahan (Opsional)
                                </label>
                                <textarea
                                    v-model="form.keterangan"
                                    rows="2"
                                    placeholder="Informasi kondisi sampel, waktu pengambilan di titik sumber, dll."
                                    class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </v-card>

                <div class="flex justify-end">
                    <v-btn
                        type="submit"
                        color="primary"
                        rounded="lg"
                        append-icon="mdi-arrow-right"
                        class="px-6 font-semibold"
                    >
                        Lanjut ke Parameter Uji
                    </v-btn>
                </div>
            </form>

            <!-- Step 2: Kategori & Parameter Pengujian -->
            <form v-else-if="step === 2" @submit.prevent="nextStep" class="space-y-6">
                <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100 dark:border-slate-800 text-slate-900 dark:text-slate-100">
                        <v-icon icon="mdi-tag-outline" color="primary" />
                        <h2 class="text-base font-bold">1. Pilih Kategori Baku Mutu</h2>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Kategori Sampel Lingkungan <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.id_kategori"
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            :class="[validationErrors.id_kategori ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        >
                            <option :value="null">-- Pilih Kategori --</option>
                            <option v-for="kat in kategori" :key="kat.id" :value="kat.id">{{ kat.nama }}</option>
                        </select>
                        <p v-if="validationErrors.id_kategori" class="text-xs text-rose-600 dark:text-rose-400 mt-1 font-medium">
                            {{ validationErrors.id_kategori }}
                        </p>
                    </div>
                </v-card>

                <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800 text-slate-900 dark:text-slate-100">
                        <div class="flex items-center gap-2">
                            <v-icon icon="mdi-checkbox-marked-circle-outline" color="primary" />
                            <h2 class="text-base font-bold">2. Parameter Uji yang Diinginkan</h2>
                        </div>
                        <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                            {{ form.parameter.length }} parameter terpilih
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 max-h-[380px] overflow-y-auto p-1">
                        <label
                            v-for="param in semuaParameter"
                            :key="param.id"
                            class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition-all text-xs"
                            :class="[
                                form.parameter.includes(param.id)
                                    ? 'border-emerald-600 bg-emerald-50/60 dark:bg-emerald-950/30 text-emerald-950 dark:text-emerald-100 font-medium ring-1 ring-emerald-500'
                                    : 'border-slate-200 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-800/40 text-slate-700 dark:text-slate-300 hover:border-emerald-300'
                            ]"
                        >
                            <input
                                type="checkbox"
                                :value="param.id"
                                v-model="form.parameter"
                                :disabled="form.id_kategori ? !parameterIsInKategori(param.id) : false"
                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 mt-0.5"
                            />
                            <div class="flex-1 min-w-0">
                                <p class="truncate font-semibold">{{ param.nama_parameter }}</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ formatRupiah(param.harga || 0) }}
                                </p>
                            </div>
                        </label>
                    </div>
                    <p v-if="validationErrors.parameter" class="text-xs text-rose-600 dark:text-rose-400 mt-2 font-medium">
                        {{ validationErrors.parameter }}
                    </p>
                </v-card>

                <!-- Ringkasan Biaya Estimasi -->
                <v-card variant="outlined" rounded="xl" class="border-emerald-200 dark:border-emerald-900 bg-emerald-50/40 dark:bg-emerald-950/20 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <span class="text-xs uppercase tracking-wider font-bold text-slate-500 dark:text-slate-400">Estimasi Total Retribusi</span>
                            <p class="text-xs text-slate-600 dark:text-slate-400">Total dihitung dari akumulasi tarif parameter yang dipilih</p>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black text-emerald-800 dark:text-emerald-300">
                                {{
                                    formatRupiah(
                                        semuaParameter
                                            .filter((p) => form.parameter.includes(p.id))
                                            .reduce((sum, p) => sum + (p.harga || 0), 0)
                                    )
                                }}
                            </span>
                        </div>
                    </div>
                </v-card>

                <div class="flex items-center justify-between">
                    <v-btn
                        variant="outlined"
                        rounded="lg"
                        prepend-icon="mdi-arrow-left"
                        @click="prevStep"
                        class="px-5 font-semibold"
                    >
                        Kembali
                    </v-btn>
                    <v-btn
                        type="submit"
                        color="primary"
                        rounded="lg"
                        append-icon="mdi-arrow-right"
                        class="px-6 font-semibold"
                    >
                        Review Data Pengajuan
                    </v-btn>
                </div>
            </form>

            <!-- Step 3: Periksa & Serahkan -->
            <form v-else-if="step === 3" @submit.prevent="submit" class="space-y-6">
                <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800 text-slate-900 dark:text-slate-100">
                        <v-icon icon="mdi-clipboard-check-outline" color="primary" />
                        <h2 class="text-base font-bold">Ringkasan Data Pengajuan Sampel</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                            <span class="text-slate-400 dark:text-slate-500 uppercase tracking-wider font-semibold">Jenis Cairan</span>
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ getNamaJenisCairan() }}</p>
                        </div>
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                            <span class="text-slate-400 dark:text-slate-500 uppercase tracking-wider font-semibold">Volume Sampel</span>
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ form.volume_sampel }} ml</p>
                        </div>
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                            <span class="text-slate-400 dark:text-slate-500 uppercase tracking-wider font-semibold">Instansi Pemohon</span>
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ getNamaInstansi() }}</p>
                        </div>
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                            <span class="text-slate-400 dark:text-slate-500 uppercase tracking-wider font-semibold">Metode Pengambilan</span>
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5 capitalize">{{ form.metode_pengambilan }}</p>
                        </div>
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 sm:col-span-2">
                            <span class="text-slate-400 dark:text-slate-500 uppercase tracking-wider font-semibold">Lokasi Sampel</span>
                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100 mt-0.5">{{ form.lokasi || '-' }}</p>
                        </div>
                        <div v-if="form.waktu_pengambilan" class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                            <span class="text-slate-400 dark:text-slate-500 uppercase tracking-wider font-semibold">Jadwal Pengantaran</span>
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ form.waktu_pengambilan }}</p>
                        </div>
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                            <span class="text-slate-400 dark:text-slate-500 uppercase tracking-wider font-semibold">Kategori Baku Mutu</span>
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ getNamaKategori() }}</p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Daftar Parameter yang Akan Diuji ({{ form.parameter.length }} item):</p>
                        <div class="divide-y divide-slate-100 dark:divide-slate-800 border border-slate-200 dark:border-slate-800 rounded-lg max-h-48 overflow-y-auto">
                            <div
                                v-for="param in semuaParameter.filter((p) => form.parameter.includes(p.id))"
                                :key="param.id"
                                class="flex items-center justify-between px-3 py-2 text-xs"
                            >
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ param.nama_parameter }}</span>
                                <span class="text-slate-500 dark:text-slate-400 font-semibold">{{ formatRupiah(param.harga || 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900">
                        <div>
                            <span class="text-xs uppercase tracking-wider font-bold text-slate-500 dark:text-slate-400">Total Biaya Retribusi</span>
                            <p class="text-xs text-slate-600 dark:text-slate-400">Dapat dibayar via transfer atau tunai setelah diverifikasi</p>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black text-emerald-800 dark:text-emerald-300">
                                {{
                                    formatRupiah(
                                        semuaParameter
                                            .filter((p) => form.parameter.includes(p.id))
                                            .reduce((sum, p) => sum + (p.harga || 0), 0)
                                    )
                                }}
                            </span>
                        </div>
                    </div>
                </v-card>

                <div class="flex items-center justify-between">
                    <v-btn
                        variant="outlined"
                        rounded="lg"
                        prepend-icon="mdi-arrow-left"
                        @click="prevStep"
                        :disabled="form.processing"
                        class="px-5 font-semibold"
                    >
                        Kembali
                    </v-btn>
                    <v-btn
                        type="submit"
                        color="primary"
                        rounded="lg"
                        prepend-icon="mdi-send-check"
                        :loading="form.processing"
                        class="px-6 font-semibold"
                    >
                        Kirim Pengajuan Sekarang
                    </v-btn>
                </div>
            </form>
        </div>
    </CustomerLayout>
</template>
