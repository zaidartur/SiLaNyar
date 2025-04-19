<script setup lang="ts">
import { ref, computed } from 'vue';
import { NavigationMenu, NavigationMenuItem, NavigationMenuLink, NavigationMenuList } from '@/components/ui/navigation-menu';
import { Link, usePage } from '@inertiajs/vue3';
import { Home, Info, FileText, Menu, X } from 'lucide-vue-next';

const page = usePage();
const isCurrentRoute = computed(() => (url: string) => page.url === url);

const navItems = [
    { name: 'Home', href: '/dashboard', icon: Home },
    { name: 'About Us', href: '/about-us', icon: Info },
    { name: 'Informasi', href: '/informasi', icon: FileText },
];

const mobileMenuOpen = ref(false);
const toggleMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};
</script>

<template>
    <nav class="bg-customGreen">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Desktop Menu -->
                <NavigationMenu class="hidden md:block">
                    <NavigationMenuList class="flex items-center space-x-4">
                        <NavigationMenuItem v-for="item in navItems" :key="item.name" class="relative">
                            <Link :href="item.href" class="block">
                            <NavigationMenuLink
                                class="flex items-center gap-2 text-white hover:bg-customDarkGreen px-3 py-2 rounded-md text-sm font-medium relative"
                                :class="{ 'bg-customDarkGreen': isCurrentRoute(item.href) }">
                                <component :is="item.icon" class="h-5 w-5" />
                                {{ item.name }}
                                <div v-if="isCurrentRoute(item.href)"
                                    class="absolute -bottom-1 left-0 h-0.5 w-full bg-white rounded-b">
                                </div>
                            </NavigationMenuLink>
                            </Link>
                        </NavigationMenuItem>
                    </NavigationMenuList>
                </NavigationMenu>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white focus:outline-none" @click="toggleMenu">
                    <component :is="mobileMenuOpen ? X : Menu" class="h-6 w-6" />
                </button>
            </div>

            <!-- Mobile Menu List -->
            <div v-if="mobileMenuOpen" class="md:hidden mt-2 space-y-2">
                <div v-for="item in navItems" :key="item.name">
                    <Link :href="item.href" class="block">
                    <div class="flex items-center gap-2 text-white hover:bg-customDarkGreen px-4 py-2 rounded-md text-sm font-medium"
                        :class="{ 'bg-customDarkGreen': isCurrentRoute(item.href) }">
                        <component :is="item.icon" class="h-5 w-5" />
                        {{ item.name }}
                    </div>
                    </Link>
                </div>
            </div>
        </div>
    </nav>
</template>
