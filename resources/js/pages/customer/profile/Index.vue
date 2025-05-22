<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import TambahInstansi from './TambahInstansi.vue'
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
import { defineProps } from 'vue'
import moment from 'moment'


const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  instansi: {
    type: Array,
    default: () => []
  }
})

const showModal = ref(false)

const openModal = () => {
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const editInstansi = (id) => {
  // Implementasi edit instansi
}

const deleteInstansi = (id) => {
  // Implementasi delete instansi
}
</script>

<template>

  <Head title="Profile" />
  <CustomerLayout>
    <div class="max-w-4xl mx-auto">
      <!-- Header Profile -->
      <div class="mb-4 p-2 bg-white rounded-lg shadow-sm border border-gray-300">
        <h1 class="text-xl font-bold text-gray-800">Profile Pengguna</h1>
        <p class="text-sm text-gray-500">Terakhir Login: {{ moment(user?.last_login).format('DD MMMM YYYY, HH:mm') }}</p>
      </div>

      <!-- Profile Card -->
      <div class="mb-4 p-2 bg-white rounded-lg shadow-sm border border-gray-300">
        <!-- Avatar Section -->
        <div class="flex flex-col items-center p-8 border-gray-100">
          <div class="w-24 h-24 bg-customDarkGreen rounded-full flex items-center justify-center mb-4">
            <span class="text-3xl font-bold text-white">
              {{ user?.nama?.charAt(0).toUpperCase() || 'U' }}
            </span>
          </div>
          <h2 class="text-xl font-bold text-gray-800">{{ user?.nama }}</h2>
          <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-sm bg-green-100 text-green-800">
            Pengguna Aktif
          </span>
        </div>

        <!-- Profile Details -->
        <div class="p-6">
          <div class="grid gap-6">
            <!-- Personal Information -->
            <div class="space-y-4 bg-white rounded-lg shadow-sm border border-gray-300 p-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                  <p class="text-sm text-gray-500">Nama Lengkap</p>
                  <p class="font-medium">{{ user?.nama }}</p>
                </div>
                <div class="space-y-2">
                  <p class="text-sm text-gray-500">Email</p>
                  <p class="font-medium">{{ user?.email }}</p>
                </div>
                <div class="space-y-2">
                  <p class="text-sm text-gray-500">Kontak Pribadi</p>
                  <p class="font-medium">{{ user?.no_wa }}</p>
                </div>
                <div class="space-y-2">
                  <p class="text-sm text-gray-500">Alamat Pribadi</p>
                  <p class="font-medium">{{ user?.alamat }}</p>
                </div>
              </div>
            </div>

            <!-- Related Institutions -->
            <div>
              <h3 class="text-lg font-semibold mb-4">Instansi Terkait</h3>
              <div class="grid gap-3">
                <!-- List Instansi -->
                <div v-if="instansi.length > 0">
                  <div v-for="item in instansi" :key="item.id"
                    class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between p-3">
                      <div>
                        <p class="font-medium">{{ item.nama }}</p>
                        <p class="text-sm text-gray-500">{{ item.tipe }}</p>
                        <div class="mt-1">
                          <span :class="{
                            'px-2 py-1 text-xs rounded-full': true,
                            'bg-yellow-100 text-yellow-800': item.status_verifikasi === 'pending',
                            'bg-green-100 text-green-800': item.status_verifikasi === 'terverifikasi',
                            'bg-red-100 text-red-800': item.status_verifikasi === 'ditolak'
                          }">
                            {{ item.status_verifikasi }}
                          </span>
                        </div>
                      </div>
                      <div class="flex gap-2">
                        <button class="p-1 text-gray-400 hover:text-gray-600" @click="editInstansi(item.id)">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                          </svg>
                        </button>
                        <button class="p-1 text-red-400 hover:text-red-600" @click="deleteInstansi(item.id)">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center p-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                  <p class="text-gray-500">Belum ada instansi yang terdaftar</p>
                </div>

                <!-- Tombol Tambah Instansi -->
                <button @click="openModal"
                  class="flex items-center justify-center gap-2 p-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-customDarkGreen hover:text-customDarkGreen">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  <span>Tambah Instansi</span>
                </button>

                <TambahInstansi v-if="showModal" @close="closeModal" />
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 p-6 border-gray-100">
          <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Edit Profile
          </button>
          <!-- <button class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
            Ubah Sandi
          </button> -->
          <!-- <button class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
            Hapus Akun
          </button> -->
        </div>
      </div>
    </div>

    <!-- <div class="profile p-4 border-2 border-gray-700 rounded-lg dark:border-gray-700">
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
    </div> -->
  </CustomerLayout>
</template>

<style scoped>
.profile {
  max-width: 600px;
  margin: 0 auto;
}
</style>
