import { ref, onMounted } from 'vue';
import { useTheme } from 'vuetify';

// Singleton state agar seluruh komponen berbagi state tema yang sama
const isDark = ref(false);
let isInitialized = false;

export function useThemeMode() {
    let theme: ReturnType<typeof useTheme> | null = null;

    try {
        theme = useTheme();
    } catch {
        // Fallback jika dipanggil di luar konteks Vuetify
    }

    const applyTheme = (dark: boolean) => {
        isDark.value = dark;

        if (theme) {
            theme.global.name.value = dark ? 'dark' : 'light';
        }

        if (typeof document !== 'undefined') {
            if (dark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        if (typeof localStorage !== 'undefined') {
            localStorage.setItem('theme', dark ? 'dark' : 'light');
        }

        if (typeof document !== 'undefined') {
            document.cookie = `theme=${dark ? 'dark' : 'light'};path=/;max-age=31536000;SameSite=Lax`;
        }
    };

    const toggleTheme = () => {
        applyTheme(!isDark.value);
    };

    onMounted(() => {
        // Inisialisasi tema saat pertama kali komponen ter-mount
        if (theme) {
            theme.global.name.value = isDark.value ? 'dark' : 'light';
        }

        if (!isInitialized) {
            isInitialized = true;
            if (typeof localStorage !== 'undefined') {
                const saved = localStorage.getItem('theme');
                if (saved) {
                    applyTheme(saved === 'dark');
                    return;
                }
            }

            if (typeof window !== 'undefined' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                applyTheme(true);
            } else {
                applyTheme(false);
            }
        }
    });

    return {
        isDark,
        toggleTheme,
        applyTheme,
    };
}
