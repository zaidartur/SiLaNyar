<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { defineProps } from 'vue'
import { router } from '@inertiajs/vue3'

interface Instansi {
    nama: string
}

interface User {
    nama: string
}

interface FormPengajuan {
    kode_pengajuan: string
    instansi: Instansi
    user: User
}

interface Pembayaran {
    id: number
    id_order: string
    total_biaya: number
    tanggal_pembayaran: string | null
    metode_pembayaran: string
    bukti_pembayaran: string | null
    created_at: string
    form_pengajuan: FormPengajuan
    status_pembayaran: string
}

const props = defineProps<{
    pembayaran: Pembayaran
}>()

function kembaliKeIndex() {
    router.visit('/pegawai/pembayaran')
}

function updateStatus(status: string) {
    router.put(`/pegawai/pembayaran/${props.pembayaran.id}/edit`, {
        status_pembayaran: status,
    }, {
        onSuccess: () => kembaliKeIndex()
    })
}
</script>

<template>
    <AdminLayout>
        <div class="p-6 bg-gray-50 min-h-screen">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">Detail Pembayaran {{ pembayaran.id_order }}</h2>
                        <span
                            class="px-4 py-1 rounded-full border border-yellow-400 bg-yellow-50 text-yellow-700 text-sm font-semibold">
                            {{ pembayaran.status_pembayaran === 'diproses' ? 'Menunggu' : pembayaran.status_pembayaran
                            }}
                        </span>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">ID Pengajuan:</label>
                                <input type="text" class="w-full bg-gray-100 rounded px-2 py-1"
                                    :value="pembayaran.form_pengajuan?.kode_pengajuan ?? '-'" readonly>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nama Pelanggan:</label>
                                <input type="text" class="w-full bg-gray-100 rounded px-2 py-1"
                                    :value="pembayaran.form_pengajuan?.instansi?.nama ?? '-'" readonly>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Tanggal Pembayaran:</label>
                                <input type="text" class="w-full bg-gray-100 rounded px-2 py-1"
                                    :value="pembayaran.tanggal_pembayaran ? new Date(pembayaran.tanggal_pembayaran).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '-'"
                                    readonly>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Jumlah:</label>
                                <input type="text" class="w-full bg-gray-100 rounded px-2 py-1"
                                    :value="`Rp ${pembayaran.total_biaya.toLocaleString('id-ID')}`" readonly>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Metode Pembayaran:</label>
                                <input type="text" class="w-full bg-gray-100 rounded px-2 py-1"
                                    :value="pembayaran.metode_pembayaran" readonly>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Tanggal Upload:</label>
                                <input type="text" class="w-full bg-gray-100 rounded px-2 py-1"
                                    :value="pembayaran.created_at ? new Date(pembayaran.created_at).toLocaleString('id-ID') : '-'"
                                    readonly>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="block text-xs text-gray-500 mb-1">Bukti Pembayaran:</label>
                            <div class="bg-gray-200 rounded flex items-center justify-center h-48 mb-2 overflow-hidden">
                                <template v-if="pembayaran.bukti_pembayaran">
                                    <img :src="`/storage/${pembayaran.bukti_pembayaran}`" alt="Bukti Pembayaran"
                                        class="object-contain h-full w-full" />
                                </template>
                                <template v-else>
                                    <span class="text-gray-500 text-xl">Photo Bukti Pembayaran</span>
                                </template>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-4">
                            <button class="px-4 py-1 rounded bg-gray-200 text-gray-700 text-sm"
                                @click="kembaliKeIndex">Tutup</button>
                            <button class="px-4 py-1 rounded bg-red-500 text-white text-sm"
                                @click="updateStatus('gagal')">Tolak</button>
                            <button class="px-4 py-1 rounded bg-green-600 text-white text-sm"
                                @click="updateStatus('selesai')">Verifikasi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>