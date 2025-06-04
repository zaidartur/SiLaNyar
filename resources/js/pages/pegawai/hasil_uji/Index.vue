<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    hasil_uji: any[]
}>()

const page = usePage()
const permissions = page.props.auth.permissions as string[]

const can = (permission: string): boolean => {
    return permissions.includes(permission)
}

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

    <Head title="Daftar Hasil Uji" />
    <AdminLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl text-black font-bold">DAFTAR HASIL UJI</h1>
                <div class="flex justify-end mb-4">
                    <Link v-if="can('tambah hasil uji')" href="/pegawai/hasiluji/create"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    + Tambah Hasil Uji
                    </Link>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-xl shadow overflow-hidden">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="px-4 py-3 text-left font-semibold rounded-tl-xl">ID Hasil</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Instansi</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Pemohon</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Teknisi</th>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal Pengujian</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in hasil_uji" :key="item.id"
                            :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                            <td class="px-4 py-3 border-b">{{ item.id }}</td>
                            <td class="px-4 py-3 border-b">
                                {{ item.pengujian?.form_pengajuan?.instansi?.nama ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b">
                                {{ item.pengujian?.form_pengajuan?.instansi?.user?.nama ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b">
                                {{ item.pengujian?.user?.nama ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b">
                                {{ formatTanggal(item.pengujian?.tanggal_uji) ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border-b">
                                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">
                                    Selesai
                                </span>
                            </td>
                            <td class="px-4 py-3 border-b">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.hasil_uji.detail', item.id)"
                                        class="text-blue-500 hover:text-blue-700" title="Detail">
                                    👁️
                                    </Link>
                                    <Link :href="route('pegawai.hasil_uji.riwayat', item.id)"
                                        class="text-purple-500 hover:text-purple-700" title="Riwayat">
                                    🕓
                                    </Link>
                                    <Link :href="route('pegawai.hasil_uji.edit', item.id)"
                                        class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                    ✏️
                                    </Link>
                                    <Link :href="route('pegawai.hasil_uji.destroy', item.id)" method="delete"
                                        as="button" type="button" class="text-red-500 hover:text-red-700" title="Hapus">
                                    🗑️
                                    </Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="hasil_uji.length === 0">
                            <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data hasil uji.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
