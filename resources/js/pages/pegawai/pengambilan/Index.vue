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
    filter: {
        status: string,
        tanggal: string
    }
}>()

const formatTanggal = (tanggalStr: string) => {
    const date = new Date(tanggalStr)
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}

const status = ref(props.filter.status ?? "")
const tanggal = ref(props.filter.tanggal)

const handleFilter = () => {
    window.location.href = `/pegawai/pengambilan?status=${status.value}&tanggal=${tanggal.value}`
}
</script>

<template>
    <Head title="Daftar Pengambilan" />
    <AdminLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl text-black font-bold">DAFTAR PENGAMBILAN</h1>
                <Link href="/pegawai/pengambilan/create"
                    class="flex items-center gap-2 rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700 transition">
                <span>+</span> Tambah
                </Link>
            </div>

            <!-- Filter -->
            <div class="mb-6 flex gap-4 items-end">
                <div class="flex flex-col">
                    <label for="status" class="mb-1 text-sm font-medium text-gray-700">Status</label>
                    <select id="status" v-model="status"
                        class="rounded bg-customDarkGreen text-white border-gray-300 px-2 py-1" @change="handleFilter">
                        <option disabled value="">Pilih Status</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="tanggal" class="mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                    <input id="tanggal" type="date" v-model="tanggal"
                        class="rounded bg-customDarkGreen text-white border-gray-300 px-2 py-1" @change="handleFilter">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-xl shadow overflow-hidden">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="px-4 py-3 text-left font-semibold rounded-tl-xl">ID Pengambil/Pengantar</th>
                            <th class="px-4 py-3 text-left font-semibold">Kode Pengajuan</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Instansi</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Pemohon</th>
                            <th class="px-4 py-3 text-left font-semibold">Metode Pengambilan</th>
                            <th class="px-4 py-3 text-left font-semibold">Waktu Pengambilan/Pengantaran</th>
                            <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in props.jadwal" :key="item.id"
                            :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                            <td class="px-4 py-3">{{ item.kode_pengambilan }}</td>
                            <td class="px-4 py-3">{{ item.form_pengajuan?.kode_pengajuan }}</td>
                            <td class="px-4 py-3">{{ item.form_pengajuan?.instansi?.nama }}</td>
                            <td class="px-4 py-3">{{ item.form_pengajuan?.instansi?.user?.nama }}</td>
                            <td class="px-4 py-3">{{ item.form_pengajuan?.metode_pengambilan }}</td>
                            <td class="px-4 py-3">{{ formatTanggal(item.waktu_pengambilan) }}</td>
                            <td class="px-4 py-3">{{ item.keterangan }}</td>
                            <td class="px-4 py-3">
                                <span :class="[
                                    'px-2 py-1 rounded text-xs font-semibold',
                                    item.status === 'selesai' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white'
                                ]">
                                    {{ item.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <Link :href="`/pegawai/pengambilan/${item.id}`" method="get"
                                        class="text-blue-600 hover:text-blue-800" as="button" type="button"
                                        title="Lihat">
                                    <span>👁️</span>
                                    </Link>
                                    <Link :href="route('pegawai.pengambilan.edit', item.id)"
                                        class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                    <span>✏️</span>
                                    </Link>
                                    <Link :href="`/pegawai/pengambilan/${item.id}`" method="delete"
                                        class="text-red-500 hover:text-red-700" as="button" type="button" title="Hapus">
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