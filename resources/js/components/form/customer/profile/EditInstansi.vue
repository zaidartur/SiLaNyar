<script setup lang="ts">
/* eslint-disable */
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    modelValue: boolean;
    instansi: any | null;
}>();

const emit = defineEmits(['update:modelValue', 'submit']);

const form = useForm({
    id: null,
    nama: '',
    tipe: '',
    alamat: '',
    wilayah: '',
    desa_kelurahan: '',
    email: '',
    no_telepon: '',
    posisi_jabatan: '',
    departemen_divisi: '',
    surat_keterangan_penugasan: null,
    foto_kartu_identitas: null,
});

// Frontend validation errors
const validationErrors = ref<Record<string, string>>({});
const isValidating = ref(false);

// Validation functions
const validateField = (fieldName: string, value: any): string | null => {
    switch (fieldName) {
        case 'nama':
            if (!value || value.trim() === '') return 'Nama instansi wajib diisi';
            if (value.length > 255) return 'Nama instansi maksimal 255 karakter';
            break;

        case 'tipe':
            if (!value || value.trim() === '') return 'Tipe instansi wajib diisi';
            if (!['swasta', 'pemerintahan', 'pribadi'].includes(value)) return 'Tipe instansi tidak valid';
            break;

        case 'alamat':
            if (!value || value.trim() === '') return 'Alamat wajib diisi';
            if (value.length > 255) return 'Alamat maksimal 255 karakter';
            break;

        case 'wilayah':
            if (!value || value.trim() === '') return 'Wilayah wajib diisi';
            if (value.length > 255) return 'Wilayah maksimal 255 karakter';
            break;

        case 'desa_kelurahan':
            if (!value || value.trim() === '') return 'Desa/Kelurahan wajib diisi';
            if (value.length > 255) return 'Desa/Kelurahan maksimal 255 karakter';
            break;

        case 'email':
            if (!value || value.trim() === '') return 'Email wajib diisi';
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) return 'Format email tidak valid';
            if (value.length > 255) return 'Email maksimal 255 karakter';
            break;

        case 'no_telepon':
            if (!value || value.trim() === '') return 'Nomor telepon wajib diisi';
            const phoneRegex = /^(08|\+62|62)[0-9]{7,13}$/;
            if (!phoneRegex.test(value)) return 'Format nomor telepon tidak valid (contoh: 08123456789)';
            break;

        case 'posisi_jabatan':
            if (!value || value.trim() === '') return 'Posisi jabatan wajib diisi';
            if (value.length > 255) return 'Posisi jabatan maksimal 255 karakter';
            break;

        case 'departemen_divisi':
            if (!value || value.trim() === '') return 'Departemen/Divisi wajib diisi';
            if (value.length > 255) return 'Departemen/Divisi maksimal 255 karakter';
            break;

        case 'surat_keterangan_penugasan':
            // File opsional untuk edit
            if (value && value instanceof File) {
                const suratExt = value.name.split('.').pop()?.toLowerCase();
                if (!suratExt || !['pdf'].includes(suratExt)) return 'File harus berformat PDF';
                const suratSizeInMB = value.size / (1024 * 1024);
                if (suratSizeInMB > 5) return 'Ukuran file maksimal 5MB';
            }
            break;

        case 'foto_kartu_identitas':
            // File opsional untuk edit
            if (value && value instanceof File) {
                const fotoExt = value.name.split('.').pop()?.toLowerCase();
                if (!fotoExt || !['jpeg', 'jpg', 'png'].includes(fotoExt)) {
                    return 'File harus berformat JPEG, JPG, PNG';
                }
                const fotoSizeInMB = value.size / (1024 * 1024);
                if (fotoSizeInMB > 5) return 'Ukuran file maksimal 5MB';
            }
            break;
    }
    return null;
};

// Real-time validation for each field
const validateSingleField = (fieldName: string, value: any) => {
    const error = validateField(fieldName, value);
    if (error) {
        validationErrors.value[fieldName] = error;
    } else {
        delete validationErrors.value[fieldName];
    }
};

// Form is valid when no validation errors exist
const isFormValid = computed(() => {
    const requiredFields = ['nama', 'tipe', 'alamat', 'wilayah', 'desa_kelurahan', 'email', 'no_telepon', 'posisi_jabatan', 'departemen_divisi'];

    // Check if all required fields have values and no validation errors
    const allFieldsValid = requiredFields.every((field) => {
        const value = form[field as keyof typeof form.data];
        return validateField(field, value) === null;
    });

    return Object.keys(validationErrors.value).length === 0 && allFieldsValid;
});


const handleFileChange = (event: Event, fieldName: 'surat_keterangan_penugasan' | 'foto_kartu_identitas') => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0] || null;
    form[fieldName] = file;
    validateSingleField(fieldName, file);
};

