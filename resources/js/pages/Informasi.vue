<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Informasi',
        href: '/informasi',
    },
];

const informationItems = [
    {
        title: 'Pengujian Air',
        description: 'Informasi tentang pengujian kualitas air',
        icon: '🔍',
    },
    {
        title: 'Pengujian Udara',
        description: 'Informasi tentang pengujian kualitas udara',
        icon: '💨',
    },
    {
        title: 'Pengujian Tanah',
        description: 'Informasi tentang pengujian kualitas tanah',
        icon: '🌱',
    },
];
</script>

<template>

    <Head title="Informasi" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen w-full bg-gray-50 dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold tracking-tight text-customGreen sm:text-5xl">
                        Informasi Laboratorium
                    </h1>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                        Temukan informasi lengkap tentang layanan pengujian laboratorium kami
                    </p>
                </div>

                <!-- Information Cards -->
                <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div v-for="(item, index) in informationItems" :key="index"
                        class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-6 transition-transform hover:scale-105">
                        <div class="text-4xl mb-4">{{ item.icon }}</div>
                        <h3 class="text-xl font-semibold text-customGreen mb-2">
                            {{ item.title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ item.description }}
                        </p>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="mt-16 bg-white dark:bg-gray-700 rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-customGreen mb-4">
                        Hubungi Kami
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300">
                        Untuk informasi lebih lanjut, silakan hubungi kami melalui:
                    </p>
                    <div class="mt-4 space-y-2">
                        <p class="flex items-center text-gray-600 dark:text-gray-300">
                            📧 Email: info@silanyar.com
                        </p>
                        <p class="flex items-center text-gray-600 dark:text-gray-300">
                            📞 Telepon: (0271) 123456
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>