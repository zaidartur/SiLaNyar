<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Parameter {
    id: number;
    nama_parameter: string;
    harga?: number;
}

interface SubKategori {
    id: number;
    nama: string;
    parameter: Parameter[];
}

interface Kategori {
    id: number;
    nama: string;
    parameter: Parameter[];
    subkategori: SubKategori[];
}

interface Instansi {
    id: number;
    nama: string;
    harga?: number;
}

interface JenisCairan {
    id: number;
    nama: string;
}

interface Pengajuan {
    id: number;
    id_instansi: number;
    id_jenis_cairan: number;
    volume_sampel: number;
    metode_pengambilan: string;
    lokasi: string;
    waktu_pengambilan: string;
    kategori: Kategori | null;
    parameter: Parameter[];
    keterangan: string;
    status_pengajuan: string;
}

const { props } = usePage<{
    pengajuan: Pengajuan;
    kategori: Kategori[];
    parameter: Parameter[];
    jenis_cairan: JenisCairan[];
    instansi: Instansi[];
}>();

const pengajuan = props.pengajuan;
const kategoriList = props.kategori;
const parameterList = props.parameter;
const jenisCairanList = props.jenis_cairan;
const instansiList = props.instansi;

const form = useForm({
    id_instansi: pengajuan.id_instansi,
    id_jenis_cairan: pengajuan.id_jenis_cairan,
    volume_sampel: pengajuan.volume_sampel,
    id_form_pengajuan: pengajuan.id,
    metode_pengambilan: pengajuan.metode_pengambilan,
    lokasi: pengajuan.lokasi,
    waktu_pengambilan: pengajuan.waktu_pengambilan,
    id_kategori: pengajuan.kategori?.id ?? null,
    parameter: pengajuan.parameter.map((p) => p.id),
    keterangan: pengajuan.keterangan,
});

// Add validation state
const validationErrors = ref<Record<string, string>>({});

// Validation function for waktu_pengambilan
function validateWaktuPengambilan() {
    if (form.metode_pengambilan === 'diantar' && form.waktu_pengambilan) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const selectedDate = new Date(form.waktu_pengambilan);

        if (selectedDate < today) {
            validationErrors.value.waktu_pengambilan = 'Tanggal pengambilan tidak boleh sebelum hari ini';
            return false;
        } else {
            delete validationErrors.value.waktu_pengambilan;
            return true;
        }
    }
    return true;
}

// Watch for changes to validate waktu_pengambilan
watch(
    () => form.waktu_pengambilan,
    () => {
        validateWaktuPengambilan();
    },
);

watch(
    () => form.id_kategori,
    (kategoriId) => {
        const kat = kategoriList.find((k) => k.id === kategoriId);
        if (!kat) return;

        const allowed = kat.subkategori.length ? kat.subkategori.flatMap((s) => s.parameter.map((p) => p.id)) : kat.parameter.map((p) => p.id);

        form.parameter = [...new Set(allowed)];
    },
);

// Remove the watch for metode_pengambilan since it's no longer editable

const parameterIsInKategori = (id: number): boolean => {
    const kat = kategoriList.find((k) => k.id === form.id_kategori);
    if (!kat) return true;
    const allowedIds = kat.subkategori.length ? kat.subkategori.flatMap((s) => s.parameter.map((p) => p.id)) : kat.parameter.map((p) => p.id);
    return allowedIds.includes(id);
};

function submit() {
    if (!validateWaktuPengambilan()) {
        return;
    }
    form.put(route('customer.pengajuan.update', pengajuan.id));
}

// function verifikasi(status: 'diterima' | 'ditolak') {
//     router.put(route('customer.pengajuan.verifikasi', pengajuan.id), {
//         status_pengajuan: status
//     }, {
//         onSuccess: () => {
//             verifikasiSelesai.value = true
//         }
//     })
// }
</script>

