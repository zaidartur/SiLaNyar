<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps<{
  hasil_uji: any
}>()

const form = useForm({
  masalah: '',
  perbaikan: ''
})

const submit = () => {
  form.post(`/customer/hasiluji/aduan/${props.hasil_uji.id}`, {
    preserveScroll: true
  })
}
</script>

<template>
  <Head title="Tambah Aduan" />

  <CustomerLayout>
    <div class="p-6 max-w-3xl mx-auto bg-white shadow rounded-lg">
      <h1 class="text-xl font-bold mb-4 text-black">Tambah Aduan</h1>

      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-1">ID Hasil Uji</label>
        <p class="text-gray-900">{{ hasil_uji.kode_hasil_uji }}</p>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-1" for="masalah">Masalah</label>
        <textarea
          id="masalah"
          v-model="form.masalah"
          class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-red-200"
          rows="4"
          placeholder="Jelaskan masalah dari hasil uji..."
        ></textarea>
        <div v-if="form.errors.masalah" class="text-red-500 text-sm mt-1">{{ form.errors.masalah }}</div>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-1" for="perbaikan">Usulan Perbaikan</label>
        <textarea
          id="perbaikan"
          v-model="form.perbaikan"
          class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200"
          rows="4"
          placeholder="Saran atau perbaikan yang diinginkan..."
        ></textarea>
        <div v-if="form.errors.perbaikan" class="text-red-500 text-sm mt-1">{{ form.errors.perbaikan }}</div>
      </div>

      <div class="flex justify-end">
        <button
          @click="submit"
          :disabled="form.processing"
          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded disabled:opacity-50"
        >
          Kirim Aduan
        </button>
      </div>
    </div>
  </CustomerLayout>
</template>
