<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const { hasil_uji } = defineProps<{
    hasil_uji: any
    histori: any[]
}>()

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

    <Head title="Histori Hasil Uji" />
    <AdminLayout>
        <div class="max-w-5xl mx-auto p-6">
            <h1 class="text-2xl font-bold mb-4 text-customDarkGreen">Histori Hasil Uji</h1>
            <div class="bg-white rounded-xl border shadow p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-8">
                    <div>
                        <span class="font-bold text-customDarkGreen">Kode Hasil Uji:</span>
                        <span class="ml-2">HU-{{ hasil_uji.id.toString().padStart(4, '0') }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Status Saat Ini:</span>
                        <span class="ml-2 capitalize">{{ hasil_uji.status }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Tanggal Dibuat:</span>
                        <span class="ml-2">{{ formatTanggal(hasil_uji.created_at) }}</span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full rounded-xl border bg-white shadow">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Diperbarui Oleh</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!histori || histori.length === 0">
                            <td colspan="5" class="py-4 text-center text-gray-400">Belum ada histori.</td>
                        </tr>
                        <tr v-for="(item, idx) in histori" :key="item.id">
                            <td class="px-4 py-3">{{ idx + 1 }}</td>
                            <td class="px-4 py-3 capitalize">{{ item.status }}</td>
                            <td class="px-4 py-3">{{ formatTanggal(item.created_at) }}</td>
                            <td class="px-4 py-3">{{ item.diupdate_oleh ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <Link :href="route('pegawai.hasil_uji.riwayat.show', item.id)"
                                    class="text-blue-600 hover:underline">
                                Detail
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-6">
                <Link :href="route('pegawai.hasil_uji.index')"
                    class="bg-gray-200 text-black px-4 py-2 rounded font-semibold">Kembali</Link>
            </div>
        </div>
    </AdminLayout>
</template>