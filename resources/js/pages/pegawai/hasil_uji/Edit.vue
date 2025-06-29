<script setup lang="ts">
/* eslint-disable */
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps<{
    hasil_uji: any,
    pengujian: any,
    parameter: {
        id: number,
        nama: string,
        satuan: string | null,
        baku_mutu: string | null,
        nilai: string | null,
        keterangan: string | null,
    }[]
}>()

const form = useForm({
    hasil: props.parameter.map(param => ({
        id_parameter: param.id,
        nilai: param.nilai ?? '',
        keterangan: param.keterangan ?? '',
    }))
})

const submit = () => {
    form.put(route('pegawai.hasil_uji.update', props.hasil_uji.id), {
        onSuccess: () => {
            // Opsional: Log atau tampilkan pesan sukses jika diperlukan
            console.log('Update berhasil!')
        },
        onError: (errors) => {
            // Tampilkan error dari server jika ada
            console.error('Update gagal:', errors)
        }
    })
}
</script>

<template>

    <Head title="Edit Hasil Uji" />
    <AdminLayout>
        <div class="max-w-4xl mx-auto p-6">
            <h1 class="text-2xl font-bold mb-6 text-customDarkGreen">Edit Hasil Uji</h1>
            <div class="bg-white rounded-xl border shadow p-6 mb-6">
                <div class="bg-gray-50 rounded-xl border shadow-lg p-6 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-8">
                        <div>
                            <span class="font-bold text-customDarkGreen">Kode Pengujian:</span>
                            <span class="ml-2">{{ pengujian.kode_pengujian }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Instansi:</span>
                            <span class="ml-2">{{ pengujian.form_pengajuan?.instansi?.nama ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Kategori:</span>
                            <span class="ml-2">{{ pengujian.form_pengajuan?.kategori?.nama ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Teknisi:</span>
                            <span class="ml-2">{{ pengujian.user?.nama ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit">
                    <div class="overflow-x-auto">
                        <table class="min-w-full rounded border mb-6">
                            <thead class="bg-customDarkGreen text-white">
                                <tr>
                                    <th class="px-4 py-2 text-center">No</th>
                                    <th class="px-4 py-2">Parameter</th>
                                    <th class="px-4 py-2 text-center">Nilai</th>
                                    <th class="px-4 py-2 text-center">Satuan</th>
                                    <th class="px-4 py-2 text-center">Baku Mutu</th>
                                    <th class="px-4 py-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(param, idx) in parameter" :key="param.id">
                                    <td class="px-4 py-2 text-center">{{ idx + 1 }}</td>
                                    <td class="px-4 py-2">{{ param.nama }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <input v-model="form.hasil[idx].nilai" type="text"
                                            class="border rounded px-2 py-1 w-24 text-center"
                                            :placeholder="param.satuan ?? ''" />
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ param.satuan ?? '-' }}</td>
                                    <td class="px-4 py-2 text-center">{{ param.baku_mutu ?? '-' }}</td>
                                    <td class="px-4 py-2">
                                        <input v-model="form.hasil[idx].keterangan" type="text"
                                            class="border rounded px-2 py-1 w-full" placeholder="Keterangan" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end gap-2">
                        <Link :href="route('pegawai.hasil_uji.index')"
                            class="bg-gray-200 text-black px-4 py-2 rounded font-semibold">Batal</Link>
                        <button type="submit" class="bg-customDarkGreen text-white px-4 py-2 rounded font-semibold"
                            :disabled="form.processing">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>