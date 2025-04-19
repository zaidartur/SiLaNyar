<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

defineProps({
    src: String,
    alt: String,
});
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Remove padding and make container full width -->
        <div class="flex min-h-screen w-full flex-col">
            <!-- Hero Section - Full Width & Height -->
            <section class="relative h-screen w-full bg-cover bg-center"
                style="background-image: url('/storage/assetslandingpage/hero.png')">
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white">
                    <div class="text-center">
                        <h2 class="text-4xl font-bold tracking-tight sm:text-5xl md:text-6xl">
                            Sistem Laboratorium Lingkungan Terpadu
                        </h2>
                        <p class="mx-auto mt-6 max-w-2xl text-lg leading-8">
                            Manajemen dan monitoring laboratorium lingkungan yang komprehensif untuk memantau
                            kualitas lingkungan di Kabupaten Karanganyar.
                        </p>
                        <div class="mt-10">
                            <button
                                class="rounded-lg bg-orange-400 px-6 py-3 text-lg font-semibold text-white transition-colors hover:bg-orange-500">
                                Pelajari Sistem Lab
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main Content -->
            <section
                class="flex flex-col items-start p-5 mx-auto my-0 bg-white max-w-[1200px] max-md:max-w-[991px] max-sm:max-w-screen-sm">
                <!-- Header -->
                <header class="flex flex-col items-center mb-5 w-full max-sm:items-start">
                    <h1 class="text-4xl font-bold text-green-700 max-sm:text-3xl">
                        Sistem Laboratorium
                    </h1>
                    <p class="text-xl font-medium text-black max-sm:text-base">
                        SiLanYar menyediakan manajemen laboratorium lingkungan yang terintegrasi
                        dan efisien
                    </p>
                </header>

                <!-- Content -->
                <div class="flex flex-row items-start w-full max-md:flex-col">
                    <!-- Left Section -->
                    <article class="flex-1 pr-5 left-section">
                        <h2 class="mb-5 text-4xl font-bold text-green-700 max-sm:text-3xl">
                            Manajemen Lab yang Komprehensif
                        </h2>
                        <p class="mb-5 text-xl text-black max-sm:text-base">
                            SIMLEB (Sistem Informasi Manajemen Laboratorium Lingkungan Hidup)
                            adalah platform terintegrasi yang dirancang khusus untuk mengelola
                            seluruh proses laboratorium lingkungan Kabupaten Karanganyar secara
                            efisien dan akurat.
                        </p>
                        <p class="mb-5 text-xl text-black max-sm:text-base">
                            Sistem ini menghubungkan semua tahapan analisis laboratorium dari
                            pengambilan sampel hingga pelaporan hasil, memungkinkan pelacakan dan
                            dokumentasi lengkap untuk memenuhi standar akreditasi laboratorium
                            nasional.
                        </p>

                        <!-- Feature List -->
                        <div class="flex flex-col gap-5">
                            <div class="flex items-start gap-4">
                                <img src="/storage/assetslandingpage/ManajemenSample.png" alt=""
                                    class="w-12 h-12 object-contain" />
                                <div>
                                    <h3 class="text-xl font-semibold text-green-700 mb-2">Manajemen Sampel</h3>
                                    <p class="text-gray-600">Sistem penomoran, pelacakan, dan penyimpanan sampel secara
                                        digital dengan barcode terintegrasi.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <img src="/storage/assetslandingpage/PengujianStandar.png" alt=""
                                    class="w-12 h-12 object-contain" />
                                <div>
                                    <h3 class="text-xl font-semibold text-green-700 mb-2">Pengujian Standar</h3>
                                    <p class="text-gray-600">Database metode pengujian standar berdasarkan SNI dan ISO
                                        untuk parameter lingkungan.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <img src="/storage/assetslandingpage/QualityControl.png" alt=""
                                    class="w-12 h-12 object-contain" />
                                <div>
                                    <h3 class="text-xl font-semibold text-green-700 mb-2">Quality Control</h3>
                                    <p class="text-gray-600">Modul QC terintegrasi untuk memantau kontrol kualitas
                                        laboratorium dan validasi hasil.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <img src="/storage/assetslandingpage/AnalisisData.png" alt=""
                                    class="w-12 h-12 object-contain" />
                                <div>
                                    <h3 class="text-xl font-semibold text-green-700 mb-2">Analisis Data</h3>
                                    <p class="text-gray-600">Tools analisis data canggih untuk interpretasi hasil dan
                                        visualisasi tren parameter lingkungan.</p>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Right Section -->
                    <aside class="shrink-0 right-section max-md:mt-5">
                        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/f27eb6aae04e74c10e0e725027caf7edafe2587b?placeholderIfAbsent=true"
                            alt="Lab Image" class="rounded-3xl h-[400px] w-[500px] max-sm:w-full max-sm:h-auto" />
                    </aside>
                </div>
            </section>

            <!-- Additional Sections -->
            <section class="w-full bg-white py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <!-- Add your content here -->
                </div>
            </section>
        </div>
    </AppLayout>
</template>
