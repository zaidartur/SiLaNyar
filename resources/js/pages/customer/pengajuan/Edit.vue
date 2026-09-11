<script setup lang="ts">
/* eslint-disable */
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

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
    harga?: number;
}

interface JenisCairan {
    id: number;
    nama: string;
    batas_minimum?: number;
}

interface Pengajuan {
    id: number;
    id_instansi: number;
    id_jenis_cairan: number;
    volume_sampel: number;
    metode_pengambilan: string;
    lokasi: string;
    waktu_pengambilan: string;
    kategori: Kategori | null;
    parameter: Parameter[];
    keterangan: string;
    status_pengajuan: string;
}

const { props } = usePage<{
    pengajuan: Pengajuan;
    kategori: Kategori[];
    parameter: Parameter[];
    jenis_cairan: JenisCairan[];
    instansi: Instansi[];
}>();

const pengajuan = props.pengajuan;
const kategoriList = props.kategori;
const parameterList = props.parameter;
const jenisCairanList = props.jenis_cairan;
const instansiList = props.instansi;

const form = useForm({
    id_instansi: pengajuan.id_instansi,
    id_jenis_cairan: pengajuan.id_jenis_cairan,
    volume_sampel: pengajuan.volume_sampel,
    id_form_pengajuan: pengajuan.id,
    metode_pengambilan: pengajuan.metode_pengambilan,
    lokasi: pengajuan.lokasi,
    waktu_pengambilan: pengajuan.waktu_pengambilan,
    id_kategori: pengajuan.kategori?.id ?? null,
    parameter: pengajuan.parameter.map((p) => p.id),
    keterangan: pengajuan.keterangan || '',
});

const validationErrors = ref<Record<string, string>>({});

function validateVolumeSampel() {
    const jenis = jenisCairanList.find((j) => j.id === form.id_jenis_cairan);
    if (!jenis) {
        validationErrors.value.volume_sampel = 'Pilih jenis cairan terlebih dahulu';
        return false;
    }
    const min = (jenis as any).batas_minimum ?? 0;
    if (form.volume_sampel < min) {
        validationErrors.value.volume_sampel = `Volume minimal untuk jenis cairan ini adalah ${min} ml`;
        return false;
    } else {
        delete validationErrors.value.volume_sampel;
        return true;
    }
}

function validateWaktuPengambilan() {
    if (form.metode_pengambilan === 'diantar' && form.waktu_pengambilan) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const selectedDate = new Date(form.waktu_pengambilan);

        if (selectedDate < today) {
            validationErrors.value.waktu_pengambilan = 'Tanggal pengambilan tidak boleh sebelum hari ini';
            return false;
        } else {
            delete validationErrors.value.waktu_pengambilan;
            return true;
        }
    }
    return true;
}

watch(
    [() => form.volume_sampel, () => form.id_jenis_cairan],
    () => {
        validateVolumeSampel();
    }
);

watch(
    () => form.waktu_pengambilan,
    () => {
        validateWaktuPengambilan();
    }
);

watch(
    () => form.id_kategori,
    (kategoriId) => {
        const kat = kategoriList.find((k) => k.id === kategoriId);
        if (!kat) return;

        const allowed = kat.subkategori.length
            ? kat.subkategori.flatMap((s) => s.parameter.map((p) => p.id))
            : kat.parameter.map((p) => p.id);

        form.parameter = [...new Set(allowed)];
    }
);

const parameterIsInKategori = (id: number): boolean => {
    const kat = kategoriList.find((k) => k.id === form.id_kategori);
    if (!kat) return true;
    const allowedIds = kat.subkategori.length
        ? kat.subkategori.flatMap((s) => s.parameter.map((p) => p.id))
        : kat.parameter.map((p) => p.id);
    return allowedIds.includes(id);
};

function submit() {
    if (!validateWaktuPengambilan() || !validateVolumeSampel()) {
        return;
    }
    form.put(route('customer.pengajuan.update', pengajuan.id));
}
</script>

