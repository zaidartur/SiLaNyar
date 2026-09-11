<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm, Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Parameter {
    id: number;
    kode_parameter: string;
    nama_parameter: string;
    satuan: string;
    harga: string;
    pivot?: {
        baku_mutu: string;
    };
}

interface SubKategori {
    id: number;
    kode_subkategori: string;
    nama: string;
}

interface Kategori {
    id: number;
    kode_kategori: string;
    nama: string;
    harga: string;
    subkategori: SubKategori[];
    parameter: Parameter[];
}

const props = defineProps<{
    kategori: Kategori;
    subkategori: SubKategori[];
    parameter: Parameter[];
}>();

const displayValue = ref('');

const form = useForm({
    nama: props.kategori.nama,
    harga: props.kategori.harga,
    subkategori: props.kategori.subkategori?.map((sub) => sub.id) ?? [],
    parameter: (props.parameter || []).map((param) => ({
        id: param.id,
        checked: !!param.pivot,
        baku_mutu: param.pivot?.baku_mutu ?? '',
    })),
});

const formatCurrency = (value: string | number) => {
    if (!value) return '';
    const num = parseInt(value.toString().replace(/[^\d]/g, ''), 10);
    if (isNaN(num)) return '';
    form.harga = num.toString();
    return 'Rp ' + num.toLocaleString('id-ID');
};

const handleInput = (e: Event) => {
    const target = e.target as HTMLInputElement;
    displayValue.value = formatCurrency(target.value);
};

const formatOnBlur = () => {
    displayValue.value = formatCurrency(form.harga);
};

if (form.harga) {
    displayValue.value = formatCurrency(form.harga);
}

const isSubkategoriSelected = computed(() => form.subkategori.length > 0);
const isParameterSelected = computed(() => form.parameter.some(p => p.checked));

const submit = () => {
    const filterParam = form.parameter.filter((p) => p.checked);

    if (form.subkategori.length === 0 && filterParam.length === 0) {
        alert('Pilih minimal satu subkategori atau parameter baku mutu!');
        return;
    }

    form.parameter = filterParam;
    form.put(`/pegawai/kategori/${props.kategori.uuid || props.kategori.id}/edit`, {
        onSuccess: () => {
            router.visit('/pegawai/kategori');
        },
    });
};
</script>

<template>
    <Head title="Edit Kategori Sampel" />

    <AdminLayout title="Edit Kategori">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Halaman & Breadcrumb Back -->
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="router.visit('/pegawai/kategori')"
                            class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition"
                            title="Kembali ke daftar kategori"
                        >
                            <v-icon size="20">mdi-arrow-left</v-icon>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Edit Kategori Sampel
                        </h1>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 ml-8">
                        Perbarui informasi kategori, relasi sub-kategori, dan standar parameter baku mutu
                    </p>
                </div>

                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 hidden sm:inline-block">
                    {{ props.kategori.kode_kategori }}
                </span>
            </div>

            <!-- Form Card Kontainer Modern -->
            <v-card rounded="2xl" elevation="1" class="p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Kategori -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Nama Kategori <span class="text-emerald-600">*</span>
                            </label>
                            <input
                                v-model="form.nama"
                                type="text"
                                placeholder="Contoh: Air Limbah Industri"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                                required
                            />
                            <div v-if="form.errors.nama" class="mt-1.5 text-xs text-red-500 font-medium">
                                {{ form.errors.nama }}
                            </div>
                        </div>

                        <!-- Tarif / Harga -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Tarif Dasar Retribusi <span class="text-emerald-600">*</span>
                            </label>
                            <input
                                :value="displayValue"
                                @input="handleInput"
                                @blur="formatOnBlur"
                                type="text"
                                placeholder="Rp 0"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                                required
                            />
                            <div v-if="form.errors.harga" class="mt-1.5 text-xs text-red-500 font-medium">
                                {{ form.errors.harga }}
                            </div>
                        </div>
                    </div>

                    <!-- Sub Kategori Checkbox Group -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            Pilih Sub Kategori
                        </label>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">
                            Pilih jika kategori ini memiliki turunan sub-kategori spesifik
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                            <label
                                v-for="sub in props.subkategori"
                                :key="sub.id"
                                :class="[
                                    isParameterSelected ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
                                    form.subkategori.includes(sub.id)
                                        ? 'bg-emerald-50 dark:bg-emerald-950/70 border-emerald-300 dark:border-emerald-700 text-emerald-900 dark:text-emerald-200'
                                        : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300'
                                ]"
                                class="flex items-center gap-2.5 p-3 rounded-xl border text-xs font-medium transition"
                            >
                                <input
                                    type="checkbox"
                                    :value="sub.id"
                                    v-model="form.subkategori"
                                    :disabled="isParameterSelected"
                                    class="rounded text-emerald-600 focus:ring-emerald-500"
                                />
                                <span>{{ sub.nama }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Parameter Baku Mutu Group -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            Parameter Uji & Standar Baku Mutu
                        </label>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">
                            Centang parameter yang masuk dalam kategori ini dan tentukan nilai ambang baku mutunya
                        </p>
                        <div class="space-y-2.5 max-h-96 overflow-y-auto pr-1">
                            <div
                                v-for="(param, index) in form.parameter"
                                :key="param.id"
                                :class="[
                                    isSubkategoriSelected ? 'opacity-50 cursor-not-allowed' : '',
                                    param.checked
                                        ? 'bg-emerald-50/70 dark:bg-emerald-950/50 border-emerald-300 dark:border-emerald-800'
                                        : 'bg-slate-50 dark:bg-slate-800/40 border-slate-200 dark:border-slate-700'
                                ]"
                                class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 rounded-xl border transition"
                            >
                                <label :for="'edit-param-' + param.id" class="flex items-center gap-2.5 cursor-pointer text-xs font-medium text-slate-800 dark:text-slate-200">
                                    <input
                                        type="checkbox"
                                        v-model="param.checked"
                                        :id="'edit-param-' + param.id"
                                        :disabled="isSubkategoriSelected"
                                        class="rounded text-emerald-600 focus:ring-emerald-500"
                                    />
                                    <span>{{ props.parameter[index]?.nama_parameter }}</span>
                                    <span class="text-[10px] text-slate-400">({{ props.parameter[index]?.satuan || '-' }})</span>
                                </label>

                                <div class="flex items-center gap-2">
                                    <input
                                        v-model="param.baku_mutu"
                                        type="text"
                                        placeholder="Nilai Baku Mutu"
                                        :disabled="!param.checked || isSubkategoriSelected"
                                        class="w-full sm:w-48 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-1.5 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 disabled:bg-slate-100 dark:disabled:bg-slate-800/50 disabled:cursor-not-allowed"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Simpan & Batal -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            @click="router.visit('/pegawai/kategori')"
                            class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white font-bold text-xs py-2.5 px-6 shadow-sm transition disabled:opacity-50 cursor-pointer"
                        >
                            <v-icon size="16">mdi-content-save-check-outline</v-icon>
                            <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
                        </button>
                    </div>
                </form>
            </v-card>
        </div>
    </AdminLayout>
</template>
