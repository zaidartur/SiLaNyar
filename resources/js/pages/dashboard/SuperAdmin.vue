<template>
  <AdminLayout>
    <div>
      <h1 class="text-xl font-bold mb-4">Dashboard Superadmin</h1>
      <h2 class="text-lg font-semibold mt-4">Daftar Customer</h2>
      <ul>
        <li v-for="user in customer" :key="user.id">
          {{ user.nama }} - {{ user.email }}
        </li>
      </ul>

      <h2 class="text-lg font-semibold mt-6">Daftar Pegawai</h2>
      <ul>
        <li v-for="user in pegawai" :key="user.id">
          {{ user.nama }} - {{ user.email }}
        </li>
      </ul>
    </div>
  </AdminLayout>
</template>

<script lang="ts" setup>
import { defineProps } from 'vue';
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

defineProps<{ 
  customer: Array<{ id: number, nama: string, email: string }>, 
  pegawai: Array<{ id: number, nama: string, email: string }> 
}>()
</script>