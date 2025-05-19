<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3'

interface Permission {
  id: number
  name: string
}

const { permission } = defineProps<{
  permission: Permission[]
}>()

const form = useForm({
  name: '',
  permissions: [] as number[]
})

const submit = () => {
  form.post('/superadmin/role/store', {
    preserveScroll: true,
  })
}
</script>

<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Tambah Role Baru</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block mb-1">Nama Role</label>
        <input v-model="form.name" type="text" class="border rounded px-3 py-2 w-full" />
        <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
      </div>

      <div>
        <label class="block mb-1">Permissions</label>
        <div class="grid grid-cols-2 gap-2">
          <div v-for="perm in permission" :key="perm.id" class="flex items-center space-x-2">
            <input
              type="checkbox"
              :value="perm.id"
              v-model="form.permissions"
              class="form-checkbox"
            />
            <label>{{ perm.name }}</label>
          </div>
        </div>
      </div>

      <button
        type="submit"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
      >
        Simpan
      </button>
    </form>
  </div>
</template>
