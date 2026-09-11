<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';

interface Parameter {
    id: number;
    nama_parameter: string;
}

interface Kategori {
    id: number;
    nama: string;
    parameter?: Parameter[];
    subkategori?: Kategori[];
}

interface JenisCairan {
    id: number;
    nama: string;
}

interface User {
    id: number;
    nama: string;
    email: string;
}

interface Instansi {
    id: number;
    nama: string;
    user: User;
}

interface Pengajuan {
    id: number;
    kode_pengajuan?: string;
    instansi: Instansi;
    alamat_pengambilan: string;
    lokasi: string;
    metode_pengambilan: 'diantar' | 'diambil';
    status_pengajuan: 'diproses' | 'diterima' | 'ditolak';
    id_kategori?: number;
    kategori?: Kategori;
    parameter?: Parameter[];
    jenis_cairan?: JenisCairan;
}

interface Errors {
    status_pengajuan?: string;
    id_kategori?: string;
    parameter?: string;
}

const props = defineProps<{
    pengajuan?: Pengajuan;
    kategoriList?: Kategori[];
    parameterList?: Parameter[];
    errors?: Errors;
}>();

const form = useForm({
    status_pengajuan: props.pengajuan?.status_pengajuan || '',
    id_kategori: props.pengajuan?.id_kategori || '',
    parameter: props.pengajuan?.parameter?.map((p: Parameter) => p.id) || [],
});

const availableParameterIds = ref<number[]>([]);

// Simpan pilihan awal pelanggan
const originalKategoriId = props.pengajuan?.kategori?.id;
const originalParameterIds = props.pengajuan?.parameter?.map((p: Parameter) => p.id) || [];

// Validasi Form
const isFormValid = computed(() => {
    if (!form.status_pengajuan) return false;
    if (form.status_pengajuan === 'ditolak') return true;
    if (form.status_pengajuan === 'diterima') {
        if (props.pengajuan?.metode_pengambilan === 'diantar') {
            return Boolean(form.id_kategori && form.parameter.length > 0);
        }
        return true;
    }
    return false;
});

const hasExistingKategori = computed(() => {
    return props.pengajuan?.kategori && props.pengajuan.kategori.id;
});

const isParameterAvailable = (parameterId: number) => {
    return availableParameterIds.value.includes(parameterId);
};

const isOriginalParameter = (parameterId: number) => {
    return originalParameterIds.includes(parameterId);
};

const showAdditionalFields = computed(() => {
    return form.status_pengajuan === 'diterima' && props.pengajuan?.metode_pengambilan === 'diantar';
});

const kategoriChanged = computed(() => {
    return form.id_kategori != originalKategoriId;
});

const parameterChanged = computed(() => {
    const currentParams = [...form.parameter].sort();
    const originalParams = [...originalParameterIds].sort();
    return JSON.stringify(currentParams) !== JSON.stringify(originalParams);
});

const hasChanges = computed(() => {
    return kategoriChanged.value || parameterChanged.value;
});

const selectedKategoriName = computed(() => {
    const kategori = props.kategoriList?.find((k: Kategori) => k.id == form.id_kategori);
    return kategori?.nama || '';
});

const addedParameters = computed(() => {
    const added = form.parameter.filter((id: number) => !originalParameterIds.includes(id));
    return added
        .map((id: number) => {
            const param = props.parameterList?.find((p: Parameter) => p.id === id);
            return param?.nama_parameter || '';
        })
        .filter((name: string) => Boolean(name));
});

const removedParameters = computed(() => {
    const removed = originalParameterIds.filter((id: number) => !form.parameter.includes(id));
    return removed
        .map((id: number) => {
            const param = props.pengajuan?.parameter?.find((p: Parameter) => p.id === id);
            return param?.nama_parameter || '';
        })
        .filter((name: string) => Boolean(name));
});

const isStatusChangeDisabled = computed(() => {
    return (
        props.pengajuan?.metode_pengambilan === 'diambil' &&
        props.pengajuan?.status_pengajuan === 'ditolak'
    );
});

