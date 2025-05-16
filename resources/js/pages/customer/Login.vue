<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: '',
})

const submit = () => {
    form.post(route('customer.login'))
}
</script>

<template>
    <div class="w-full lg:grid lg:min-h-[600px] lg:grid-cols-2 xl:min-h-[800px]">
        <div class="hidden bg-green-800 lg:flex lg:items-center lg:justify-center flex-col">
            <img src="/storage/assetsadmin/logodlh.png" alt="Logo DLH" class="w-auto h-48 object-contain mx-auto" />
            <div class="text-center text-white mt-6">
                <h2 class="text-2xl font-bold mb-2 border-b border-white pb-2">SiLanYar</h2>
                <p class="text-sm">Sistem Laboratoruim Karanganyar</p>
            </div>
        </div>
        <div class="flex items-center justify-center py-12">
            <div class="mx-auto grid w-[350px] gap-6">
                <div class="grid gap-2 text-center">
                    <h1 class="text-3xl font-bold">
                        Login ke Akun Anda
                    </h1>
                </div>
                <form @submit.prevent="submit" class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" v-model="form.email" placeholder="Enter your email" required />
                    </div>
                    <div class="grid gap-2">
                        <div class="flex items-center">
                            <Label for="password">Password</Label>
                            <a href="/forgot-password" class="ml-auto inline-block text-sm underline">
                                Forgot your password?
                            </a>
                        </div>
                        <Input id="password" type="password" v-model="form.password" placeholder="********" required />
                    </div>
                    <Button type="submit" class="w-full" :disabled="form.processing">
                        {{ form.processing ? 'Logging in...' : 'Login' }}
                    </Button>
                    <Button variant="outline" class="w-full" type="button">
                        Login with Google
                    </Button>
                </form>
                <div class="mt-4 text-center text-sm">
                    Don't have an account?
                    <a href="#" class="underline">
                        Sign up
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>