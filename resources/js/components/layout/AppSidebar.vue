<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { ADMIN_SIDEBAR_ITEMS, CUSTOMER_SIDEBAR_ITEMS } from '@/constants/navigation.constants';
import { useMobileNav } from '@/composables/useMobileNav';
import { useLogoutConfirm } from '@/composables/useLogoutConfirm';
import type { NavigationItem } from '@/types/navigation.types';

const props = defineProps<{
    modelValue: boolean;
}>();

const emit = defineEmits<{
    'update:modelValue': [val: boolean];
}>();

const { isMobile } = useMobileNav();
const { openLogoutDialog } = useLogoutConfirm();
const page = usePage();

const user = computed(() => (page.props as any).auth?.user || {});
const roles = computed<any[]>(() => user.value?.roles || []);

// Deteksi apakah pengguna yang sedang aktif adalah customer/pelanggan
const isCustomer = computed(() => {
    const hasCustomerRole = roles.value.some((r: any) => {
        const name = (typeof r === 'string' ? r : r.name || '').toLowerCase();
        return name === 'pelanggan' || name === 'customer';
    });
    const isCustomerPath = (page.url || '').startsWith('/customer');
    return hasCustomerRole || isCustomerPath;
});

// Pilih dataset sidebar sesuai peran pengguna (Pegawai vs Pelanggan)
const currentSidebarItems = computed<NavigationItem[]>(() => {
    return isCustomer.value ? CUSTOMER_SIDEBAR_ITEMS : ADMIN_SIDEBAR_ITEMS;
});

const permissions = computed<string[]>(() => (page.props as any).auth?.permissions || []);

const can = (permission?: string): boolean => {
    if (!permission) return true;
    return permissions.value.includes(permission);
};

const filterItem = (item: NavigationItem): boolean => {
    if (!can(item.permission)) return false;
    if (item.children) {
        return item.children.some(child => can(child.permission));
    }
    return true;
};

// State grup menu yang sedang terbuka (dropdown expand)
const openedGroups = ref<string[]>([]);

const isItemActive = (href?: string) => {
    if (!href || href === '#') return false;
    const path = (page.url || '').split('?')[0];
    return path === href || path.startsWith(`${href}/`);
};

const isGroupActive = (item: NavigationItem): boolean => {
    if (!item.children || item.children.length === 0) return false;
    return item.children.some(child => isItemActive(child.href));
};

// Sinkronisasi otomatis agar menu utama terbuka (dropdown) saat submenu aktif
const syncActiveSubmenu = () => {
    const path = (page.url || '').split('?')[0];
    currentSidebarItems.value.forEach((item) => {
        if (item.children && item.children.length > 0) {
            const hasActiveChild = item.children.some((child) => {
                if (!child.href || child.href === '#') return false;
                return path === child.href || path.startsWith(`${child.href}/`);
            });
            if (hasActiveChild && !openedGroups.value.includes(item.title)) {
                openedGroups.value.push(item.title);
            }
        }
    });
};

watch(
    () => page.url,
    () => {
        syncActiveSubmenu();
    },
    { immediate: true },
);

// Navigasi SPA Inertia tanpa reload halaman penuh
const navigateTo = (href?: string) => {
    if (!href || href === '#') return;
    router.visit(href);
    if (isMobile.value) {
        emit('update:modelValue', false);
    }
};
</script>

<template>
    <v-navigation-drawer
        :model-value="props.modelValue"
        :permanent="!isMobile"
        :temporary="isMobile"
        :mobile-breakpoint="768"
        elevation="2"
        color="#0b2410"
        class="text-white select-none border-r border-emerald-900/60"
        width="280"
        @update:model-value="emit('update:modelValue', $event)"
    >
        <!-- Logo & Branding Header Vuetify Standar -->
        <div class="px-5 py-4 flex items-center gap-3 border-b border-emerald-800/60 bg-emerald-950/50">
            <img
                src="/assets/assetsadmin/logodlh.png"
                alt="Logo DLH Karanganyar"
                class="w-10 h-10 object-contain drop-shadow"
            />
            <div>
                <h2 class="text-base font-extrabold tracking-wide uppercase text-white leading-tight">SiLaNyar</h2>
                <p class="text-[11px] text-emerald-300 font-medium">Lab Lingkungan Hidup</p>
            </div>
        </div>

        <!-- Daftar Navigasi Berjenjang Standar Vuetify -->
        <v-list
            v-model:opened="openedGroups"
            density="comfortable"
            nav
            class="px-3 py-4 space-y-1"
        >
            <template v-for="item in currentSidebarItems.filter(filterItem)" :key="item.title">
                <!-- Menu Grup Bersarang (Sub-menu) -->
                <v-list-group v-if="item.children && item.children.length > 0" :value="item.title">
                    <template #activator="{ props: groupProps }">
                        <v-list-item
                            v-bind="groupProps"
                            :prepend-icon="item.icon"
                            :title="item.title"
                            rounded="lg"
                            class="text-xs sm:text-sm font-semibold text-emerald-100 hover:bg-emerald-800/40 cursor-pointer"
                            :class="{ 'bg-emerald-900/60 text-emerald-300 font-bold border-l-2 border-emerald-400': isGroupActive(item) }"
                        />
                    </template>

                    <v-list-item
                        v-for="child in item.children.filter(filterItem)"
                        :key="child.title"
                        :prepend-icon="child.icon"
                        :title="child.title"
                        rounded="lg"
                        :active="isItemActive(child.href)"
                        active-color="emerald-400"
                        class="text-xs font-medium pl-6 my-0.5 text-emerald-200 hover:bg-emerald-800/50 transition cursor-pointer"
                        :class="{ 'bg-emerald-800/70 text-white font-bold': isItemActive(child.href) }"
                        @click.prevent="navigateTo(child.href)"
                    />
                </v-list-group>

                <!-- Menu Item Tunggal -->
                <v-list-item
                    v-else
                    :prepend-icon="item.icon"
                    :title="item.title"
                    rounded="lg"
                    :active="isItemActive(item.href)"
                    active-color="emerald-400"
                    class="text-xs sm:text-sm font-semibold text-emerald-100 hover:bg-emerald-800/50 transition my-0.5 cursor-pointer"
                    :class="{ 'bg-emerald-800/70 text-white font-bold': isItemActive(item.href) }"
                    @click.prevent="navigateTo(item.href)"
                />
            </template>
        </v-list>

        <!-- Footer Drawer Logout -->
        <template #append>
            <div class="p-3 border-t border-emerald-900/60 bg-emerald-950/40">
                <v-list-item
                    prepend-icon="mdi-logout-variant"
                    title="Keluar (Logout)"
                    rounded="lg"
                    class="text-xs font-bold text-rose-300 hover:bg-rose-950/50 hover:text-rose-200 transition cursor-pointer"
                    @click.prevent="openLogoutDialog"
                />
            </div>
        </template>
    </v-navigation-drawer>
</template>