const onKategoriChange = () => {
    const selectedKategori = props.kategoriList?.find((k: Kategori) => k.id == form.id_kategori);

    if (selectedKategori) {
        let parameterIds: number[] = [];

        if (selectedKategori.parameter) {
            parameterIds = [...selectedKategori.parameter.map((p: Parameter) => p.id)];
        }

        if (selectedKategori.subkategori) {
            selectedKategori.subkategori.forEach((sub: Kategori) => {
                if (sub.parameter) {
                    parameterIds = [...parameterIds, ...sub.parameter.map((p: Parameter) => p.id)];
                }
            });
        }

        availableParameterIds.value = [...new Set(parameterIds)];
    } else {
        availableParameterIds.value = [];
    }

    form.parameter = form.parameter.filter((id: number) => availableParameterIds.value.includes(id));
};

const restoreAvailableOriginalParameters = () => {
    const availableOriginalParams = originalParameterIds.filter((id: number) => availableParameterIds.value.includes(id));
    form.parameter = [...availableOriginalParams];
};

onMounted(() => {
    if (form.id_kategori) {
        onKategoriChange();
        restoreAvailableOriginalParameters();
    }
});

watch(
    () => form.status_pengajuan,
    (newStatus) => {
        if (newStatus === 'diterima' && props.pengajuan?.metode_pengambilan === 'diantar') {
            if (hasExistingKategori.value) {
                form.id_kategori = originalKategoriId || '';
                onKategoriChange();
                setTimeout(() => {
                    restoreAvailableOriginalParameters();
                }, 0);
            }
        } else if (newStatus === 'ditolak') {
            form.id_kategori = '';
            form.parameter = [];
            availableParameterIds.value = [];
        }
    },
);

const submit = () => {
    if (props.pengajuan) {
        form.put(`/pegawai/pengajuan/${props.pengajuan.uuid || props.pengajuan.id}/edit`, {
            onSuccess: () => {
                router.visit('/pegawai/pengajuan');
            },
        });
    }
};
</script>

