<script setup lang="ts">
import AdminSidebar from '@/components/admin/Sidebar.vue'
import AdminHeader from '@/components/admin/Header.vue'

</script>

<template>
    <div class="flex h-screen">
        <AdminSidebar />
        <div class="flex-1 flex flex-col bg-gray-100">
            <AdminHeader />
            <main class="flex-1 overflow-y-auto p-4">
                <slot />
            </main>
        </div>
    </div>
</template>