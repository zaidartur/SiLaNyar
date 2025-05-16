<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const syaratPenerimaan = ref([
    'Volume / jumlah sample minimal 2,5 liter',
    'Volume / jumlah sample lemak 1 liter dengan berbotol kaca gelap dan bermulut lebar',
    'Volume sample vecal coli dan total coli minimal 100 ml dengan botol kaca gelap steril',
    'Kondisi wadah / kemasan sampel harus bersih dan tidak terkontaminasi',
    'Waktu pengambilan sampel serta lama penyimpanan harus jelas',
    'Memberikan informasi jika sampel diawetkan meliputi waktu pengawetan dan bahan pengawet yang digunakan',
]);

const sampleDitolak = ref([
    'Volume atau jumlah sampel kurang dari persyaratan',
    'Sampel sudah terlalu lama disolasi',
    'Sampel mengalami kerusakan di perjalanan saat pengiriman sampel',
    'Kemasan sampel sudah rusak atau tidak sesuai sehingga mempengaruhi isi sampel',
]);

defineProps({
    src: String,
    alt: String,
});

const workflowSteps = ref([
    {
        number: 1,
        title: 'Registrasi Sampel',
        description: 'Pendaftaran sampel dengan data lengkap lokasi, waktu pengambilan, dan parameter uji.',
    },
    {
        number: 2,
        title: 'Verifikasi Sample',
        description: 'Pemeriksaan kesesuaian sampel dengan kriteria pengujian.',
    },
    {
        number: 3,
        title: 'Distribusi Sample',
        description: 'Pembagian sampel ke bagian laboratorium yang sesuai.',
    },
    {
        number: 4,
        title: 'Menganalisis Sample',
        description: 'Pengujian sampel menggunakan metode dan peralatan yang tepat.',
    },
    {
        number: 5,
        title: 'Pelaporan',
        description: 'Penyusunan hasil analisis dalam bentuk laporan.',
    },
]);
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Remove padding and make container full width -->
        <div class="flex min-h-screen w-full flex-col">
            <!-- Hero Section -->
            <section class="relative h-screen w-full bg-cover bg-center" style="background-image: url('/storage/assetslandingpage/hero.png')">
                <div class="absolute inset-0 flex items-center justify-center bg-black/40 text-white">
                    <div class="text-center">
                        <h2 class="text-4xl font-bold tracking-tight sm:text-5xl md:text-6xl">Sistem Laboratorium Lingkungan Terpadu</h2>
                        <p class="mx-auto mt-6 max-w-2xl text-lg leading-8">
                            Manajemen dan monitoring laboratorium lingkungan yang komprehensif untuk memantau kualitas lingkungan di Kabupaten
                            Karanganyar.
                        </p>
                        <div class="mt-10">
                            <button class="rounded-lg bg-orange-400 px-6 py-3 text-lg font-semibold text-white transition-colors hover:bg-orange-500">
                                Pelajari Sistem Lab
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main Content -->
            <section id="informasi" class="flex w-full flex-col bg-white p-12">
                <h1 class="mb-12 self-center text-4xl font-bold text-green-700 dark:text-green-500 max-md:max-w-full">
                    Jadwal Pelayanan dan Syarat Penerimaan Sample
                </h1>

                <!-- Container pakai grid -->
                <div class="mt-8 grid w-full grid-cols-2 gap-12 px-8 max-md:grid-cols-1">
                    <!-- Syarat Penerimaan Sample -->
                    <div class="space-y-8">
                        <h2 class="mb-6 text-4xl font-bold text-green-700 dark:text-green-500">Syarat Penerimaan Sample</h2>
                        <h3 class="mt-6 text-xl text-black">Syarat Kelengkapan dan Kelayakan Sample :</h3>
                        <div class="mt-8">
                            <ul class="space-y-6">
                                <li v-for="(text, index) in syaratPenerimaan" :key="index" class="flex items-start gap-3">
                                    <div class="mt-1 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-green-600">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xl font-medium text-black">
                                        {{ text }}
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <!-- Sample Ditolak -->
                        <div class="mb-8 mt-16">
                            <h2 class="mb-8 text-4xl font-bold text-green-700">Sample dapat ditolak apabila</h2>
                            <div class="mt-6">
                                <ul class="space-y-6">
                                    <li v-for="(text, index) in sampleDitolak" :key="index" class="flex items-start gap-3">
                                        <div class="mt-1 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-red-500">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xl font-medium text-black">
                                            {{ text }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal Sample -->
                    <div class="flex flex-col space-y-8">
                        <!-- BOD Sample Table -->
                        <div class="mb-12">
                            <h2 class="mb-5 text-center text-4xl font-extrabold text-green-700">Sample dengan parameter BOD</h2>

                            <div class="overflow-hidden rounded-lg border border-green-700">
                                <table class="w-full border-collapse">
                                    <thead class="bg-green-700 text-white">
                                        <tr>
                                            <th class="border-r border-white px-6 py-3 text-center text-3xl font-bold">Hari</th>
                                            <th class="px-6 py-3 text-center text-3xl font-bold">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-green-700 bg-white">
                                        <tr>
                                            <td class="border-r border-green-700 px-6 py-4 text-center text-3xl font-bold text-black">Rabu-Kamis</td>
                                            <td class="px-6 py-4 text-center text-3xl font-bold text-black">08.00 - 11.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Non-BOD Sample Table -->
                            <div class="mt-8">
                                <h2 class="mb-5 mt-8 text-center text-4xl font-extrabold text-green-700">Sample Tanpa parameter BOD</h2>

                                <div class="overflow-hidden rounded-lg border border-green-700">
                                    <table class="w-full border-collapse">
                                        <thead class="bg-green-700 text-white">
                                            <tr>
                                                <th class="border-r border-white px-6 py-3 text-center text-3xl font-bold">Hari</th>
                                                <th class="px-6 py-3 text-center text-3xl font-bold">Waktu</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-green-700 bg-white">
                                            <tr>
                                                <td
                                                    class="border-r border-green-700 px-6 py-4 text-center text-3xl font-bold text-black dark:border-green-500"
                                                >
                                                    Rabu-Kamis
                                                </td>
                                                <td class="px-6 py-4 text-center text-3xl font-bold text-black">08.00 - 11.00</td>
                                            </tr>
                                            <tr>
                                                <td
                                                    class="border-r border-green-700 px-6 py-4 text-center text-3xl font-bold text-black dark:border-green-500"
                                                >
                                                    Jumat
                                                </td>
                                                <td class="px-6 py-4 text-center text-3xl font-bold text-black">08.00 - 10.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Note -->
                <footer class="text-1xl mt-24 px-8 font-normal text-green-700 dark:text-green-500 max-md:mt-10 max-md:max-w-full">
                    <p class="text-center italic">
                        Note: Apabila ada hal-hal yang meragukan, petugas penerima sampel dapat menolak<br />
                        setelah berkonsultasi dengan pengendali teknis
                    </p>
                </footer>
            </section>

            <!-- Section Alur Pelayanan -->
            <section>
                <div class="flex flex-col items-center justify-center rounded-md border border-blue-300 bg-green-100 p-6">
                    <h1 class="mb-8 text-center text-3xl font-semibold text-green-700">
                        Diagram Alur Pelayanan Laboratorium Penguji Dinas Lingkungan Hidup<br />
                        Kabupaten karanganyar
                    </h1>

                    <div class="mx-auto mb-8 w-full max-w-4xl">
                        <img
                            src="/storage/assetslandingpage/alurdiagram.png"
                            alt="Diagram Alur Pelayanan Laboratorium"
                            class="h-auto w-full rounded-lg object-contain shadow-lg"
                        />
                    </div>

                    <div class="mt-6 text-sm italic text-green-700">
                        <p>Note: Pendaftaran Dilakukan 1 Hari Sebelum Penyerahan Sample</p>
                    </div>
                </div>
            </section>

            <!-- Section Alur Kerja -->
            <section class="rounded-0 bg-green-50 p-4 md:p-8">
                <h1 class=":text-green-500 mb-4 text-center text-2xl font-bold text-green-700 md:mb-6 md:text-4xl">Alur Kerja Laboratorium</h1>
                <p class=":text-gray-300 mb-8 text-center text-base text-black md:mb-16 md:text-lg">
                    SiLanyar mengotomatisasi dan mengintegrasikan seluruh alur kerja laboratorium lingkungan
                </p>

                <!-- Workflow diagram -->
                <div class="mb-8 md:mb-12">
                    <!-- Desktop workflow -->
                    <div class="relative hidden md:block">
                        <!-- Connecting line -->
                        <div class=":bg-green-500 absolute left-[10%] top-8 z-0 h-0.5 w-[80%] bg-green-700"></div>

                        <!-- Steps with circles -->
                        <div class="relative z-10 flex justify-between">
                            <div v-for="step in workflowSteps" :key="step.number" class="flex flex-col items-center">
                                <div
                                    class=":bg-green-600 mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-700 text-xl font-bold text-white lg:h-16 lg:w-16 lg:text-2xl"
                                >
                                    {{ step.number }}
                                </div>
                                <h3 class=":text-green-500 mb-2 text-center text-sm font-bold text-green-700 lg:text-lg">
                                    {{ step.title }}
                                </h3>
                                <p class=":text-gray-300 max-w-xs text-center text-xs text-black lg:text-sm">
                                    {{ step.description }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile workflow -->
                    <div class="space-y-0 md:hidden">
                        <!-- Changed space-y-6 to space-y-0 -->
                        <div v-for="(step, index) in workflowSteps" :key="step.number" class="relative flex">
                            <!-- Added relative positioning -->
                            <div class="mr-4 flex flex-col items-center">
                                <!-- Circle with number -->
                                <div
                                    class="z-10 flex h-12 w-12 items-center justify-center rounded-full bg-green-700 text-xl font-bold text-white dark:bg-green-600"
                                >
                                    <!-- Added z-10 -->
                                    {{ step.number }}
                                </div>
                                <!-- Vertical line -->
                                <div v-if="index !== workflowSteps.length - 1" class="mt-0 h-24 w-0.5 bg-green-700 dark:bg-green-500">
                                    <!-- Changed h-12 to h-24 and mt-2 to mt-0 -->
                                </div>
                            </div>
                            <!-- Content -->
                            <div class="pb-8">
                                <!-- Added padding bottom -->
                                <h3 class="mb-1 text-lg font-bold text-green-700 dark:text-green-500">
                                    {{ step.title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ step.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Us -->
            <section id="about-us" class="bg-green-100 py-16">
                <div class="mx-auto max-w-4xl p-4">
                    <!-- Header -->
                    <h1 class="mb-8 text-center text-4xl font-bold text-green-700">About Us</h1>

                    <h2 class="mb-4 text-2xl font-bold text-green-700">Dinas Lingkungan Hidup Kabupaten Karanganyar</h2>

                    <div class="space-y-4 font-normal text-gray-800">
                        <p>
                            Dinas Lingkungan Hidup Kabupaten Karanganyar adalah instansi pemerintah yang bertugas mengelola dan menjaga kelestarian
                            lingkungan hidup di wilayah Kabupaten Karanganyar. Kami berkomitmen untuk menciptakan lingkungan yang bersih, sehat, dan
                            berkelanjutan bagi seluruh masyarakat.
                        </p>

                        <p>
                            Sejak didirikan pada tahun 2008, kami telah melaksanakan berbagai program pengelolaan lingkungan, termasuk pengelolaan
                            sampah terpadu, penghijauan, konservasi sumber daya air, pemantauan kualitas udara, serta pendidikan dan kesadaran
                            lingkungan untuk masyarakat.
                        </p>

                        <p>
                            Visi kami adalah mewujudkan Kabupaten Karanganyar yang hijau, bersih, dan lestari melalui pengelolaan lingkungan yang
                            berkelanjutan dan partisipatif. Kami mengajak seluruh lapisan masyarakat untuk berperan aktif dalam menjaga kelestarian
                            lingkungan hidup demi masa depan yang lebih baik.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