<template>
    <Head title="Validasi & Edit Pengajuan Sampel" />

    <AdminLayout title="Validasi Pengajuan">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Halaman & Tombol Kembali -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="router.visit('/pegawai/pengajuan')"
                            class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition"
                            title="Kembali ke daftar pengajuan"
                        >
                            <v-icon size="20">mdi-arrow-left</v-icon>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Validasi & Edit Pengajuan
                        </h1>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 ml-8">
                        Verifikasi kesesuaian dokumen permohonan pengujian sampel laboratorium
                    </p>
                </div>

                <div v-if="pengajuan" class="ml-8 sm:ml-0 flex items-center gap-2">
                    <span
                        class="px-3 py-1 rounded-full text-xs font-bold border"
                        :class="{
                            'bg-amber-50 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-300 dark:border-amber-800': pengajuan.status_pengajuan === 'diproses',
                            'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800': pengajuan.status_pengajuan === 'diterima',
                            'bg-rose-50 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 border-rose-300 dark:border-rose-800': pengajuan.status_pengajuan === 'ditolak',
                        }"
                    >
                        Status: {{ pengajuan.status_pengajuan.toUpperCase() }}
                    </span>
                </div>
            </div>

            <!-- Detail Kartu Pengajuan Pemohon -->
            <v-card v-if="pengajuan" rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <v-icon color="primary" size="20">mdi-office-building-outline</v-icon>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wide">
                        Informasi Pemohon & Sampel
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Instansi / Perusahaan:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 text-sm">
                            {{ pengajuan.instansi?.nama || '-' }}
                        </p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Nama Pemohon (PIC):</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 text-sm">
                            {{ pengajuan.instansi?.user?.nama || '-' }}
                        </p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Email Kontak:</span>
                        <p class="font-semibold text-slate-800 dark:text-slate-200">
                            {{ pengajuan.instansi?.user?.email || '-' }}
                        </p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Jenis Cairan Sampel:</span>
                        <p class="font-semibold text-emerald-700 dark:text-emerald-400">
                            {{ pengajuan.jenis_cairan?.nama || '-' }}
                        </p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Metode Pengambilan:</span>
                        <p class="font-semibold capitalize text-slate-900 dark:text-slate-100">
                            {{ pengajuan.metode_pengambilan }}
                        </p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Lokasi / Alamat Pengambilan:</span>
                        <p class="font-medium text-slate-800 dark:text-slate-200">
                            {{ pengajuan.alamat_pengambilan || pengajuan.lokasi || '-' }}
                        </p>
                    </div>
                </div>
            </v-card>

            <!-- Form Kontainer Verifikasi -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 sm:p-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Pilihan Status Pengajuan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            Keputusan Verifikasi Status <span class="text-emerald-600">*</span>
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Tombol Terima -->
                            <button
                                type="button"
                                @click="form.status_pengajuan = 'diterima'"
                                :disabled="pengajuan?.status_pengajuan === 'diterima' || isStatusChangeDisabled"
                                class="flex items-center justify-center gap-2 p-4 rounded-xl border-2 font-bold text-sm transition cursor-pointer disabled:cursor-not-allowed disabled:opacity-50"
                                :class="[
                                    form.status_pengajuan === 'diterima'
                                        ? 'border-emerald-600 bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-200 dark:border-emerald-500 shadow-sm'
                                        : 'border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-emerald-300 dark:hover:border-emerald-800'
                                ]"
                            >
                                <v-icon :color="form.status_pengajuan === 'diterima' ? 'success' : undefined" size="20">
                                    mdi-check-circle-outline
                                </v-icon>
                                <span>Terima Pengajuan</span>
                            </button>

                            <!-- Tombol Tolak -->
                            <button
                                type="button"
                                @click="form.status_pengajuan = 'ditolak'"
                                :disabled="pengajuan?.status_pengajuan === 'diterima' || isStatusChangeDisabled"
                                class="flex items-center justify-center gap-2 p-4 rounded-xl border-2 font-bold text-sm transition cursor-pointer disabled:cursor-not-allowed disabled:opacity-50"
                                :class="[
                                    form.status_pengajuan === 'ditolak'
                                        ? 'border-rose-600 bg-rose-50 text-rose-800 dark:bg-rose-950/60 dark:text-rose-200 dark:border-rose-500 shadow-sm'
                                        : 'border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-rose-300 dark:hover:border-rose-800'
                                ]"
                            >
                                <v-icon :color="form.status_pengajuan === 'ditolak' ? 'error' : undefined" size="20">
                                    mdi-close-circle-outline
                                </v-icon>
                                <span>Tolak Pengajuan</span>
                            </button>
                        </div>

                        <div v-if="errors?.status_pengajuan" class="mt-2 text-xs text-rose-500 font-medium">
                            {{ errors.status_pengajuan }}
                        </div>
                        <div v-if="pengajuan?.status_pengajuan === 'diterima'" class="mt-2 p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 text-xs border border-emerald-200 dark:border-emerald-800">
                            Pengajuan sudah berstatus <strong>DITERIMA</strong> dan tidak dapat diubah lagi.
                        </div>
                        <div v-else-if="isStatusChangeDisabled" class="mt-2 p-2.5 rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-800 dark:text-rose-300 text-xs border border-rose-200 dark:border-rose-800">
                            Pengajuan sudah berstatus <strong>DITOLAK</strong> dan tidak dapat diubah lagi.
                        </div>
                    </div>

                    <!-- Data Asli Pilihan Pelanggan -->
                    <div
                        v-if="pengajuan && (pengajuan.kategori || (pengajuan.parameter && pengajuan.parameter.length > 0))"
                        class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 space-y-3 text-xs"
                    >
                        <div class="flex items-center gap-1.5 font-bold text-slate-900 dark:text-slate-100">
                            <v-icon size="16" color="primary">mdi-information-outline</v-icon>
                            <span>Pilihan Awal Pelanggan</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-if="pengajuan.kategori">
                                <span class="text-slate-500 dark:text-slate-400">Kategori Sampel Asli:</span>
                                <p class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">
                                    {{ pengajuan.kategori.nama }}
                                </p>
                            </div>
                            <div v-if="pengajuan.parameter && pengajuan.parameter.length > 0">
                                <span class="text-slate-500 dark:text-slate-400">Parameter Uji Asli ({{ pengajuan.parameter.length }} parameter):</span>
                                <div class="flex flex-wrap gap-1.5 mt-1">
                                    <span
                                        v-for="param in pengajuan.parameter"
                                        :key="param.id"
                                        class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-[11px]"
                                    >
                                        {{ param.nama_parameter }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Tambahan Khusus Pengajuan Diterima & Metode Diantar -->
                    <div v-if="showAdditionalFields" class="space-y-5 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <!-- Pilihan Kategori -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Kategori Uji Laboratorium <span class="text-emerald-600">*</span>
                            </label>
                            <select
                                v-model="form.id_kategori"
                                @change="onKategoriChange"
                                required
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                            >
                                <option value="">-- Pilih Kategori Sampel --</option>
                                <option
                                    v-for="kategori in kategoriList"
                                    :key="(kategori as any).id"
                                    :value="(kategori as any).id"
                                >
                                    {{ (kategori as any).nama }}
                                    <template v-if="pengajuan?.kategori && pengajuan.kategori.id === (kategori as any).id">
                                        (Pilihan Pemohon)
                                    </template>
                                </option>
                            </select>
                            <div v-if="errors?.id_kategori" class="mt-1.5 text-xs text-rose-500 font-medium">
                                {{ errors.id_kategori }}
                            </div>
                        </div>

                        <!-- Pilihan Parameter Uji -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    Parameter Pengujian Sampel <span class="text-emerald-600">*</span>
                                </label>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                    {{ form.parameter.length }} parameter dipilih
                                </span>
                            </div>

                            <div class="max-h-60 overflow-y-auto rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800/40 p-3 space-y-2">
                                <div v-if="!parameterList || parameterList.length === 0" class="text-xs text-slate-400 italic py-2">
                                    Memuat daftar parameter...
                                </div>
                                <label
                                    v-for="parameter in parameterList"
                                    :key="(parameter as any).id"
                                    :for="`param-${(parameter as any).id}`"
                                    class="flex items-center gap-2.5 p-2 rounded-lg border transition cursor-pointer text-xs"
                                    :class="[
                                        !isParameterAvailable((parameter as any).id) || !form.id_kategori
                                            ? 'opacity-40 border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/40 cursor-not-allowed'
                                            : form.parameter.includes((parameter as any).id)
                                                ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30'
                                                : 'border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50'
                                    ]"
                                >
                                    <input
                                        type="checkbox"
                                        :id="`param-${(parameter as any).id}`"
                                        :value="(parameter as any).id"
                                        v-model="form.parameter"
                                        :disabled="!isParameterAvailable((parameter as any).id) || !form.id_kategori"
                                        class="h-4 w-4 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer disabled:cursor-not-allowed"
                                    />
                                    <span class="flex-1 font-medium text-slate-800 dark:text-slate-200">
                                        {{ (parameter as any).nama_parameter }}
                                    </span>

                                    <!-- Indikator Pilihan Pelanggan -->
                                    <span
                                        v-if="isOriginalParameter((parameter as any).id)"
                                        class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300 border border-blue-200 dark:border-blue-800"
                                    >
                                        Pilihan Pemohon
                                    </span>

                                    <!-- Status Ketersediaan Parameter -->
                                    <span
                                        v-if="form.id_kategori"
                                        class="px-1.5 py-0.5 rounded text-[10px] font-semibold"
                                        :class="isParameterAvailable((parameter as any).id)
                                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300'
                                            : 'bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300'"
                                    >
                                        {{ isParameterAvailable((parameter as any).id) ? 'Tersedia' : 'Non-Kategori' }}
                                    </span>
                                </label>
                            </div>

                            <div v-if="errors?.parameter" class="mt-1.5 text-xs text-rose-500 font-medium">
                                {{ errors.parameter }}
                            </div>
                        </div>

                        <!-- Ringkasan Perubahan Parameter -->
                        <div v-if="hasChanges" class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-300 space-y-1.5">
                            <div class="flex items-center gap-1.5 font-bold">
                                <v-icon size="16" color="warning">mdi-alert-circle-outline</v-icon>
                                <span>Perubahan dari Pilihan Awal Pemohon:</span>
                            </div>
                            <div v-if="kategoriChanged" class="ml-5">
                                Kategori: <span class="line-through opacity-70">{{ pengajuan?.kategori?.nama }}</span> &rarr; <strong>{{ selectedKategoriName }}</strong>
                            </div>
                            <div v-if="parameterChanged" class="ml-5 space-y-0.5">
                                <div v-if="addedParameters.length > 0">
                                    Parameter Ditambahkan: <strong>{{ addedParameters.join(', ') }}</strong>
                                </div>
                                <div v-if="removedParameters.length > 0">
                                    Parameter Dihapus: <strong>{{ removedParameters.join(', ') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Simpan / Batal -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            @click="router.visit('/pegawai/pengajuan')"
                            class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing || !isFormValid"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white font-bold text-xs py-2.5 px-6 shadow-sm transition disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed"
                        >
                            <v-icon size="16">mdi-content-save-check-outline</v-icon>
                            <span>{{ form.processing ? 'Memproses...' : 'Simpan & Perbarui Status' }}</span>
                        </button>
                    </div>
                </form>
            </v-card>
        </div>
    </AdminLayout>
</template>
