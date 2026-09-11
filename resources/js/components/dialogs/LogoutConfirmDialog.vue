<script setup lang="ts">
import { ref } from 'vue';
import { useLogoutConfirm } from '@/composables/useLogoutConfirm';

const { isLogoutDialogOpen, closeLogoutDialog } = useLogoutConfirm();
const isLoggingOut = ref(false);

const handleLogout = () => {
    isLoggingOut.value = true;
    window.location.href = '/sso/logout';
};
</script>

<template>
    <v-dialog
        v-model="isLogoutDialogOpen"
        max-width="440"
        transition="dialog-bottom-transition"
        persistent
    >
        <v-card
            rounded="2xl"
            elevation="6"
            class="p-6 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100"
        >
            <!-- Header Icon & Title -->
            <div class="flex items-center gap-3.5 mb-4">
                <div class="h-12 w-12 rounded-xl bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 flex items-center justify-center shrink-0 shadow-sm">
                    <v-icon size="26">mdi-logout-variant</v-icon>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-slate-100 tracking-tight leading-snug">
                        Konfirmasi Keluar Akun
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        SiLaNyar &bull; DLH Kab. Karanganyar
                    </p>
                </div>
            </div>

            <!-- Vuetify Prompt Alert Box -->
            <v-alert
                type="warning"
                variant="tonal"
                density="compact"
                rounded="lg"
                class="text-xs mb-4 text-left border border-amber-200 dark:border-amber-800"
            >
                <template #prepend>
                    <v-icon size="20" color="warning">mdi-alert-circle-outline</v-icon>
                </template>
                <span>Pastikan data form atau verifikasi yang Anda kerjakan sudah disimpan sebelum mengakhiri sesi.</span>
            </v-alert>

            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                Apakah Anda yakin ingin keluar dari akun Anda saat ini? Sesi login Anda akan diakhiri dan Anda perlu login kembali untuk mengakses layanan.
            </p>

            <!-- Actions Buttons -->
            <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                <v-btn
                    variant="tonal"
                    rounded="lg"
                    size="small"
                    class="text-none font-semibold text-xs text-slate-700 dark:text-slate-300"
                    :disabled="isLoggingOut"
                    @click="closeLogoutDialog"
                >
                    Batal
                </v-btn>

                <v-btn
                    color="error"
                    variant="elevated"
                    rounded="lg"
                    size="small"
                    prepend-icon="mdi-logout"
                    :loading="isLoggingOut"
                    class="text-none font-bold text-xs shadow-sm"
                    @click="handleLogout"
                >
                    Ya, Keluar Sekarang
                </v-btn>
            </div>
        </v-card>
    </v-dialog>
</template>
