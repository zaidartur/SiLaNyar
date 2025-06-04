<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { router, Head } from '@inertiajs/vue3'
import { ref } from 'vue'

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

const showDeleteModal = ref(false)
const deletingRole = ref<Role | null>(null)

const openDeleteModal = (role: Role) => {
  deletingRole.value = role
  showDeleteModal.value = true
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  deletingRole.value = null
}

const handleDelete = () => {
  if (!deletingRole.value) return
  router.delete(`/superadmin/role/${deletingRole.value.id}`, {
    onSuccess: () => closeDeleteModal()
  })
}
</script>

<template>

  <Head title="Role" />
  <AdminLayout>
    <div class="p-6 max-w-3xl mx-auto">
      <div class="flex justify-between items-center mb-6 rounded-lg bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Role</h1>
        <a href="/superadmin/role/create"
          class="flex items-center gap-2 bg-customDarkGreen hover:bg-green-800 text-white px-4 py-2 rounded-lg shadow transition">
          <span class="text-xl font-bold">+</span> Tambah Role
        </a>
      </div>
      <div>
        <div v-if="role.length === 0" class="text-center text-gray-500 py-10">
          Belum ada role yang terdaftar.
        </div>
        <div v-for="r in role" :key="r.id"
          class="border border-gray-200 bg-white rounded-xl mb-6 shadow-md hover:shadow-lg transition-shadow p-6">
          <div class="flex justify-between items-center mb-2">
            <div>
              <h2 class="text-lg font-semibold text-gray-800">{{ r.name }}</h2>
              <div class="flex flex-wrap gap-2 mt-2">
                <template v-if="r.permissions.length === 0">
                  <span class="text-xs text-gray-400 italic">Tidak ada permission</span>
                </template>
                <template v-else>
                  <span v-for="p in r.permissions" :key="p.id"
                    class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">
                    {{ p.name }}
                  </span>
                </template>
              </div>
            </div>
            <div class="flex gap-2">
              <a :href="`/superadmin/role/edit/${r.id}`"
                class="px-3 py-1 rounded bg-yellow-100 text-yellow-700 hover:bg-yellow-200 font-semibold text-sm transition">
                Edit
              </a>
              <button @click="openDeleteModal(r)"
                class="px-3 py-1 rounded bg-red-100 text-red-700 hover:bg-red-200 font-semibold text-sm transition">
                Hapus
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal Delete -->
      <Dialog :open="showDeleteModal" @update:open="closeDeleteModal">
        <DialogContent
          class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-lg shadow-xl p-6">
          <DialogHeader>
            <DialogTitle class="text-center text-xl font-bold text-gray-400">
              <div class="flex flex-col items-center">
                <svg width="64" height="64" viewBox="0 0 94 94" fill="none">
                  <path
                    d="M53.4152 15.4982C52.7777 14.3582 51.8477 13.4088 50.721 12.748C49.5943 12.0871 48.3118 11.7388 47.0056 11.7388C45.6994 11.7388 44.4169 12.0871 43.2902 12.748C42.1635 13.4088 41.2335 14.3582 40.596 15.4982L12.6721 65.4474C12.0475 66.5647 11.7257 67.8257 11.7387 69.1057C11.7516 70.3856 12.0989 71.6399 12.7461 72.7442C13.3932 73.8485 14.3178 74.7645 15.4281 75.4014C16.5384 76.0383 17.7959 76.3739 19.0758 76.3749H74.9118C76.1923 76.3749 77.4505 76.04 78.5616 75.4036C79.6727 74.7671 80.5981 73.8512 81.246 72.7467C81.8938 71.6422 82.2416 70.3875 82.2549 69.1071C82.2681 67.8267 81.9463 66.5651 81.3215 65.4474L53.4152 15.4982ZM51.406 60.2187C51.406 61.3873 50.9417 62.5081 50.1154 63.3344C49.2891 64.1607 48.1683 64.6249 46.9997 64.6249C45.8311 64.6249 44.7104 64.1607 43.884 63.3344C43.0577 62.5081 42.5935 61.3873 42.5935 60.2187C42.5935 59.0501 43.0577 57.9293 43.884 57.103C44.7104 56.2767 45.8311 55.8124 46.9997 55.8124C48.1683 55.8124 49.2891 56.2767 50.1154 57.103C50.9417 57.9293 51.406 59.0501 51.406 60.2187ZM44.0622 46.9999V32.3124C44.0622 31.5334 44.3717 30.7862 44.9226 30.2353C45.4735 29.6844 46.2206 29.3749 46.9997 29.3749C47.7788 29.3749 48.526 29.6844 49.0768 30.2353C49.6277 30.7862 49.9372 31.5334 49.9372 32.3124V46.9999C49.9372 47.779 49.6277 48.5262 49.0768 49.0771C48.526 49.628 47.7788 49.9374 46.9997 49.9374C46.2206 49.9374 45.4735 49.628 44.9226 49.0771C44.3717 48.5262 44.0622 47.779 44.0622 46.9999Z"
                    fill="#E94235" />
                </svg>
              </div>
            </DialogTitle>
          </DialogHeader>
          <div class="text-center">
            <p class="font-bold text-gray-900">
              HAPUS ROLE
            </p>
            <p class="text-gray-600">
              Apakah Anda yakin ingin menghapus role
              <span class="font-bold">{{ deletingRole?.name }}</span>?
            </p>
          </div>
          <div class="mt-6 flex justify-center gap-4">
            <button @click="closeDeleteModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
              Batal
            </button>
            <button @click="handleDelete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
              Hapus
            </button>
          </div>
        </DialogContent>
      </Dialog>
    </div>
  </AdminLayout>
</template>