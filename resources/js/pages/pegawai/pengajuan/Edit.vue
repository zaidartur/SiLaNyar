<template>
    <AdminLayout>
        <div class="container mx-auto px-4 py-6">
            <div class="rounded-lg bg-white p-6 shadow-md">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">Edit Pengajuan</h1>
                    <Link :href="route('pegawai.pengajuan.index')" class="rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600"> Kembali </Link>
                </div>

                <!-- Pengajuan Details -->
                <div class="mb-6 rounded bg-gray-50 p-4">
                    <h3 class="mb-2 font-semibold">Detail Pengajuan</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><strong>Instansi:</strong> {{ pengajuan.instansi.user.name }}</div>
                        <div><strong>Email:</strong> {{ pengajuan.instansi.user.email }}</div>
                        <div><strong>Alamat:</strong> {{ pengajuan.alamat_pengambilan }}</div>
                        <div><strong>Metode Pengambilan:</strong> {{ pengajuan.metode_pengambilan }}</div>
                        <div><strong>Jenis Cairan:</strong> {{ pengajuan.jenis_cairan?.nama || '-' }}</div>
                        <div>
                            <strong>Status Saat Ini:</strong>
                            <span
                                :class="{
                                    'text-yellow-600': pengajuan.status_pengajuan === 'diproses',
                                    'text-green-600': pengajuan.status_pengajuan === 'diterima',
                                    'text-red-600': pengajuan.status_pengajuan === 'ditolak',
                                }"
                            >
                                {{ pengajuan.status_pengajuan }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <form @submit.prevent="submit">
                    <!-- Status Selection with Buttons -->
                    <div class="mb-6">
                        <label class="mb-3 block text-sm font-medium text-gray-700">Status Pengajuan *</label>
                        <div class="flex gap-4">
                            <button
                                type="button"
                                @click="form.status_pengajuan = 'diterima'"
                                :class="[
                                    'rounded-lg px-6 py-3 font-medium transition-colors',
                                    form.status_pengajuan === 'diterima'
                                        ? 'bg-green-500 text-white shadow-lg'
                                        : 'bg-gray-200 text-gray-700 hover:bg-green-100',
                                ]"
                            >
                                ✓ Terima
                            </button>
                            <button
                                type="button"
                                @click="form.status_pengajuan = 'ditolak'"
                                :class="[
                                    'rounded-lg px-6 py-3 font-medium transition-colors',
                                    form.status_pengajuan === 'ditolak'
                                        ? 'bg-red-500 text-white shadow-lg'
                                        : 'bg-gray-200 text-gray-700 hover:bg-red-100',
                                ]"
                            >
                                ✗ Tolak
                            </button>
                        </div>
                        <div v-if="errors.status_pengajuan" class="mt-2 text-sm text-red-500">
                            {{ errors.status_pengajuan }}
                        </div>
                    </div>

                    <!-- Existing Customer Data Display -->
                    <div v-if="pengajuan.kategori || pengajuan.parameter?.length > 0" class="mb-6 rounded bg-blue-50 p-4">
                        <h3 class="mb-3 font-semibold text-blue-800">Data Asli yang Dipilih Customer</h3>
                        <div class="grid grid-cols-1 gap-3 text-sm">
                            <div v-if="pengajuan.kategori"><strong class="text-blue-700">Kategori Asli:</strong> {{ pengajuan.kategori.nama }}</div>
                            <div v-if="pengajuan.parameter?.length > 0">
                                <strong class="text-blue-700">Parameter Asli yang Dipilih:</strong>
                                <ul class="ml-4 mt-1 list-disc">
                                    <li v-for="param in pengajuan.parameter" :key="param.id">
                                        {{ param.nama_parameter }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-3 text-xs text-blue-600">
                            <strong>Catatan:</strong> Anda dapat mengubah kategori dan parameter di bawah ini sesuai kebutuhan.
                        </div>
                    </div>

                    <!-- Additional Fields for Accepted Diantar Submissions -->
                    <div v-if="showAdditionalFields" class="space-y-4">
                        <!-- Category Selection -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Kategori *
                                <span class="text-sm font-normal text-gray-500">(Dapat diubah)</span>
                            </label>
                            <select
                                v-model="form.id_kategori"
                                @change="onKategoriChange"
                                class="w-full rounded border border-gray-300 p-2 hover:border-blue-400 focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="">Pilih Kategori</option>
                                <option v-for="kategori in kategoriList" :key="kategori.id" :value="kategori.id">
                                    {{ kategori.nama }}
                                    <span v-if="pengajuan.kategori && pengajuan.kategori.id === kategori.id"> (Pilihan Customer) </span>
                                </option>
                            </select>
                            <div v-if="errors.id_kategori" class="mt-1 text-sm text-red-500">
                                {{ errors.id_kategori }}
                            </div>
                        </div>

                        <!-- Parameter Selection -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Parameter Uji *
                                <span class="text-sm font-normal text-gray-500"
                                    >(Parameter yang tidak tersedia untuk kategori ini akan dinonaktifkan)</span
                                >
                            </label>
                            <div class="max-h-48 overflow-y-auto rounded border border-gray-300 p-2 hover:border-blue-400">
                                <div v-if="parameterList.length === 0" class="text-sm text-gray-500">Memuat parameter...</div>
                                <div v-for="parameter in parameterList" :key="parameter.id" class="mb-2 flex items-center">
                                    <input
                                        type="checkbox"
                                        :id="`param-${parameter.id}`"
                                        :value="parameter.id"
                                        v-model="form.parameter"
                                        :disabled="!isParameterAvailable(parameter.id) || !form.id_kategori"
                                        class="mr-2 cursor-pointer disabled:cursor-not-allowed disabled:opacity-50"
                                    />
                                    <label
                                        :for="`param-${parameter.id}`"
                                        class="flex-1 cursor-pointer text-sm"
                                        :class="{
                                            'text-gray-400': !isParameterAvailable(parameter.id) || !form.id_kategori,
                                            'cursor-not-allowed': !isParameterAvailable(parameter.id) || !form.id_kategori,
                                        }"
                                    >
                                        {{ parameter.nama_parameter }}

                                        <!-- Original customer selection indicator -->
                                        <span
                                            v-if="isOriginalParameter(parameter.id)"
                                            class="ml-2 inline-block rounded bg-blue-100 px-2 py-1 text-xs text-blue-700"
                                        >
                                            Dipilih Customer
                                        </span>

                                        <!-- Available/Not available indicator -->
                                        <span
                                            v-if="form.id_kategori"
                                            :class="[
                                                'ml-2 inline-block rounded px-2 py-1 text-xs',
                                                isParameterAvailable(parameter.id) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700',
                                            ]"
                                        >
                                            {{ isParameterAvailable(parameter.id) ? 'Tersedia' : 'Tidak Tersedia' }}
                                        </span>

                                        <!-- No category selected indicator -->
                                        <span v-if="!form.id_kategori" class="ml-2 inline-block rounded bg-gray-100 px-2 py-1 text-xs text-gray-500">
                                            Pilih kategori dulu
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div v-if="errors.parameter" class="mt-1 text-sm text-red-500">
                                {{ errors.parameter }}
                            </div>
                            <div class="mt-2 text-xs text-gray-600">
                                <strong>Total parameter dipilih:</strong> {{ form.parameter.length }}
                                <span v-if="form.id_kategori" class="ml-4">
                                    <strong>Tersedia untuk kategori ini:</strong> {{ availableParameterIds.length }}
                                </span>
                            </div>
                        </div>

                        <!-- Show changes summary -->
                        <div v-if="hasChanges" class="rounded border border-yellow-200 bg-yellow-50 p-3">
                            <h4 class="mb-2 font-medium text-yellow-800">Perubahan yang Akan Diterapkan:</h4>
                            <div class="space-y-1 text-sm text-yellow-700">
                                <div v-if="kategoriChanged">
                                    <strong>Kategori:</strong>
                                    <span class="line-through">{{ pengajuan.kategori?.nama }}</span>
                                    → {{ selectedKategoriName }}
                                </div>
                                <div v-if="parameterChanged">
                                    <strong>Parameter:</strong>
                                    <span v-if="addedParameters.length > 0"> Ditambah: {{ addedParameters.join(', ') }} </span>
                                    <span v-if="removedParameters.length > 0" class="block"> Dihapus: {{ removedParameters.join(', ') }} </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex justify-end">
                        <button
                            type="submit"
                            :disabled="form.processing || !isFormValid"
                            class="rounded bg-blue-500 px-6 py-2 text-white hover:bg-blue-600 disabled:bg-blue-300"
                        >
                            {{ form.processing ? 'Memproses...' : 'Update Pengajuan' }}
                        </button>
                    </div>

                    <!-- Debug info -->
                    <!-- <div v-if="showDebugInfo" class="mt-4 rounded bg-gray-100 p-3 text-xs">
                        <h4 class="font-semibold">Debug Info:</h4>
                        <div>Status: {{ form.status_pengajuan }}</div>
                        <div>Metode Pengambilan: {{ props.pengajuan.metode_pengambilan }}</div>
                        <div>Show Additional Fields: {{ showAdditionalFields }}</div>
                        <div>Kategori: {{ form.id_kategori }}</div>
                        <div>Parameter Count: {{ form.parameter.length }}</div>
                        <div>Form Valid: {{ isFormValid }}</div>
                    </div> -->
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    pengajuan: Object,
    kategoriList: Array,
    parameterList: Array,
    errors: Object,
});