const validateAllFields = () => {
    isValidating.value = true;
    const fields = [
        'nama',
        'tipe',
        'alamat',
        'wilayah',
        'desa_kelurahan',
        'email',
        'no_telepon',
        'posisi_jabatan',
        'departemen_divisi',
        'surat_keterangan_penugasan',
        'foto_kartu_identitas',
    ];

    validationErrors.value = {};
    let hasErrors = false;

    fields.forEach((field) => {
        const value = form[field as keyof typeof form.data];
        const error = validateField(field, value);
        if (error) {
            validationErrors.value[field] = error;
            hasErrors = true;
        }
    });

    return !hasErrors;
};

// Watch form fields for real-time validation
const watchField = (fieldName: string) => {
    return computed({
        get: () => form[fieldName as keyof typeof form.data],
        set: (value) => {
            form[fieldName as keyof typeof form.data] = value;
            if (isValidating.value) {
                validateSingleField(fieldName, value);
            }
        },
    });
};

watch(
    () => props.instansi,
    (val) => {
        if (val) {
            form.id = val.id;
            form.nama = val.nama;
            form.tipe = val.tipe;
            form.alamat = val.alamat;
            form.wilayah = val.wilayah;
            form.desa_kelurahan = val.desa_kelurahan;
            form.email = val.email;
            form.no_telepon = val.no_telepon;
            form.posisi_jabatan = val.posisi_jabatan;
            form.departemen_divisi = val.departemen_divisi;

            // Reset validation errors when loading new data
            validationErrors.value = {};
            isValidating.value = false;
        }
    },
    { immediate: true },
);

function close() {
    emit('update:modelValue', false);
}

function submit() {
    if (!validateAllFields()) {
        return;
    }

    form.put(`/customer/profile/instansi/${form.id}/edit`, {
        onSuccess: () => {
            emit('submit');
            close();
        },
        onError: (errors) => {
            // Handle server-side validation errors
            Object.keys(errors).forEach((field) => {
                if (errors[field]) {
                    validationErrors.value[field] = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                }
            });
        },
    });
}
</script>

