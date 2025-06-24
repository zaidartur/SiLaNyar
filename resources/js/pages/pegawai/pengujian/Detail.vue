<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, defineProps } from 'vue';

interface Kategori {
    id: number;
    nama: string;
}

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    nama: string;
    user: User;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    instansi: Instansi;
}

interface Pengujian {
    id: number;
    kode_pengujian: string;
    form_pengajuan: Pengajuan;
    user: User;
    kategori: Kategori;
    tanggal_uji: string;
    jam_mulai: string;
    jam_selesai: string;
    status: 'diproses' | 'selesai';
}

const props = defineProps<{
    pengujian: Pengujian;
}>();

const page = usePage();
const permissions =
    (page.props.auth && Array.isArray((page.props.auth as any).permissions))
        ? ((page.props.auth as any).permissions as string[])
        : [];

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

const statusLabels: Record<string, string> = {
    diproses: 'Diproses',
    selesai: 'Selesai',
};

const statusFlow = {
    diproses: ['selesai'],
    selesai: [],
};

const availableStatus = computed(() => statusFlow[props.pengujian.status] || []);

function updateStatus(newStatus: string) {
    router.put(`/pegawai/pengujian/verifikasi/${props.pengujian.id}`, {
        status: newStatus,
    });
}

// function kembali() {
//     router.visit(route('pegawai.pengujian.index'));
// }
</script>

<template>
    <Head title="Detail Pengujian" />
    <AdminLayout>
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-black">Detail Pengujian</h1>
                <Link :href="route('pegawai.pengujian.index')" class="rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600"> Kembali </Link>
            </div>

            <!-- Informasi Pengujian -->
            <div class="rounded-lg bg-white p-6 shadow">
                <h2 class="mb-4 text-xl font-semibold text-customDarkGreen">Informasi Pengujian</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div><strong>Kode Pengujian:</strong> {{ props.pengujian.kode_pengujian }}</div>
                    <div><strong>Kode Pengajuan:</strong> {{ props.pengujian.form_pengajuan.kode_pengajuan }}</div>
                    <div><strong>Nama Instansi:</strong> {{ props.pengujian.form_pengajuan.instansi.nama ?? '-' }}</div>
                    <div><strong>Nama Pemohon:</strong> {{ props.pengujian.form_pengajuan.instansi.user.nama ?? '-' }}</div>
                    <div><strong>Nama Teknisi:</strong> {{ props.pengujian.user.nama ?? '-' }}</div>
                    <div><strong>Nama Kategori:</strong> {{ props.pengujian.kategori.nama ?? '-' }}</div>
                    <div><strong>Tanggal Pengujian:</strong> {{ formatTanggal(props.pengujian.tanggal_uji) ?? '-' }}</div>
                    <div><strong>Jam Mulai:</strong> {{ props.pengujian.jam_mulai ?? '-' }}</div>
                    <div><strong>Jam Selesai:</strong> {{ props.pengujian.jam_selesai ?? '-' }}</div>
                    <div>
                        <strong>Status:</strong>
                        <span
                            :class="[
                                'ml-2 inline-block rounded px-3 py-1 text-sm font-medium',
                                props.pengujian.status === 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800',
                            ]"
                        >
                            {{ statusLabels[props.pengujian.status] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Edit Status -->
            <div class="rounded-lg bg-white p-6 shadow" v-if="can('edit status pengujian') && availableStatus.length > 0">
                <h2 class="mb-4 text-xl font-semibold">Ubah Status Pengujian</h2>
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Status Saat Ini:</label>
                    <span class="inline-block rounded bg-gray-200 px-3 py-1 font-semibold text-customDarkGreen">
                        {{ statusLabels[props.pengujian.status] }}
                    </span>
                </div>
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Ubah Status:</label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="status in availableStatus"
                            :key="status"
                            type="button"
                            @click="updateStatus(status)"
                            class="rounded bg-green-700 px-4 py-2 font-semibold text-white transition hover:bg-green-800 focus:ring-2 focus:ring-green-400"
                        >
                            {{ statusLabels[status] }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
