import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useDisplay } from 'vuetify';
import {
    PPCU_BOTTOM_NAV_ITEMS,
    PELANGGAN_BOTTOM_NAV_ITEMS,
    ANALIS_BOTTOM_NAV_ITEMS,
} from '@/constants/navigation.constants';
import type { BottomNavItem } from '@/types/navigation.types';

export function useMobileNav() {
    const isDrawerOpen = ref(false);
    const display = useDisplay();

    const isMobile = computed(() => display.mobile.value);

    const toggleDrawer = () => {
        isDrawerOpen.value = !isDrawerOpen.value;
    };

    const userRoles = computed<string[]>(() => {
        const page = usePage();
        const user = page.props?.auth?.user as any;
        if (!user || !user.roles) return [];
        return user.roles.map((r: any) => r.name);
    });

    const primaryRole = computed<string>(() => {
        const roles = userRoles.value;
        const roleOrder = [
            'superadmin',
            'kepala_dinas',
            'kepala_lab',
            'pengendali_teknis',
            'penyelia',
            'staf_administrator',
            'analis',
            'ppcu',
            'pelanggan',
            'admin',
            'teknisi',
            'customer',
        ];

        for (const r of roleOrder) {
            if (roles.includes(r)) return r;
        }
        return roles[0] || '';
    });

    const hasBottomNav = computed<boolean>(() => {
        const role = primaryRole.value;
        return ['ppcu', 'pelanggan', 'customer', 'analis', 'teknisi'].includes(role);
    });

    const bottomNavItems = computed<BottomNavItem[]>(() => {
        const role = primaryRole.value;
        if (role === 'ppcu') {
            return PPCU_BOTTOM_NAV_ITEMS;
        }
        if (role === 'analis' || role === 'teknisi') {
            return ANALIS_BOTTOM_NAV_ITEMS;
        }
        if (role === 'pelanggan' || role === 'customer') {
            return PELANGGAN_BOTTOM_NAV_ITEMS;
        }
        return [];
    });

    return {
        isDrawerOpen,
        isMobile,
        toggleDrawer,
        primaryRole,
        hasBottomNav,
        bottomNavItems,
    };
}