<template>
    <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" @click.self="close">
        <div class="relative w-full max-w-2xl overflow-hidden rounded-lg bg-white shadow-lg">
            <div class="max-h-[90vh] overflow-y-auto p-6">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Edit Instansi</h2>
                    <button @click="close" class="absolute right-4 top-3 text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Nama Instansi -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700"> Nama Instansi <span class="text-red-500">*</span> </label>
                        <input
                            v-model="watchField('nama').value"
                            type="text"
                            class="mt-1 block w-full rounded-md border p-2 focus:border-transparent focus:ring-2 focus:ring-green-500"
                            :class="{ 'border-red-500': validationErrors.nama }"
                            placeholder="Masukkan nama instansi"
                            @blur="validateSingleField('nama', form.nama)"
                        />
                        <p v-if="validationErrors.nama" class="mt-1 text-sm text-red-600">
                            {{ validationErrors.nama }}
                        </p>
                    </div>

                    <!-- Tipe Instansi -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700"> Jenis Instansi <span class="text-red-500">*</span> </label>
                        <select
                            v-model="watchField('tipe').value"
                            class="mt-1 block w-full rounded-md border p-2 focus:border-transparent focus:ring-2 focus:ring-green-500"
                            :class="{ 'border-red-500': validationErrors.tipe }"
                            @change="validateSingleField('tipe', form.tipe)"
                        >
                            <option value="" disabled>Pilih jenis instansi</option>
                            <option value="pemerintahan">Pemerintahan</option>
                            <option value="swasta">Swasta</option>
                            <option value="pribadi">Pribadi</option>
                        </select>
                        <p v-if="validationErrors.tipe" class="mt-1 text-sm text-red-600">
                            {{ validationErrors.tipe }}
                        </p>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700"> Alamat Instansi <span class="text-red-500">*</span> </label>
                        <textarea
                            v-model="watchField('alamat').value"
                            class="mt-1 block w-full rounded-md border p-2 focus:border-transparent focus:ring-2 focus:ring-green-500"
                            :class="{ 'border-red-500': validationErrors.alamat }"
                            rows="3"
                            placeholder="Masukkan alamat lengkap"
                            @blur="validateSingleField('alamat', form.alamat)"
                        ></textarea>
                        <p v-if="validationErrors.alamat" class="mt-1 text-sm text-red-600">
                            {{ validationErrors.alamat }}
                        </p>
                    </div>

                    <!-- Wilayah -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700"> Wilayah <span class="text-red-500">*</span> </label>
                        <input
                            v-model="watchField('wilayah').value"
                            type="text"
                            class="mt-1 block w-full rounded-md border p-2 focus:border-transparent focus:ring-2 focus:ring-green-500"
                            :class="{ 'border-red-500': validationErrors.wilayah }"
                            placeholder="Masukkan wilayah"
                            @blur="validateSingleField('wilayah', form.wilayah)"
                        />
                        <p v-if="validationErrors.wilayah" class="mt-1 text-sm text-red-600">
                            {{ validationErrors.wilayah }}
                        </p>
                    </div>

                    <!-- Desa/Kelurahan -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700"> Desa/Kelurahan <span class="text-red-500">*</span> </label>
                        <input
                            v-model="watchField('desa_kelurahan').value"
                            type="text"
                            class="mt-1 block w-full rounded-md border p-2 focus:border-transparent focus:ring-2 focus:ring-green-500"
                            :class="{ 'border-red-500': validationErrors.desa_kelurahan }"
                            placeholder="Masukkan desa/kelurahan"
                            @blur="validateSingleField('desa_kelurahan', form.desa_kelurahan)"
                        />
                        <p v-if="validationErrors.desa_kelurahan" class="mt-1 text-sm text-red-600">
                            {{ validationErrors.desa_kelurahan }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700"> Email Instansi <span class="text-red-500">*</span> </label>
                        <input
                            v-model="watchField('email').value"
                            type="email"
                            class="mt-1 block w-full rounded-md border p-2 focus:border-transparent focus:ring-2 focus:ring-green-500"
                            :class="{ 'border-red-500': validationErrors.email }"
                            placeholder="contoh@email.com"
                            @blur="validateSingleField('email', form.email)"
                        />
                        <p v-if="validationErrors.email" class="mt-1 text-sm text-red-600">
                            {{ validationErrors.email }}
                        </p>
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Nomor Telepon Instansi <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="watchField('no_telepon').value"
                            type="tel"
                            class="mt-1 block w-full rounded-md border p-2 focus:border-transparent focus:ring-2 focus:ring-green-500"
                            :class="{ 'border-red-500': validationErrors.no_telepon }"
                            placeholder="08123456789"
                            @blur="validateSingleField('no_telepon', form.no_telepon)"
                        />
                        <p v-if="validationErrors.no_telepon" class="mt-1 text-sm text-red-600">
                            {{ validationErrors.no_telepon }}
                        </p>
                    </div>

                    <!-- Posisi Jabatan -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700"> Posisi/Jabatan Anda <span class="text-red-500">*</span> </label>
                        <input
                            v-model="watchField('posisi_jabatan').value"
                            type="text"
                            class="mt-1 block w-full rounded-md border p-2 focus:border-transparent focus:ring-2 focus:ring-green-500"
                            :class="{ 'border-red-500': validationErrors.posisi_jabatan }"
                            placeholder="Masukkan posisi jabatan"
                            @blur="validateSingleField('posisi_jabatan', form.posisi_jabatan)"
                        />
                        <p v-if="validationErrors.posisi_jabatan" class="mt-1 text-sm text-red-600">
                            {{ validationErrors.posisi_jabatan }}
                        </p>
                    </div>

                    <!-- Departemen/Divisi -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700"> Departemen/Divisi <span class="text-red-500">*</span> </label>
                        <input
                            v-model="watchField('departemen_divisi').value"
                            type="text"
                            class="mt-1 block w-full rounded-md border p-2 focus:border-transparent focus:ring-2 focus:ring-green-500"
                            :class="{ 'border-red-500': validationErrors.departemen_divisi }"
                            placeholder="Masukkan departemen/divisi"
                            @blur="validateSingleField('departemen_divisi', form.departemen_divisi)"
                        />
                        <p v-if="validationErrors.departemen_divisi" class="mt-1 text-sm text-red-600">
                            {{ validationErrors.departemen_divisi }}
                        </p>
                    </div>

                    <!-- Dokumen Pendukung -->
                    <div>
                        <h3 class="mb-2 text-lg font-semibold text-gray-700">Dokumen Pendukung</h3>
                        <div class="space-y-4">
                            <!-- Surat Keterangan Penugasan -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700"> Surat Keterangan Penugasan </label>
                                <input
                                    type="file"
                                    accept="application/pdf"
                                    class="mt-1 block w-full rounded-sm border p-2 focus:border-transparent focus:ring-2 focus:ring-green-500"
                                    :class="{ 'border-red-500': validationErrors.surat_keterangan_penugasan }"
                                    @change="handleFileChange($event, 'surat_keterangan_penugasan')"
                                />
                                <p v-if="validationErrors.surat_keterangan_penugasan" class="mt-1 text-sm text-red-600">
                                    {{ validationErrors.surat_keterangan_penugasan }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">Format PDF, maksimal 5MB. Kosongkan jika tidak ingin mengubah.</p>
                            </div>

                            <!-- Foto Kartu Identitas -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700"> Foto Kartu Identitas Instansi </label>
                                <input
                                    type="file"
                                    accept="image/png,image/jpeg,application/pdf"
                                    class="mt-1 block w-full rounded-sm border p-2 focus:border-transparent focus:ring-2 focus:ring-green-500"
                                    :class="{ 'border-red-500': validationErrors.foto_kartu_identitas }"
                                    @change="handleFileChange($event, 'foto_kartu_identitas')"
                                />
                                <p v-if="validationErrors.foto_kartu_identitas" class="mt-1 text-sm text-red-600">
                                    {{ validationErrors.foto_kartu_identitas }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    Format JPG, PNG, atau PDF, maksimal 5MB. Kosongkan jika tidak ingin mengubah.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="close" class="rounded-md border border-gray-300 px-4 py-2 text-gray-600 hover:bg-gray-100">
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing || !isFormValid"
                            class="rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
