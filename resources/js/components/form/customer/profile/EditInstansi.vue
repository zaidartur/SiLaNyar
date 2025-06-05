<script setup lang="ts">
import {  watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps<{
    modelValue: boolean,
    instansi: any | null
}>()

const emit = defineEmits(['update:modelValue', 'submit'])

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
})

watch(
    () => props.instansi,
    (val) => {
        if (val) {
            form.id = val.id
            form.nama = val.nama
            form.tipe = val.tipe
            form.alamat = val.alamat
            form.wilayah = val.wilayah
            form.desa_kelurahan = val.desa_kelurahan
            form.email = val.email
            form.no_telepon = val.no_telepon
            form.posisi_jabatan = val.posisi_jabatan
            form.departemen_divisi = val.departemen_divisi
        }
    },
    { immediate: true }
)

function close() {
    emit('update:modelValue', false)
}

function submit() {
    form.put(`/customer/profile/instansi/${form.id}/edit`, {
        onSuccess: () => {
            emit('submit')
            close()
        }
    })
}
</script>

<template>
    <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
        @click.self="close">
        <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg relative overflow-hidden">
            <div class="max-h-[90vh] overflow-y-auto p-6">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Edit Instansi</h2>
                    <button @click="close" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Instansi</label>
                        <input v-model="form.nama" type="text" class="mt-1 block w-full border rounded-md p-2"
                            required />
                        <span v-if="form.errors.nama" class="text-sm text-red-600">{{ form.errors.nama }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Instansi</label>
                        <select v-model="form.tipe" class="mt-1 block w-full border rounded-md p-2" required>
                            <option value="" disabled>Pilih jenis instansi</option>
                            <option value="pemerintahan">Pemerintah</option>
                            <option value="swasta">Swasta</option>
                            <option value="pribadi">Perorangan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat Instansi</label>
                        <textarea v-model="form.alamat" class="mt-1 block w-full border rounded-md p-2"
                            rows="3"></textarea>
                        <span v-if="form.errors.alamat" class="text-sm text-red-600">{{ form.errors.alamat }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Wilayah</label>
                        <input v-model="form.wilayah" type="text" class="mt-1 block w-full border rounded-md p-2" />
                        <span v-if="form.errors.wilayah" class="text-sm text-red-600">{{ form.errors.wilayah }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Desa/Kelurahan</label>
                        <input v-model="form.desa_kelurahan" type="text"
                            class="mt-1 block w-full border rounded-md p-2" />
                        <span v-if="form.errors.desa_kelurahan" class="text-sm text-red-600">{{
                            form.errors.desa_kelurahan }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Instansi</label>
                        <input v-model="form.email" type="email" class="mt-1 block w-full border rounded-md p-2" />
                        <span v-if="form.errors.email" class="text-sm text-red-600">{{ form.errors.email }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon Instansi</label>
                        <input v-model="form.no_telepon" type="tel" class="mt-1 block w-full border rounded-md p-2" />
                        <span v-if="form.errors.no_telepon" class="text-sm text-red-600">{{ form.errors.no_telepon
                            }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Posisi/Jabatan Anda</label>
                        <input v-model="form.posisi_jabatan" type="text"
                            class="mt-1 block w-full border rounded-md p-2" />
                        <span v-if="form.errors.posisi_jabatan" class="text-sm text-red-600">{{
                            form.errors.posisi_jabatan }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Departemen/Divisi</label>
                        <input v-model="form.departemen_divisi" type="text"
                            class="mt-1 block w-full border rounded-md p-2" />
                        <span v-if="form.errors.departemen_divisi" class="text-sm text-red-600">{{
                            form.errors.departemen_divisi }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Dokumen Pendukung</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Surat Keterangan Penugasan
                                </label>
                                <input type="file" accept="application/pdf"
                                    @change="e => form.surat_keterangan_penugasan = e.target.files[0]"
                                    class="mt-1 block w-full border rounded-sm p-2" />
                                <span v-if="form.errors.surat_keterangan_penugasan" class="text-sm text-red-600">
                                    {{ form.errors.surat_keterangan_penugasan }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">Format PDF, maksimal 2MB. Kosongkan jika tidak
                                    ingin mengubah.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Foto Kartu Identitas Instansi
                                </label>
                                <input type="file" accept="image/png,image/jpeg,application/pdf"
                                    @change="e => form.foto_kartu_identitas = e.target.files[0]"
                                    class="mt-1 block w-full border rounded-sm p-2" />
                                <span v-if="form.errors.foto_kartu_identitas" class="text-sm text-red-600">
                                    {{ form.errors.foto_kartu_identitas }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">Format JPG, PNG, atau PDF, maksimal 2MB. Kosongkan
                                    jika tidak ingin mengubah.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="close"
                            class="px-4 py-2 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>