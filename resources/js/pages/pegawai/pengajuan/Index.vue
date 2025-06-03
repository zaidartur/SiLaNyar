<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import { ref } from 'vue'

interface User {
    id: number
    nama: string
}

interface Instansi {
    id: number
    nama: string
    user: User
}

interface JenisCairan {
    id: number
    nama: string
}

interface Parameter {
    id: number
    nama_parameter: string
}

interface Kategori {
    id: number
    nama: string
}

interface Pengajuan {
    id: number
    kode_pengajuan: string
    volume_sampel: number
    status_pengajuan: string
    metode_pengambilan: string
    lokasi: string
    instansi: Instansi
    kategori: Kategori
    jenis_cairan: JenisCairan
    parameter: Parameter[]
}

const props = defineProps<{
    pengajuan: Pengajuan[],
    filter: {
        status: string,
        tanggal: string
    }
}>()

const status = ref(props.filter.status ?? "")
const tanggal = ref(props.filter.tanggal)

const handleFilter = () => {
    window.location.href = `/pegawai/pengajuan?status=${status.value}&tanggal=${tanggal.value}`
}
</script>

<template>

    <Head title="Daftar Pengujian" />
    <AdminLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl text-black font-bold">DAFTAR PENGAJUAN</h1>
                <Link :href="route('pegawai.pengajuan.create')"
                    class="bg-customDarkGreen text-white px-4 py-2 rounded flex items-center gap-2">
                <span>+</span> Tambah
                </Link>
            </div>

            <!-- Filter -->
            <div class="mb-6 flex gap-4 items-end">
                <!-- Status Filter -->
                <div class="flex flex-col w-40">
                    <label for="status" class="mb-1 text-sm font-medium text-gray-700">Pilih Status:</label>
                    <select id="status" v-model="status"
                        class="rounded bg-customDarkGreen text-white border-gray-300 px-2 py-1" @change="handleFilter">
                        <option disabled value="">Pilih Status</option>
                        <option value="proses_validasi">Proses Validasi</option>
                        <option value="diterima">Di Terima</option>
                        <option value="ditolak">Di Tolak</option>
                    </select>
                </div>

                <!-- Tanggal Filter -->
                <div class="flex flex-col w-40">
                    <label for="tanggal" class="mb-1 text-sm font-medium text-gray-700">Tanggal:</label>
                    <input id="tanggal" type="date" v-model="tanggal"
                        class="rounded bg-customDarkGreen text-white border-gray-300 px-2 py-1" @change="handleFilter">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-xl shadow overflow-hidden">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="px-4 py-3 text-left font-semibold rounded-tl-xl">ID Pengajuan</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Pemohon</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Instansi</th>
                            <th class="px-4 py-3 text-left font-semibold">Jenis Cairan</th>
                            <th class="px-4 py-3 text-left font-semibold">Kategori</th>
                            <th class="px-4 py-3 text-left font-semibold">Parameter</th>
                            <th class="px-4 py-3 text-left font-semibold">Volume Sampel</th>
                            <th class="px-4 py-3 text-left font-semibold">Metode Pengambilan</th>
                            <th class="px-4 py-3 text-left font-semibold">Status Pengajuan</th>
                            <th class="px-4 py-3 text-left font-semibold rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in props.pengajuan" :key="item.id"
                            :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                            <td class="px-4 py-3 border-b">{{ item.kode_pengajuan }}</td>
                            <td class="px-4 py-3 border-b">{{ item.instansi.user.nama }}</td>
                            <td class="px-4 py-3 border-b">{{ item.instansi?.nama ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">{{ item.jenis_cairan.nama }}</td>
                            <td class="px-4 py-3 border-b">{{ item.kategori?.nama }}</td>
                            <td class="px-4 py-3 border-b">
                                <ul class="list-disc ml-4">
                                    <li v-for="param in item.parameter" :key="param.id">
                                        {{ param.nama_parameter }}
                                    </li>
                                </ul>
                            </td>
                            <td class="px-4 py-3 border-b">{{ item.volume_sampel }}</td>
                            <td class="px-4 py-3 border-b">{{ item.metode_pengambilan }}</td>
                            <td class="px-4 py-3 border-b">
                                <span :class="[
                                    'inline-block min-w-[110px] text-center px-2 py-1 rounded-full text-xs font-semibold',
                                    item.status_pengajuan === 'diterima'
                                        ? 'bg-green-100 text-green-700 border border-green-400'
                                        : item.status_pengajuan === 'ditolak'
                                            ? 'bg-red-100 text-red-700 border border-red-400'
                                            : 'bg-yellow-100 text-yellow-800 border border-yellow-400'
                                ]">
                                    {{ item.status_pengajuan.replace('_', ' ').toUpperCase() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border-b">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.pengajuan.detail', item.id)"
                                        class="text-blue-500 hover:text-blue-700" title="Lihat">
                                    <span>👁️</span>
                                    </Link>
                                    <Link :href="route('pegawai.pengajuan.edit', item.id)"
                                        class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                    <span>✏️</span>
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