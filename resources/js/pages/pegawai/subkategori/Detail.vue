<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { defineProps } from 'vue';

const { subkategori } = defineProps({
    subkategori: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <AdminLayout>
        <div class="container mx-auto p-6">
            <h1 class="mb-4 text-2xl font-bold">Detail SubKategori</h1>
            <div v-if="subkategori">
                <div class="mb-6 rounded border p-4 shadow">
                    <h2 class="mb-2 text-xl font-semibold">Nama: {{ subkategori.nama }}</h2>
                    <h3 class="mb-2 text-lg font-medium">Daftar Parameter:</h3>
                    <table class="min-w-full border bg-white">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Kode Parameter</th>
                                <th class="border px-4 py-2">Nama Parameter</th>
                                <th class="border px-4 py-2">Satuan</th>
                                <th class="border px-4 py-2">Baku Mutu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="param in subkategori.parameter" :key="param.id">
                                <td class="border px-4 py-2">{{ param.kode_parameter }}</td>
                                <td class="border px-4 py-2">{{ param.nama_parameter }}</td>
                                <td class="border px-4 py-2">{{ param.satuan }}</td>
                                <td class="border px-4 py-2">{{ param.pivot.baku_mutu }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div v-else>
                <p>Data subkategori tidak ditemukan.</p>
            </div>
        </div>
    </AdminLayout>
</template>
