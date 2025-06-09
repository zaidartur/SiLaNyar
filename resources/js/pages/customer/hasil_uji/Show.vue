<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import { Head } from '@inertiajs/vue3'

interface ParameterPengujian {
    id_parameter: number
    nama_parameter: string
    satuan: string | null
    nilai: string | null
    baku_mutu: string | null
    keterangan: string | null
}

interface HasilUji {
    id: number
    status: string
    created_at: string
    pengujian: {
        kode_pengujian: string
        form_pengajuan: {
            kode_pengajuan: string
            kategori: {
                nama: string
            }
            instansi: {
                nama: string
                user: {
                    nama: string
                }
            }
        }
        user: {
            nama: string
        }
    }
}

const props = defineProps<{
    hasil_uji: HasilUji
    parameter_pengujian: ParameterPengujian[]
}>()

const statusLabels = {
    draf: 'Draf',
    revisi: 'Revisi',
    proses_review: 'Proses Review',
    proses_peresmian: 'Proses Peresmian',
    selesai: 'Selesai'
}

function kembali() {
    window.history.back()
}
</script>

<template>
    <CustomerLayout>
        <div class="bg-gray-200 min-h-screen py-8">

            <Head title="Detail Hasil Uji" />

            <div class="max-w-4xl mx-auto py-8 space-y-8">
                <h1
                    class="text-3xl font-bold border-b-2 border-customDarkGreen inline-block w-fit pb-2 mb-1 text-customDarkGreen">
                    Detail Hasil Uji
                </h1>

                <!-- Informasi Umum -->
                <div class="bg-gray-50 rounded-xl border shadow-lg p-6 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-8">
                        <div>
                            <span class="font-bold text-customDarkGreen">Kode Hasil Uji:</span>
                            <span class="ml-2">HU-{{ hasil_uji.id.toString().padStart(4, '0') }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Kode Pengujian:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.kode_pengujian }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Status:</span>
                            <span class="ml-2">{{ statusLabels[hasil_uji.status] ?? hasil_uji.status }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Tanggal Dibuat:</span>
                            <span class="ml-2">{{ new Date(hasil_uji.created_at).toLocaleString() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pengajuan -->
                <div class="bg-gray-50 rounded-xl border shadow-lg p-6 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-8">
                        <div>
                            <span class="font-bold text-customDarkGreen">Kode Pengajuan:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.kode_pengajuan }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Instansi:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.instansi.nama }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Penanggung Jawab Instansi:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.instansi.user.nama }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Kategori:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.kategori.nama }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Teknisi:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.user.nama }}</span>
                        </div>
                    </div>
                </div>

                <!-- Parameter Pengujian -->
                <div class="bg-white rounded-xl border shadow p-6">
                    <h2 class="text-xl font-semibold mb-4 text-customDarkGreen">Parameter Pengujian</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-gray-200 rounded">
                            <thead class="bg-customDarkGreen text-white">
                                <tr>
                                    <th class="border px-4 py-2">No</th>
                                    <th class="border px-4 py-2">Parameter</th>
                                    <th class="border px-4 py-2">Nilai</th>
                                    <th class="border px-4 py-2">Satuan</th>
                                    <th class="border px-4 py-2">Baku Mutu</th>
                                    <th class="border px-4 py-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in parameter_pengujian" :key="item.id_parameter"
                                    class="hover:bg-gray-50">
                                    <td class="border px-4 py-2 text-center">{{ index + 1 }}</td>
                                    <td class="border px-4 py-2">{{ item.nama_parameter }}</td>
                                    <td class="border px-4 py-2">{{ item.nilai ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ item.satuan ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ item.baku_mutu ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ item.keterangan ?? '-' }}</td>
                                </tr>
                                <tr v-if="parameter_pengujian.length === 0">
                                    <td class="border px-4 py-2 text-center" colspan="6">Tidak ada parameter.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex gap-2 justify-end">
                    <button @click="kembali"
                        class="bg-gray-200 text-black px-4 py-2 rounded font-semibold">Kembali</button>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>