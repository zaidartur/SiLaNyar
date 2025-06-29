<script setup lang="ts">
/* eslint-disable */
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import { Head, router, Link } from '@inertiajs/vue3'
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

const aduanStatusLabel = (status: string) => {
    const labels: Record<string, string> = {
        diterima_administrasi: 'Diterima Administrasi',
        diterima_pengajuan: 'Diterima Pengajuan',
        diproses: 'Diproses',
        ditolak: 'Ditolak'
    }
    return labels[status] ?? status
}

const verifikasi = (id: number) => {
    router.put(route('customer.hasiluji.verifikasi', id), {
        status: 'proses_peresmian'
    }, {
        onSuccess: () => {
            router.visit(route('customer.hasil_uji.index'))
        }
    })
}

const showModal = ref(false)
const selectedId = ref<number | null>(null)

function openVerifikasiModal(id: number) {
    selectedId.value = id
    showModal.value = true
}

function closeModal() {
    showModal.value = false
    selectedId.value = null
}

function handleVerifikasi() {
    if (selectedId.value !== null) {
        verifikasi(selectedId.value)
        closeModal()
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
                            <th class="px-4 py-3 text-left font-semibold">Status Aduan</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold">Aduan</th>
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
                                <span v-if="item.aduan && item.aduan.status" :class="[
                                    'inline-block text-xs font-semibold px-3 py-1 rounded-full border shadow-sm transition-colors duration-200',
                                    item.aduan.status === 'diterima_administrasi'
                                        ? 'bg-blue-100 text-blue-800 border-blue-400'
                                        : item.aduan.status === 'diterima_pengajuan'
                                            ? 'bg-green-100 text-green-800 border-green-400'
                                            : item.aduan.status === 'diproses'
                                                ? 'bg-yellow-100 text-yellow-800 border-yellow-400'
                                                : item.aduan.status === 'ditolak'
                                                    ? 'bg-red-100 text-red-800 border-red-400'
                                                    : 'bg-gray-200 text-gray-700 border-gray-400'
                                ]">
                                    {{ aduanStatusLabel(item.aduan.status) }}
                                </span>
                                <span v-else>-</span>
                            </td>
                            <td class="px-4 py-3 border-b">
                                <span :class="[
                                    'inline-block text-center text-xs font-semibold px-3 py-1 rounded-full border shadow-sm transition-colors duration-200',
                                    item.status === 'selesai'
                                        ? 'bg-green-100 text-green-800 border-green-400'
                                        : item.status === 'proses_review'
                                            ? 'bg-yellow-100 text-yellow-800 border-yellow-400'
                                            : item.status === 'proses_peresmian'
                                                ? 'bg-blue-100 text-blue-800 border-blue-400'
                                                : 'bg-gray-200 text-gray-700 border-gray-400'
                                ]">
                                    {{ statusLabel(item.status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border-b">
                                <button v-if="item.status === 'proses_review'" @click="openVerifikasiModal(item.id)"
                                    class="text-green-600 hover:text-green-800" title="Verifikasi hasil uji">
                                    ✅
                                </button>
                                <Link v-if="item.status === 'proses_review'"
                                    :href="`/customer/hasiluji/aduan/${item.id}`"
                                    class="text-red-500 hover:text-red-700" title="Tambah Aduan">
                                📝
                                </Link>
                            </td>
                            <td class="px-4 py-3 border-b">
                                <Link :href="`/customer/hasiluji/ ${item.id}`" class="text-blue-500 hover:text-blue-700"
                                    title="Lihat Detail">
                                👁
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="tableData.length === 0">
                            <td colspan="10" class="text-center py-4 text-gray-500">Tidak ada data hasil uji atau hasil
                                uji sedang diproses.</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Modal Konfirmasi -->
                <div v-if="showModal"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 transition-all duration-300">
                    <div class="bg-white p-8 rounded-xl shadow-2xl w-96 animate-fadeIn relative">
                        <div class="flex flex-col items-center">
                            <div class="bg-green-100 rounded-full p-3 mb-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2l4 -4M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10z" />
                                </svg>
                            </div>
                            <div class="mb-2 text-xl font-bold text-gray-800 text-center">Konfirmasi Verifikasi</div>
                            <div class="mb-6 text-gray-600 text-center">
                                Apakah Anda yakin ingin <span class="font-semibold text-green-700">memverifikasi</span>
                                hasil uji ini?
                            </div>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button @click="closeModal"
                                class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition">
                                Batal
                            </button>
                            <button @click="handleVerifikasi"
                                class="px-5 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 shadow transition">
                                Verifikasi
                            </button>
                        </div>
                        <button @click="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </CustomerLayout>
</template>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.3s;
}
</style>