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

// const todayDate = computed(() => {
//     const today = new Date();
//     return today.toISOString().split('T')[0];
// });

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
    // If date is not changing, allow current date
    // If date is changing, must be after today
    return isDateChanging.value ? tomorrowDate.value : originalDate.value;
});

const submit = () => {
    form.put(`/pegawai/pengambilan/${props.jadwal.id}/edit`, {
        onSuccess: () => {
            router.visit('/pegawai/pengambilan');
        },
        onError: (errors) => {
            console.log('Error:', errors);
        },
    });
};
</script>

<template>
    <Head title="Edit Jadwal Pengambilan" />
    <AdminLayout>
        <div class="space-y-6 p-6">
            <h1 class="text-2xl font-bold text-black">Edit Jadwal Pengambilan</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Kode Form Pengajuan</label>
                    <input
                        :value="props.jadwal.form_pengajuan?.kode_pengajuan"
                        class="w-full rounded border border-gray-300 bg-gray-100 px-3 py-2"
                        disabled
                    />
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Nama Pengambil/Penerima</label>
                    <input
                        :value="props.jadwal.user?.nama"
                        class="w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-gray-600"
                        disabled
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium">Waktu Pengambilan</label>
                    <input
                        type="date"
                        v-model="form.waktu_pengambilan"
                        :min="minDate"
                        class="w-full rounded border border-gray-300 p-2"
                        :disabled="metode === 'diantar' || metode === 'diambil'"
                    />
                    <div v-if="form.errors.waktu_pengambilan" class="mt-1 text-sm text-red-500">
                        {{ form.errors.waktu_pengambilan }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                        <span v-if="isDateChanging">Tanggal baru harus setelah hari ini ({{ tomorrowDate }})</span>
                        <span v-else>Tanggal saat ini: {{ originalDate }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select v-model="form.status" class="w-full rounded border border-gray-300 p-2">
                        <option value="diproses">Diproses</option>
                        <option value="diterima">Diterima</option>
                    </select>
                    <div v-if="form.errors.status" class="mt-1 text-sm text-red-500">
                        {{ form.errors.status }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Keterangan</label>
                    <textarea
                        v-model="form.keterangan"
                        class="w-full rounded border border-gray-300 p-2"
                        :disabled="metode === 'diantar'"
                        rows="3"
                    ></textarea>
                    <div v-if="form.errors.keterangan" class="mt-1 text-sm text-red-500">
                        {{ form.errors.keterangan }}
                    </div>
                </div>

                <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">Simpan</button>
            </form>
        </div>
    </AdminLayout>
</template>
