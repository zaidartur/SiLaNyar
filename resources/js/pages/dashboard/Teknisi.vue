<script lang="ts" setup>
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps<{
    statistik: { jadwalPengujian: number; jadwalPengambilan: number };
    pengujian: Array<any>;
    pengambilan: Array<any>;
}>();
</script>

<template>
    <Head title="Teknisi" />
    <AdminLayout>
        <div class="h-full rounded-xl bg-[#D9D9D9] p-6">
            <!-- Card Statistik -->
            <div class="mb-8 grid grid-cols-1 gap-8 sm:grid-cols-2">
                <!-- Card Jadwal Pengujian -->
                <div class="flex min-h-[120px] items-center gap-6 rounded-xl bg-white p-8 shadow-md">
                    <div class="rounded-lg p-2">
                        <svg width="86" height="85" viewBox="0 0 86 85" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="86" height="85" rx="10" fill="#81C784" fill-opacity="0.26" />
                            <mask id="mask0_1417_4574" style="mask-type: alpha" maskUnits="userSpaceOnUse" x="7" y="5" width="72" height="72">
                                <rect x="7" y="5" width="72" height="72" fill="#D9D9D9" />
                            </mask>
                            <g mask="url(#mask0_1417_4574)">
                                <path
                                    d="M43 47C42.15 47 41.4375 46.7125 40.8625 46.1375C40.2875 45.5625 40 44.85 40 44C40 43.15 40.2875 42.4375 40.8625 41.8625C41.4375 41.2875 42.15 41 43 41C43.85 41 44.5625 41.2875 45.1375 41.8625C45.7125 42.4375 46 43.15 46 44C46 44.85 45.7125 45.5625 45.1375 46.1375C44.5625 46.7125 43.85 47 43 47ZM31 47C30.15 47 29.4375 46.7125 28.8625 46.1375C28.2875 45.5625 28 44.85 28 44C28 43.15 28.2875 42.4375 28.8625 41.8625C29.4375 41.2875 30.15 41 31 41C31.85 41 32.5625 41.2875 33.1375 41.8625C33.7125 42.4375 34 43.15 34 44C34 44.85 33.7125 45.5625 33.1375 46.1375C32.5625 46.7125 31.85 47 31 47ZM55 47C54.15 47 53.4375 46.7125 52.8625 46.1375C52.2875 45.5625 52 44.85 52 44C52 43.15 52.2875 42.4375 52.8625 41.8625C53.4375 41.2875 54.15 41 55 41C55.85 41 56.5625 41.2875 57.1375 41.8625C57.7125 42.4375 58 43.15 58 44C58 44.85 57.7125 45.5625 57.1375 46.1375C56.5625 46.7125 55.85 47 55 47ZM43 59C42.15 59 41.4375 58.7125 40.8625 58.1375C40.2875 57.5625 40 56.85 40 56C40 55.15 40.2875 54.4375 40.8625 53.8625C41.4375 53.2875 42.15 53 43 53C43.85 53 44.5625 53.2875 45.1375 53.8625C45.7125 54.4375 46 55.15 46 56C46 56.85 45.7125 57.5625 45.1375 58.1375C44.5625 58.7125 43.85 59 43 59ZM31 59C30.15 59 29.4375 58.7125 28.8625 58.1375C28.2875 57.5625 28 56.85 28 56C28 55.15 28.2875 54.4375 28.8625 53.8625C29.4375 53.2875 30.15 53 31 53C31.85 53 32.5625 53.2875 33.1375 53.8625C33.7125 54.4375 34 55.15 34 56C34 56.85 33.7125 57.5625 33.1375 58.1375C32.5625 58.7125 31.85 59 31 59ZM55 59C54.15 59 53.4375 58.7125 52.8625 58.1375C52.2875 57.5625 52 56.85 52 56C52 55.15 52.2875 54.4375 52.8625 53.8625C53.4375 53.2875 54.15 53 55 53C55.85 53 56.5625 53.2875 57.1375 53.8625C57.7125 54.4375 58 55.15 58 56C58 56.85 57.7125 57.5625 57.1375 58.1375C56.5625 58.7125 55.85 59 55 59ZM22 71C20.35 71 18.9375 70.4125 17.7625 69.2375C16.5875 68.0625 16 66.65 16 65V23C16 21.35 16.5875 19.9375 17.7625 18.7625C18.9375 17.5875 20.35 17 22 17H25V11H31V17H55V11H61V17H64C65.65 17 67.0625 17.5875 68.2375 18.7625C69.4125 19.9375 70 21.35 70 23V65C70 66.65 69.4125 68.0625 68.2375 69.2375C67.0625 70.4125 65.65 71 64 71H22ZM22 65H64V35H22V65Z"
                                    fill="#2E7D32"
                                />
                            </g>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-500">Jadwal Pengujian</p>
                        <h2 class="text-6xl font-bold text-customDarkGreen">{{ statistik.jadwalPengujian }}</h2>
                        <p class="mt-2 text-sm text-gray-400">Total Jadwal Pengujian</p>
                    </div>
                </div>
                <!-- Card Jadwal Pengambilan -->
                <div class="flex min-h-[120px] items-center gap-6 rounded-xl bg-white p-8 shadow-md">
                    <div class="rounded-lg p-2">
                        <svg width="86" height="85" viewBox="0 0 86 85" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="86" height="85" rx="10" fill="#81C784" fill-opacity="0.26" />
                            <mask id="mask0_1417_4574" style="mask-type: alpha" maskUnits="userSpaceOnUse" x="7" y="5" width="72" height="72">
                                <rect x="7" y="5" width="72" height="72" fill="#D9D9D9" />
                            </mask>
                            <g mask="url(#mask0_1417_4574)">
                                <path
                                    d="M43 47C42.15 47 41.4375 46.7125 40.8625 46.1375C40.2875 45.5625 40 44.85 40 44C40 43.15 40.2875 42.4375 40.8625 41.8625C41.4375 41.2875 42.15 41 43 41C43.85 41 44.5625 41.2875 45.1375 41.8625C45.7125 42.4375 46 43.15 46 44C46 44.85 45.7125 45.5625 45.1375 46.1375C44.5625 46.7125 43.85 47 43 47ZM31 47C30.15 47 29.4375 46.7125 28.8625 46.1375C28.2875 45.5625 28 44.85 28 44C28 43.15 28.2875 42.4375 28.8625 41.8625C29.4375 41.2875 30.15 41 31 41C31.85 41 32.5625 41.2875 33.1375 41.8625C33.7125 42.4375 34 43.15 34 44C34 44.85 33.7125 45.5625 33.1375 46.1375C32.5625 46.7125 31.85 47 31 47ZM55 47C54.15 47 53.4375 46.7125 52.8625 46.1375C52.2875 45.5625 52 44.85 52 44C52 43.15 52.2875 42.4375 52.8625 41.8625C53.4375 41.2875 54.15 41 55 41C55.85 41 56.5625 41.2875 57.1375 41.8625C57.7125 42.4375 58 43.15 58 44C58 44.85 57.7125 45.5625 57.1375 46.1375C56.5625 46.7125 55.85 47 55 47ZM43 59C42.15 59 41.4375 58.7125 40.8625 58.1375C40.2875 57.5625 40 56.85 40 56C40 55.15 40.2875 54.4375 40.8625 53.8625C41.4375 53.2875 42.15 53 43 53C43.85 53 44.5625 53.2875 45.1375 53.8625C45.7125 54.4375 46 55.15 46 56C46 56.85 45.7125 57.5625 45.1375 58.1375C44.5625 58.7125 43.85 59 43 59ZM31 59C30.15 59 29.4375 58.7125 28.8625 58.1375C28.2875 57.5625 28 56.85 28 56C28 55.15 28.2875 54.4375 28.8625 53.8625C29.4375 53.2875 30.15 53 31 53C31.85 53 32.5625 53.2875 33.1375 53.8625C33.7125 54.4375 34 55.15 34 56C34 56.85 33.7125 57.5625 33.1375 58.1375C32.5625 58.7125 31.85 59 31 59ZM55 59C54.15 59 53.4375 58.7125 52.8625 58.1375C52.2875 57.5625 52 56.85 52 56C52 55.15 52.2875 54.4375 52.8625 53.8625C53.4375 53.2875 54.15 53 55 53C55.85 53 56.5625 53.2875 57.1375 53.8625C57.7125 54.4375 58 55.15 58 56C58 56.85 57.7125 57.5625 57.1375 58.1375C56.5625 58.7125 55.85 59 55 59ZM22 71C20.35 71 18.9375 70.4125 17.7625 69.2375C16.5875 68.0625 16 66.65 16 65V23C16 21.35 16.5875 19.9375 17.7625 18.7625C18.9375 17.5875 20.35 17 22 17H25V11H31V17H55V11H61V17H64C65.65 17 67.0625 17.5875 68.2375 18.7625C69.4125 19.9375 70 21.35 70 23V65C70 66.65 69.4125 68.0625 68.2375 69.2375C67.0625 70.4125 65.65 71 64 71H22ZM22 65H64V35H22V65Z"
                                    fill="#2E7D32"
                                />
                            </g>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-500">Jadwal Pengambilan</p>
                        <h2 class="text-6xl font-bold text-customDarkGreen">{{ statistik.jadwalPengambilan }}</h2>
                        <p class="mt-2 text-sm text-gray-400">Total Jadwal Pengambilan</p>
                    </div>
                </div>
            </div>

            <!-- Table Daftar Pengajuan & Pengambilan Sampingan -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Table Daftar Pengajuan -->
                <!-- <div>
          <h2 class="text-lg font-semibold mb-2">Daftar Pengujian</h2>
          <div class="overflow-x-auto max-h-96 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <table class="min-w-full bg-white rounded-xl shadow overflow-hidden">
              <thead>
                <tr class="bg-customDarkGreen text-white">
                  <th class="px-4 py-3 text-left font-semibold rounded-tl-xl">ID</th>
                  <th class="px-4 py-3 text-left font-semibold">Nama Pengujian</th>
                  <th class="px-4 py-3 text-left font-semibold rounded-tr-xl">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in pengujian" :key="item.id" class="even:bg-gray-50">
                  <td class="px-4 py-3 border-b">{{ item.id }}</td>
                  <td class="px-4 py-3 border-b">{{ item.nama_pengujian }}</td>
                  <td class="px-4 py-3 border-b">
                    <span class="text-blue-600 hover:underline cursor-pointer">Detail</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div> -->

                <!-- Table Daftar Pengambilan -->
                <!-- <div>
          <h2 class="text-lg font-semibold mb-2">Daftar Pengambilan</h2>
          <div class="overflow-x-auto max-h-96 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <table class="min-w-full bg-white rounded-xl shadow overflow-hidden">
              <thead>
                <tr class="bg-customDarkGreen text-white">
                  <th class="px-4 py-3 text-left font-semibold rounded-tl-xl">ID</th>
                  <th class="px-4 py-3 text-left font-semibold">Nama Pengambilan</th>
                  <th class="px-4 py-3 text-left font-semibold rounded-tr-xl">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in pengambilan" :key="item.id" class="even:bg-gray-50">
                  <td class="px-4 py-3 border-b">{{ item.id }}</td>
                  <td class="px-4 py-3 border-b">{{ item.user.nama }}</td>
                  <td class="px-4 py-3 border-b">
                    <span class="text-blue-600 hover:underline cursor-pointer">Detail</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div> -->
            </div>
        </div>
    </AdminLayout>
</template>
