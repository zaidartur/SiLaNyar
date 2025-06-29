<script setup lang="ts">
/* eslint-disable */
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface ParameterPengujian {
    id_parameter: number;
    nama_parameter: string;
    satuan: string | null;
    nilai: string | null;
    baku_mutu: string | null;
    keterangan: string | null;
}

interface HasilUji {
    id: number;
    status: string;
    diupdate_oleh: string;
    created_at: string;
    pengujian: {
        kode_pengujian: string;
        form_pengajuan: {
            kode_pengajuan: string;
            kategori: {
                nama: string;
            };
            instansi: {
                nama: string;
                user: {
                    nama: string;
                };
            };
        };
        user: {
            nama: string;
        };
    };
}

const props = defineProps<{
    hasil_uji: HasilUji;
    parameter_pengujian: ParameterPengujian[];
}>();

const page = usePage();
const permissions = (page.props.auth as { permissions: string[] }).permissions;

const can = (permission: string): boolean => {
    return permissions.includes(permission);
};

const STATUS_FLOW = {
    draf: ['proses_review', 'revisi'],
    revisi: ['draf', 'proses_review'],
    proses_review: ['proses_peresmian', 'revisi'],
    proses_peresmian: ['selesai', 'revisi'],
    selesai: [],
};

const statusLabels = {
    draf: 'Draf',
    revisi: 'Revisi',
    proses_review: 'Proses Review',
    proses_peresmian: 'Proses Peresmian',
    selesai: 'Selesai',
};

// const status = ref(props.hasil_uji.status)

const availableStatus = computed(() => STATUS_FLOW[props.hasil_uji.status as keyof typeof STATUS_FLOW] || []);

function kembali() {
    router.visit(route('pegawai.hasil_uji.index'));
}

function perbaruiStatus(newStatus: string) {
    router.put(`/pegawai/hasiluji/verifikasi/${props.hasil_uji.id}`, {
        status: newStatus,
    });
}

function bukaPDF() {
    window.open(route('hasil_uji.convert', props.hasil_uji.id), '_blank')
}
</script>

<template>
    <AdminLayout>
        <div class="min-h-screen bg-gray-200 py-8">

            <Head title="Detail Hasil Uji" />

            <div class="mx-auto max-w-4xl space-y-8 py-8">
                <h1
                    class="mb-1 inline-block w-fit border-b-2 border-customDarkGreen pb-2 text-3xl font-bold text-customDarkGreen">
                    Detail Hasil Uji
                </h1>

                <!-- Informasi Umum -->
                <div class="mb-4 rounded-xl border bg-gray-50 p-6 shadow-lg">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-2 md:grid-cols-2">
                        <div>
                            <span class="font-bold text-customDarkGreen">Kode Hasil Uji:</span>
                            <span class="ml-2">HU-{{ hasil_uji.id.toString().padStart(4, '0') }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Kode Pengujian:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.kode_pengujian }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Status:</span>
                            <span class="ml-2">{{ hasil_uji.status }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Diupdate Oleh:</span>
                            <span class="ml-2">{{ hasil_uji.diupdate_oleh }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Tanggal Dibuat:</span>
                            <span class="ml-2">{{ new Date(hasil_uji.created_at).toLocaleString() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pengajuan -->
                <div class="mb-4 rounded-xl border bg-gray-50 p-6 shadow-lg">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-2 md:grid-cols-2">
                        <div>
                            <span class="font-bold text-customDarkGreen">Kode Pengajuan:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.kode_pengajuan }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Instansi:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.instansi.nama }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Penanggung Jawab Instansi:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.instansi.user.nama }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Kategori:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.kategori.nama }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Teknisi:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.user.nama }}</span>
                        </div>
                    </div>
                </div>

                <!-- Parameter Pengujian -->
                <div class="rounded-xl border bg-white p-6 shadow">
                    <h2 class="mb-4 text-xl font-semibold text-customDarkGreen">Parameter Pengujian</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full rounded border border-gray-200 text-sm">
                            <thead class="bg-customDarkGreen text-white">
                                <tr>
                                    <th class="border px-4 py-2">No</th>
                                    <th class="border px-4 py-2">Parameter</th>
                                    <th class="border px-4 py-2">Nilai</th>
                                    <th class="border px-4 py-2">Satuan</th>
                                    <th class="border px-4 py-2">Baku Mutu</th>
                                    <th class="border px-4 py-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in parameter_pengujian" :key="item.id_parameter"
                                    class="hover:bg-gray-50">
                                    <td class="border px-4 py-2 text-center">{{ index + 1 }}</td>
                                    <td class="border px-4 py-2">{{ item.nama_parameter }}</td>
                                    <td class="border px-4 py-2">{{ item.nilai ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ item.satuan ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ item.baku_mutu ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ item.keterangan ?? '-' }}</td>
                                </tr>
                                <tr v-if="parameter_pengujian.length === 0">
                                    <td class="border px-4 py-2 text-center" colspan="6">Tidak ada parameter.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button @click="bukaPDF"
                            class="inline-flex items-center gap-2 rounded bg-indigo-600 px-5 py-2 text-white font-semibold shadow hover:bg-indigo-700 transition">
                            Lihat PDF
                        </button>
                    </div>
                </div>

                <!-- Edit Status -->
                <div class="mb-4 rounded-xl border bg-white p-6 shadow-lg" v-if="can('edit status hasil uji')">
                    <h2 class="mb-4 text-xl font-semibold">Edit Status</h2>
                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-semibold">ID Hasil Uji:</label>
                        <div class="mb-2">DJ-{{ props.hasil_uji.id.toString().padStart(3, '0') }}</div>
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-semibold">Status Saat Ini:</label>
                        <span class="inline-block rounded bg-gray-200 px-3 py-1 font-semibold text-customDarkGreen">
                            {{ statusLabels[props.hasil_uji.status as keyof typeof statusLabels] }}
                        </span>
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-semibold">Ubah Status:</label>
                        <div class="flex flex-wrap gap-2">
                            <button v-for="opt in availableStatus" :key="opt" type="button" @click="perbaruiStatus(opt)"
                                class="rounded bg-green-700 px-4 py-2 font-semibold text-white transition hover:bg-green-800 focus:ring-2 focus:ring-green-400">
                                {{ statusLabels[opt as keyof typeof statusLabels] }}
                            </button>
                            <span v-if="availableStatus.length === 0" class="text-gray-400">Tidak ada status
                                lanjutan.</span>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button @click="kembali"
                            class="rounded bg-gray-200 px-4 py-2 font-semibold text-black">Kembali</button>
                    </div>
                </div>

                <!-- Kembali button for users without edit permission -->
                <div class="mb-4 rounded-xl border bg-white p-6 shadow-lg" v-if="!can('edit status hasil uji')">
                    <div class="flex justify-end gap-2">
                        <button @click="kembali"
                            class="rounded bg-gray-200 px-4 py-2 font-semibold text-black">Kembali</button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
