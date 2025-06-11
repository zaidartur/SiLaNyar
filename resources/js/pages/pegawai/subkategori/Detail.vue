<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { defineProps } from 'vue'

const props = defineProps({
    subkategori: {
        type: Array,
        required: true
    }
})
</script>

<template>
    <AdminLayout>
        <div class="container mx-auto p-6">
            <h1 class="text-2xl font-bold mb-4">Detail SubKategori</h1>
            <div v-if="subkategori && subkategori.length">
                <div class="mb-6 border p-4 rounded shadow" v-for="item in subkategori" :key="item.id">
                    <h2 class="text-xl font-semibold mb-2">Nama: {{ item.nama }}</h2>
                    <h3 class="text-lg font-medium mb-2">Daftar Parameter:</h3>
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border">Kode Parameter</th>
                                <th class="py-2 px-4 border">Nama Parameter</th>
                                <th class="py-2 px-4 border">Satuan</th>
                                <th class="py-2 px-4 border">Baku Mutu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="param in item.parameter" :key="param.id">
                                <td class="py-2 px-4 border">{{ param.kode_parameter }}</td>
                                <td class="py-2 px-4 border">{{ param.nama_parameter }}</td>
                                <td class="py-2 px-4 border">{{ param.satuan }}</td>
                                <td class="py-2 px-4 border">{{ param.pivot.baku_mutu }}</td>
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