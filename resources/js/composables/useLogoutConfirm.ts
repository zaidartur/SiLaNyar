import { ref } from 'vue';

const isLogoutDialogOpen = ref(false);

export function useLogoutConfirm() {
    const openLogoutDialog = () => {
        isLogoutDialogOpen.value = true;
    };

    const closeLogoutDialog = () => {
        isLogoutDialogOpen.value = false;
    };

    return {
        isLogoutDialogOpen,
        openLogoutDialog,
        closeLogoutDialog,
    };
}
