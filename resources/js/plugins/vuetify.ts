import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';
import { createVuetify } from 'vuetify';

export const vuetify = createVuetify({
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: '#1b5e20', // DLH Forest Green
                    secondary: '#2e7d32',
                    accent: '#059669',
                    success: '#16a34a',
                    info: '#0284c7',
                    warning: '#d97706',
                    error: '#dc2626',
                    background: '#f8fafc',
                    surface: '#ffffff',
                },
            },
            dark: {
                dark: true,
                colors: {
                    primary: '#2e7d32',
                    secondary: '#059669',
                    accent: '#34d399',
                    success: '#22c55e',
                    info: '#38bdf8',
                    warning: '#f59e0b',
                    error: '#ef4444',
                    background: '#020617',
                    surface: '#0f172a',
                },
            },
        },
    },
    display: {
        mobileBreakpoint: 'md',
        thresholds: {
            xs: 0,
            sm: 640,
            md: 768,
            lg: 1024,
            xl: 1280,
            xxl: 1536,
        },
    },
});

export default vuetify;
