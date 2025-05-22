<script setup lang="ts">
import { capitalize, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

// Mengambil data user yang sedang login
const user = computed(() => usePage().props.auth.user)

// Membuat computed property untuk inisial nama
const userInitials = computed(() => {
    if (!user.value?.nama) return ''
    return user.value.nama
        .split(' ')
        .slice(0, 2) // mengambil 2 kata pertama saja
        .map(word => word[0])
        .join('')
        .toUpperCase()
})
</script>

<template>
    <header class="bg-white shadow px-6 py-4 flex items-center justify-between">
        <h2 class="text-xl text-black font-bold">{{ capitalize(user.roles[0].name) }} Dashboard</h2>
        <div class="flex items-center space-x-3">
            <div class="text-right">
                <p class="text-black font-semibold">{{ user.nama }}</p>
                <p class="text-sm text-gray-500">{{ user.roles[0].name }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">
                {{userInitials}}
            </div>
        </div>
    </header>
</template>