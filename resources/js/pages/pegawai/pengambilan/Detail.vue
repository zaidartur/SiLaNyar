<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

interface User {
    id: number
    nama: string
}

interface Instansi {
    id: number
    nama: string
    user: User
}

interface Pengajuan {
    id: number
    kode_pengajuan: string
    metode_pengambilan: string
    lokasi: string
    instansi: Instansi
}

interface Jadwal {
    id: number
    kode_pengambilan: string
    form_pengajuan: Pengajuan
    user: User
    waktu_pengambilan: string
    status: 'diproses' | 'diterima'
    keterangan: string
}

const props = defineProps<{
    jadwal: Jadwal
}>()

const formatTanggal = (tanggalStr: string) => {
    const date = new Date(tanggalStr)
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}
</script>

<template>
    <AdminLayout>
        <div class="p-6 max-w-3xl mx-auto">
            <h1 class="text-2xl font-bold text-customDarkGreen mb-6 flex items-center gap-2">
                Detail Jadwal Pengambilan
            </h1>

            <div class="rounded-xl bg-gradient-to-br from-gray-50 to-white p-6 shadow flex flex-col gap-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-5 text-blue-400">🔑</span>
                        <span class="font-semibold text-gray-700">Kode Pengambilan:</span>
                        <span class="ml-1 text-gray-900">{{ props.jadwal.kode_pengambilan }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-5 text-blue-400">📄</span>
                        <span class="font-semibold text-gray-700">Kode Pengajuan:</span>
                        <span class="ml-1 text-gray-900">{{ props.jadwal.form_pengajuan?.kode_pengajuan }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-5 text-blue-400">👤</span>
                        <span class="font-semibold text-gray-700">Nama Pemohon:</span>
                        <span class="ml-1 text-gray-900">{{ props.jadwal.form_pengajuan?.instansi?.user?.nama }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-5 text-blue-400">🏢</span>
                        <span class="font-semibold text-gray-700">Nama Instansi:</span>
                        <span class="ml-1 text-gray-900">{{ props.jadwal.form_pengajuan?.instansi?.nama }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-5 text-blue-400">🚚</span>
                        <span class="font-semibold text-gray-700">Metode Pengambilan:</span>
                        <span class="ml-1 text-gray-900 capitalize">{{ props.jadwal.form_pengajuan?.metode_pengambilan
                            }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-5 text-blue-400">⏰</span>
                        <span class="font-semibold text-gray-700">Waktu Pengambilan:</span>
                        <span class="ml-1 text-gray-900">{{ formatTanggal(props.jadwal.waktu_pengambilan) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-5 text-blue-400">📌</span>
                        <span class="font-semibold text-gray-700">Status:</span>
                        <span class="ml-1 rounded-full px-3 py-1 text-xs font-bold" :class="{
                            'bg-yellow-100 text-yellow-700 border border-yellow-300': props.jadwal.status === 'diproses',
                            'bg-green-100 text-green-700 border border-green-300': props.jadwal.status === 'diterima'
                        }">
                            {{ props.jadwal.status.charAt(0).toUpperCase() + props.jadwal.status.slice(1) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 md:col-span-2">
                        <span class="inline-block w-5 text-blue-400">📝</span>
                        <span class="font-semibold text-gray-700">Keterangan:</span>
                        <span class="ml-1 text-gray-900">{{ props.jadwal.keterangan || '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <Link href="/pegawai/pengambilan"
                    class="inline-block rounded bg-gray-300 px-4 py-2 font-semibold text-black hover:bg-gray-400 transition">
                Kembali
                </Link>
            </div>
        </div>
    </AdminLayout>
</template>