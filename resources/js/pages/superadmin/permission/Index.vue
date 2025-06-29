<script setup lang="ts">

import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

interface Permission {
  id: number
  name: string
}

const { permission } = defineProps<{
  permission: Permission[]
}>()

// Search & Pagination
const search = ref('')
const currentPage = ref(1)
const pageSize = 10

const filteredPermission = computed(() => {
  if (!search.value) return permission
  return permission.filter((item: Permission) =>
    item.name.toLowerCase().includes(search.value.toLowerCase())
  )
})

const paginatedPermission = computed(() => {
  const start = (currentPage.value - 1) * pageSize
  return filteredPermission.value.slice(start, start + pageSize)
})

const totalPages = computed(() =>
  Math.ceil(filteredPermission.value.length / pageSize)
)

function goToPage(page: number) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}
</script>

<template>

  <Head title="Permission" />
  <AdminLayout>
    <div class="flex flex-col items-center justify-center min-h-[60vh] p-6">
      <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-6">
          <h1 class="text-2xl font-bold mb-2">Daftar Permission</h1>
          <input v-model="search" type="text" placeholder="Cari permission..."
            class="rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-200 w-full" />
        </div>

        <div class="overflow-x-auto rounded-lg shadow">
          <table class="min-w-full bg-white border border-gray-200 text-center">
            <thead>
              <tr class="bg-customDarkGreen">
                <th class="w-16 p-3 border-b border-gray-200 text-center text-white font-semibold tracking-wide">No</th>
                <th class="p-3 border-b border-gray-200 text-center text-white font-semibold tracking-wide">Nama</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(perm, index) in paginatedPermission" :key="perm.id"
                :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'" class="hover:bg-green-50 transition">
                <td class="p-3 border-b border-gray-200 text-center text-gray-700">{{ (currentPage - 1) * pageSize +
                  index + 1 }}</td>
                <td class="p-3 border-b border-gray-200 text-center text-gray-700">{{ perm.name }}</td>
              </tr>
              <tr v-if="paginatedPermission.length === 0">
                <td colspan="2" class="text-center text-gray-400 py-8">Tidak ada data permission.</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center items-center gap-2 mt-8">
          <button class="px-3 py-1 rounded border text-sm"
            :class="currentPage === 1 ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-100'"
            :disabled="currentPage === 1" @click="goToPage(currentPage - 1)">
            Prev
          </button>
          <span class="text-sm">Halaman {{ currentPage }} dari {{ totalPages }}</span>
          <button class="px-3 py-1 rounded border text-sm"
            :class="currentPage === totalPages ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-100'"
            :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)">
            Next
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
