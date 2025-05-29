<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

<<<<<<< HEAD
const props = defineProps<{
    jadwal: any[],
=======
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
    status: 'diproses' | 'selesai'
    keterangan: string
}

const props = defineProps<{
    jadwal: Jadwal[]
>>>>>>> 6bb41a2 (Update Controllers, Models, Database, Routes, Page)
    filter: {
        status: string,
        tanggal: string
    }
}>()

<<<<<<< HEAD
=======
const formatTanggal = (tanggalStr: string) => {
    const date = new Date(tanggalStr)
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}

>>>>>>> 6bb41a2 (Update Controllers, Models, Database, Routes, Page)
const status = ref(props.filter.status)
const tanggal = ref(props.filter.tanggal)

const handleFilter = () => {
<<<<<<< HEAD
    window.location.href = `/pegawai/jadwal?status=${status.value}&tanggal=${tanggal.value}`
=======
    window.location.href = `/pegawai/pengambilan?status=${status.value}&tanggal=${tanggal.value}`
>>>>>>> 6bb41a2 (Update Controllers, Models, Database, Routes, Page)
}
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
<<<<<<< HEAD
                <h1 class="text-2xl text-black font-bold">Daftar Jadwal Pengambilan</h1>
                <Link :href="route('pegawai.jadwal.create')" class="bg-customDarkGreen text-white px-4 py-2 rounded">
                Tambah Jadwal
=======
                <h1 class="text-2xl text-black font-bold">DAFTAR PENGAMBILAN</h1>
                <Link href="/pegawai/pengambilan/create"
                    class="flex items-center gap-2 rounded bg-green-600 px-4 py-2 text-white">
                <span>+</span> Tambah
>>>>>>> 6bb41a2 (Update Controllers, Models, Database, Routes, Page)
                </Link>
            </div>

            <!-- Filter -->
            <div class="mb-6 flex gap-4 items-end">
                <!-- Status Filter -->
                <div class="flex flex-col">
                    <label for="status" class="mb-1 text-sm font-medium text-gray-700">Status</label>
                    <select id="status" v-model="status"
                        class="rounded bg-green-600 text-white border-gray-300 px-2 py-1" @change="handleFilter">
                        <option disabled value="">Pilih Status</option>
<<<<<<< HEAD
                        <option value="diproses">Diproses</option>
=======
                        <option value="diproses">Dalam Proses</option>
>>>>>>> 6bb41a2 (Update Controllers, Models, Database, Routes, Page)
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <!-- Tanggal Filter -->
                <div class="flex flex-col">
                    <label for="tanggal" class="mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                    <input id="tanggal" type="date" v-model="tanggal"
                        class="rounded bg-green-600 text-white border-gray-300 px-2 py-1" @change="handleFilter">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg">
                    <thead>
                        <tr class="bg-gray-500 text-white">
<<<<<<< HEAD
                            <th class="px-6 py-3 rounded-tl-lg">ID Jadwal</th>
                            <th class="px-6 py-3">ID Form Pengajuan</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Petugas</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Catatan</th>
                            <th class="px-6 py-3 rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in jadwal" :key="item.id">
                            <td class="px-6 py-4 border">JD-001</td>
                            <td class="px-6 py-4 border">DP-001</td>
                            <td class="px-6 py-4 border">15/04/2025</td>
                            <td class="px-6 py-4 border">Beni</td>
                            <td class="px-6 py-4 border">
                                <span :class="{
                                    'px-2 py-1 rounded text-white': true,
                                    'bg-green-500': item.status === 'selesai',
                                    'bg-yellow-500': item.status === 'diproses',
                                    'bg-red-500': item.status === 'dibatalkan'
                                }">
                                    {{ item.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 border">-</td>
                            <td class="px-6 py-4 border rounded-br-lg">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.jadwal.show', item.id)"
                                        class="bg-blue-500 text-white px-2 py-1 rounded">
                                    <span>👁️</span>
                                    </Link>
                                    <Link :href="route('pegawai.jadwal.edit', item.id)"
                                        class="bg-yellow-500 text-white px-2 py-1 rounded">
                                    <span>✏️</span>
                                    </Link>
                                    <Link :href="route('pegawai.jadwal.destroy', item.id)" method="delete"
                                        class="bg-red-500 text-white px-2 py-1 rounded" as="button" type="button">
=======
                            <th class="px-6 py-3">ID Pengambil/Pengantar</th>
                            <th class="px-6 py-3">Kode Pengajuan</th>
                            <th class="px-6 py-3">Nama Instansi</th>
                            <th class="px-6 py-3">Nama Pemohon</th>
                            <th class="px-6 py-3">Metode Pengambilan</th>
                            <th class="px-6 py-3">Waktu Pengambilan/Pengantaran</th>
                            <th class="px-6 py-3">Keterangan</th>
                            <th class="px-6 py-3">Status Pengambilan/Pengantaran</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in props.jadwal" :key="item.id"
                            :class="{ 'bg-gray-200': index % 2 !== 0 }">
                            <td class="px-6 py-4">{{ item.kode_pengambilan }}</td>
                            <td class="px-6 py-4">{{ item.form_pengajuan?.kode_pengajuan }}</td>
                            <td class="px-6 py-4">{{ item.form_pengajuan?.instansi?.nama }}</td>
                            <td class="px-6 py-4">{{ item.form_pengajuan?.instansi?.user?.nama }}</td>
                            <td class="px-6 py-4">{{ item.form_pengajuan?.metode_pengambilan }}</td>
                            <td class="px-6 py-4">{{ formatTanggal(item.waktu_pengambilan) }}</td>
                            <td class="px-6 py-4">{{ item.keterangan }}</td>
                            <td class="px-6 py-4">{{ item.status }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <Link :href="`/pegawai/pengambilan/${item.id})`" method="get"
                                        class="text-red-500" as="button" type="button">
                                    <span>👁️</span>
                                    </Link>
                                    <Link :href="route('pegawai.pengambilan.edit', item.id)" class="text-yellow-500">
                                    <span>✏️</span>
                                    </Link>
                                    <Link :href="`/pegawai/pengambilan/${item.id})`" method="delete"
                                        class="text-red-500" as="button" type="button">
>>>>>>> 6bb41a2 (Update Controllers, Models, Database, Routes, Page)
                                    <span>🗑️</span>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>