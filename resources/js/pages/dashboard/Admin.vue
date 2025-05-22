<template>
  <div>
    <h1 class="text-xl font-bold mb-4">Dashboard Admin</h1>
    <div class="grid grid-cols-2 gap-4">
      <div>Jumlah Pengajuan: {{ statistik.pengajuan }}</div>
      <div>Jumlah Jadwal: {{ statistik.jadwal }}</div>
      <div>Jumlah Pengujian: {{ statistik.pengujian }}</div>
      <div>Jumlah Hasil Uji: {{ statistik.hasil_uji }}</div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { defineProps } from 'vue';

defineProps<{ 
  statistik: {
    pengajuan: number,
    jadwal: number,
    pengujian: number,
    hasil_uji: number
  }
}>()
</script>