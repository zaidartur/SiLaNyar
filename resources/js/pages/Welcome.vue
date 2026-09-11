<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { useThemeMode } from '@/composables/useThemeMode';
import WorkflowTracker from '@/components/ui/WorkflowTracker.vue';

const { isDark, toggleTheme } = useThemeMode();
const page = usePage();

// Scrollspy & Active Section Tracking
const activeSection = ref('hero');
let observer: IntersectionObserver | null = null;

const navItems = [
    { id: 'hero', label: 'Beranda' },
    { id: 'informasi', label: 'Syarat & Jadwal' },
    { id: 'alur', label: 'Alur Layanan' },
    { id: 'about-us', label: 'Tentang Kami' },
    { id: 'kontak', label: 'Kontak' },
];

onMounted(() => {
    if (typeof window !== 'undefined' && 'IntersectionObserver' in window) {
        observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        activeSection.value = entry.target.id;
                    }
                });
            },
            {
                rootMargin: '-20% 0px -60% 0px',
                threshold: 0.1,
            }
        );

        navItems.forEach((item) => {
            const el = document.getElementById(item.id);
            if (el && observer) {
                observer.observe(el);
            }
        });
    }
});

onUnmounted(() => {
    if (observer) {
        observer.disconnect();
    }
});

// Form Kontak Kami
const contactForm = ref({
    nama: '',
    email: '',
    pesan: '',
});
const isSubmittingContact = ref(false);
const contactSuccess = ref(false);

const handleContactSubmit = () => {
    isSubmittingContact.value = true;
    setTimeout(() => {
        isSubmittingContact.value = false;
        contactSuccess.value = true;
        contactForm.value = { nama: '', email: '', pesan: '' };
        setTimeout(() => {
            contactSuccess.value = false;
        }, 4000);
    }, 600);
};

const scrollToSection = (id: string) => {
    if (typeof document !== 'undefined') {
        const el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    }
};

const syaratPenerimaan = [
    'Volume atau jumlah sampel minimal 2,5 liter untuk pengujian umum.',
    'Volume sampel lemak 1 liter dengan botol kaca gelap dan bermulut lebar.',
    'Volume sampel fecal coli dan total coliform minimal 100 ml dengan botol kaca steril.',
    'Kondisi wadah atau kemasan sampel harus bersih, tertutup rapat, dan tidak bocor.',
    'Waktu pengambilan sampel serta lama penyimpanan harus tercatat jelas.',
    'Menyertakan informasi pengawetan sampel (jenis bahan dan waktu pengawetan).',
];

const sampleDitolak = [
    'Volume atau jumlah contoh uji kurang dari batas minimal pengujian.',
    'Masa simpan sampel telah melampaui batas waktu maksimum holding time.',
    'Kemasan sampel mengalami kebocoran atau kerusakan selama perjalanan.',
    'Wadah sampel kotor, berlumut, atau terindikasi kontaminasi luar.',
];
</script>

