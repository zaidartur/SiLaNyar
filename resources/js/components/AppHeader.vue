<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import type { BreadcrumbItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();

interface User {
    nama?: string;
}

interface Auth {
    user?: User;
}
const auth = computed<Auth>(() => page.props.auth as Auth);
</script>

<template>
    <!-- <pre>{{ auth }}</pre> -->
    <div>
        <div class="bg-white border-b border-sidebar-border/80">
            <div class="flex h-20 w-full items-center px-4">
                <Link :href="route('dashboard')" class="flex items-center gap-x-2">
                    <AppLogo />
                </Link>

                <div class="ml-auto flex items-center space-x-2">
                    <div class="relative flex items-center space-x-1">
                        <span v-if="auth.user?.nama" class="ml-2 font-bold text-customDarkGreen">
                            {{ auth.user.nama }}
                        </span>
                    </div>

                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button variant="ghost" size="icon"
                                class="relative size-16 w-auto rounded-full p-1 focus-within:ring-2 focus-within:ring-primary">
                                <Avatar class="size-14 overflow-hidden rounded-full bg-white">
                                    <AvatarImage src="/storage/assetslandingpage/LogoUser.png" :alt="auth.user?.nama" />
                                    <!-- <AvatarFallback class="rounded-lg bg-neutral-200 font-semibold text-black">
                                        {{ getInitials(auth.user?.nama) }}
                                    </AvatarFallback> -->
                                </Avatar>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56 z-50">
                            <UserMenuContent />
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </div>

        <div v-if="props.breadcrumbs.length > 1" class="flex w-full border-b border-sidebar-border/70">
            <div class="flex h-12 w-full items-center justify-start px-4 text-neutral-500">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>
    </div>
</template>
