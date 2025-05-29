<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import TambahInstansi from '@/components/form/customer/profile/TambahInstansi.vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
import moment from 'moment'

interface Instansi {
  id: number
  nama: string
  jabatan: string
  tipe?: 'swasta' | 'pemerintahan' | 'pribadi'
  status_verifikasi?: 'diproses' | 'diterima' | 'ditolak'
}

interface User {
  id: number
  nama: string
  email: string
  no_wa: string
  alamat: string
  instansi: Instansi
  last_login?: string
}

const props = defineProps<{
  user: User,
  instansi: Instansi[]
}>()

const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const showEditModal = ref(false);

const toggleEditModal = () => {
  showEditModal.value = !showEditModal.value;
}
</script>

<template>

  <Head title="Profile" />

  <CustomerLayout>
    <div class="max-w-4xl mx-auto">
      <!-- Header Profile -->
      <div class="mb-4 p-2 bg-white rounded-lg shadow-sm border border-gray-300">
<<<<<<< HEAD
        <div class="flex justify-between items-start">
          <div>
            <h1 class="text-xl font-bold text-gray-800">Profile Pengguna</h1>
            <p class="text-sm text-gray-500">
              Terakhir Login: {{ moment(user?.last_login).format('DD MMMM YYYY, HH:mm') }}
            </p>
          </div>
          <div>
            <AppearanceTabs />
          </div>
        </div>
=======
        <h1 class="text-xl font-bold text-gray-800">Profile Pengguna</h1>
        <p class="text-sm text-gray-500">Terakhir Login: {{ moment(props.user.last_login).format('DD MMMM YYYY, HH:mm') }}
        </p>
>>>>>>> 6bb41a2 (Update Controllers, Models, Database, Routes, Page)
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
          <h2 class="text-xl font-bold text-gray-800">{{ props.user.nama }}</h2>
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
<<<<<<< HEAD
                  <p class="font-medium dark:text-black">{{ user?.nama }}</p>
                </div>
                <div class="space-y-2">
                  <p class="text-sm text-gray-500">Email</p>
                  <p class="font-medium dark:text-black">{{ user?.email }}</p>
                </div>
                <div class="space-y-2">
                  <p class="text-sm text-gray-500">Kontak Pribadi</p>
                  <p class="font-medium dark:text-black">{{ user?.no_wa }}</p>
                </div>
                <div class="space-y-2">
                  <p class="text-sm text-gray-500">Alamat Pribadi</p>
                  <p class="font-medium dark:text-black">{{ user?.alamat }}</p>
=======
                  <p class="font-medium">{{ props.user.nama }}</p>
                </div>
                <div class="space-y-2">
                  <p class="text-sm text-gray-500">Email</p>
                  <p class="font-medium">{{ props.user.email }}</p>
                </div>
                <div class="space-y-2">
                  <p class="text-sm text-gray-500">Kontak Pribadi</p>
                  <p class="font-medium">{{ props.user.no_wa }}</p>
                </div>
                <div class="space-y-2">
                  <p class="text-sm text-gray-500">Alamat Pribadi</p>
                  <p class="font-medium">{{ props.user.alamat }}</p>
>>>>>>> 6bb41a2 (Update Controllers, Models, Database, Routes, Page)
                </div>
              </div>
            </div>

            <!-- Related Institutions -->
            <div>
              <h3 class="mb-4 text-lg font-semibold dark:text-black">Instansi Terkait</h3>
              <div class="grid gap-3">
                <!-- List Instansi -->
                <div v-if="props.instansi.length > 0">
                  <div v-for="item in props.instansi" :key="item.id"
                    class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between p-3">
                      <div>
                        <p class="font-medium">{{ item.nama }}</p>
                        <p class="text-sm text-gray-500">{{ item.tipe }}</p>
                        <div class="mt-1">
                          <span :class="{
                            'rounded-full px-2 py-1 text-xs': true,
                            'bg-yellow-100 text-yellow-800': item.status_verifikasi === 'diproses',
                            'bg-green-100 text-green-800': item.status_verifikasi === 'diterima',
                            'bg-red-100 text-red-800': item.status_verifikasi === 'ditolak',
                          }">
                            {{ item.status_verifikasi }}
                          </span>
                        </div>
                      </div>
                      <div class="flex gap-2">
                        <button class="p-1 text-gray-400 hover:text-gray-600" @click="editInstansi(item.id)">
                          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                          </svg>
                        </button>
                        <button class="p-1 text-red-400 hover:text-red-600" @click="deleteInstansi(item.id)">
                          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Empty State -->
                <div v-else class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                  <p class="text-gray-500">Belum ada instansi yang terdaftar</p>
                </div>

                <!-- Tombol Tambah Instansi -->
                <button @click="openModal"
                  class="flex items-center justify-center gap-2 rounded-lg border-2 border-dashed border-gray-300 p-3 hover:border-customDarkGreen hover:text-customDarkGreen">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="dark:text-customDarkGreen" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  <span class="dark:text-customDarkGreen">Tambah Instansi</span>
                </button>

                <TambahInstansi v-if="showModal" @close="closeModal" />
              </div>
            </div>

            <div class="flex justify-end gap-3 p-6 border-gray-100">
              <button @click="toggleEditModal" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Edit Profile
              </button>
            </div>

            <Dialog :open="showEditModal" @update:open="showEditModal = false">
              <DialogContent
                class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-gradient-to-br from-lime-500 to-green-900 rounded-lg shadow-xl">
                <DialogHeader>
                  <DialogTitle class="text-center text-2xl font-bold text-gray-300">Edit Profile
                  </DialogTitle>
                  <button @click="toggleEditModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                  </button>
                </DialogHeader>

                <div class="flex flex-col items-center space-y-6 p-4">
                  <div class="text-center">
                    <p class="text-xl font-bold text-gray-300 mb-2">
                      Anda akan di arahkan ke portal
                      <br />SAKTI Karanganyar
                    </p>
                    <p class="text-sm font-semibold text-gray-300 mb-2">
                      Fitur edit profil tersedia melalui portal SAKTI Karanganyar.
                    </p>
                    <p class="text-sm italic text-gray-300">
                      Note:Klik "lanjutkan" untuk melanjutkan ke portal SAKTI Karanganyar.
                    </p>
                  </div>

                  <div class="flex gap-4">
                    <a href="https://sakti.karanganyarkab.go.id/profile"
                      class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                      Lanjutkan
                    </a>
                  </div>
                </div>
              </DialogContent>
            </Dialog>

          </div>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>
