<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    statistik: {
        pengajuan: number;
        jadwal: number;
        pengujian: number;
        hasil_uji: number;
    };
    pengajuan?: any[];
}>();
</script>

<template>
    <Head title="Dashboard Pengendali Teknis" />
    <AdminLayout>
        <div class="bg-[#D9D9D9] min-h-screen w-full mx-auto p-4 sm:p-6 lg:p-8 rounded-xl space-y-8">
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Pengendali Teknis</h1>
                    <p class="text-gray-500 text-sm mt-1">
                        Kendali mutu permohonan, verifikasi jadwal PPCU, evaluasi kesesuaian contoh uji, dan penyusunan draf Lhus.
                    </p>
                </div>
                <div class="flex gap-2">
                    <Link href="/pegawai/pengambilan" class="bg-customDarkGreen text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-800 transition">
                        Kelola Pengambilan (PPCU)
                    </Link>
                    <Link href="/pegawai/hasiluji" class="bg-green-700 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-800 transition">
                        Draf Lhus
                    </Link>
                </div>
            </div>

            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-14 h-14 bg-green-100 text-green-700 rounded-xl flex items-center justify-center font-bold text-2xl">
                        📋
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Pengajuan Masuk</p>
                        <h3 class="text-3xl font-extrabold text-customDarkGreen">{{ statistik.pengajuan }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center font-bold text-2xl">
                        🚚
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Jadwal PPCU</p>
                        <h3 class="text-3xl font-extrabold text-blue-800">{{ statistik.jadwal }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-14 h-14 bg-yellow-100 text-yellow-700 rounded-xl flex items-center justify-center font-bold text-2xl">
                        🧪
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Proses Pengujian</p>
                        <h3 class="text-3xl font-extrabold text-yellow-800">{{ statistik.pengujian }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-14 h-14 bg-purple-100 text-purple-700 rounded-xl flex items-center justify-center font-bold text-2xl">
                        📄
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Hasil Uji / Lhus</p>
                        <h3 class="text-3xl font-extrabold text-purple-800">{{ statistik.hasil_uji }}</h3>
                    </div>
                </div>
            </div>

            <!-- Info Alur Kerja DLH -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Tanggung Jawab Operasional Pengendali Teknis</h3>
                <ul class="list-disc list-inside space-y-2 text-sm text-gray-600">
                    <li>Menerima disposisi permohonan pengambilan contoh uji dan menyusun jadwal tugas PPCU.</li>
                    <li>Melakukan verifikasi kesesuaian contoh uji di loket laboratorium (sampel mandiri dievaluasi Sesuai/Tidak Sesuai; sampel petugas PPCU otomatis memenuhi syarat).</li>
                    <li>Mengalokasikan contoh uji yang sesuai kepada Penyelia untuk diteruskan ke Analis.</li>
                    <li>Menyusun draf Lembar Hasil Uji Sementara (Lhus) sebelum diteruskan untuk verifikasi Kepala Lab.</li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>
