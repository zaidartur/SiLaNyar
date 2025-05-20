<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
</script>

<template>
    <CustomerLayout>
        <div class="max-w-7xl mx-auto space-y-6 p-6">
            <!-- Dashboard Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <h1 class="text-2xl font-semibold p-4 border-b">Dashboard Pengajuan</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Left Section -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Status Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <h3 class="text-yellow-500 font-medium">Belum Terverifikasi</h3>
                            <p class="text-3xl font-bold mt-2">4</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <h3 class="text-red-500 font-medium">Ditolak</h3>
                            <p class="text-3xl font-bold mt-2">3</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <h3 class="text-green-500 font-medium">Diterima</h3>
                            <p class="text-3xl font-bold mt-2">3</p>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="p-4 flex items-center justify-between border-b">
                            <div class="relative">
                                <button class="px-4 py-2 text-sm border rounded-md">
                                    Filter
                                    <span class="ml-2">▼</span>
                                </button>
                            </div>
                        </div>
                        <table class="w-full">
                            <thead class="bg-green-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-green-700">ID</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-green-700">Jenis Cairan</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-green-700">Metode
                                        Pengambilan</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-green-700">Lokasi</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-green-700">Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-green-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3 text-sm">DP-001</td>
                                    <td class="px-4 py-3 text-sm">Universal</td>
                                    <td class="px-4 py-3 text-sm">Diambil</td>
                                    <td class="px-4 py-3 text-sm">Gedung A</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Diterima</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex space-x-2">
                                            <button class="text-blue-500 hover:text-blue-700">
                                                <span class="sr-only">View</span>
                                                👁️
                                            </button>
                                            <button class="text-yellow-500 hover:text-yellow-700">
                                                <span class="sr-only">Edit</span>
                                                ✏️
                                            </button>
                                            <button class="text-red-500 hover:text-red-700">
                                                <span class="sr-only">Delete</span>
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right Section - Status Pengujian -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg border border-gray-200 shadow-lg p-4 sticky top-4">
                        <h2 class="text-lg font-semibold mb-4 flex items-center">
                            <svg width="27" height="28" viewBox="0 0 27 28" fill="none"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <rect width="27" height="28" fill="url(#pattern0_741_1283)" />
                                <defs>
                                    <pattern id="pattern0_741_1283" patternContentUnits="objectBoundingBox" width="1"
                                        height="1">
                                        <use xlink:href="#image0_741_1283"
                                            transform="matrix(0.01 0 0 0.00964286 0 0.0178571)" />
                                    </pattern>
                                    <image id="image0_741_1283" width="100" height="100" preserveAspectRatio="none"
                                        xlink:href="data:image/png;base64,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" />
                                </defs>
                            </svg>
                            Status Pengujian
                        </h2>
                        <div class="space-y-6">
                            <!-- Timeline line -->
                            <div class="relative">
                                <div class="absolute left-5 top-5 w-0.5 bg-gray-200" style="height: calc(100% - 2rem);">
                                </div>

                                <!-- Timeline items -->
                                <div class="space-y-6">
                                    <div class="flex items-center">
                                        <div
                                            class="z-10 flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                                            <div class="h-4 w-4 rounded-full bg-green-600"></div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium text-green-600">Pengajuan Diterima</p>
                                            <p class="text-sm text-gray-500">20 Apr 2022</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div
                                            class="z-10 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                                            <div class="h-4 w-4 rounded-full bg-gray-400"></div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium text-gray-600">Pembayaran</p>
                                            <p class="text-sm text-gray-500">Menunggu</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div
                                            class="z-10 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                                            <div class="h-4 w-4 rounded-full bg-gray-400"></div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium text-gray-600">Pengantaran/Pengambilan</p>
                                            <p class="text-sm text-gray-500">Menunggu</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div
                                            class="z-10 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                                            <div class="h-4 w-4 rounded-full bg-gray-400"></div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium text-gray-600">Sampel Diterima</p>
                                            <p class="text-sm text-gray-500">Menunggu</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div
                                            class="z-10 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                                            <div class="h-4 w-4 rounded-full bg-gray-400"></div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium text-gray-600">Pengujian Berjalan</p>
                                            <p class="text-sm text-gray-500">Menunggu</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div
                                            class="z-10 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                                            <div class="h-4 w-4 rounded-full bg-gray-400"></div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium text-gray-600">Hasil Tersedia</p>
                                            <p class="text-sm text-gray-500">Menunggu</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Parameter Pengujian Card -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <div class="border-b-2 border-gray-200 pb-2 mb-4">
                        <h2 class="text-lg font-semibold mb-4 flex items-center">
                            <svg width="37" height="31" viewBox="0 0 37 31" fill="none"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <rect width="37" height="31" fill="url(#pattern0_741_1314)" />
                                <defs>
                                    <pattern id="pattern0_741_1314" patternContentUnits="objectBoundingBox" width="1"
                                        height="1">
                                        <use xlink:href="#image0_741_1314"
                                            transform="matrix(0.00837838 0 0 0.01 0.0810811 0)" />
                                    </pattern>
                                    <image id="image0_741_1314" width="100" height="100" preserveAspectRatio="none"
                                        xlink:href="data:image/png;base64,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" />
                                </defs>
                            </svg>
                            Parameter Pengujian
                        </h2>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span>Hg (Hydrargyrum / Merkuri) 3x</span>
                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Diproses</span>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pembayaran Card -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <div class="border-b-2 border-gray-200 pb-2 mb-4">
                        <h2 class="text-lg font-semibold mb-4 flex items-center">
                            <svg width="30" height="27" viewBox="0 0 30 27" fill="none"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <rect width="30" height="27" fill="url(#pattern0_741_1336)" />
                                <defs>
                                    <pattern id="pattern0_741_1336" patternContentUnits="objectBoundingBox" width="1"
                                        height="1">
                                        <use xlink:href="#image0_741_1336" transform="matrix(0.009 0 0 0.01 0.05 0)" />
                                    </pattern>
                                    <image id="image0_741_1336" width="100" height="100" preserveAspectRatio="none"
                                        xlink:href="data:image/png;base64,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" />
                                </defs>
                            </svg>
                            Ringkasan Pembayaran
                        </h2>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Hg (Hydrargyrum / Merkuri)</span>
                            <span>Rp 75.000</span>
                        </div>
                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between font-semibold">
                                <span>Total Pembayaran</span>
                                <span>Rp 225.000</span>
                            </div>
                        </div>
                        <button class="w-full mt-4 bg-green-600 text-white py-2 rounded-md hover:bg-green-700 flex items-center justify-center gap-2">
                            <svg width="30" height="25" viewBox="0 0 30 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <rect width="30" height="25" fill="url(#pattern0_741_1350)" />
                                <defs>
                                    <pattern id="pattern0_741_1350" patternContentUnits="objectBoundingBox" width="1"
                                        height="1">
                                        <use xlink:href="#image0_741_1350"
                                            transform="matrix(0.00833333 0 0 0.01 0.0833333 0)" />
                                    </pattern>
                                    <image id="image0_741_1350" width="100" height="100" preserveAspectRatio="none"
                                        xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE2klEQVR4nO2dW2gcVRzGpyZtiOCLtVDiLdXiheRBBJtWUUSC2zQtEbF4QUUyZ5ONadLmpaU1KlUpWLddm0tFUEwC2fjQi+ac04t9CFsfTKp9qFbRpJCLlFIbTZHOyVKtfznLpoSYTTJnZ53t8v3gI08Lm+83Z+bMOcuMZQEAAAAAAAAAAAAAAAAAAAAAwIKp+Y4WMxm/j3H1eFBMliOT/+lAd6M7eruP8jN2aNki/iCTTpct1GUmFSFqIR1M2MLprOmNP+CdCaJFTKhmWzh/QYIyOhCT3e3QXabtwxbqQ4hQnpwRbKEi6cmQ6iXIUJ6enoNcvWAko+EIFdjSGYUQ5akQ3anu1rUQJpwqyFAZmrxcWe9aiC1UG4SojAixpWoxGCGqF0JUpoR84V6IdPogRGXolOX0QYi8wYWUh9658MSL9YTUe96B7ta1kCdfbpwoC1QRUuV5B7pbCAlkz8EFIQH/JUBIwP/iISTgf9kQEvC/YAgJ+F8qhAT8LxJCAv6XByEB/wuDkCwoqSzbhazbun/46U27CNnleQe6W9dCsPyusmu1F0IUhDDf9ymyeD8EI0RBCPP9yMUIyYJyFIT4X4iCEP9LUFkUXNTJfwkQQv4XDyGUqoSmjl+ovTZMYlUFxYpLaOC22xN/edk6agvtoS2dQ7N+Ligd2i2O01eigYbEo3RR3Evj/C46x1fTSfEatYiDFJITEMIWeDQ2do9QT4VNZ/Py6UfLSpkf8hdTdH0tNUbHrn92j5A0yh8i4tacGRd30yfy04Q8jBCZWsaOlm+of9kdc4qYmYGlRfRmJEYH5Lv0D79pXhnTc0o8R6/LcZyy2CwymiMn6cySQlcypnKhvJCu9eS5kjGVn/hTVCP/xDWEzThNnbp1uZGM86UW0WaLaKtFdNC9EJ0TfBOEsGlCPl9bbSTj5wKL/q5NCtH5wEyIPtXtlP2YZbHkbGq+C3iq/LZmmgydJovogJmU0+IZCGFS0f7gbiMZOvFXZwjR+chMyFVeSPXyEm4M+SNrjWQM3jKLDJ33zIToRGQvhMSKS4yEDBelENJsLqRLtkHIwNIiIyFj96QQss1cyCGxE0JiK0qzZoR0inYIEasqcA3JprTXhs1nWa9gluW5kKaOwcRCodF9yGrv7kO+5c/iPoQlpUQrg97cqYfNZFzjefSWOA0hLCmkoXvUeLZ1vmTaWtYhMyHHxWasZbEZp643Il/TmYKb//fV3rOiHKu9LMX1ZHtrP/Uvu9OVDL2TqJfujfZD+Eaqk79jP4TNtWMYHaNoZc28F/rv85dQ94Y6auj59fpnw/IoDfOH5xVxiRfTx7LTxeQDvzohvWfeFtqb2EPXN4+JPfUVpcTLKqm1LkJbus7NWp7eln1fnkhcFwb5Y3SRr0zsqQ+JNRTj1bRPHKaQvOxyNmggxJbqS7/vKViuRqjD7n9sLVSr719c5mz2uR8hR5wNWfDFKRdTLa5UuhaiH9TIpDPi95dnORdnxOghmIlRItXz/v8DKqdiC7XRSgcm1V6//wmWKxFO2EobokW2VNvxqHGVxqhwrjKhtnnyqPEpqo/G72fS6bCl+sP3I03eGNFd2dL5TL8pwcoU+hUM9rHJlXhdxWTKV3XobnRHGX1dBQAAAAAAAAAAAAAAAAAAAADAyj3+BV/upDvDm8j4AAAAAElFTkSuQmCC" />
                                </defs>
                            </svg>
                            Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>