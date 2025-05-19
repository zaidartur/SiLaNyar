<script setup lang="ts">
interface User {
  nik: string
  nama: string
  tgl_lahir: string
  rt: string
  rw: string
  kode_pos: string
  alamat: string
  email: string
  no_wa: string
  username: string
}

const props = defineProps<{
  user: User | null
}>()
</script>

<template>
  <div class="bg-blue-700" v-if="props.user">
    <h1>Profil Saya</h1>
    <ul>
      <li><strong>NIK:</strong> {{ props.user.nik }}</li>
      <li><strong>Nama:</strong> {{ props.user.nama }}</li>
      <li><strong>Tanggal Lahir:</strong> {{ props.user.tgl_lahir }}</li>
      <li><strong>Kode Pos:</strong> {{ props.user.kode_pos }}</li>
      <li><strong>Alamat:</strong> {{ props.user.alamat }}</li>
      <li><strong>Email:</strong> {{ props.user.email }}</li>
      <li><strong>No Telepon:</strong> {{ props.user.no_wa }}</li>
      <li><strong>Username:</strong> {{ props.user.username }}</li>
    </ul>
  </div>
  <div v-else>
    <p>Loading profil...</p>
  </div>
</template>