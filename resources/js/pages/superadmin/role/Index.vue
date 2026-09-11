<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { router, Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Permission {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
    permissions: Permission[];
}

const props = defineProps<{
    role: Role[];
}>();

const search = ref('');

const filteredRoles = computed(() => {
    if (!search.value) return props.role || [];
    const q = search.value.toLowerCase();
    return (props.role || []).filter((r) =>
        r.name.toLowerCase().includes(q) ||
        r.permissions.some((p) => p.name.toLowerCase().includes(q))
    );
});

const showDeleteModal = ref(false);
const deletingRole = ref<Role | null>(null);
const isDeleting = ref(false);

const openDeleteModal = (r: Role) => {
    deletingRole.value = r;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingRole.value = null;
};

const handleDelete = () => {
    if (!deletingRole.value) return;
    isDeleting.value = true;

    router.delete(`/superadmin/role/${deletingRole.value.id}`, {
        onSuccess: () => {
            isDeleting.value = false;
            closeDeleteModal();
        },
        onError: () => {
            isDeleting.value = false;
        },
    });
};

const formatRoleName = (name: string) => {
    return name
        .split('_')
        .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
        .join(' ');
};
</script>

<template>
    <Head title="Manajemen Peran & Hak Akses" />

    <AdminLayout title="Manajemen Peran">
        <div class="space-y-6">
            <!-- Header Halaman & Aksi -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Manajemen Peran (Roles)
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredRoles.length }} Peran
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Definisi kelompok otoritas pengguna dan hak akses izin sistem laboratorium
                    </p>
                </div>

                <div class="flex items-center gap-2.5">
                    <div class="relative w-full sm:w-60">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <v-icon size="16">mdi-magnify</v-icon>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari peran/izin..."
                            class="w-full pl-8 pr-3 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                        />
                    </div>

                    <Link
                        href="/superadmin/role/create"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-xs font-bold px-4 py-2.5 shadow-sm transition"
                    >
                        <v-icon size="16">mdi-plus-circle-outline</v-icon>
                        <span>Tambah Peran</span>
                    </Link>
                </div>
            </div>

            <!-- Grid Kartu Peran Modern -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <v-card
                    v-for="r in filteredRoles"
                    :key="r.id"
                    rounded="2xl"
                    elevation="1"
                    class="p-6 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 flex flex-col justify-between space-y-4"
                >
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950 flex items-center justify-center text-emerald-800 dark:text-emerald-300">
                                    <v-icon size="20">mdi-shield-account</v-icon>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">
                                        {{ formatRoleName(r.name) }}
                                    </h2>
                                    <span class="font-mono text-[10px] text-slate-400 font-medium">slug: {{ r.name }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Izin (Permissions) -->
                        <div class="space-y-1.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                                Hak Akses ({{ r.permissions.length }})
                            </span>
                            <div v-if="r.permissions.length > 0" class="flex flex-wrap gap-1.5 max-h-32 overflow-y-auto pr-1">
                                <span
                                    v-for="p in r.permissions"
                                    :key="p.id"
                                    class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-[10px] font-medium border border-emerald-200 dark:border-emerald-800"
                                >
                                    {{ p.name }}
                                </span>
                            </div>
                            <span v-else class="text-xs text-slate-400 italic block">
                                Tidak ada izin spesifik
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Aksi Edit & Hapus -->
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <Link
                            :href="`/superadmin/role/edit/${r.id}`"
                            class="px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:hover:bg-amber-900/60 dark:text-amber-300 text-xs font-semibold transition"
                        >
                            Ubah Peran
                        </Link>
                        <button
                            type="button"
                            @click="openDeleteModal(r)"
                            class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 dark:bg-red-950/50 dark:hover:bg-red-900/60 dark:text-red-300 text-xs font-semibold transition cursor-pointer"
                        >
                            Hapus
                        </button>
                    </div>
                </v-card>

                <div v-if="filteredRoles.length === 0" class="col-span-full text-center py-12 text-slate-400 dark:text-slate-500">
                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-shield-search</v-icon>
                    <p class="font-medium text-xs">Belum ada peran yang terdaftar atau sesuai pencarian.</p>
                </div>
            </div>

            <!-- Modal Konfirmasi Hapus -->
            <v-dialog v-model="showDeleteModal" max-width="440" persistent>
                <v-card rounded="2xl" class="p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-center space-y-4">
                    <div class="w-14 h-14 mx-auto rounded-full bg-red-100 dark:bg-red-950 flex items-center justify-center text-red-600 dark:text-red-400">
                        <v-icon size="32">mdi-alert-octagon-outline</v-icon>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                            Konfirmasi Hapus Peran
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">
                            Apakah Anda yakin ingin menghapus peran
                            <strong class="text-slate-900 dark:text-slate-100">{{ deletingRole?.name }}</strong>?
                            Pengguna dengan peran ini akan kehilangan hak akses terkait.
                        </p>
                    </div>

                    <div class="flex items-center justify-center gap-3 pt-2">
                        <button
                            type="button"
                            @click="closeDeleteModal"
                            class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            :disabled="isDeleting"
                            @click="handleDelete"
                            class="px-4 py-2 rounded-xl text-xs font-bold bg-red-600 hover:bg-red-700 active:bg-red-800 text-white shadow-sm transition disabled:opacity-50"
                        >
                            {{ isDeleting ? 'Menghapus...' : 'Ya, Hapus' }}
                        </button>
                    </div>
                </v-card>
            </v-dialog>
        </div>
    </AdminLayout>
</template>