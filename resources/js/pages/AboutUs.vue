<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'About Us',
        href: '/about-us',
    },
];
</script>

<template>

    <Head title="About Us" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen w-full bg-white dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-customGreen sm:text-5xl md:text-6xl">
                        About Us
                    </h1>
                    <div class="mx-auto mt-8 max-w-3xl">
                        <p class="text-xl text-gray-600 dark:text-gray-300">
                            Selamat datang di SiLaNyar - Sistem Laboratorium Lingkungan Terpadu
                        </p>
                    </div>
                </div>

                <div class="mt-16 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Content Cards -->
                    <div class="rounded-lg bg-gray-50 p-6 shadow-sm dark:bg-gray-700">
                        <h2 class="mb-4 text-xl font-semibold text-customGreen">Visi</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            Menjadi sistem laboratorium lingkungan terpadu yang unggul dan terpercaya.
                        </p>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-6 shadow-sm dark:bg-gray-700">
                        <h2 class="mb-4 text-xl font-semibold text-customGreen">Misi</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            Menyediakan layanan pengujian lingkungan yang akurat dan profesional.
                        </p>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-6 shadow-sm dark:bg-gray-700">
                        <h2 class="mb-4 text-xl font-semibold text-customGreen">Tujuan</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            Mendukung pemantauan dan pengelolaan kualitas lingkungan di Kabupaten Karanganyar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>