<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
    hasil_uji: any[],
    currentPage?: number,
    totalPages?: number
}>()

const tableData = props.hasil_uji

function formatTanggal(tanggal: string) {
    if (!tanggal) return '-'
    const d = new Date(tanggal)
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
}

const statusLabel = (status: string) => {
    const labels: Record<string, string> = {
        draf: 'Draf',
        revisi: 'Revisi',
        proses_review: 'Proses Review',
        proses_peresmian: 'Proses Peresmian',
        selesai: 'Selesai'
    }
    return labels[status] ?? status
}

const verifikasi = (id: number) => {
    if (confirm('Anda yakin tidak ada aduan dalam hasil uji ini?')) {
        router.put(route('customer.hasil_uji.verifikasi', id), {
            status: 'proses_peresmian'
        }, {
            onSuccess: () => {
                router.reload({ only: ['hasil_uji'] })
            }
        })
    }
}
</script>

<template>

    <Head title="Hasil Uji Sample" />
    <CustomerLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl text-black font-bold">DAFTAR HASIL UJI</h1>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-xl shadow overflow-hidden">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="px-4 py-3 text-left font-semibold rounded-tl-xl">ID Hasil</th>
                            <th class="px-4 py-3 text-left font-semibold">Jenis Cairan</th>
                            <th class="px-4 py-3 text-left font-semibold">Kategori</th>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold">Lokasi</th>
                            <th class="px-4 py-3 text-left font-semibold">Metode Pengambilan</th>
                            <th class="px-4 py-3 text-left font-semibold">Aduan</th>
                            <th class="px-4 py-3 text-left font-semibold">Rating</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in tableData" :key="item.id"
                            :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                            <td class="px-4 py-3 border-b">{{ item.id }}</td>
                            <td class="px-4 py-3 border-b">
                                {{ item.pengujian?.form_pengajuan?.jenis_cairan?.nama ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b">
                                {{ item.pengujian?.form_pengajuan?.kategori?.nama ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b">
                                {{ formatTanggal(item.pengujian?.updated_at) ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b">
                                {{ item.pengujian?.form_pengajuan?.lokasi ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b">
                                {{ item.pengujian?.form_pengajuan?.metode_pengambilan ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b text-center">
                                <span v-if="item.aduan && item.status === 'proses_review'" class="text-blue-500">
                                    {{ item.aduan?.status }}
                                </span>
                                <span v-else>-</span>
                            </td>
                            <td class="px-4 py-3 border-b">
                                <span :class="[
                                    'text-xs px-2 py-1 rounded',
                                    item.status === 'selesai' ? 'bg-green-500 text-white' :
                                        item.status === 'proses_review' ? 'bg-yellow-500 text-white' :
                                            item.status === 'proses_peresmian' ? 'bg-blue-500 text-white' :
                                                'bg-gray-400 text-white'
                                ]">
                                    {{ statusLabel(item.status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border-b">
                                <button v-if="item.status === 'proses_review'" @click="verifikasi(item.id)"
                                    class="text-green-600 hover:text-green-800" title="Verifikasi hasil uji">
                                    ✅
                                </button>
                                <Link v-if="item.status === 'proses_review'" :href="`/customer/hasiluji/aduan/${item.id}`"
                                    class="text-red-500 hover:text-red-700" title="Tambah Aduan">
                                📝
                                </Link>
                                <Link :href="`/customer/hasil-uji/${item.id}`" class="text-blue-500 hover:text-blue-700"
                                    title="Lihat Detail">
                                👁️
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="tableData.length === 0">
                            <td colspan="10" class="text-center py-4 text-gray-500">Tidak ada data hasil uji atau hasil
                                uji sedang diproses.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </CustomerLayout>
</template>