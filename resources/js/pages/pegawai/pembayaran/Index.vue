<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { defineProps } from 'vue';
import { router, Head } from '@inertiajs/vue3';
interface User {
    id: number;
    nama: string;
}

interface Instansi {
    nama: string;
    user: User;
}

interface FormPengajuan {
    id: number;
    kode_pengajuan: string;
    instansi: Instansi;
}

interface Pembayaran {
    id: number;
    id_order: string;
    total_biaya: number;
    tanggal_pembayaran: string | null;
    metode_pembayaran: string;
    status_pembayaran: string;
    bukti_pembayaran: string | null;
    form_pengajuan: FormPengajuan;
}

const props = defineProps<{
    pembayaran: Pembayaran[];
}>();

function formatTanggal(tanggal: string | null) {
    if (!tanggal) return '-';
    return tanggal.split('T')[0];
}

function lihatDetail(id: number) {
    router.visit(`/pegawai/pembayaran/${id}`);
}
</script>

<template>
    <Head title="Daftar Pembayaran" />
    <AdminLayout>
        <div class="p-6">
            <h1 class="mb-6 text-2xl font-bold text-black">DAFTAR PEMBAYARAN</h1>
            <div class="overflow-x-auto">
                <table class="min-w-full overflow-hidden rounded-xl bg-white shadow">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="rounded-tl-xl px-4 py-3 text-left font-semibold">ID Order</th>
                            <th class="px-4 py-3 text-left font-semibold">ID Form Pengajuan</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Pelanggan</th>
                            <th class="px-4 py-3 text-left font-semibold">Instansi</th>
                            <th class="px-4 py-3 text-left font-semibold">Total Biaya</th>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal Pembayaran</th>
                            <th class="px-4 py-3 text-left font-semibold">Metode Pembayaran</th>
                            <th class="px-4 py-3 text-left font-semibold">Status Pembayaran</th>
                            <th class="px-4 py-3 text-left font-semibold">Bukti Pembayaran</th>
                            <th class="rounded-tr-xl px-4 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in props.pembayaran" :key="item.id"
                            :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                            <td class="border-b px-4 py-3">{{ item.id_order }}</td>
                            <td class="border-b px-4 py-3">{{ item.form_pengajuan?.kode_pengajuan ?? '-' }}</td>
                            <td class="border-b px-4 py-3">{{ item.form_pengajuan?.instansi?.user?.nama ?? '-' }}</td>
                            <td class="border-b px-4 py-3">{{ item.form_pengajuan?.instansi?.nama ?? '-' }}</td>
                            <td class="border-b px-4 py-3">{{ item.total_biaya }}</td>
                            <td class="border-b px-4 py-3">{{ formatTanggal(item.tanggal_pembayaran) }}</td>
                            <td class="border-b px-4 py-3 capitalize">{{ item.metode_pembayaran }}</td>
                            <td class="border-b px-4 py-3">
                                <span :class="[
                                        'inline-block min-w-[90px] rounded-full px-2 py-1 text-center text-xs font-semibold',
                                        item.status_pembayaran === 'selesai'
                                            ? 'border border-green-400 bg-green-100 text-green-700'
                                            : item.status_pembayaran === 'gagal'
                                            ? 'border border-red-400 bg-red-100 text-red-700'
                                            : 'border border-yellow-400 bg-yellow-100 text-yellow-800',
                                    ]">
                                    {{ item.status_pembayaran.toUpperCase() }}
                                </span>
                            </td>
                            <td class="border-b px-4 py-3">
                                <span v-if="item.bukti_pembayaran">
                                    <a :href="`/storage/${item.bukti_pembayaran}`" target="_blank"
                                        class="text-blue-600 underline">Lihat Bukti</a>
                                </span>
                                <span v-else>-</span>
                            </td>
                            <td class="border-b px-4 py-3">
                                <button @click="lihatDetail(item.id)"
                                    class="transition-colors duration-200 cursor-pointer text-blue-500 hover:text-blue-700"
                                    title="Detail">
                                    <span>👁️</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
