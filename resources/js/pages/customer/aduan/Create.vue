<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    hasil_uji: {
        id: number;
        kode_hasil_uji?: string;
    };
}>();

const form = useForm({
    terkait: 'pengujian' as 'administrasi' | 'pengujian',
    masalah: '',
    perbaikan: '',
});

function submit() {
    form.post(`/customer/hasiluji/aduan/${props.hasil_uji.id}`);
}
</script>

<template>
    <Head title="Tambah Aduan Hasil Uji" />
    <CustomerLayout>
        <div class="max-w-2xl mx-auto space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300">
                        Layanan Pengaduan
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Tambah Aduan Hasil Uji
                    </h1>
                </div>

                <v-btn
                    component="a"
                    href="/customer/hasiluji"
                    variant="outlined"
                    rounded="lg"
                    size="small"
                    prepend-icon="mdi-arrow-left"
                    class="text-none font-semibold text-xs"
                >
                    Kembali
                </v-btn>
            </div>

            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                <form @submit.prevent="submit" class="space-y-5">
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Nomor Kode Hasil Uji:</span>
                        <span class="font-extrabold text-slate-900 dark:text-slate-100 text-sm">
                            {{ props.hasil_uji.kode_hasil_uji || 'HU-' + props.hasil_uji.id }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-2 text-slate-700 dark:text-slate-300">
                            Bidang Aduan <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label
                                @click="form.terkait = 'pengujian'"
                                class="cursor-pointer p-3.5 rounded-xl border flex items-center gap-3 transition-all"
                                :class="[
                                    form.terkait === 'pengujian'
                                        ? 'border-emerald-600 bg-emerald-50/70 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-100 ring-2 ring-emerald-500 font-semibold'
                                        : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 text-slate-700 dark:text-slate-300 hover:border-emerald-400'
                                ]"
                            >
                                <v-icon icon="mdi-test-tube" color="primary" />
                                <div class="text-xs">
                                    <p class="font-bold text-sm">Teknis Pengujian</p>
                                    <p class="opacity-75">Nilai parameter, baku mutu</p>
                                </div>
                            </label>

                            <label
                                @click="form.terkait = 'administrasi'"
                                class="cursor-pointer p-3.5 rounded-xl border flex items-center gap-3 transition-all"
                                :class="[
                                    form.terkait === 'administrasi'
                                        ? 'border-emerald-600 bg-emerald-50/70 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-100 ring-2 ring-emerald-500 font-semibold'
                                        : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 text-slate-700 dark:text-slate-300 hover:border-emerald-400'
                                ]"
                            >
                                <v-icon icon="mdi-file-document-outline" color="primary" />
                                <div class="text-xs">
                                    <p class="font-bold text-sm">Pelayanan / Administrasi</p>
                                    <p class="opacity-75">Keterlambatan, dokumen, penagihan</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Subjek Aduan / Masalah <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400">{{ form.masalah.length }}/100</span>
                        </div>
                        <input
                            type="text"
                            v-model="form.masalah"
                            maxlength="100"
                            required
                            placeholder="Ringkasan singkat kendala hasil uji..."
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                        />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Usulan Perbaikan <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400">{{ form.perbaikan.length }}/1000</span>
                        </div>
                        <textarea
                            v-model="form.perbaikan"
                            rows="4"
                            maxlength="1000"
                            required
                            placeholder="Saran atau perbaikan yang diinginkan..."
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                        ></textarea>
                    </div>

                    <div class="flex justify-end pt-3 border-t border-slate-100 dark:border-slate-800">
                        <v-btn
                            type="submit"
                            color="primary"
                            rounded="lg"
                            prepend-icon="mdi-send"
                            :loading="form.processing"
                            class="text-none font-semibold text-xs px-6"
                        >
                            Kirim Aduan
                        </v-btn>
                    </div>
                </form>
            </v-card>
        </div>
    </CustomerLayout>
</template>