const form = useForm({
    status_pengajuan: '',
    id_kategori: props.pengajuan.id_kategori || '',
    parameter: props.pengajuan.parameter?.map((p) => p.id) || [],
});

// Debug flag - you can remove this later
const showDebugInfo = ref(true);

const availableParameterIds = ref([]);

// Store original customer selections for comparison
const originalKategoriId = props.pengajuan.kategori?.id;
const originalParameterIds = props.pengajuan.parameter?.map((p) => p.id) || [];

// Form validation
const isFormValid = computed(() => {
    if (!form.status_pengajuan) {
        return false;
    }

    if (form.status_pengajuan === 'ditolak') {
        return true;
    }

    if (form.status_pengajuan === 'diterima') {
        if (props.pengajuan.metode_pengambilan === 'diantar') {
            return form.id_kategori && form.parameter.length > 0;
        }
        return true;
    }

    return false;
});

// Computed properties for existing data indicators
const hasExistingKategori = computed(() => {
    return props.pengajuan.kategori && props.pengajuan.kategori.id;
});

const hasExistingParameter = computed(() => {
    return props.pengajuan.parameter && props.pengajuan.parameter.length > 0;
});

// Check if a parameter is available for the selected category
const isParameterAvailable = (parameterId) => {
    return availableParameterIds.value.includes(parameterId);
};

