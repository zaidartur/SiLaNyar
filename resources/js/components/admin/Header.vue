<script setup lang="ts">
import { capitalize, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const rolePriority = ['superadmin', 'admin', 'teknisi']

const userPrimaryRole = computed(() => {
    const roles = user.value?.roles || []
    const sorted = [...roles].sort(
        (a, b) => rolePriority.indexOf(a.name) - rolePriority.indexOf(b.name)
    )
    return sorted[0]?.name || ''
})


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
        <div class="flex items-center gap-3">
            <button @click="$emit('toggle-sidebar')" type="button"
                class="inline-flex items-center p-2 text-sm text-white bg-customDarkGreen rounded-lg lg:hidden hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-200">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                    </path>
                </svg>
            </button>
            <h2 class="text-xl text-black font-bold">{{ capitalize(userPrimaryRole) }} Dashboard</h2>
        </div>
        <div class="flex items-center space-x-3">
            <div class="text-right">
                <p class="text-black font-semibold">{{ user.nama }}</p>
                <p class="text-sm text-gray-500">{{ (userPrimaryRole) }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">
                {{userInitials}}
            </div>
        </div>
    </header>
</template>