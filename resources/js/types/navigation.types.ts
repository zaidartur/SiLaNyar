export interface NavigationItem {
    title: string;
    href: string;
    icon: string;
    permission?: string;
    roles?: string[];
    badge?: string | number;
    children?: NavigationItem[];
}

export interface BottomNavItem {
    label: string;
    href: string;
    icon: string;
    value: string;
}

export type ThemeMode = 'light' | 'dark' | 'system';
