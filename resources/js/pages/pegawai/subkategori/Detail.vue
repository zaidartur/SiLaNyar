<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head } from '@inertiajs/vue3'
import { defineProps } from 'vue';

const { subkategori } = defineProps({
    subkategori: {
        type: Object,
        required: true,
    },
});
</script>

<template>

    <Head title="Detail SubKategori" />
    <AdminLayout>
        <div class="container mx-auto p-6">
            <div class="mb-6 flex items-center gap-4">
                <h1 class="text-3xl font-extrabold text-customDarkGreen">Detail SubKategori</h1>
            </div>
            <div v-if="subkategori">
                <div class="mb-8 rounded-xl border border-green-200 bg-white p-6 shadow-lg">
                    <h2 class="mb-4 text-2xl font-bold text-green-700 flex items-center gap-2">
                        {{ subkategori.nama }}
                    </h2>
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">Daftar Parameter</h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-green-100 text-green-800">
                                    <th class="border-b px-6 py-3 text-left text-sm font-bold">Kode Parameter</th>
                                    <th class="border-b px-6 py-3 text-left text-sm font-bold">Nama Parameter</th>
                                    <th class="border-b px-6 py-3 text-left text-sm font-bold">Satuan</th>
                                    <th class="border-b px-6 py-3 text-left text-sm font-bold">Baku Mutu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="param in subkategori.parameter" :key="param.id"
                                    class="hover:bg-green-50 transition">
                                    <td class="border-b px-6 py-3">{{ param.kode_parameter }}</td>
                                    <td class="border-b px-6 py-3">{{ param.nama_parameter }}</td>
                                    <td class="border-b px-6 py-3">{{ param.satuan }}</td>
                                    <td class="border-b px-6 py-3">
                                        <span
                                            class="inline-block rounded bg-green-200 px-2 py-1 text-xs font-semibold text-green-800">
                                            {{ param.pivot.baku_mutu }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="!subkategori.parameter || subkategori.parameter.length === 0">
                                    <td colspan="4" class="text-center text-gray-400 py-6">Tidak ada parameter.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="rounded bg-red-100 text-red-700 p-4 text-center font-semibold">
                    Data subkategori tidak ditemukan.
                </div>
            </div>
        </div>
    </AdminLayout>
</template>