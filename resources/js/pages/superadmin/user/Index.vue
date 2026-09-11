<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { router, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

type Role = {
    id: number;
    name: string;
    guard_name?: string;
};

const props = defineProps<{
    users: Array<{
        id: number;
        nama: string;
        email: string;
        roles: Role[];
    }>;
    roles: Role[];
    filters: {
        search?: string;
    };
}>();

const search = ref(props.filters?.search || '');

function submitSearch() {
    router.get(
        '/superadmin/users',
        { search: search.value },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
}

function toggleRole(userId: number, roleName: string) {
    router.post(
        `/superadmin/users/${userId}/sync-roles`,
        { roles: [roleName] },
        {
            preserveScroll: true,
            onSuccess: () => {
                const targetUser = props.users.find((u) => u.id === userId);
                if (targetUser) {
                    targetUser.roles = props.roles.filter((role) => role.name === roleName);
                }
            },
        },
    );
}

const formatRoleName = (name: string) => {
    return name
        .split('_')
        .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
        .join(' ');
};
</script>

<template>
    <Head title="Manajemen Pengguna & Hak Akses" />

    <AdminLayout title="Manajemen Pengguna">
        <div class="space-y-6">
            <!-- Header Halaman & Aksi -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Manajemen Pengguna & Peran
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ props.users.length }} Pengguna
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Kelola akun pengguna, penetapan peran akses (role-based access control), dan otorisasi sistem
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <div class="relative w-full sm:w-64">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <v-icon size="16">mdi-magnify</v-icon>
                        </span>
                        <input
                            v-model="search"
                            @keyup.enter="submitSearch"
                            type="text"
                            placeholder="Cari nama atau email..."
                            class="w-full pl-8 pr-3 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                        />
                    </div>
                    <button
                        @click="submitSearch"
                        class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-sm transition"
                    >
                        Cari
                    </button>
                </div>
            </div>

            <!-- Tabel Data Pengguna Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">Nama Pengguna</th>
                                <th class="py-3.5 px-5">Email Terdaftar</th>
                                <th class="py-3.5 px-5">Penetapan Peran (Role)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                            <tr
                                v-for="u in props.users"
                                :key="u.id"
                                class="transition-colors hover:bg-emerald-50/50 dark:hover:bg-slate-800/60"
                            >
                                <td class="py-3.5 px-5">
                                    <div class="font-bold text-slate-900 dark:text-slate-100">{{ u.nama }}</div>
                                    <span class="font-mono text-[10px] text-slate-400">UID #{{ u.id }}</span>
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300">
                                    {{ u.email }}
                                </td>
                                <td class="py-3.5 px-5">
                                    <div class="flex flex-wrap gap-2 py-1">
                                        <label
                                            v-for="role in props.roles"
                                            :key="role.id"
                                            :class="[
                                                u.roles.some((r) => r.name === role.name)
                                                    ? 'bg-emerald-100 text-emerald-900 dark:bg-emerald-950/80 dark:text-emerald-200 border-emerald-400 dark:border-emerald-700 font-bold'
                                                    : 'bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:bg-slate-100'
                                            ]"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-[11px] cursor-pointer transition select-none"
                                        >
                                            <input
                                                type="radio"
                                                :name="'role-' + u.id"
                                                :value="role.name"
                                                :checked="u.roles.some((r) => r.name === role.name)"
                                                @change="() => toggleRole(u.id, role.name)"
                                                class="text-emerald-600 focus:ring-emerald-500 h-3 w-3"
                                            />
                                            <span>{{ formatRoleName(role.name) }}</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="props.users.length === 0">
                                <td colspan="3" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-account-search-outline</v-icon>
                                    <p class="font-medium text-xs">Tidak ada data pengguna yang sesuai pencarian.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>