<template>
    <Head title="Edit Pengajuan Sampel" />
    <CustomerLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Perubahan Data
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            #{{ pengajuan.id }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Edit Pengajuan Sampel
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
                    Batal & Kembali
                </v-btn>
            </div>

            <!-- Form Card -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Instansi (Read-only) -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Instansi Terdaftar
                        </label>
                        <select
                            v-model="form.id_instansi"
                            disabled
                            class="w-full rounded-lg border px-3 py-2 text-sm bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700 cursor-not-allowed"
                        >
                            <option v-for="ins in instansiList" :key="ins.id" :value="ins.id">
                                {{ ins.nama }}
                            </option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">Instansi pemohon tidak dapat diubah</p>
                    </div>

                    <!-- Jenis Cairan & Volume Sampel -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Jenis Sampel / Cairan <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.id_jenis_cairan"
                                required
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                            >
                                <option value="">-- Pilih Jenis Cairan --</option>
                                <option v-for="jenis in jenisCairanList" :key="jenis.id" :value="jenis.id">
                                    {{ jenis.nama }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Volume Sampel (ml) <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="number"
                                step="0.1"
                                min="0"
                                v-model.number="form.volume_sampel"
                                required
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                :class="[validationErrors.volume_sampel ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                            />
                            <p v-if="validationErrors.volume_sampel" class="text-xs text-rose-600 dark:text-rose-400 mt-1 font-medium">
                                {{ validationErrors.volume_sampel }}
                            </p>
                        </div>
                    </div>

                    <!-- Metode Pengambilan & Lokasi (Read-only info) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Metode Pengambilan
                            </label>
                            <input
                                type="text"
                                :value="form.metode_pengambilan === 'diantar' ? 'Diantar oleh Pemohon' : 'Diambil Petugas PPCU'"
                                disabled
                                class="w-full rounded-lg border px-3 py-2 text-sm bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700 cursor-not-allowed"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Lokasi Sampel
                            </label>
                            <input
                                type="text"
                                :value="form.lokasi || '-'"
                                disabled
                                class="w-full rounded-lg border px-3 py-2 text-sm bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700 cursor-not-allowed"
                            />
                        </div>
                    </div>

                    <!-- Jadwal Pengantaran jika diantar -->
                    <div v-if="form.metode_pengambilan === 'diantar'">
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Jadwal Tanggal Pengantaran <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="date"
                            v-model="form.waktu_pengambilan"
                            :min="new Date().toISOString().split('T')[0]"
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            :class="[validationErrors.waktu_pengambilan ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                            required
                        />
                        <p v-if="validationErrors.waktu_pengambilan" class="text-xs text-rose-600 dark:text-rose-400 mt-1 font-medium">
                            {{ validationErrors.waktu_pengambilan }}
                        </p>
                    </div>

                    <!-- Kategori Baku Mutu -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Kategori Baku Mutu <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.id_kategori"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                        >
                            <option :value="null">-- Pilih Kategori --</option>
                            <option v-for="kat in kategoriList" :key="kat.id" :value="kat.id">
                                {{ kat.nama }}
                            </option>
                        </select>
                    </div>

                    <!-- Parameter List Checkboxes -->
                    <div>
                        <label class="block text-xs font-semibold mb-2 text-slate-700 dark:text-slate-300">
                            Pilihan Parameter Analisis
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 border border-slate-200 dark:border-slate-800 rounded-xl p-3 max-h-56 overflow-y-auto">
                            <label
                                v-for="param in parameterList"
                                :key="param.id"
                                class="flex items-center gap-2 p-2 rounded-lg cursor-pointer text-xs"
                                :class="[
                                    form.parameter.includes(param.id)
                                        ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-100 font-semibold'
                                        : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'
                                ]"
                            >
                                <input
                                    type="checkbox"
                                    :value="param.id"
                                    v-model="form.parameter"
                                    :disabled="form.id_kategori ? !parameterIsInKategori(param.id) : false"
                                    class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                />
                                <span class="truncate">{{ param.nama_parameter }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Keterangan Tambahan
                        </label>
                        <textarea
                            v-model="form.keterangan"
                            rows="2"
                            placeholder="Catatan tambahan bila ada..."
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                        ></textarea>
                    </div>

                    <div class="flex justify-end pt-2">
                        <v-btn
                            type="submit"
                            color="primary"
                            rounded="lg"
                            prepend-icon="mdi-content-save-outline"
                            :loading="form.processing"
                            class="px-6 font-semibold"
                        >
                            Simpan Perubahan
                        </v-btn>
                    </div>
                </form>
            </v-card>
        </div>
    </CustomerLayout>
</template>
