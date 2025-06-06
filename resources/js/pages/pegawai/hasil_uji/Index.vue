<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

// const props = defineProps<{
//     hasil_uji: any[]
// }>()

const page = usePage();
const permissions = page.props.auth.permissions as string[];

const can = (permission: string): boolean => {
    return permissions.includes(permission);
};

const formatTanggal = (tanggalStr: string) => {
    const date = new Date(tanggalStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

const statusLabel = (status: string) => {
    const labels: Record<string, string> = {
        draf: 'Draf',
        revisi: 'Revisi',
        proses_review: 'Proses Review',
        proses_peresmian: 'Proses Peresmian',
        selesai: 'Selesai',
    };
    return labels[status] ?? status;
};
</script>

<template>
    <Head title="Daftar Hasil Uji" />
    <AdminLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-black">DAFTAR HASIL UJI</h1>
                <div class="mb-4 flex justify-end">
                    <Link
                        v-if="can('tambah hasil uji')"
                        href="/pegawai/hasiluji/create"
                        class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700"
                    >
                        + Tambah Hasil Uji
                    </Link>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full overflow-hidden rounded-xl bg-white shadow">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="rounded-tl-xl px-4 py-3 text-left font-semibold">ID Hasil</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Instansi</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Pemohon</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Teknisi</th>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal Pengujian</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="rounded-tr-xl px-4 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in hasil_uji" :key="item.id" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                            <td class="border-b px-4 py-3">{{ item.id }}</td>
                            <td class="border-b px-4 py-3">
                                {{ item.pengujian?.form_pengajuan?.instansi?.nama ?? '-' }}
                            </td>
                            <td class="border-b px-4 py-3">
                                {{ item.pengujian?.form_pengajuan?.instansi?.user?.nama ?? '-' }}
                            </td>
                            <td class="border-b px-4 py-3">
                                {{ item.pengujian?.user?.nama ?? '-' }}
                            </td>
                            <td class="border-b px-4 py-3">
                                {{ formatTanggal(item.pengujian?.tanggal_uji) ?? '-' }}
                            </td>
                            <td class="border-b px-4 py-3">
                                <span
                                    :class="[
                                        'rounded px-2 py-1 text-xs',
                                        item.status === 'selesai'
                                            ? 'bg-green-500 text-white'
                                            : item.status === 'proses_review'
                                              ? 'bg-yellow-500 text-white'
                                              : item.status === 'proses_peresmian'
                                                ? 'bg-blue-500 text-white'
                                                : item.status === 'revisi'
                                                  ? 'bg-red-500 text-white'
                                                  : 'bg-gray-400 text-white',
                                    ]"
                                >
                                    {{ statusLabel(item.status) }}
                                </span>
                            </td>
                            <td class="border-b px-4 py-3">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.hasil_uji.detail', item.id)" class="text-blue-500 hover:text-blue-700" title="Detail">
                                        👁️
                                    </Link>
                                    <Link
                                        :href="route('pegawai.hasil_uji.riwayat', item.id)"
                                        class="text-purple-500 hover:text-purple-700"
                                        title="Riwayat"
                                    >
                                        🕓
                                    </Link>
                                    <Link :href="route('pegawai.hasil_uji.edit', item.id)" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                        ✏️
                                    </Link>
                                    <Link
                                        :href="route('pegawai.hasil_uji.destroy', item.id)"
                                        method="delete"
                                        as="button"
                                        type="button"
                                        class="text-red-500 hover:text-red-700"
                                        title="Hapus"
                                    >
                                        🗑️
                                    </Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="hasil_uji.length === 0">
                            <td colspan="6" class="py-4 text-center text-gray-500">Tidak ada data hasil uji.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
