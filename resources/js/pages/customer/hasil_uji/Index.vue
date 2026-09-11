<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps<{
    hasil_uji: any[];
    currentPage?: number;
    totalPages?: number;
}>();

const search = ref('');

function formatTanggal(tanggal: string) {
    if (!tanggal) return '-';
    const d = new Date(tanggal);
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

const statusLabel = (status: string) => {
    const labels: Record<string, string> = {
        draf: 'Draf',
        revisi: 'Revisi',
        proses_review: 'Proses Review',
        proses_peresmian: 'Proses Peresmian',
        selesai: 'Selesai',
    };
    return labels[status] ?? status;
};

const getStatusBadge = (st: string) => {
    switch (st) {
        case 'selesai':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800';
        case 'proses_review':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-300 dark:border-amber-800';
        case 'proses_peresmian':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300 border-blue-300 dark:border-blue-800';
        case 'revisi':
            return 'bg-red-100 text-red-800 dark:bg-red-950/80 dark:text-red-300 border-red-300 dark:border-red-800';
        default:
            return 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-300 dark:border-slate-700';
    }
};

const showModal = ref(false);
const selectedId = ref<number | null>(null);

function openVerifikasiModal(id: number) {
    selectedId.value = id;
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    selectedId.value = null;
}

const isVerifying = ref(false);
function handleVerifikasi() {
    if (selectedId.value !== null) {
        isVerifying.value = true;
        router.put(
            `/customer/hasiluji/${selectedId.value}/verifikasi`,
            { status: 'proses_peresmian' },
            {
                onSuccess: () => {
                    isVerifying.value = false;
                    closeModal();
                },
                onError: () => {
                    isVerifying.value = false;
                },
            },
        );
    }
}

const filteredList = computed(() => {
    if (!search.value) return props.hasil_uji || [];
    const q = search.value.toLowerCase();
    return (props.hasil_uji || []).filter((item: any) =>
        String(item.id).includes(q) ||
        item.pengujian?.form_pengajuan?.kategori?.nama?.toLowerCase().includes(q) ||
        item.pengujian?.form_pengajuan?.jenis_cairan?.nama?.toLowerCase().includes(q)
    );
});
</script>

<template>
    <Head title="Hasil Uji Laboratorium" />

    <CustomerLayout title="Hasil Uji Sampel">
        <div class="space-y-6">
            <!-- Header Halaman & Filter -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Hasil Uji Laboratorium (LHU)
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredList.length }} LHU
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Daftar sertifikat hasil uji dan lembar laporan pengujian sampel laboratorium Anda
                    </p>
                </div>

                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <v-icon size="16">mdi-magnify</v-icon>
                    </span>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari ID / kategori..."
                        class="w-full pl-8 pr-3 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    />
                </div>
            </div>

            <!-- Tabel Data Hasil Uji Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">ID LHU</th>
                                <th class="py-3.5 px-5">Kategori / Cairan</th>
                                <th class="py-3.5 px-5">Tanggal Uji</th>
                                <th class="py-3.5 px-5">Metode</th>
                                <th class="py-3.5 px-5">Status LHU</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                            <tr
                                v-for="item in filteredList"
                                :key="item.id"
                                class="transition-colors hover:bg-emerald-50/50 dark:hover:bg-slate-800/60"
                            >
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        LHU-{{ String(item.id).padStart(4, '0') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5">
                                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ item.pengujian?.form_pengajuan?.kategori?.nama || '-' }}</div>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ item.pengujian?.form_pengajuan?.jenis_cairan?.nama || '' }}</span>
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                    {{ formatTanggal(item.pengujian?.tanggal_uji) }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300 capitalize">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[11px] font-medium border border-slate-200 dark:border-slate-700">
                                        {{ item.pengujian?.form_pengajuan?.metode_pengambilan || '-' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span
                                        :class="getStatusBadge(item.status)"
                                        class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border"
                                    >
                                        {{ statusLabel(item.status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2 justify-end">
                                        <!-- Tombol Verifikasi Persetujuan -->
                                        <button
                                            v-if="item.status === 'proses_review'"
                                            @click="openVerifikasiModal(item.id)"
                                            class="px-2.5 py-1 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-bold shadow-xs transition cursor-pointer"
                                        >
                                            Verifikasi
                                        </button>

                                        <!-- Tombol Detail -->
                                        <Link
                                            :href="`/customer/hasil_uji/${item.id}`"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold transition"
                                        >
                                            <v-icon size="14">mdi-eye-outline</v-icon>
                                            <span>Detail</span>
                                        </Link>

                                        <!-- Tombol Aduan -->
                                        <Link
                                            :href="`/customer/aduan/tambah/${item.id}`"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:hover:bg-amber-900/60 dark:text-amber-300 text-xs font-semibold transition"
                                            title="Ajukan Aduan Terkait LHU ini"
                                        >
                                            <v-icon size="14">mdi-alert-circle-outline</v-icon>
                                            <span>Aduan</span>
                                        </Link>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="filteredList.length === 0">
                                <td colspan="6" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-file-check-outline</v-icon>
                                    <p class="font-medium text-xs">Belum ada Lembar Hasil Uji yang diterbitkan.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </v-card>

            <!-- Modal Verifikasi Persetujuan -->
            <v-dialog v-model="showModal" max-width="440" persistent>
                <v-card rounded="2xl" class="p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-center space-y-4">
                    <div class="w-14 h-14 mx-auto rounded-full bg-emerald-100 dark:bg-emerald-950 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <v-icon size="32">mdi-check-circle-outline</v-icon>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                            Verifikasi Hasil Pengujian
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">
                            Apakah Anda menyetujui hasil uji ini? Setelah diverifikasi, dokumen akan diteruskan untuk proses peresmian tanda tangan digital.
                        </p>
                    </div>

                    <div class="flex items-center justify-center gap-3 pt-2">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            :disabled="isVerifying"
                            @click="handleVerifikasi"
                            class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-700 hover:bg-emerald-800 text-white shadow-sm transition disabled:opacity-50"
                        >
                            {{ isVerifying ? 'Memproses...' : 'Ya, Setujui & Verifikasi' }}
                        </button>
                    </div>
                </v-card>
            </v-dialog>
        </div>
    </CustomerLayout>
</template>