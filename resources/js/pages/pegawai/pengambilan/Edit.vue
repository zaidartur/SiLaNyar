<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { router, useForm, Head } from '@inertiajs/vue3';
import { computed } from 'vue';

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    nama: string;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    instansi: Instansi;
    metode_pengambilan: string;
    status_pengajuan: string;
}

interface Jadwal {
    id: number;
    form_pengajuan: Pengajuan;
    user: User;
    waktu_pengambilan: string;
    status: 'diproses' | 'diterima';
    keterangan: string;
}

const props = defineProps<{
    jadwal: Jadwal;
}>();

const metode = props.jadwal.form_pengajuan?.metode_pengambilan;

const formatDateForInput = (dateString: string) => {
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
};

const form = useForm({
    waktu_pengambilan: formatDateForInput(props.jadwal.waktu_pengambilan),
    status: props.jadwal.status,
    keterangan: props.jadwal.keterangan || '',
});

const tomorrowDate = computed(() => {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    return tomorrow.toISOString().split('T')[0];
});

const originalDate = computed(() => {
    return formatDateForInput(props.jadwal.waktu_pengambilan);
});

const isDateChanging = computed(() => {
    return form.waktu_pengambilan !== originalDate.value;
});

const minDate = computed(() => {
    return isDateChanging.value ? tomorrowDate.value : originalDate.value;
});

const submit = () => {
    form.put(`/pegawai/pengambilan/${props.jadwal.uuid || props.jadwal.id}/edit`, {
        onSuccess: () => {
            router.visit('/pegawai/pengambilan');
        },
    });
};
</script>

<template>
    <Head title="Edit Jadwal Pengambilan Sampel" />

    <AdminLayout title="Edit Jadwal Pengambilan">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Halaman & Breadcrumb Back -->
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="router.visit('/pegawai/pengambilan')"
                            class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition"
                            title="Kembali ke daftar jadwal"
                        >
                            <v-icon size="20">mdi-arrow-left</v-icon>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Edit Jadwal Pengambilan Sampel
                        </h1>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 ml-8">
                        Perbarui waktu pengambilan, status penerimaan, atau keterangan operasional
                    </p>
                </div>

                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 hidden sm:inline-block">
                    {{ props.jadwal.form_pengajuan?.kode_pengajuan }}
                </span>
            </div>

            <!-- Form Card Kontainer Modern -->
            <v-card rounded="2xl" elevation="1" class="p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Form Pengajuan (Readonly) -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Kode Form Pengajuan
                            </label>
                            <input
                                :value="props.jadwal.form_pengajuan?.kode_pengajuan"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/60 px-3.5 py-2.5 text-xs text-slate-600 dark:text-slate-400 font-mono font-medium cursor-not-allowed"
                                disabled
                            />
                        </div>

                        <!-- Nama Pengambil / Penerima (Readonly) -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Petugas Pengambil / Penerima
                            </label>
                            <input
                                :value="props.jadwal.user?.nama || '-'"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/60 px-3.5 py-2.5 text-xs text-slate-600 dark:text-slate-400 font-medium cursor-not-allowed"
                                disabled
                            />
                        </div>
                    </div>

                    <!-- Waktu Pengambilan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Waktu Pengambilan <span class="text-emerald-600">*</span>
                        </label>
                        <input
                            type="date"
                            v-model="form.waktu_pengambilan"
                            :min="minDate"
                            :disabled="metode === 'diantar' || metode === 'diambil'"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition disabled:bg-slate-100 dark:disabled:bg-slate-800/60 disabled:cursor-not-allowed"
                        />
                        <div v-if="form.errors.waktu_pengambilan" class="mt-1.5 text-xs text-red-500 font-medium">
                            {{ form.errors.waktu_pengambilan }}
                        </div>
                        <div class="mt-1.5 text-[11px] text-slate-500 dark:text-slate-400">
                            <span v-if="isDateChanging">Tanggal baru harus setelah hari ini ({{ tomorrowDate }})</span>
                            <span v-else>Tanggal tersimpan saat ini: {{ originalDate }}</span>
                        </div>
                    </div>

                    <!-- Status Pengambilan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Status Pengambilan <span class="text-emerald-600">*</span>
                        </label>
                        <select
                            v-model="form.status"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition disabled:bg-slate-100 dark:disabled:bg-slate-800/60 disabled:cursor-not-allowed"
                            :disabled="props.jadwal.form_pengajuan?.metode_pengambilan === 'diantar' && props.jadwal.form_pengajuan?.status_pengajuan === 'proses_validasi'"
                        >
                            <option value="diproses">Diproses</option>
                            <option value="diterima">Diterima</option>
                        </select>
                        <div
                            v-if="props.jadwal.form_pengajuan?.metode_pengambilan === 'diantar' && props.jadwal.form_pengajuan?.status_pengajuan === 'proses_validasi'"
                            class="mt-1.5 p-2.5 rounded-lg bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs"
                        >
                            Status tidak bisa diubah sebelum pengajuan sampel tervalidasi dan diterima.
                        </div>
                        <div v-if="form.errors.status" class="mt-1.5 text-xs text-red-500 font-medium">
                            {{ form.errors.status }}
                        </div>
                    </div>

                    <!-- Keterangan Operasional -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Keterangan Tambahan
                        </label>
                        <textarea
                            v-model="form.keterangan"
                            rows="3"
                            :disabled="metode === 'diantar'"
                            placeholder="Tuliskan catatan khusus terkait pengambilan sampel..."
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition disabled:bg-slate-100 dark:disabled:bg-slate-800/60 disabled:cursor-not-allowed resize-none"
                        ></textarea>
                        <div v-if="form.errors.keterangan" class="mt-1.5 text-xs text-red-500 font-medium">
                            {{ form.errors.keterangan }}
                        </div>
                    </div>

                    <!-- Tombol Aksi Simpan & Batal -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            @click="router.visit('/pegawai/pengambilan')"
                            class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white font-bold text-xs py-2.5 px-6 shadow-sm transition disabled:opacity-50 cursor-pointer"
                        >
                            <v-icon size="16">mdi-content-save-check-outline</v-icon>
                            <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
                        </button>
                    </div>
                </form>
            </v-card>
        </div>
    </AdminLayout>
</template>
