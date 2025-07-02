<script setup lang="ts">
/* eslint-disable */
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const { props } = usePage();
</script>

<template>

    <Head title="Pengajuan Sampel" />
    <CustomerLayout>
        <div>
            <h1 class="mb-2 text-2xl font-bold text-customDarkGreen">Pengajuan Sampel</h1>
            <p class="mb-6 inline-block w-fit border-b-2 border-green-700 text-gray-600">Ikuti langkah-langkah berikut
                untuk mengajukan sampel.</p>
        </div>
        <div class="mb-5 flex p-6">
            <div class="h-screen w-full text-center ">
                <span class="text-xl">Anda Belum Memiliki Instansi. Silahkan Tambahkan Instansi Terlebih Dahulu di menu <a href="/customer/profile/show" class="text-customDarkGreen text-decoration-line">Profile</a></span>
            </div>
        </div>
    </CustomerLayout>
</template>
