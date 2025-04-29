<script setup lang="ts">
import AdminSidebar from '@/components/admin/Sidebar.vue'
import AdminHeader from '@/components/admin/Header.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({})

const handleLogout = () => {
    form.post(route('admin.logout'))
}
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Admin Sidebar -->
        <AdminSidebar />


        <div class="lg:ml-64">
            <!-- Admin Header -->
            <AdminHeader @logout="handleLogout" />


            <!-- Main Content -->
            <main class="p-8">
                <slot />
            </main>
        </div>
    </div>
</template>