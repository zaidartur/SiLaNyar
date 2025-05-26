<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { router, Head } from '@inertiajs/vue3'
import { ref } from 'vue'
type Role = {
    id: number
    name: string
    guard_name?: string
}

const props = defineProps<{
    users: Array<{
        id: number,
        nama: string,
        email: string,
        roles: Role[],
    }>,
    roles: Role[],
    filters: {
        search?: string
    }
}>()
const search = ref(props.filters.search || '')

function submitSearch() {
    router.get('/superadmin/users', { search: search.value }, {
        preserveScroll: true,
        preserveState: true,
    })
}

function toggleRole(user: number, roleName: string) {
    router.post(`/superadmin/users/${user}/sync-roles`, { roles: [roleName] }, {
        preserveScroll: true,
        onSuccess: () => {
            const users = props.users.find(u => u.id === user)
            if (users) {
                users.roles = props.roles.filter(role => role.name === roleName)
            }
        }
    })
}

</script>

<template>
    <Head title="Manajemen Pengguna" />
    <AdminLayout>
        <div>
            <h1 class="text-2xl font-bold mb-6">Manajemen Pengguna</h1>

            <div class="mb-4 flex items-center gap-2">
                <input v-model="search" @keyup.enter="submitSearch" type="text" placeholder="Cari nama atau email..."
                    class="border rounded px-3 py-1" />
                <button @click="submitSearch" class="bg-blue-600 text-white px-3 py-1 rounded">Cari</button>
            </div>
            <table class="table-auto w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-2 border">Nama</th>
                        <th class="p-2 border">Email</th>
                        <th class="p-2 border">Roles</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td class="p-2 border">{{ user.nama }}</td>
                        <td class="p-2 border">{{ user.email }}</td>
                        <td class="p-2 border">
                            <div class="flex gap-4">
                                <label v-for="role in roles" :key="role.id" class="flex items-center gap-1">
                                    <input type="radio" :name="'role-' + user.id" :value="role.name"
                                        :checked="user.roles.some(r => r.name === role.name)"
                                        @change="() => toggleRole(user.id, role.name)" />

                                    {{ role.name }}
                                </label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
