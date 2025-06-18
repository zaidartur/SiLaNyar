<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    nama: string;
    user: User;
}

interface JenisCairan {
    id: number;
    nama: string;
}

interface Parameter {
    id: number;
    nama_parameter: string;
}

interface SubKategori {
    id: number;
    parameter: Parameter[];
}

interface Kategori {
    id: number;
    nama: string;
    parameter: Parameter[];
    subkategori: SubKategori[];
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    volume_sampel: number;
    status_pengajuan: string;
    metode_pengambilan: string;
    lokasi: string;
    instansi: Instansi;
    kategori: Kategori;
    jenis_cairan: JenisCairan;
    parameter: Parameter[];
}

const props = defineProps<{
    pengajuan: Pengajuan;
    kategoriList: Kategori[];
    parameterList: Parameter[];
}>();

const form = ref({
    kategori_id: props.pengajuan.kategori?.id ?? null,
    parameter_ids: props.pengajuan.parameter?.map((p) => p.id) ?? [],
    metode_pembayaran: '',
});

const kategoriTerpilih = computed(() => props.kategoriList.find((k) => k.id === form.value.kategori_id));

const parameterGabungan = computed(() => {
    const langsung = kategoriTerpilih.value?.parameter ?? [];
    const dariSub = kategoriTerpilih.value?.subkategori.flatMap((s) => s.parameter) ?? [];
    const all = [...langsung, ...dariSub];
    const unique = Array.from(new Map(all.map((p) => [p.id, p])).values());
    return unique;
});

watch(
    () => form.value.kategori_id,
    () => {
        const newParams = parameterGabungan.value.map((p) => p.id);
        const current = new Set(form.value.parameter_ids);

        newParams.forEach((id) => current.add(id));

        form.value.parameter_ids = Array.from(current);
    },
);

watch(
    () => props.pengajuan.metode_pengambilan,
    (val) => {
        if (val === 'diambil') {
            form.value.metode_pembayaran = 'transfer';
        }
    },
    { immediate: true },
);

watch(
    () => props.pengajuan.metode_pengambilan,
    (val) => {
        if (val === 'diambil') {
            form.value.metode_pembayaran = 'transfer'
        }
    },
    { immediate: true }
)

const handleVerifikasi = (status: 'diterima' | 'ditolak') => {
    const payload: Record<string, any> = {
        status_pengajuan: status,
    };

    if (status === 'diterima' || props.pengajuan.metode_pengambilan === 'diantar') {
        payload.id_kategori = form.value.kategori_id;
        payload.parameter = form.value.parameter_ids;
        payload.metode_pembayaran = form.value.metode_pembayaran;
    }

    router.put(`/pegawai/pengajuan/${props.pengajuan.id}/edit`, payload, {
        onSuccess: () => {
            router.visit('/pegawai/pengajuan') // arahkan ke halaman index pengajuan
        }
    })
}

const handleUpdateKategoriParameter = () => {
    const payload = {
        id_kategori: form.value.kategori_id,
        parameter: form.value.parameter_ids,
    };

    router.put(`/pegawai/pengajuan/${props.pengajuan.id}/edit-parameter-kategori`, payload, {
        onSuccess: () => {
            router.visit(`/pegawai/pengajuan/`);
        }
    });
};
</script>

<template>

    <Head title="Edit Pengajuan" />
    <AdminLayout>
        <div class="mx-auto max-w-4xl rounded bg-white p-6 shadow">
            <h1 class="mb-6 text-2xl font-bold">Edit Pengajuan</h1>

            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold">Kode Pengajuan</label>
                    <input :value="props.pengajuan.kode_pengajuan" class="w-full" disabled />
                </div>

                <div>
                    <label class="font-semibold">Status Pengajuan</label>
                    <input :value="props.pengajuan.status_pengajuan" class="w-full capitalize" disabled />
                </div>

                <div>
                    <label class="font-semibold">Nama Pemohon</label>
                    <input :value="props.pengajuan.instansi.user.nama" class="w-full" disabled />
                </div>

                <div>
                    <label class="font-semibold">Instansi</label>
                    <input :value="props.pengajuan.instansi.nama" class="w-full" disabled />
                </div>

                <div>
                    <label class="font-semibold">Jenis Cairan</label>
                    <input :value="props.pengajuan.jenis_cairan?.nama" class="w-full" disabled />
                </div>

                <div>
                    <label class="font-semibold">Volume Sampel</label>
                    <input :value="props.pengajuan.volume_sampel" class="w-full" disabled />
                </div>

                <div>
                    <label class="font-semibold">Metode Pengambilan</label>
                    <input :value="props.pengajuan.metode_pengambilan" class="w-full" disabled />
                </div>

                <div>
                    <label class="font-semibold">Lokasi</label>
                    <input :value="props.pengajuan.lokasi" class="w-full" disabled />
                </div>
            </div>

            <div v-if="props.pengajuan.metode_pengambilan === 'diambil'" class="mb-4">
                <label class="font-semibold block mb-1">Metode Pembayaran</label>
                <select v-model="form.metode_pembayaran" class="w-full border rounded px-2 py-1"
                    :disabled="props.pengajuan.metode_pengambilan === 'diambil'">
                    <option value="transfer">Transfer</option>
                    <option value="tunai">Tunai</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="mb-1 block font-semibold">Pilih Kategori</label>
                <select v-model="form.kategori_id" class="w-full rounded border px-2 py-1"
                    :disabled="props.pengajuan.metode_pengambilan !== 'diantar'">
                    <option v-for="k in kategoriList" :key="k.id" :value="k.id">{{ k.nama }}</option>
                </select>
            </div>

            <label v-for="param in parameterList" :key="param.id" class="flex items-center gap-2">
                <input type="checkbox" :value="param.id" v-model="form.parameter_ids"
                    :disabled="props.pengajuan.metode_pengambilan !== 'diantar'" />
                {{ param.nama_parameter }}
            </label>

            <!-- Button Verifikasi -->
            <div v-if="props.pengajuan.status_pengajuan === 'proses_validasi'" class="flex gap-4">
                <button @click="handleVerifikasi('diterima')" class="rounded bg-green-600 px-4 py-2 text-white">✅ Terima
                    Pengajuan</button>
                <button @click="handleVerifikasi('ditolak')" class="rounded bg-red-600 px-4 py-2 text-white">❌ Tolak
                    Pengajuan</button>
            </div>

            <div v-else>
                <div v-if="props.pengajuan.metode_pengambilan === 'diantar'">
                    <button @click="handleUpdateKategoriParameter" class="rounded bg-green-600 px-4 py-2 text-white">✅
                        Kirim</button>
                </div>
                <div>
                    <p class="text-sm italic text-gray-500">Pengajuan sudah diverifikasi</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>