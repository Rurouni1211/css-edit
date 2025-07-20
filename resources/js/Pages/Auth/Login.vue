<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import NormalButton from "@/Components/Buttons/NormalButton.vue";
import CancelButton from "@/Components/Buttons/CancelButton.vue";

defineProps({
    status: {
        type: String,
    },
    quickLoginUsers: {
        type: Array,
        default: [],
    },
    loginTitle: {
        type: String,
        default: 'ログイン',
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const page = usePage();
const multiAuthGuard = page.props.multiAuthGuard;
const submit = () => {
    const url = route(`${multiAuthGuard}.login`);
    form.post(url, {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="loginTitle" />
        <div class="pt-8">
            <h1 class="text-2xl font-bold text-center mb-6">{{ loginTitle }}</h1>
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="p-2 lg:w-1/2 mx-auto">
                <div>
                    <InputLabel for="email" value="メールアドレス" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="パスワード" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-600">次回から省略</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <Link
                        :href="route(`${multiAuthGuard}.password.request`)"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        パスワードを忘れた場合
                    </Link>

                    <NormalButton
                        class="ml-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="submit">ログイン</NormalButton>
                </div>

                <div class="mt-7 bg-gray-100 p-3" style="line-height:2.8rem;" v-if="quickLoginUsers.length > 0">
                    <template v-for="quickLoginUser in quickLoginUsers" :key="quickLoginUser.id">
                        <CancelButton
                            size="sm"
                            class="mr-3"
                            @click="form.email = quickLoginUser.email, form.password = 'xxxxxxxx'"
                        >
                            {{ quickLoginUser.name }}
                        </CancelButton>
                    </template>
                </div>

            </form>
        </div>
    </GuestLayout>
</template>
