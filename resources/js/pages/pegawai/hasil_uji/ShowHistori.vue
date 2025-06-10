<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const { histori, data_parameter } = defineProps<{ histori: any, data_parameter: any[] }>()

const formatTanggal = (tanggalStr: string) => {
    if (!tanggalStr) return '-'
    const date = new Date(tanggalStr)
    return date.toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<template>

    <Head title="Detail Histori Hasil Uji" />
    <AdminLayout>
        <pre>{{ JSON.stringify(data_parameter, null, 2) }}</pre>
        <div class="max-w-4xl mx-auto p-6">
            <h1 class="text-2xl font-bold mb-4 text-customDarkGreen">Detail Histori Hasil Uji</h1>
            <div class="bg-white rounded-xl border shadow p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-8">
                    <div>
                        <span class="font-bold text-customDarkGreen">Status:</span>
                        <span class="ml-2 capitalize">{{ histori.status }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Tanggal Update:</span>
                        <span class="ml-2">{{ formatTanggal(histori.created_at) }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Diperbarui Oleh:</span>
                        <span class="ml-2">{{ histori.diupdate_oleh ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Kode Hasil Uji:</span>
                        <span class="ml-2">HU-{{ histori.hasil_uji?.id?.toString().padStart(4, '0') ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Kode Pengujian:</span>
                        <span class="ml-2">{{ histori.hasil_uji?.pengujian?.kode_pengujian ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Instansi:</span>
                        <span class="ml-2">{{ histori.hasil_uji?.pengujian?.form_pengajuan?.instansi?.nama ?? '-'
                            }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Kategori:</span>
                        <span class="ml-2">{{ histori.hasil_uji?.pengujian?.form_pengajuan?.kategori?.nama ?? '-'
                            }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Teknisi:</span>
                        <span class="ml-2">{{ histori.hasil_uji?.pengujian?.user?.nama ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border shadow p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4 text-customDarkGreen">Parameter Pengujian</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full rounded border">
                        <thead class="bg-customDarkGreen text-white">
                            <tr>
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Parameter</th>
                                <th class="px-4 py-2">Nilai</th>
                                <th class="px-4 py-2">Baku Mutu</th>
                                <th class="px-4 py-2">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!data_parameter || data_parameter.length === 0">
                                <td colspan="5" class="py-4 text-center text-gray-400">Tidak ada data parameter.</td>
                            </tr>
                            <tr v-for="(param, idx) in data_parameter" :key="idx">
                                <td class="px-4 py-2 text-center">{{ idx + 1 }}</td>
                                <td class="px-4 py-2">{{ param.nama_parameter }}</td>
                                <td class="px-4 py-2">{{ param.nilai ?? '-' }}</td>
                                <td class="px-4 py-2">{{ param.baku_mutu ?? '-' }}</td>
                                <td class="px-4 py-2">{{ param.keterangan ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end">
                <Link :href="route('pegawai.hasil_uji.riwayat', histori.hasil_uji?.id)"
                    class="bg-gray-200 text-black px-4 py-2 rounded font-semibold">Kembali</Link>
            </div>
        </div>
    </AdminLayout>
</template>