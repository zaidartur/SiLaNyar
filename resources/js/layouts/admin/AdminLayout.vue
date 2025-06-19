<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import AdminSidebar from '@/components/admin/Sidebar.vue'
import AdminHeader from '@/components/admin/Header.vue'

const windowWidth = ref(window.innerWidth)
const isSidebarOpen = ref(window.innerWidth >= 1024)

const handleResize = () => {
    windowWidth.value = window.innerWidth
    isSidebarOpen.value = window.innerWidth >= 1024
}

onMounted(() => {
    window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
    window.removeEventListener('resize', handleResize)
})

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}
</script>

<template>
    <div class="flex h-screen">
        <AdminSidebar :isSidebarOpen="isSidebarOpen" :windowWidth="windowWidth" />
        <div class="flex-1 flex flex-col bg-gray-100">
            <AdminHeader @toggle-sidebar="toggleSidebar" />
            <main class="flex-1 overflow-y-auto p-4">
                <slot />
            </main>
        </div>
    </div>
</template>