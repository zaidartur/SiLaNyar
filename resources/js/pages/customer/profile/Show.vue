<template>
  <div class="profile">
    <h1>Profil Saya</h1>
    <ul>
      <li><strong>NIK:</strong> {{ user.nik }}</li>
      <li><strong>Nama:</strong> {{ user.nama }}</li>
      <li><strong>Tanggal Lahir:</strong> {{ user.tgl_lahir }}</li>
      <li><strong>Provinsi:</strong> {{ user.provinsi }}</li>
      <li><strong>Kab/Kota:</strong> {{ user.kab_kota }}</li>
      <li><strong>Kecamatan:</strong> {{ user.kecamatan }}</li>
      <li><strong>Kelurahan:</strong> {{ user.kelurahan }}</li>
      <li><strong>RT:</strong> {{ user.rt }}</li>
      <li><strong>RW:</strong> {{ user.rw }}</li>
      <li><strong>Kode Pos:</strong> {{ user.kode_pos }}</li>
      <li><strong>Alamat:</strong> {{ user.alamat }}</li>
      <li><strong>Email:</strong> {{ user.email }}</li>
      <li><strong>No WA:</strong> {{ user.no_wa }}</li>
      <li><strong>Username:</strong> {{ user.username }}</li>
      <li><strong>ID:</strong> {{ user.id }}</li>
    </ul>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  user: Object
})
</script>

<style scoped>
.profile {
  max-width: 600px;
  margin: 0 auto;
}
</style>
