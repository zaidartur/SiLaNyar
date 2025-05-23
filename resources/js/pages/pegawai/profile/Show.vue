<script setup lang="ts">

interface User {
    id: number;
    nik: string;
    nama: string;
    tgl_lahir: string;
    provinsi: string;
    kab_kota: string;
    kecamatan: string;
    kelurahan: string;
    rt: string;
    rw: string;
    kode_pos: string;
    alamat: string;
    email: string;
    no_wa: string;
    username: string;
}

const props = defineProps<{
    user?: User;
}>();
</script>

<template>
        <div class="profile">
            <h1>Profil Saya</h1>
            <ul>
                <li><strong>NIK:</strong> {{ props.user?.nik }}</li>
                <li><strong>Nama:</strong> {{ props.user?.nama }}</li>
                <li><strong>Tanggal Lahir:</strong> {{ props.user?.tgl_lahir }}</li>
                <li><strong>Provinsi:</strong> {{ props.user?.provinsi }}</li>
                <li><strong>Kab/Kota:</strong> {{ props.user?.kab_kota }}</li>
                <li><strong>Kecamatan:</strong> {{ props.user?.kecamatan }}</li>
                <li><strong>Kelurahan:</strong> {{ props.user?.kelurahan }}</li>
                <li><strong>RT:</strong> {{ props.user?.rt }}</li>
                <li><strong>RW:</strong> {{ props.user?.rw }}</li>
                <li><strong>Kode Pos:</strong> {{ props.user?.kode_pos }}</li>
                <li><strong>Alamat:</strong> {{ props.user?.alamat }}</li>
                <li><strong>Email:</strong> {{ props.user?.email }}</li>
                <li><strong>No WA:</strong> {{ props.user?.no_wa }}</li>
                <li><strong>Username:</strong> {{ props.user?.username }}</li>
                <li><strong>ID:</strong> {{ props.user?.id }}</li>
            </ul>
        </div>
</template>

<style scoped>
.profile {
    max-width: 600px;
    margin: 0 auto;
}
</style>
