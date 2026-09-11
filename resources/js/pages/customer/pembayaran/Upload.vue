<script setup lang="ts">
/* eslint-disable */
import { Head, useForm, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { ref } from 'vue';

interface Pengajuan {
    id: number;
}

interface Pembayaran {
    id: number;
    id_order: string;
    total_biaya: number;
    metode_pembayaran: 'transfer' | 'tunai';
}

const props = defineProps<{
    pengajuan: Pengajuan;
    pembayaran: Pembayaran;
    errors: Record<string, string>;
}>();

const form = useForm({
    metode_pembayaran: 'transfer' as const,
    bukti_pembayaran: null as File | null,
});

const previewUrl = ref<string | null>(null);

function handleFile(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0] || null;
    form.bukti_pembayaran = file;
    if (file && file.type.startsWith('image/')) {
        previewUrl.value = URL.createObjectURL(file);
    } else {
        previewUrl.value = null;
    }
}

function submit() {
    form.post(route('customer.pembayaran.process', props.pengajuan.uuid || props.pengajuan.id));
}

function formatRupiah(val: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val);
}
</script>

<template>
    <Head title="Unggah Bukti Pembayaran" />
    <CustomerLayout>
        <div class="max-w-xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                        Konfirmasi Pembayaran
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Unggah Bukti Transfer
                    </h1>
                </div>

                <v-btn
                    component="a"
                    :href="route('customer.pembayaran.show', props.pengajuan.uuid || props.pengajuan.id)"
                    variant="outlined"
                    rounded="lg"
                    size="small"
                    prepend-icon="mdi-arrow-left"
                    class="text-none font-semibold text-xs"
                >
                    Kembali
                </v-btn>
            </div>

            <!-- Card Tagihan & Rekening -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-5">
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">Nomor Tagihan</span>
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">#{{ props.pembayaran.id_order }}</p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">Metode</span>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 mt-0.5 uppercase">
                            {{ props.pembayaran.metode_pembayaran }}
                        </p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Total Pembayaran Retribusi:</span>
                    <span class="text-xl font-extrabold text-emerald-800 dark:text-emerald-300">
                        {{ formatRupiah(props.pembayaran.total_biaya) }}
                    </span>
                </div>

                <!-- Info Rekening Resmi -->
                <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-xs space-y-1">
                    <p class="font-bold text-slate-900 dark:text-slate-100">Rekening Resmi Tujuan Transfer:</p>
                    <p class="text-emerald-700 dark:text-emerald-400 font-extrabold text-sm">
                        Bank Jateng: 3-001-12345-6
                    </p>
                    <p class="text-slate-500 dark:text-slate-400 text-[11px]">a.n. DINAS LINGKUNGAN HIDUP KAB. KARANGANYAR</p>
                </div>

                <!-- Form Upload -->
                <form @submit.prevent="submit" class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Pilih Foto / Berkas Bukti Transfer <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="file"
                            @change="handleFile"
                            accept="image/jpeg,image/png,image/jpg,application/pdf"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer"
                        />
                        <p v-if="props.errors.bukti_pembayaran" class="text-xs text-rose-600 mt-1 font-medium">
                            {{ props.errors.bukti_pembayaran }}
                        </p>
                    </div>

                    <div v-if="previewUrl" class="text-center p-2 border border-slate-200 dark:border-slate-700 rounded-lg">
                        <img :src="previewUrl" alt="Pratinjau Bukti" class="max-h-48 mx-auto rounded shadow-sm" />
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                        <v-btn
                            component="a"
                            :href="route('customer.pembayaran.show', props.pengajuan.uuid || props.pengajuan.id)"
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
                            prepend-icon="mdi-upload"
                            :loading="form.processing"
                            class="text-none font-semibold text-xs px-6"
                        >
                            Unggah & Konfirmasi
                        </v-btn>
                    </div>
                </form>
            </v-card>
        </div>
    </CustomerLayout>
</template>