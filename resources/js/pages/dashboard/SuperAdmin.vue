<script lang="ts" setup>
import { defineProps } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

defineProps<{
  customer: Array<{ id: number, nama: string, email: string }>,
  pegawai: Array<{ id: number, nama: string, email: string }>
}>()
</script>

<template>

  <Head title="Dashboard" />
  <AdminLayout>
    <div>
      <div class="mb-4 flex gap-4">
        <a href="/pegawai/dashboard" class="font-bold text-customGreen hover:text-green-700 hover:underline transition">
          Daftar User & Pegawai
        </a>
        <a href="/superadmin/users"
          class="font-bold text-customGreen hover:text-green-700 hover:underline transition">
          Ke Manajemen Pengguna
        </a>
      </div>
      <div class="flex gap-8">
        <!-- Tabel Customer -->
        <div class="w-1/2">
          <div class="flex items-center gap-2 mb-2">
            <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink">
              <rect width="33" height="33" fill="url(#pattern0_1671_7228)" />
              <defs>
                <pattern id="pattern0_1671_7228" patternContentUnits="objectBoundingBox" width="1" height="1">
                  <use xlink:href="#image0_1671_7228" transform="scale(0.01)" />
                </pattern>
                <image id="image0_1671_7228" width="100" height="100" preserveAspectRatio="none"
                  xlink:href="data:image/png;base64,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" />
              </defs>
            </svg>
            <h2 class="text-lg font-semibold text-customGreen mb-2 pt-6">Daftar Customer</h2>
          </div>
          <div class="overflow-hidden rounded-lg shadow">
            <table class="min-w-full border border-gray-300 rounded-lg">
              <thead>
                <tr class="bg-customGreen text-white">
                  <th class="py-2 px-4 border-b">Nama</th>
                  <th class="py-2 px-4 border-b">Email</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user, index) in customer" :key="user.id"
                  :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-200'">
                  <td class="py-2 px-4 border-b">{{ user.nama }}</td>
                  <td class="py-2 px-4 border-b">{{ user.email }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Tabel Pegawai -->
        <div class="w-1/2">
          <div class="flex items-center gap-1 mb-2">
            <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink">
              <rect width="33" height="33" fill="url(#pattern0_1671_7230)" />
              <defs>
                <pattern id="pattern0_1671_7230" patternContentUnits="objectBoundingBox" width="1" height="1">
                  <use xlink:href="#image0_1671_7230" transform="scale(0.01)" />
                </pattern>
                <image id="image0_1671_7230" width="100" height="100" preserveAspectRatio="none"
                  xlink:href="data:image/png;base64,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" />
              </defs>
            </svg>
            <h2 class="text-lg font-semibold text-customGreen mb-2 pt-6">Daftar Pegawai</h2>
          </div>
          <div class="overflow-hidden rounded-lg shadow">
            <table class="min-w-full border border-gray-300 rounded-lg">
              <thead>
                <tr class="bg-customGreen text-white">
                  <th class="py-2 px-4 border-b">Nama</th>
                  <th class="py-2 px-4 border-b">Email</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user, index) in pegawai" :key="user.id"
                  :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-200'">
                  <td class="py-2 px-4 border-b">{{ user.nama }}</td>
                  <td class="py-2 px-4 border-b">{{ user.email }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>