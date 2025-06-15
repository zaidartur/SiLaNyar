<script lang="ts" setup>
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

interface Aduan {
    id: number;
    status: string;
    terkait: string;
    masalah: string;
    perbaikan: string;
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

const props = defineProps<{
    aduan: Aduan;
}>();

const form = useForm({
    status: props.aduan.status,
    diverifikasi_oleh: '', // Diisi otomatis di backend
});

function submit(status: string) {
    form.status = status;
    form.put(`/pegawai/aduan/verifikasi/${props.aduan.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Detail Aduan" />
    <AdminLayout>
        <div class="space-y-6 p-6">
            <h1 class="text-2xl font-bold">Detail Aduan</h1>

            <div class="space-y-2 rounded-md border bg-white p-4 text-sm shadow">
                <p><strong>ID Aduan:</strong> {{ aduan.id }}</p>
                <p><strong>Pelapor:</strong> {{ aduan.user.nama }}</p>
                <p><strong>Instansi:</strong> {{ aduan.hasil_uji?.pengujian?.form_pengajuan?.instansi?.nama || '-' }}</p>
                <p><strong>Tanggal:</strong> {{ new Date(aduan.created_at).toLocaleDateString() }}</p>
                <p><strong>Terkait:</strong> {{ aduan.terkait }}</p>
                <p><strong>Subjek:</strong> {{ aduan.masalah }}</p>
                <p><strong>Deskripsi:</strong> {{ aduan.perbaikan }}</p>
                <p>
                    <strong>Status:</strong> <span class="text-blue-600">{{ aduan.status }}</span>
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <Link href="/pegawai/aduan" class="rounded bg-gray-300 px-4 py-2 transition hover:bg-gray-400"> Kembali </Link>

                <!-- Tampilkan button sesuai kolom 'terkait' -->
                <button
                    v-if="aduan.terkait === 'administrasi'"
                    @click="submit('diterima_administrasi')"
                    class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700"
                >
                    Terima Administrasi
                </button>

                <button
                    v-if="aduan.terkait === 'pengujian'"
                    @click="submit('diterima_pengujian')"
                    class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                >
                    Terima Pengujian
                </button>

                <button @click="submit('ditolak')" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">Tolak Aduan</button>
            </div>
        </div>
    </AdminLayout>
</template>
