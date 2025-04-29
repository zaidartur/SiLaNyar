<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'

defineProps<{
    totalUsers: number
    totalSamples: number
}>()
</script>

<template>
    <AdminLayout>

        <Head title="Dashboard Admin" />

        <h1 class="text-2xl text-black font-semibold mb-6">Dashboard Admin</h1>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg text-black font-medium">Total Pengguna</h3>
                <p class="text-3xl font-bold mt-2">{{ totalUsers }}</p>
            </div>
            <!-- ...more stats cards... -->
        </div>
    </AdminLayout>
</template>