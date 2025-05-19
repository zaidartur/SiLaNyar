<script setup lang="ts">
import { router } from '@inertiajs/vue3'
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

function toggleRole(userId: number, roleName: string, checked: boolean) {
    const user = props.users.find(u => u.id === userId)
    if (!user) return

    let updatedRoles = user.roles.map((r: Role) => r.name)

    if (checked) {
        if (!updatedRoles.includes(roleName)) {
            updatedRoles.push(roleName)
        }
    } else {
        updatedRoles = updatedRoles.filter(r => r !== roleName)
    }

    router.post(`/superadmin/users/${userId}/sync-roles`, { roles: updatedRoles }, {
        preserveScroll: true,
        onSuccess: () => {
            user.roles = props.roles.filter(role => updatedRoles.includes(role.name))
        }
    })
}

</script>

<template>
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
                                <input type="checkbox" :checked="user.roles.some((r: any) => r.name === role.name)"
                                    @change="e => toggleRole(user.id, role.name, (e.target as HTMLInputElement).checked)" />
                                {{ role.name }}
                            </label>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
