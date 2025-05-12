<template>
    <AuthBase title="Log in to your account" description="Enter your email and password below to log in" >
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>
        <v-container class="d-flex justify-center">
            <v-card class="pa-6" width="400">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <!-- <Label for="email">Email address</Label> -->
                             <v-text-field
                                id="email"
                                label="Email Address"
                                type="email"
                                required
                                autofocus
                                :tabindex="1"
                                autocomplete="email"
                                v-model="form.email"
                                placeholder="email@example.com"
                            />
                        
                        </div>
                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <!-- <Label for="password">Password</Label> -->
                                 <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm" :tabindex="5">
                                    Forgot password?
                                 </TextLink>
                                </div>
                                <v-text-field
                                    id="password"
                                    label="Password"
                                    type="password"
                                    required
                                    :tabindex="2"
                                    autocomplete="current-password"
                                    v-model="form.password"
                                    placeholder="Password"
                                />
                            </div>
                            <div class="flex items-center justify-between" :tabindex="3">
                                <Label for="remember" class="flex items-center space-x-3">
                                    <Checkbox id="remember"  :tabindex="4" /> 
                                    <!-- v-model:checked="form.remember" -->
                                     <span>Remember me</span>
                                    </Label>
                                </div>
                                <Button
                                    type="submit"
                                    class="mt-4 w-full rounded-lg" 
                                    :tabindex="4"
                                    :disabled="form.processing"
                                >
                                    
                                    Log in
                                </Button>
                            </div>
                            <div class="text-center text-sm text-muted-foreground">
                                Don't have an account?
                                <TextLink :href="route('register')" :tabindex="5">Sign up</TextLink>
                            </div>
                        </form>
                    </v-card>
                </v-container>
                </AuthBase>
</template>
<script setup >

import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';

import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
// import { LoaderCircle } from 'lucide-vue-next';

// defineProps<{
//     status?: string,
//     canResetPassword: boolean,
// }>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>
