<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    statistik: {
        perluVerifikasi: number;
        totalSelesai: number;
    };
    hasil_uji?: any[];
}>();
</script>

<template>
    <Head title="Dashboard Kepala Laboratorium" />
    <AdminLayout>
        <div class="bg-[#D9D9D9] min-h-screen w-full mx-auto p-4 sm:p-6 lg:p-8 rounded-xl space-y-8">
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Kepala Laboratorium Penguji</h1>
                    <p class="text-gray-500 text-sm mt-1">
                        Pemeriksaan teknis berkas Lhus, verifikasi hasil lab analis (setujui / tolak dengan komentar revisi), dan Tanda Tangan Elektronik (TTE Tahap 1).
                    </p>
                </div>
                <div>
                    <Link href="/pegawai/hasiluji" class="bg-customDarkGreen text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-800 transition">
                        Daftar Lembar Hasil Uji
                    </Link>
                </div>
            </div>

            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-16 h-16 bg-yellow-100 text-yellow-700 rounded-xl flex items-center justify-center font-bold text-3xl">
                        📝
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Menunggu Verifikasi & TTE Tahap 1</p>
                        <h3 class="text-4xl font-extrabold text-yellow-800">{{ statistik.perluVerifikasi }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Draf Lhus siap diverifikasi oleh Kepala Lab</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-16 h-16 bg-green-100 text-green-700 rounded-xl flex items-center justify-center font-bold text-3xl">
                        ✍️
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Lhus Terverifikasi / Selesai</p>
                        <h3 class="text-4xl font-extrabold text-customDarkGreen">{{ statistik.totalSelesai }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Telah disahkan dan diteruskan ke Kepala Dinas</p>
                    </div>
                </div>
            </div>

            <!-- Catatan Kerja Kepala Lab -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Ketentuan Verifikasi & TTE Kepala Lab</h3>
                <ul class="list-disc list-inside space-y-2 text-sm text-gray-600">
                    <li>Kepala Lab memeriksa akurasi perhitungan data dan kesesuaian baku mutu lingkungan pada draf Lhus.</li>
                    <li>Jika hasil belum sesuai atau meragukan: Dokumen dapat dikembalikan ke status revisi dengan menyertakan **catatan komentar revisi** spesifik bagi Analis.</li>
                    <li>Jika hasil telah valid: Kepala Lab membubuhkan Tanda Tangan Elektronik (TTE Tahap 1) dan dokumen diteruskan ke Kepala Dinas.</li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>