<template>
    <CustomerLayout>
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-8 space-y-6">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">Edit Pengajuan</h1>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Instansi -->
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Instansi</label>
                    <select v-model="form.id_instansi" class="w-full text-base px-3 py-2 rounded border-gray-300 bg-gray-100" disabled>
                        <option v-for="ins in instansiList" :key="ins.id" :value="ins.id">
                            {{ ins.nama }}
                        </option>
                    </select>
                    <div class="mt-1 text-xs text-gray-500">Instansi tidak dapat diubah</div>
                </div>

                <!-- Jenis Cairan (Editable) -->
                <div>
                    <label class="mb-1 block font-medium text-gray-700">Jenis Cairan</label>
                    <select v-model="form.id_jenis_cairan" class="w-full rounded border-gray-300 px-3 py-2 text-base" required>
                        <option value="">Pilih Jenis Cairan</option>
                        <option v-for="jenis in jenisCairanList" :key="jenis.id" :value="jenis.id">{{ jenis.nama }}</option>
                    </select>
                </div>

                <!-- Volume (Editable) -->
                <div>
                    <label class="mb-1 block font-medium text-gray-700">Volume Sampel</label>
                    <input
                        type="number"
                        step="0.1"
                        min="0"
                        v-model="form.volume_sampel"
                        class="w-full rounded border-gray-300 px-3 py-2 text-base"
                        required
                    />
                </div>

                <!-- Metode Pengambilan (Disabled) -->
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Metode Pengambilan</label>
                    <select v-model="form.metode_pengambilan" class="w-full text-base px-3 py-2 rounded border-gray-300 bg-gray-100" disabled>
                        <option value="diantar">Diantar</option>
                        <option value="diambil">Diambil</option>
                    </select>
                    <div class="mt-1 text-xs text-gray-500">Metode pengambilan tidak dapat diubah</div>
                </div>

                <!-- Lokasi (Disabled for both methods) -->
                <div>
                    <label class="mb-1 block font-medium text-gray-700">Lokasi</label>
                    <input type="text" v-model="form.lokasi" class="w-full rounded border-gray-300 bg-gray-100 px-3 py-2 text-base" disabled />
                    <div class="mt-1 text-xs text-gray-500">Lokasi tidak dapat diubah</div>
                </div>

                <!-- Waktu (Editable for diantar method) -->
                <div v-if="form.metode_pengambilan === 'diantar'">
                    <label class="mb-1 block font-medium text-gray-700">Jadwal Pengantaran</label>
                    <input
                        type="date"
                        v-model="form.waktu_pengambilan"
                        :class="[
                            'w-full rounded border px-3 py-2 text-base',
                            validationErrors.waktu_pengambilan ? 'border-red-500' : 'border-gray-300',
                        ]"
                        :min="new Date().toISOString().split('T')[0]"
                        required
                    />
                    <div v-if="validationErrors.waktu_pengambilan" class="mt-1 text-sm text-red-600">
                        {{ validationErrors.waktu_pengambilan }}
                    </div>
                    <div v-else class="mt-1 text-xs text-gray-500">
                        Pilih tanggal mulai dari hari ini ({{
                            new Date().toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                            })
                        }})
                    </div>
                </div>

                <!-- Kategori (Editable) -->
                <div>
                    <label for="kategori" class="mb-1 block font-medium text-gray-700">Kategori</label>
                    <select v-model="form.id_kategori" class="w-full rounded border-gray-300 px-3 py-2 text-base" id="kategori" required>
                        <option :value="null" disabled>Pilih kategori</option>
                        <option v-for="kat in kategoriList" :key="kat.id" :value="kat.id">
                            {{ kat.nama }}
                        </option>
                    </select>
                </div>

                <!-- Parameter (Auto-updated based on kategori) -->
                <div>
                    <label class="mb-1 block font-medium text-gray-700">Parameter</label>
                    <div class="grid grid-cols-1 gap-2 text-sm sm:grid-cols-2">
                        <div v-for="param in parameterList" :key="param.id" class="flex items-center">
                            <input
                                type="checkbox"
                                :value="param.id"
                                v-model="form.parameter"
                                :disabled="form.id_kategori ? !parameterIsInKategori(param.id) : false"
                                class="rounded border-gray-300 px-3 py-2 text-base"
                            />
                            <span
                                class="ml-2"
                                :class="{
                                    'text-gray-400': form.id_kategori && !parameterIsInKategori(param.id),
                                }"
                            >
                                {{ param.nama_parameter }}
                            </span>
                        </div>
                    </div>
                    <!-- Total Biaya -->
                    <div class="mt-2 text-right font-semibold text-gray-700">
                        Total Biaya: Rp
                        {{
                            parameterList
                                .filter((p) => form.parameter.includes(p.id))
                                .reduce((sum, p) => sum + (p.harga || 0), 0)
                                .toLocaleString('id-ID')
                        }}
                    </div>
                </div>

                <!-- Keterangan (Disabled) -->
                <div v-if="form.metode_pengambilan === 'diantar'">
                    <label class="mb-1 block font-medium text-gray-700">Keterangan</label>
                    <textarea v-model="form.keterangan" class="w-full rounded border-gray-300 bg-gray-100 p-3" rows="3"></textarea>
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="rounded bg-blue-600 px-4 py-2 font-semibold text-white transition hover:bg-blue-700"
                        :disabled="form.processing || Object.keys(validationErrors).length > 0"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </CustomerLayout>
</template>
