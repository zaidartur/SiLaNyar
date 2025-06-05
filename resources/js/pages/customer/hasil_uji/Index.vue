<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
    hasil_uji: any[],
    currentPage?: number,
    totalPages?: number
}>()

const tableData = props.hasil_uji

const currentPage = ref(props.currentPage ?? 1)
const totalPages = ref(props.totalPages ?? 1)

function handlePageChange(page: number) {
    currentPage.value = page
    // Untuk pagination dinamis, request ke backend di sini
}

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
                                {{ formatTanggal(item.pengujian?.tanggal_uji) ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b">
                                {{ item.pengujian?.form_pengajuan?.lokasi ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b">
                                {{ item.pengujian?.form_pengajuan?.metode_pengambilan ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b text-center">
                                <span v-if="item.aduan" class="text-blue-500">
                                    <i class="fas fa-comment"></i>
                                </span>
                                <span v-else>-</span>
                            </td>
                            <td class="px-4 py-3 border-b text-center">
                                <span v-if="item.rating" class="text-yellow-400">
                                    <i class="fas fa-star"></i>
                                </span>
                                <span v-else>-</span>
                            </td>
                            <td class="px-4 py-3 border-b">
                                <span :class="[
                                    'text-xs px-2 py-1 rounded',
                                    item.status === 'selesai' ? 'bg-green-500 text-white' :
                                        item.status === 'proses_review' ? 'bg-yellow-500 text-white' :
                                            item.status === 'proses_peresmian' ? 'bg-blue-500 text-white' :
                                                item.status === 'revisi' ? 'bg-red-500 text-white' :
                                                    'bg-gray-400 text-white'
                                ]">
                                    {{ statusLabel(item.status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border-b">
                                <a :href="`/customer/hasil-uji/${item.id}`" class="text-blue-600 hover:underline"
                                    title="Detail">
                                    👁️
                                </a>
                            </td>
                        </tr>
                        <tr v-if="tableData.length === 0">
                            <td colspan="10" class="text-center py-4 text-gray-500">Tidak ada data hasil uji.</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="p-4 border-t border-gray-200 flex justify-end" v-if="totalPages > 1">
                    <nav aria-label="Page navigation">
                        <ul class="flex items-center -space-x-px h-10 text-base">
                            <!-- Previous Button -->
                            <li>
                                <a href="#" @click.prevent="currentPage > 1 && handlePageChange(currentPage - 1)"
                                    class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">
                                    <span class="sr-only">Previous</span>
                                    <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 1 1 5l4 4" />
                                    </svg>
                                </a>
                            </li>
                            <!-- Page Numbers -->
                            <li v-for="page in totalPages" :key="page">
                                <a href="#" @click.prevent="handlePageChange(page)" :class="[
                                    'flex items-center justify-center px-4 h-10 leading-tight border border-gray-300',
                                    currentPage === page
                                        ? 'z-10 text-white border-customDarkGreen bg-customDarkGreen'
                                        : 'text-gray-500 bg-white hover:bg-customLightGreen hover:text-gray-700'
                                ]" :aria-current="currentPage === page ? 'page' : undefined">
                                    {{ page }}
                                </a>
                            </li>
                            <!-- Next Button -->
                            <li>
                                <a href="#"
                                    @click.prevent="currentPage < totalPages && handlePageChange(currentPage + 1)"
                                    class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">
                                    <span class="sr-only">Next</span>
                                    <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>