// Check if a parameter was originally selected by customer
const isOriginalParameter = (parameterId) => {
    return originalParameterIds.includes(parameterId);
};

// Show additional fields only for accepted "diantar" submissions
const showAdditionalFields = computed(() => {
    return form.status_pengajuan === 'diterima' && props.pengajuan.metode_pengambilan === 'diantar';
});

// Check if there are changes from original selections
const hasChanges = computed(() => {
    return kategoriChanged.value || parameterChanged.value;
});

const kategoriChanged = computed(() => {
    return form.id_kategori != originalKategoriId;
});

const parameterChanged = computed(() => {
    const currentParams = form.parameter.sort();
    const originalParams = originalParameterIds.sort();
    return JSON.stringify(currentParams) !== JSON.stringify(originalParams);
});

const selectedKategoriName = computed(() => {
    const kategori = props.kategoriList.find((k) => k.id == form.id_kategori);
    return kategori?.nama || '';
});

const addedParameters = computed(() => {
    const added = form.parameter.filter((id) => !originalParameterIds.includes(id));
    return added
        .map((id) => {
            const param = props.parameterList.find((p) => p.id === id);
            return param?.nama_parameter || '';
        })
        .filter((name) => name);
});

const removedParameters = computed(() => {
    const removed = originalParameterIds.filter((id) => !form.parameter.includes(id));
    return removed
        .map((id) => {
            const param = props.pengajuan.parameter?.find((p) => p.id === id);
            return param?.nama_parameter || '';
        })
        .filter((name) => name);
});

// Update available parameters when category changes
const onKategoriChange = () => {
    const selectedKategori = props.kategoriList.find((k) => k.id == form.id_kategori);

    if (selectedKategori) {
        let parameterIds = [];

        // Add direct parameters from category
        if (selectedKategori.parameter) {
            parameterIds = [...selectedKategori.parameter.map((p) => p.id)];
        }

        // Add parameters from subcategories
        if (selectedKategori.subkategori) {
            selectedKategori.subkategori.forEach((sub) => {
                if (sub.parameter) {
                    parameterIds = [...parameterIds, ...sub.parameter.map((p) => p.id)];
                }
            });
        }

        // Remove duplicates and store available parameter IDs
        availableParameterIds.value = [...new Set(parameterIds)];
    } else {
        availableParameterIds.value = [];
    }

    // Remove disabled parameters from selection
    form.parameter = form.parameter.filter((id) => availableParameterIds.value.includes(id));
};

// Initialize available parameters if category is already selected
if (form.id_kategori) {
    onKategoriChange();
    // Restore original parameter selection only for available parameters
    const availableOriginalParams = originalParameterIds.filter((id) => availableParameterIds.value.includes(id));
    form.parameter = [...availableOriginalParams];
}

// Watch for status changes
watch(
    () => form.status_pengajuan,
    (newStatus) => {
        console.log('Status changed to:', newStatus); // Debug log

        if (newStatus === 'diterima' && props.pengajuan.metode_pengambilan === 'diantar') {
            // When accepting "diantar" submission, load existing customer data as starting point
            if (hasExistingKategori.value) {
                form.id_kategori = originalKategoriId;
                onKategoriChange();
                // Restore original parameters after category change (only available ones)
                setTimeout(() => {
                    const availableOriginalParams = originalParameterIds.filter((id) => availableParameterIds.value.includes(id));
                    form.parameter = [...availableOriginalParams];
                }, 0);
            }
        } else if (newStatus === 'ditolak') {
            // Reset fields when rejecting
            form.id_kategori = '';
            form.parameter = [];
            availableParameterIds.value = [];
        }
    },
);

const submit = () => {
    console.log('Submitting form with data:', form.data()); // Debug log
    form.put(route('pegawai.pengajuan.update', props.pengajuan.id));
};
</script>