<template>
    <Head title="Sistem Informasi Laboratorium Lingkungan Hidup Kabupaten Karanganyar" />

    <v-app :theme="isDark ? 'dark' : 'light'" class="font-sans min-h-screen text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-slate-950">
        <!-- Topbar Navbar Konsisten & Modern -->
        <v-app-bar
            elevation="1"
            color="surface"
            class="px-3 sm:px-6 border-b border-slate-200/90 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md"
        >
            <!-- Logo & Brand Title -->
            <div class="flex items-center gap-3 cursor-pointer" @click="scrollToSection('hero')">
                <img
                    src="/assets/assetsadmin/logodlh.png"
                    alt="Logo DLH Karanganyar"
                    class="w-10 h-10 object-contain drop-shadow-sm"
                />
                <div class="flex flex-col">
                    <span class="text-base sm:text-lg font-extrabold tracking-wide uppercase text-emerald-800 dark:text-emerald-400 leading-tight">
                        SiLaNyar
                    </span>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 hidden sm:inline font-medium">
                        Lab Lingkungan Hidup Kab. Karanganyar
                    </span>
                </div>
            </div>

            <v-spacer />

            <!-- Navigasi Menu Header Konsisten (Desktop) dengan Scrollspy -->
            <div class="hidden lg:flex items-center gap-2 sm:gap-3 mx-4">
                <v-btn
                    v-for="item in navItems"
                    :key="item.id"
                    :variant="activeSection === item.id ? 'tonal' : 'text'"
                    :color="activeSection === item.id ? 'primary' : undefined"
                    rounded="lg"
                    class="text-none font-semibold text-xs transition-all duration-200"
                    :class="[
                        activeSection === item.id
                            ? 'font-bold bg-emerald-100/70 text-emerald-900 dark:bg-emerald-950/80 dark:text-emerald-200 border border-emerald-300/80 dark:border-emerald-700/80'
                            : 'text-slate-600 dark:text-slate-300 hover:text-emerald-700 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800'
                    ]"
                    @click="scrollToSection(item.id)"
                >
                    {{ item.label }}
                </v-btn>
            </div>

            <!-- Action Buttons & Dark Mode Toggle -->
            <div class="flex items-center gap-2">
                <v-btn
                    icon
                    variant="text"
                    size="small"
                    :title="isDark ? 'Mode Terang' : 'Mode Gelap'"
                    :aria-label="isDark ? 'Mode Terang' : 'Mode Gelap'"
                    class="text-slate-600 dark:text-slate-300"
                    @click="toggleTheme"
                >
                    <v-icon size="20" :color="isDark ? 'amber' : 'primary'">
                        {{ isDark ? 'mdi-weather-sunny' : 'mdi-weather-night' }}
                    </v-icon>
                </v-btn>

                <!-- Tombol Auth -->
                <template v-if="!page.props.auth?.user">
                    <v-btn
                        component="a"
                        href="/customer/sso/login"
                        color="primary"
                        variant="elevated"
                        elevation="1"
                        rounded="lg"
                        prepend-icon="mdi-login"
                        class="text-none font-bold text-xs px-4"
                    >
                        Masuk (SSO)
                    </v-btn>
                    <v-btn
                        component="a"
                        href="https://sakti.karanganyarkab.go.id/register"
                        target="_blank"
                        rel="noopener"
                        variant="outlined"
                        color="primary"
                        rounded="lg"
                        class="text-none font-semibold text-xs px-3 hidden sm:inline-flex"
                    >
                        Daftar Akun
                    </v-btn>
                </template>

                <template v-else>
                    <v-btn
                        component="a"
                        href="/dashboard"
                        color="primary"
                        variant="elevated"
                        elevation="1"
                        rounded="lg"
                        prepend-icon="mdi-view-dashboard"
                        class="text-none font-bold text-xs px-4"
                    >
                        Ke Dashboard
                    </v-btn>
                </template>
            </div>
        </v-app-bar>

        <!-- Main Content Wrapper -->
        <v-main>
            <!-- Hero Section -->
            <section
                id="hero"
                class="relative min-h-[640px] flex items-center justify-center bg-cover bg-center overflow-hidden"
                style="background-image: url('/assets/assetslandingpage/hero.png')"
            >
                <!-- Subtle Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-950/90 via-emerald-900/80 to-slate-950/85 backdrop-blur-[2px]" />

                <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 py-24 text-center text-white space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 text-xs font-semibold uppercase tracking-wider backdrop-blur-sm">
                        <v-icon size="16">mdi-shield-check</v-icon>
                        Terakreditasi KAN & Standar ISO/IEC 17025
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                        Pelayanan Uji Laboratorium Lingkungan Hidup
                    </h1>

                    <p class="max-w-3xl mx-auto text-sm sm:text-lg text-emerald-100/90 leading-relaxed font-normal">
                        Sistem terintegrasi pemantauan mutu air, udara, dan tanah untuk menjamin kelestarian serta baku mutu lingkungan di Kabupaten Karanganyar.
                    </p>

                    <div class="pt-4 flex flex-wrap items-center justify-center gap-3 sm:gap-4">
                        <v-btn
                            color="accent"
                            size="large"
                            variant="elevated"
                            elevation="2"
                            rounded="xl"
                            prepend-icon="mdi-clipboard-text-search"
                            class="text-none font-bold text-sm text-slate-950 px-6"
                            @click="scrollToSection('informasi')"
                        >
                            Syarat & Jadwal Pelayanan
                        </v-btn>

                        <v-btn
                            component="a"
                            :href="page.props.auth?.user ? '/dashboard' : '/customer/sso/login'"
                            color="white"
                            size="large"
                            variant="outlined"
                            rounded="xl"
                            prepend-icon="mdi-flask-plus-outline"
                            class="text-none font-bold text-sm text-white px-6 border-white/80 hover:bg-white/10"
                        >
                            Pengajuan Uji Sampel
                        </v-btn>
                    </div>
                </div>
            </section>

            <!-- Syarat & Jadwal Section -->
            <section id="informasi" class="py-16 sm:py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12">
                <div class="text-center space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                        Standar Operasional Prosedur
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-slate-100">
                        Jadwal Penerimaan & Ketentuan Contoh Uji
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
                        Petugas penerima contoh uji hanya menerima sampel yang memenuhi persyaratan teknis dan jadwal operasional laboratorium.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Kolom Kiri: Syarat Penerimaan & Penolakan -->
                    <div class="space-y-6">
                        <!-- Syarat Diterima -->
                        <v-card rounded="2xl" elevation="1" class="p-6 border border-emerald-200/80 dark:border-emerald-900/60 bg-white dark:bg-slate-900">
                            <h3 class="text-lg font-bold text-emerald-800 dark:text-emerald-400 flex items-center gap-2 mb-4">
                                <v-icon color="success">mdi-check-circle</v-icon>
                                Syarat Kelayakan Contoh Uji
                            </h3>
                            <ul class="space-y-3">
                                <li v-for="(item, idx) in syaratPenerimaan" :key="idx" class="flex items-start gap-3 text-xs sm:text-sm text-slate-700 dark:text-slate-300">
                                    <v-icon color="success" size="18" class="shrink-0 mt-0.5">mdi-check</v-icon>
                                    <span>{{ item }}</span>
                                </li>
                            </ul>
                        </v-card>

                        <!-- Syarat Ditolak -->
                        <v-card rounded="2xl" elevation="1" class="p-6 border border-rose-200/80 dark:border-rose-900/60 bg-white dark:bg-slate-900">
                            <h3 class="text-lg font-bold text-rose-700 dark:text-rose-400 flex items-center gap-2 mb-4">
                                <v-icon color="error">mdi-alert-circle</v-icon>
                                Contoh Uji Dapat Ditolak Apabila
                            </h3>
                            <ul class="space-y-3">
                                <li v-for="(item, idx) in sampleDitolak" :key="idx" class="flex items-start gap-3 text-xs sm:text-sm text-slate-700 dark:text-slate-300">
                                    <v-icon color="error" size="18" class="shrink-0 mt-0.5">mdi-close</v-icon>
                                    <span>{{ item }}</span>
                                </li>
                            </ul>
                        </v-card>
                    </div>

                    <!-- Kolom Kanan: Jadwal Pelayanan Laboratorium -->
                    <div class="space-y-6">
                        <v-card rounded="2xl" elevation="1" class="p-6 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                    <v-icon color="primary">mdi-calendar-clock</v-icon>
                                    Jadwal Penerimaan Sampel Uji
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Loket Laboratorium Lingkungan Hidup Kabupaten Karanganyar
                                </p>
                            </div>

                            <!-- Tabel BOD -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-emerald-800 dark:text-emerald-400">
                                        Sampel dengan Parameter BOD
                                    </span>
                                    <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-bold">
                                        Perlakuan Khusus
                                    </span>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                                    <table class="w-full text-xs text-left">
                                        <thead class="bg-emerald-50 dark:bg-emerald-950/60 text-emerald-900 dark:text-emerald-300 font-bold">
                                            <tr>
                                                <th class="px-4 py-2.5">Hari Pelayanan</th>
                                                <th class="px-4 py-2.5 text-right">Jam Operasional</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                            <tr>
                                                <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200">Rabu & Kamis</td>
                                                <td class="px-4 py-3 text-right font-mono font-bold text-emerald-700 dark:text-emerald-400">08.00 - 11.00 WIB</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tabel Non-BOD -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                        Sampel Tanpa Parameter BOD (Fisika & Kimia Umum)
                                    </span>
                                    <span class="text-[10px] px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 font-bold">
                                        Reguler
                                    </span>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                                    <table class="w-full text-xs text-left">
                                        <thead class="bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-bold">
                                            <tr>
                                                <th class="px-4 py-2.5">Hari Pelayanan</th>
                                                <th class="px-4 py-2.5 text-right">Jam Operasional</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                            <tr>
                                                <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200">Rabu & Kamis</td>
                                                <td class="px-4 py-3 text-right font-mono font-bold text-slate-700 dark:text-slate-300">08.00 - 11.00 WIB</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200">Jumat</td>
                                                <td class="px-4 py-3 text-right font-mono font-bold text-slate-700 dark:text-slate-300">08.00 - 10.00 WIB</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/60 text-[11px] text-amber-800 dark:text-amber-300 flex items-start gap-2">
                                <v-icon size="16" color="warning" class="shrink-0 mt-0.5">mdi-information</v-icon>
                                <span>Pendaftaran pengujian disarankan dilakukan minimal 1 hari sebelum penyerahan contoh uji di loket laboratorium.</span>
                            </div>
                        </v-card>
                    </div>
                </div>
            </section>

            <!-- Alur Layanan Section -->
            <section id="alur" class="py-16 bg-emerald-50/50 dark:bg-slate-900/50 border-y border-slate-200/80 dark:border-slate-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                    <div class="text-center space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                            Transparansi Layanan Publik
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-slate-100">
                            Tahapan Alur Pengujian Laboratorium
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
                            Seluruh tahapan dipantau secara digital mulai dari registrasi sampai terbitnya Lembar Hasil Uji (LHU) dengan TTE sah.
                        </p>
                    </div>

                    <!-- Workflow Tracker Component -->
                    <WorkflowTracker />
                </div>
            </section>

            <!-- Tentang Kami (About Us) Section -->
            <section id="about-us" class="py-16 sm:py-20 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-8">
                <div class="text-center space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                        Profil Laboratorium
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-slate-100">
                        Dinas Lingkungan Hidup Kabupaten Karanganyar
                    </h2>
                </div>

                <v-card rounded="2xl" elevation="1" class="p-6 sm:p-10 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-5 text-xs sm:text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                    <p>
                        Dinas Lingkungan Hidup Kabupaten Karanganyar adalah instansi pemerintah yang bertugas mengelola dan menjaga kelestarian lingkungan hidup di wilayah Kabupaten Karanganyar. Kami berkomitmen untuk menciptakan lingkungan yang bersih, sehat, dan berkelanjutan bagi seluruh lapisan masyarakat.
                    </p>
                    <p>
                        Laboratorium Penguji DLH Karanganyar menyediakan layanan pengujian kualitas air permukaan, air limbah, air minum, dan udara dengan metode acuan standar SNI dan didukung oleh analis yang bersertifikasi kompetensi.
                    </p>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-around gap-4 text-center">
                        <div>
                            <div class="text-xl sm:text-2xl font-extrabold text-emerald-700 dark:text-emerald-400">ISO/IEC 17025</div>
                            <span class="text-[11px] text-slate-400">Standar Kompetensi Laboratorium</span>
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-extrabold text-emerald-700 dark:text-emerald-400">TTE Terintegrasi</div>
                            <span class="text-[11px] text-slate-400">Keabsahan Dokumen Digital</span>
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-extrabold text-emerald-700 dark:text-emerald-400">100% Akuntabel</div>
                            <span class="text-[11px] text-slate-400">Penerimaan Retribusi Daerah Sah</span>
                        </div>
                    </div>
                </v-card>
            </section>

            <!-- Hubungi Kami & Form Kontak -->
            <section id="kontak" class="py-16 bg-slate-100/70 dark:bg-slate-900 border-t border-slate-200/80 dark:border-slate-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                    <div class="text-center space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                            Layanan Informasi & Konsultasi
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-slate-100">
                            Hubungi Laboratorium Lingkungan
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Info Alamat & Kontak -->
                        <v-card rounded="2xl" elevation="1" class="p-6 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                <v-icon color="primary">mdi-map-marker-radius</v-icon>
                                Lokasi Kantor & Lab
                            </h3>

                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                Jl. Lawu No. 204, Tegalasri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57716
                            </p>

                            <div class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-300">
                                <div class="flex items-center gap-2">
                                    <v-icon size="16" color="primary">mdi-phone-outline</v-icon>
                                    <span>(0271) 495149</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <v-icon size="16" color="primary">mdi-email-outline</v-icon>
                                    <span>dlh@karanganyarkab.go.id</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <v-icon size="16" color="primary">mdi-clock-outline</v-icon>
                                    <span>Senin - Kamis : 08.00 - 16.30 WIB | Jumat : 08.00 - 15.00 WIB</span>
                                </div>
                            </div>
                        </v-card>

                        <!-- Peta Google Maps Embed -->
                        <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 overflow-hidden bg-white dark:bg-slate-900">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.1601683843385!2d110.95587037570697!3d-7.599453192437656!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a188319bc5cb5%3A0x5e3a9c5fea004c28!2sDinas%20Lingkungan%20Hidup!5e0!3m2!1sid!2sid!4v1681636149407!5m2!1sid!2sid"
                                class="w-full h-full min-h-[240px] border-0"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            />
                        </v-card>

                        <!-- Form Kirim Pesan dengan Komponen Vuetify Selaras -->
                        <v-card rounded="2xl" elevation="1" class="p-6 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                <v-icon color="primary">mdi-message-text-outline</v-icon>
                                Kirim Pertanyaan
                            </h3>

                            <form class="space-y-4" @submit.prevent="handleContactSubmit">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Nama Lengkap <span class="text-emerald-600">*</span>
                                    </label>
                                    <input
                                        v-model="contactForm.nama"
                                        type="text"
                                        placeholder="Masukkan nama Anda"
                                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                                        required
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Alamat Email <span class="text-emerald-600">*</span>
                                    </label>
                                    <input
                                        v-model="contactForm.email"
                                        type="email"
                                        placeholder="nama@email.com"
                                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                                        required
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Pesan / Pertanyaan <span class="text-emerald-600">*</span>
                                    </label>
                                    <textarea
                                        v-model="contactForm.pesan"
                                        rows="3"
                                        placeholder="Tuliskan pertanyaan atau kebutuhan konsultasi uji Anda"
                                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition resize-none"
                                        required
                                    ></textarea>
                                </div>

                                <button
                                    type="submit"
                                    :disabled="isSubmittingContact"
                                    class="w-full mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white font-bold text-xs py-3 px-4 shadow-md transition disabled:opacity-50 cursor-pointer"
                                >
                                    <v-icon size="16">mdi-send</v-icon>
                                    <span>{{ isSubmittingContact ? 'Mengirim...' : 'Kirim Pesan' }}</span>
                                </button>

                                <div v-if="contactSuccess" class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/70 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs text-center font-semibold animate-fade-in">
                                    ✓ Terima kasih, pesan Anda berhasil terkirim ke petugas DLH.
                                </div>
                            </form>
                        </v-card>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="bg-emerald-950 text-white py-10 px-4 sm:px-6 lg:px-8 border-t border-emerald-900">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-emerald-200/80">
                    <div class="flex items-center gap-3">
                        <img src="/assets/assetsadmin/logodlh.png" alt="Logo DLH" class="w-8 h-8 object-contain" />
                        <div>
                            <span class="font-bold text-white uppercase">SiLaNyar Karanganyar</span>
                            <p class="text-[11px] text-emerald-300">Dinas Lingkungan Hidup Kabupaten Karanganyar</p>
                        </div>
                    </div>
                    <p class="text-center sm:text-right">
                        &copy; 2026 Pemerintah Kabupaten Karanganyar. Hak Cipta Dilindungi.
                    </p>
                </div>
            </footer>
        </v-main>
    </v-app>
</template>