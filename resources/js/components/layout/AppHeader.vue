<script setup lang="ts">
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useThemeMode } from '@/composables/useThemeMode';
import { useLogoutConfirm } from '@/composables/useLogoutConfirm';

const props = defineProps<{
    title?: string;
}>();

defineEmits<{
    'toggle-drawer': [];
}>();

const { isDark, toggleTheme } = useThemeMode();
const { openLogoutDialog } = useLogoutConfirm();
const page = usePage();

const user = computed(() => (page.props as any).auth?.user || {});

const userInitials = computed(() => {
    const name = user.value?.nama || 'User';
    return name
        .split(' ')
        .slice(0, 2)
        .map((part: string) => part[0])
        .join('')
        .toUpperCase();
});

const formattedRole = computed(() => {
    const roles = user.value?.roles || [];
    const roleName = roles[0]?.name || 'Pegawai';
    return roleName
        .split('_')
        .map((word: string) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
});
</script>

<template>
    <v-app-bar
        elevation="0"
        color="surface"
        class="px-2 border-b border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100"
    >
        <v-app-bar-nav-icon
            variant="text"
            color="primary"
            aria-label="Buka Navigasi Menu"
            @click="$emit('toggle-drawer')"
        />

        <div class="flex items-center gap-3 ml-1">
            <div class="flex flex-col">
                <v-app-bar-title class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-slate-100 leading-tight">
                    {{ props.title || `Dashboard ${formattedRole}` }}
                </v-app-bar-title>
                <span class="text-xs text-slate-500 dark:text-slate-400 hidden sm:inline">
                    Laboratorium Penguji Dinas Lingkungan Hidup Kabupaten Karanganyar
                </span>
            </div>
        </div>

        <v-spacer />

        <div class="flex items-center gap-1 sm:gap-2">
            <!-- Icon Notifikasi Sistem -->
            <v-menu location="bottom end" transition="scale-transition">
                <template #activator="{ props: notifProps }">
                    <v-btn
                        v-bind="notifProps"
                        icon
                        variant="text"
                        size="small"
                        title="Pemberitahuan Sistem"
                        aria-label="Pemberitahuan Sistem"
                        class="text-slate-600 dark:text-slate-300"
                    >
                        <v-badge color="error" dot>
                            <v-icon size="22">mdi-bell-outline</v-icon>
                        </v-badge>
                    </v-btn>
                </template>

                <v-card
                    min-width="320"
                    max-width="380"
                    rounded="xl"
                    elevation="6"
                    class="p-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100"
                >
                    <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900 dark:text-slate-100">Pemberitahuan Sistem</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Terbaru
                        </span>
                    </div>

                    <v-list density="compact" nav class="py-1">
                        <v-list-item
                            prepend-icon="mdi-clipboard-clock-outline"
                            title="Pengajuan Sampel Masuk"
                            subtitle="Menunggu verifikasi kelengkapan berkas teknis"
                            href="/pegawai/pengajuan"
                            rounded="lg"
                            class="my-1 text-slate-700 dark:text-slate-200"
                        />
                        <v-list-item
                            prepend-icon="mdi-truck-check-outline"
                            title="Sampel Tiba di Loket"
                            subtitle="Petugas PPCU menyelesaikan serah terima contoh uji"
                            href="/pegawai/pengambilan"
                            rounded="lg"
                            class="my-1 text-slate-700 dark:text-slate-200"
                        />
                        <v-list-item
                            prepend-icon="mdi-cash-check"
                            title="Pembayaran Retribusi"
                            subtitle="Setoran retribusi laboratorium siap diverifikasi"
                            href="/pegawai/pembayaran"
                            rounded="lg"
                            class="my-1 text-slate-700 dark:text-slate-200"
                        />
                    </v-list>
                </v-card>
            </v-menu>

            <!-- Toggle Mode Gelap / Terang -->
            <v-btn
                icon
                variant="text"
                size="small"
                :title="isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
                :aria-label="isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
                class="text-slate-600 dark:text-slate-300"
                @click="toggleTheme"
            >
                <v-icon size="22" :color="isDark ? 'amber' : 'primary'">
                    {{ isDark ? 'mdi-weather-sunny' : 'mdi-weather-night' }}
                </v-icon>
            </v-btn>

            <!-- Profil User Dropdown Vuetify Standar -->
            <v-menu location="bottom end" transition="scale-transition">
                <template #activator="{ props: menuProps }">
                    <button
                        v-bind="menuProps"
                        type="button"
                        class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition text-left focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                        <v-avatar color="primary" size="36" class="shadow-sm">
                            <span class="text-xs font-bold text-white">{{ userInitials }}</span>
                        </v-avatar>
                        <div class="hidden md:flex flex-col text-right">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate max-w-[140px]">
                                {{ user.nama || 'Pengguna' }}
                            </span>
                            <span class="text-[11px] text-emerald-700 dark:text-emerald-400 font-semibold">
                                {{ formattedRole }}
                            </span>
                        </div>
                        <v-icon size="16" class="text-slate-400 hidden md:inline">mdi-chevron-down</v-icon>
                    </button>
                </template>

                <v-card min-width="240" rounded="xl" elevation="6" class="p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                    <div class="px-3 py-2.5 border-b border-slate-100 dark:border-slate-800">
                        <p class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate">{{ user.nama }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ user.email }}</p>
                        <span class="inline-block mt-1 text-[10px] font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                            {{ formattedRole }}
                        </span>
                    </div>

                    <v-list density="compact" nav class="py-1">
                        <v-list-item
                            prepend-icon="mdi-account-outline"
                            title="Profil Saya"
                            rounded="lg"
                            class="text-slate-700 dark:text-slate-200 text-xs cursor-pointer"
                            @click.prevent="router.visit(formattedRole === 'Pelanggan' ? '/customer/profile/show' : '/pegawai/profile/show')"
                        />
                        <v-divider class="my-1" />
                        <v-list-item
                            prepend-icon="mdi-logout-variant"
                            title="Keluar (Logout)"
                            color="error"
                            rounded="lg"
                            class="text-xs font-bold text-rose-600 dark:text-rose-400 cursor-pointer"
                            @click.prevent="openLogoutDialog"
                        />
                    </v-list>
                </v-card>
            </v-menu>
        </div>
    </v-app-bar>
</template>
