<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { router, Head } from '@inertiajs/vue3'

interface Permission {
  id: number
  name: string
}

interface Role {
  id: number
  name: string
  permissions: Permission[]
}

defineProps<{
  role: Role[]
}>()

const deleteRole = (id: number) => {
  if (confirm('Yakin ingin menghapus role ini?')) {
    router.delete(`/superadmin/role/${id}`)
  }
}
</script>

<template>

  <Head title="Role" />
  <AdminLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Daftar Role</h1>
      <div class="mb-4">
        <a href="/superadmin/role/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          + Tambah Role
        </a>
      </div>
      <div v-for="r in role" :key="r.id" class="border p-4 rounded mb-4 shadow-sm">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-xl font-semibold">{{ r.name }}</h2>
            <p class="text-sm text-gray-600">
              Permissions:
              <span v-if="r.permissions.length === 0">Tidak ada</span>
              <span v-else>
                <span v-for="(p, i) in r.permissions" :key="p.id">
                  {{ p.name }}<span v-if="i < r.permissions.length - 1">, </span>
                </span>
              </span>
            </p>
          </div>
          <div class="flex gap-2">
            <a :href="`/superadmin/role/edit/${r.id}`" class="text-blue-500 hover:underline">Edit</a>
            <button @click="deleteRole(r.id)" class="text-red-600 hover:underline">Hapus</button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
