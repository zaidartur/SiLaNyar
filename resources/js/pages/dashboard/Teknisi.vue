<template>
  <div>
    <h1 class="text-xl font-bold mb-4">Dashboard Teknisi</h1>
    <div class="grid grid-cols-2 gap-4">
      <div>Jadwal Pengujian: {{ statistik.jadwalPengujian }}</div>
      <div>Jadwal Pengambilan: {{ statistik.jadwalPengambilan }}</div>
    </div>

    <h2 class="text-lg font-semibold mt-6">Daftar Pengajuan</h2>
    <ul>
      <li v-for="item in pengajuan" :key="item.id">
        {{ item.nama_pengajuan }}
      </li>
    </ul>

    <h2 class="text-lg font-semibold mt-6">Daftar Pengambilan</h2>
    <ul>
      <li v-for="item in pengambilan" :key="item.id">
        {{ item.nama_pengambilan }}
      </li>
    </ul>
  </div>
</template>

<script lang="ts" setup>
import { defineProps } from 'vue';

defineProps<{ 
  statistik: { jadwalPengujian: number, jadwalPengambilan: number },
  pengajuan: Array<any>,
  pengambilan: Array<any>
}>()
</script>
