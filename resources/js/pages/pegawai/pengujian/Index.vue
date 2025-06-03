<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link,Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
    pengujian: any[],
    filter: {
        status: string,
        tanggal: string
    }
}>()

const status = ref(props.filter.status ?? "")
const tanggal = ref(props.filter.tanggal)

const handleFilter = () => {
    window.location.href = `/pegawai/pengujian?status=${status.value}&tanggal=${tanggal.value}`
}
</script>

<template>
    <Head title="Daftar Pengujian" />
    <AdminLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl text-black font-bold">DAFTAR PENGUJIAN</h1>
                <Link :href="route('pegawai.pengujian.create')"
                    class="bg-customDarkGreen text-white px-4 py-2 rounded flex items-center gap-2">
                <span>+</span> Tambah
                </Link>
            </div>

            <!-- Filter -->
            <div class="mb-6 flex gap-4 items-end">
                <div class="flex flex-col w-40">
                    <label for="status" class="mb-1 text-sm font-medium text-gray-700">Status</label>
                    <select id="status" v-model="status"
                        class="rounded bg-customDarkGreen text-white border-gray-300 px-2 py-1"
                        @change="handleFilter">
                        <option disabled value="">Pilih Status</option>
                        <option value="selesai">Selesai</option>
                        <option value="diproses">Diproses</option>
                    </select>
                </div>
                <div class="flex flex-col w-40">
                    <label for="tanggal" class="mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                    <input id="tanggal" type="date" v-model="tanggal"
                        class="rounded bg-customDarkGreen text-white border-gray-300 px-2 py-1 focus:bg-white focus:text-black transition-colors"
                        @change="handleFilter">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-xl shadow overflow-hidden">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="px-4 py-3 text-left font-semibold rounded-tl-xl">ID Pengujian</th>
                            <th class="px-4 py-3 text-left font-semibold">ID Form Pengajuan</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Teknisi</th>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal Pengujian</th>
                            <th class="px-4 py-3 text-left font-semibold">Jam Mulai</th>
                            <th class="px-4 py-3 text-left font-semibold">Jam Selesai</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in pengujian" :key="item.id"
                            :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                            <td class="px-4 py-3 border-b">{{ item.kode_pengujian ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">{{ item.form_pengajuan_id ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">{{ item.teknisi?.nama ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">{{ item.tanggal_pengujian ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">{{ item.jam_mulai ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">{{ item.jam_selesai ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">
                                <span :class="[
                                    'px-2 py-1 rounded text-xs font-semibold',
                                    item.status === 'selesai' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white'
                                ]">
                                    {{ item.status ? item.status.charAt(0).toUpperCase() + item.status.slice(1) : '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border-b">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.pengujian.show', item.id)"
                                        class="text-blue-500 hover:text-blue-700" title="Lihat">
                                    <span>👁️</span>
                                    </Link>
                                    <Link :href="route('pegawai.pengujian.edit', item.id)"
                                        class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                    <span>✏️</span>
                                    </Link>
                                    <Link :href="route('pegawai.pengujian.destroy', item.id)" method="delete"
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