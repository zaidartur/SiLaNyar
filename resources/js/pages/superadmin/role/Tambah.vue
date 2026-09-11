<script lang="ts" setup>
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

interface Permission {
    id: number;
    name: string;
}

const props = defineProps<{
    permission: Permission[];
}>();

const form = useForm({
    name: '',
    dashboard_view: '',
    permissions: [] as number[],
});

const dashboardOptions = [
    { label: 'Super Admin', value: 'dashboard/SuperAdmin' },
    { label: 'Staf Administrator', value: 'dashboard/Admin' },
    { label: 'Petugas PPCU', value: 'dashboard/PPCU' },
    { label: 'Teknisi Penguji', value: 'dashboard/Teknisi' },
    { label: 'Penyelia Laboratorium', value: 'dashboard/Penyelia' },
    { label: 'Kepala Laboratorium', value: 'dashboard/KepalaLab' },
    { label: 'Pengendali Teknis', value: 'dashboard/PengendaliTeknis' },
    { label: 'Kepala Dinas', value: 'dashboard/KepalaDinas' },
    { label: 'Pelanggan / Customer', value: 'customer/dashboard/Index' },
];

const submit = () => {
    form.post('/superadmin/role/store', {
        preserveScroll: true,
    });
};

const toggleAll = (e: Event) => {
    const checked = (e.target as HTMLInputElement).checked;
    if (checked) {
        form.permissions = props.permission.map((p) => p.id);
    } else {
        form.permissions = [];
    }
};
</script>

<template>
    <Head title="Tambah Role Baru" />
    <AdminLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                        Manajemen Akses
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Tambah Peran / Role Baru
                    </h1>
                </div>

                <v-btn
                    component="a"
                    href="/superadmin/role"
                    variant="outlined"
                    rounded="lg"
                    size="small"
                    prepend-icon="mdi-arrow-left"
                    class="text-none font-semibold text-xs"
                >
                    Kembali
                </v-btn>
            </div>

            <!-- Form Card -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Nama Role -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Nama Role / Jabatan <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Contoh: Manajer Mutu, Bendahara Penerima"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            :class="[form.errors.name ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        />
                        <p v-if="form.errors.name" class="text-xs text-rose-600 mt-1 font-medium">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Tampilan Dashboard -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Tampilan Dashboard Default <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.dashboard_view"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            :class="[form.errors.dashboard_view ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        >
                            <option value="" disabled>-- Pilih Tampilan Dashboard --</option>
                            <option v-for="opt in dashboardOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }} ({{ opt.value }})
                            </option>
                        </select>
                        <p v-if="form.errors.dashboard_view" class="text-xs text-rose-600 mt-1 font-medium">
                            {{ form.errors.dashboard_view }}
                        </p>
                    </div>

                    <!-- Permissions Checklist -->
                    <div class="pt-2">
                        <div class="flex items-center justify-between mb-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Hak Akses / Permissions ({{ form.permissions.length }} dipilih)
                            </label>

                            <label class="flex items-center gap-1.5 text-xs text-slate-500 cursor-pointer">
                                <input type="checkbox" @change="toggleAll" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
                                <span>Pilih Semua</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 border border-slate-200 dark:border-slate-800 rounded-xl p-3 max-h-60 overflow-y-auto">
                            <label
                                v-for="perm in props.permission"
                                :key="perm.id"
                                class="flex items-center gap-2 p-2 rounded-lg cursor-pointer text-xs"
                                :class="[
                                    form.permissions.includes(perm.id)
                                        ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-100 font-semibold'
                                        : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'
                                ]"
                            >
                                <input
                                    type="checkbox"
                                    :value="perm.id"
                                    v-model="form.permissions"
                                    class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                />
                                <span class="truncate">{{ perm.name }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                        <v-btn
                            component="a"
                            href="/superadmin/role"
                            variant="text"
                            size="small"
                            class="text-none text-xs"
                        >
                            Batal
                        </v-btn>

                        <v-btn
                            type="submit"
                            color="primary"
                            rounded="lg"
                            prepend-icon="mdi-content-save"
                            :loading="form.processing"
                            class="text-none font-semibold text-xs px-6"
                        >
                            Simpan Role
                        </v-btn>
                    </div>
                </form>
            </v-card>
        </div>
    </AdminLayout>
</template>
