<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    statistik: {
        perluTTE: number;
        totalSelesai: number;
        totalPengajuan: number;
    };
    hasil_uji?: any[];
}>();
</script>

<template>
    <Head title="Dashboard Kepala Dinas Lingkungan Hidup" />
    <AdminLayout>
        <div class="bg-[#D9D9D9] min-h-screen w-full mx-auto p-4 sm:p-6 lg:p-8 rounded-xl space-y-8">
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Kepala Dinas Lingkungan Hidup</h1>
                    <p class="text-gray-500 text-sm mt-1">
                        Otoritas pengesahan tertinggi pelayanan laboratorium, pembubuhan TTE Tahap 2 (Final) untuk penerbitan Laporan Hasil Uji (LHU) resmi.
                    </p>
                </div>
                <div class="flex gap-2">
                    <Link href="/pegawai/hasiluji" class="bg-customDarkGreen text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-800 transition">
                        Pengesahan LHU (TTE)
                    </Link>
                    <Link href="/pegawai/laporan-keuangan" class="bg-green-700 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-800 transition">
                        Laporan Retribusi
                    </Link>
                </div>
            </div>

            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-16 h-16 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center font-bold text-3xl">
                        🖋️
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Menunggu TTE Final Kepala Dinas</p>
                        <h3 class="text-4xl font-extrabold text-blue-800">{{ statistik.perluTTE }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Lhus lolos verifikasi Kepala Lab</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-16 h-16 bg-green-100 text-green-700 rounded-xl flex items-center justify-center font-bold text-3xl">
                        📜
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Total LHU Resmi Terbit</p>
                        <h3 class="text-4xl font-extrabold text-customDarkGreen">{{ statistik.totalSelesai }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Telah disahkan dan siap diunduh pelanggan</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-16 h-16 bg-purple-100 text-purple-700 rounded-xl flex items-center justify-center font-bold text-3xl">
                        🏢
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Akumulasi Pelayanan Uji</p>
                        <h3 class="text-4xl font-extrabold text-purple-800">{{ statistik.totalPengajuan }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Seluruh pengajuan pelanggan DLH</p>
                    </div>
                </div>
            </div>

            <!-- Alur Pengesahan -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Pengesahan Akhir Laporan Hasil Uji (LHU)</h3>
                <ul class="list-disc list-inside space-y-2 text-sm text-gray-600">
                    <li>Kepala Dinas membubuhkan Tanda Tangan Elektronik resmi (TTE Tahap 2 / Final).</li>
                    <li>Setelah disahkan, dokumen resmi berstatus LHU dan diteruskan ke Staf Administrator.</li>
                    <li>Pelanggan dapat mengunduh LHU resmi setelah menuntaskan pengisian formulir survei kepuasan dan rating IKM.</li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>
