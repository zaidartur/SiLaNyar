<script lang="ts" setup>
import { router } from '@inertiajs/vue3'

interface Permission {
  id: number
  name: string
}

const { permission } = defineProps<{
  permission: Permission[]
}>()

const deletePermission = (id: number) => {
  if (confirm('Yakin ingin menghapus permission ini?')) {
    router.delete(`/superadmin/permission/${id}`)
  }
}
</script>

<template>
  <div class="p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Daftar Permission</h1>
    </div>

    <table class="min-w-full border">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th class="p-2 border">#</th>
          <th class="p-2 border">Nama</th>
          <th class="p-2 border">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(perm, index) in permission" :key="perm.id">
          <td class="p-2 border">{{ index + 1 }}</td>
          <td class="p-2 border">{{ perm.name }}</td>
          <td class="p-2 border space-x-2">
            <button @click="deletePermission(perm.id)" class="text-red-600 hover:underline">Hapus</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
