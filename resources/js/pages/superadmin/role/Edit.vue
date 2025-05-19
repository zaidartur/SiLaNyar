<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3'

interface Permission {
  id: number
  name: string
}

interface Role {
  id: number
  name: string
  permissions: Permission[]
}

const props = defineProps<{
  roles: Role
  permissions: Permission[]
}>()

const form = useForm({
  name: props.roles.name,
  permissions: props.roles.permissions.map(p => p.id) as number[]
})

const submit = () => {
  form.put(`/superadmin/role/${props.roles.id}/edit`, {
    preserveScroll: true,
  })
}
</script>

<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Role</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block mb-1">Nama Role</label>
        <input v-model="form.name" type="text" class="border rounded px-3 py-2 w-full" />
        <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
      </div>

      <div>
        <label class="block mb-1">Permissions</label>
        <div class="grid grid-cols-2 gap-2">
          <div
            v-for="perm in permissions"
            :key="perm.id"
            class="flex items-center space-x-2"
          >
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
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        Update
      </button>
    </form>
  </div>
</template>
