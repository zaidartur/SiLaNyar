<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    jadwal: any;
    from: string;
}>();

const formatTanggal = (tanggalStr: string) => {
    if (!tanggalStr) return '-';
    const date = new Date(tanggalStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

// Ambil query parameter "from" dari URL
const backLink = computed(() => `/customer/jadwal/${props.from}`);
</script>

<template>
    <Head title="Detail Jadwal" />
    <CustomerLayout>
        <div class="mx-auto max-w-3xl p-4">
            <h1 class="mb-6 text-2xl font-bold text-customDarkGreen">Detail Jadwal</h1>
            <div class="mb-6 rounded-xl border bg-white p-6 shadow-lg">
                <div class="grid grid-cols-1 gap-x-8 gap-y-4 md:grid-cols-2">
                    <div>
                        <span class="font-bold text-customDarkGreen">Kode Pengambilan:</span>
                        <span class="ml-2">{{ props.jadwal.kode_pengambilan ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Kode Pengajuan:</span>
                        <span class="ml-2">{{ props.jadwal.form_pengajuan?.kode_pengajuan ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Nama Instansi:</span>
                        <span class="ml-2">{{ props.jadwal.form_pengajuan?.instansi?.nama ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Nama Pemohon:</span>
                        <span class="ml-2">{{ props.jadwal.form_pengajuan?.instansi?.user?.nama ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Kategori:</span>
                        <span class="ml-2">{{ props.jadwal.form_pengajuan?.kategori?.nama ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Metode Pengambilan:</span>
                        <span class="ml-2">{{ props.jadwal.form_pengajuan?.metode_pengambilan ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Waktu Pengantaran:</span>
                        <span class="ml-2">{{ formatTanggal(props.jadwal.waktu_pengambilan) }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Keterangan:</span>
                        <span class="ml-2">{{ props.jadwal.keterangan ? props.jadwal.keterangan : '-' }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-customDarkGreen">Status:</span>
                        <span class="ml-2 capitalize">{{ props.jadwal.status ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <Link :href="backLink" class="mr-2 rounded bg-gray-200 px-4 py-2 font-semibold text-black">Kembali</Link>
            </div>
        </div>
    </CustomerLayout>
</template>
