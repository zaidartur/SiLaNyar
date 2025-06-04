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
  dashboard_view: '',
  permissions: [] as number[]
})

const submit = () => {
  form.post('/superadmin/role/store', {
    preserveScroll: true,
  })
}
</script>

<template>
  <div class="h-screen w-full bg-white lg:grid lg:grid-cols-3">
    <!-- Left Side - Logo Section -->
    <div class="hidden h-screen flex-col bg-customDarkGreen lg:col-span-1 lg:flex lg:items-center lg:justify-center">
      <img src="/assets/assetsadmin/logodlh.png" alt="Logo DLH" class="mx-auto h-48 w-auto object-contain" />
      <div class="mt-6 text-center text-white">
        <h2 class="mb-2 border-b border-white pb-2 text-2xl font-bold">SiLanYar</h2>
        <p class="text-sm">Sistem Laboratorium Karanganyar</p>
      </div>
    </div>

    <!-- Right Side - Form Section -->
    <div class="flex h-screen items-start justify-center overflow-y-auto bg-white lg:col-span-2">
      <form @submit.prevent="submit" class="mx-auto grid w-full max-w-xl gap-6 p-6 md:p-12">
        <div class="grid gap-2 text-center">
          <h1 class="text-3xl font-bold">Tambah Role Baru</h1>
        </div>

        <div class="grid gap-4">
          <!-- Nama Role -->
          <div class="grid gap-2">
            <label for="name" class="font-semibold">Nama Role</label>
            <input id="name" v-model="form.name" type="text" placeholder="Masukkan nama role" required
              class="w-full rounded border px-3 py-2" />
            <span v-if="form.errors.name" class="text-sm text-red-600">
              {{ form.errors.name }}
            </span>
          </div>

          <!-- Tampilan Dashboard -->
          <div class="grid gap-2">
            <label for="dashboard_view" class="font-semibold">Tampilan Dashboard</label>
            <select id="dashboard_view" v-model="form.dashboard_view" class="w-full rounded border px-3 py-2" required>
              <option disabled value="">Pilih Dashboard</option>
              <option value="dashboard/SuperAdmin">Super Admin</option>
              <option value="dashboard/Admin">Admin</option>
              <option value="dashboard/Teknisi">Teknisi</option>
              <option value="Dashboard">Customer</option>
            </select>
            <span v-if="form.errors.dashboard_view" class="text-sm text-red-600">
              {{ form.errors.dashboard_view }}
            </span>
          </div>

          <!-- Permissions -->
          <div class="grid gap-2">
            <label class="font-semibold">Permissions</label>
            <div class="grid grid-cols-2 gap-2">
              <div v-for="perm in permission" :key="perm.id" class="flex items-center gap-2">
                <input type="checkbox" :value="perm.id" v-model="form.permissions" :id="'perm-' + perm.id"
                  class="form-checkbox" />
                <label :for="'perm-' + perm.id" class="text-sm font-medium">{{ perm.name }}</label>
              </div>
            </div>
            <span v-if="form.errors.permissions" class="text-sm text-red-600">
              {{ form.errors.permissions }}
            </span>
          </div>
        </div>

        <button type="submit"
          class="mb-8 w-full rounded bg-customDarkGreen px-4 py-2 text-white transition-colors hover:bg-green-800">
          Simpan
        </button>
      </form>
    </div>
  </div>
</template>
