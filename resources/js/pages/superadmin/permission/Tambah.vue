<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: ''
})

const submit = () => {
  form.post('/superadmin/permission/store')
}
</script>

<template>
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-xl font-bold mb-4">Tambah Permission</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block mb-1">Nama Permission</label>
        <input v-model="form.name" type="text" class="border rounded px-3 py-2 w-full" />
        <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
  </div>
</template>
