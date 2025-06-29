<script lang="ts" setup>
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Aduan {
    id: number;
    masalah: string;
    perbaikan: string;
    terkait: string;
    status: string;
    created_at: string;
    user: {
        nama: string;
    };
    hasil_uji?: {
        id: number;
        kode?: string;
        pengujian?: {
            form_pengajuan?: {
                instansi?: {
                    nama: string;
                    user?: {
                        nama: string;
                    };
                };
            };
        };
    };
}

defineProps<{
    aduan: Aduan[];
}>();
</script>

<template>

    <Head title="Daftar Aduan" />
    <AdminLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-3xl font-bold text-gray-800">📋 Daftar Aduan</h1>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                <div v-for="item in aduan" :key="item.id"
                    class="rounded-xl border border-gray-200 bg-white p-5 shadow hover:shadow-lg transition-all duration-200">
                    <!-- Header Status -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-xs text-gray-500">
                            ID Aduan: <span class="font-semibold text-gray-800">#{{ item.id }}</span>
                        </div>
                        <span :class="[
                            'px-2 py-1 rounded-full text-xs font-semibold border capitalize',
                            item.status === 'ditolak'
                                ? 'bg-red-100 text-red-700 border-red-300'
                                : item.status === 'diterima_administrasi'
                                    ? 'bg-blue-100 text-blue-700 border-blue-300'
                                    : item.status === 'diterima_pengujian'
                                        ? 'bg-green-100 text-green-700 border-green-300'
                                        : 'bg-yellow-100 text-yellow-700 border-yellow-300'
                        ]">
                            {{ item.status.replaceAll('_', ' ') }}
                        </span>
                    </div>

                    <!-- Detail Konten -->
                    <div class="space-y-2 text-sm text-gray-700">
                        <p>
                            <span class="font-semibold">Untuk Hasil Uji:</span>
                            <span v-if="item.hasil_uji">
                                <span class="ml-1 font-mono">#{{ item.hasil_uji.id }}</span>
                                <span v-if="item.hasil_uji.kode" class="ml-1 text-gray-500">| {{ item.hasil_uji.kode
                                    }}</span>
                            </span>
                            <span v-else>-</span>
                        </p>

                        <p><span class="font-semibold">Nama Pelapor:</span> {{ item.user.nama }}</p>
                        <p><span class="font-semibold">Instansi:</span> {{
                            item.hasil_uji?.pengujian?.form_pengajuan?.instansi?.nama || '-' }}</p>
                        <p><span class="font-semibold">Tanggal Aduan:</span> {{ new
                            Date(item.created_at).toLocaleDateString() }}</p>

                        <p>
                            <span class="font-semibold">Terkait:</span>
                            <span :class="[
                                'inline-block ml-1 px-2 py-0.5 rounded-full text-xs font-medium',
                                item.terkait === 'administrasi'
                                    ? 'bg-blue-100 text-blue-700'
                                    : item.terkait === 'pengujian'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-gray-100 text-gray-700'
                            ]">
                                {{ item.terkait }}
                            </span>
                        </p>

                        <p><span class="font-semibold">Subjek:</span> {{ item.masalah }}</p>
                        <p><span class="font-semibold">Deskripsi:</span> {{ item.perbaikan }}</p>
                    </div>

                    <!-- Button -->
                    <div class="mt-4 text-right">
                        <Link :href="`/pegawai/aduan/${item.id}`"
                            class="inline-block rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 shadow transition">
                        Lihat Detail
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
