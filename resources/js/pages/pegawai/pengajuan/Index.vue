<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    nama: string;
    user: User;
}

interface JenisCairan {
    id: number;
    nama: string;
}

interface Parameter {
    id: number;
    nama_parameter: string;
}

interface Kategori {
    id: number;
    nama: string;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    volume_sampel: number;
    status_pengajuan: string;
    metode_pengambilan: string;
    lokasi: string;
    instansi: Instansi;
    kategori: Kategori;
    jenis_cairan: JenisCairan;
    parameter: Parameter[];
}

const props = defineProps<{
    pengajuan: Pengajuan[];
    filter: {
        status: string;
    };
}>();

const status = ref(props.filter.status ?? '');

const handleFilter = () => {
    router.get(
        '/pegawai/pengajuan',
        {
            status: status.value || undefined,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};

watch([status], () => {
    handleFilter();
});
</script>

<template>

    <Head title="Daftar Pengajuan" />
    <AdminLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-black">DAFTAR PENGAJUAN</h1>
                <!-- <Link :href="route('pegawai.pengajuan.create')"
                    class="bg-customDarkGreen text-white px-4 py-2 rounded flex items-center gap-2">
                <span>+</span> Tambah
                </Link> -->
            </div>

            <!-- Filter -->
            <div class="mb-6 flex items-end gap-4">
                <!-- Status Filter -->
                <div class="flex flex-col">
                    <label for="status" class="mb-1 text-sm font-medium text-gray-700">Status</label>
                    <select id="status" v-model="status"
                        class="rounded border-gray-300 bg-customDarkGreen px-2 py-1 text-white">
                        <option value="">Semua Status</option>
                        <option value="proses_validasi">Proses Validasi</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full overflow-hidden rounded-xl bg-white shadow">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="rounded-tl-xl px-4 py-3 text-left font-semibold">ID Pengajuan</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Pemohon</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Instansi</th>
                            <th class="px-4 py-3 text-left font-semibold">Jenis Cairan</th>
                            <th class="px-4 py-3 text-left font-semibold">Kategori</th>
                            <th class="px-4 py-3 text-left font-semibold">Parameter</th>
                            <th class="px-4 py-3 text-left font-semibold">Volume Sampel</th>
                            <th class="px-4 py-3 text-left font-semibold">Metode Pengambilan</th>
                            <th class="px-4 py-3 text-left font-semibold">Status Pengajuan</th>
                            <th class="rounded-tr-xl px-4 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in props.pengajuan" :key="item.id"
                            :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-200'">
                            <td class="border-b px-4 py-3">{{ item.kode_pengajuan }}</td>
                            <td class="border-b px-4 py-3">{{ item.instansi.user.nama }}</td>
                            <td class="border-b px-4 py-3">{{ item.instansi?.nama ?? '-' }}</td>
                            <td class="border-b px-4 py-3">{{ item.jenis_cairan.nama }}</td>
                            <td class="border-b px-4 py-3">{{ item.kategori?.nama }}</td>
                            <td class="border-b px-4 py-3">
                                <ul class="ml-4 list-disc">
                                    <li v-for="param in item.parameter" :key="param.id">
                                        {{ param.nama_parameter }}
                                    </li>
                                </ul>
                            </td>
                            <td class="border-b px-4 py-3">{{ item.volume_sampel }}</td>
                            <td class="border-b px-4 py-3">{{ item.metode_pengambilan }}</td>
                            <td class="border-b px-4 py-3">
                                <span :class="[
                                        'inline-block min-w-[110px] rounded-full px-2 py-1 text-center text-xs font-semibold',
                                        item.status_pengajuan === 'diterima'
                                            ? 'border border-green-400 bg-green-100 text-green-700'
                                            : item.status_pengajuan === 'ditolak'
                                              ? 'border border-red-400 bg-red-100 text-red-700'
                                              : 'border border-yellow-400 bg-yellow-100 text-yellow-800',
                                    ]">
                                    {{ item.status_pengajuan.replace('_', ' ').toUpperCase() }}
                                </span>
                            </td>
                            <td class="border-b px-4 py-3">
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
