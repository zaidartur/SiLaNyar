<script lang="ts" setup>
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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

const props = defineProps<{ aduan: Aduan }>();

const form = useForm({
    status: props.aduan.status,
    diverifikasi_oleh: '',
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
        <div class="p-6 space-y-6">
            <h1 class="text-3xl font-bold text-gray-800">📄 Detail Aduan</h1>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow space-y-3 text-sm text-gray-700">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><span class="font-medium">ID Aduan:</span> #{{ aduan.id }}</div>

                    <div>
                        <span class="font-medium">Tanggal Aduan:</span>
                        {{ new Date(aduan.created_at).toLocaleDateString() }}
                    </div>

                    <div>
                        <span class="font-medium">Pelapor:</span> {{ aduan.user.nama }}
                    </div>

                    <div>
                        <span class="font-medium">Instansi:</span>
                        {{ aduan.hasil_uji?.pengujian?.form_pengajuan?.instansi?.nama || '-' }}
                    </div>

                    <div>
                        <span class="font-medium">Terkait:</span>
                        <span :class="[
                            'inline-block px-2 py-0.5 rounded-full text-xs font-semibold',
                            aduan.terkait === 'administrasi'
                                ? 'bg-blue-100 text-blue-700'
                                : aduan.terkait === 'pengujian'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-100 text-gray-700'
                        ]">
                            {{ aduan.terkait }}
                        </span>
                    </div>

                    <div>
                        <span class="font-medium">Status:</span>
                        <span :class="[
                            'inline-block px-2 py-0.5 rounded-full text-xs font-semibold',
                            aduan.status === 'ditolak'
                                ? 'bg-red-100 text-red-700'
                                : aduan.status === 'diterima_administrasi'
                                    ? 'bg-blue-100 text-blue-700'
                                    : aduan.status === 'diterima_pengujian'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-yellow-100 text-yellow-700'
                        ]">
                            {{ aduan.status.replaceAll('_', ' ') }}
                        </span>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="font-medium">Untuk Hasil Uji:</span>
                        <span v-if="aduan.hasil_uji">
                            <span class="ml-1 font-mono">#{{ aduan.hasil_uji.id }}</span>
                            <span v-if="aduan.hasil_uji.kode" class="ml-1 text-gray-500">| {{ aduan.hasil_uji.kode
                                }}</span>
                        </span>
                        <span v-else>-</span>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="font-medium">Subjek:</span>
                        <p class="text-gray-800 mt-1">{{ aduan.masalah }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="font-medium">Deskripsi:</span>
                        <p class="text-gray-800 mt-1">{{ aduan.perbaikan }}</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-wrap gap-2 pt-2">
                <Link href="/pegawai/aduan"
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                ← Kembali
                </Link>

                <template v-if="aduan.status === 'diproses'">
                    <button v-if="aduan.terkait === 'administrasi'" @click="submit('diterima_administrasi')"
                        class="rounded-md bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">
                        ✅ Terima Administrasi
                    </button>

                    <button v-if="aduan.terkait === 'pengujian'" @click="submit('diterima_pengujian')"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">
                        ✅ Terima Pengujian
                    </button>

                    <button @click="submit('ditolak')"
                        class="rounded-md bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700">
                        ❌ Tolak Aduan
                    </button>
                </template>
            </div>
        </div>
    </AdminLayout>
</template>
