<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const emit = defineEmits(['close'])

const form = useForm({
    nama: '',
    tipe: '',
    alamat: '',
    wilayah: '',
    desa: '',
    email: '',
    telepon: '',
    posisi: '',
    departemen: '',
    surat_penugasan: null,
    kartu_identitas: null,
})

const submitForm = () => {
    form.post('/customer/instansi', {
        forceFormData: true,
        onSuccess: () => emit('close'),
        onError: (errors) => console.error('Gagal menyimpan:', errors),
    })
}

const closeModal = () => {
    emit('close')
}
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" @click.self="closeModal">
        <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg relative overflow-hidden">
            <div class="max-h-[90vh] overflow-y-auto p-6">
                <!-- Header -->
                <div class=" mb-4">
                <h2 class="text-xl font-bold text-gray-800">Tambah Instansi</h2>
                <p class="text-sm text-gray-500">Silakan isi informasi instansi di bawah ini.</p>
                <button @click="closeModal" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <!-- Form -->
            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Informasi Instansi -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Informasi Instansi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Instansi</label>
                            <input v-model="form.nama" type="text" placeholder="Masukan nama instansi" class="mt-1 block w-full border rounded-md p-2"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Instansi</label>
                            <select v-model="form.tipe" class="mt-1 block w-full border rounded-md p-2" required>
                                <option value="" disabled>Pilih jenis instansi</option>
                                <option value="pemerintah">Pemerintah</option>
                                <option value="swasta">Swasta</option>
                                <option value="perorangan">Perorangan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat Instansi</label>
                            <textarea v-model="form.alamat" placeholder="Masukan alamat lengkap instansi" class="mt-1 block w-full border rounded-md p-2"
                                rows="3"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Wilayah</label>
                            <input v-model="form.wilayah" type="text" placeholder="Kabupaten Karanganyar" class="mt-1 block w-full border rounded-md p-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Desa/Kelurahan</label>
                            <input v-model="form.desa" type="text" placeholder="Pilih desa dan kelurahan" class="mt-1 block w-full border rounded-md p-2" />
                        </div>
                    </div>
                </div>

                <!-- Kontak Instansi -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Kontak Instansi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Instansi</label>
                            <input v-model="form.email" type="email" placeholder="Email instansi" class="mt-1 block w-full border rounded-md p-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Telepon Instansi</label>
                            <input v-model="form.telepon" type="tel" placeholder="Contoh: 081123456789" class="mt-1 block w-full border rounded-md p-2" />
                        </div>
                    </div>
                </div>

                <!-- Informasi Posisi -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Informasi Posisi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Posisi/Jabatan Anda</label>
                            <input v-model="form.posisi" type="text" placeholder="Contoh: Administrator, Staff Laboratorium" class="mt-1 block w-full border rounded-md p-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Departemen/Divisi</label>
                            <input v-model="form.departemen" type="text"
                                class="mt-1 block w-full border rounded-md p-2" />
                            <p class="text-xs italic text-gray-500 mt-1">Opsional - Isi jika berlaku di instansi Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Pendukung -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Dokumen Pendukung</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Surat Keterangan
                                Penugasan</label>
                            <input type="file" @change="e => form.surat_penugasan = e.target.files[0]"
                                class="mt-1 block w-full border rounded-sm p-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Foto Kartu Identitas
                                Instansi</label>
                            <input type="file" @change="e => form.kartu_identitas = e.target.files[0]"
                                class="mt-1 block w-full border rounded-sm p-2" />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="closeModal"
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