<script lang="ts" setup>
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

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
        <div class="space-y-6 p-6">
            <h1 class="text-2xl font-bold">Daftar Aduan</h1>

            <div class="space-y-4">
                <div v-for="item in aduan" :key="item.id" class="rounded-lg border bg-white p-4 shadow-sm">
                    <div class="grid grid-cols-1 gap-2 text-sm md:grid-cols-2 lg:grid-cols-3">
                        <div><strong>ID Aduan:</strong> {{ item.id }}</div>
                        <div><strong>Nama Pelapor:</strong> {{ item.user.nama }}</div>
                        <div>
                            <strong>Instansi:</strong>
                            {{ item.hasil_uji?.pengujian?.form_pengajuan?.instansi?.nama || '-' }}
                        </div>
                        <div><strong>Tanggal:</strong> {{ new Date(item.created_at).toLocaleDateString() }}</div>
                        <div>
                            <strong>Terkait:</strong> <span class="text-purple-600">{{ item.terkait }}</span>
                        </div>
                        <div><strong>Subjek:</strong> {{ item.masalah }}</div>
                        <div><strong>Deskripsi:</strong> {{ item.perbaikan }}</div>
                        <div>
                            <strong>Status:</strong> <span class="text-blue-600">{{ item.status }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <Link
                            :href="`/pegawai/aduan/${item.id}`"
                            class="inline-block rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                        >
                            Lihat Detail
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
