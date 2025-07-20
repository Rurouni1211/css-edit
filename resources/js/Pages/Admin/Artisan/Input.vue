<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {computed, nextTick, onMounted, ref} from "vue";
import LoadingModal from "@/Components/LoadingModal.vue";
import {getErrorMessages} from "@/Utils/Ajax.js";
import {getRandomPassword} from "@/Utils/Password.js";
import FooterButtons from "@/Components/FooterButtons.vue";
import SubButton from "@/Components/Buttons/SubButton.vue";

// Common
const props = defineProps({
    artisan: {
        type: Object,
        default() {
            return {};
        },
    },
    passwordLength: {
        type: Number,
        default: 16,
    },
});
const isModeCreate = computed(() => {

    return props.artisan.id === undefined;

});
const isModeEdit = computed(() => {

    return props.artisan.id !== undefined;

});
const isLoading = ref(false);
onMounted(() => {

    if (props.artisan.id) {

        params.value.name = props.artisan.name;
        params.value.email = props.artisan.email;
        params.value.has_password = false;

    }

});

// Form
const params = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    has_notification: true,
    has_password: true,
});
const errors = ref({});
const onSubmit = () => {

    let url = '';
    let data = {};

    if(isModeEdit.value) {

        url = route('admin.artisan.update', {artisan: props.artisan.id});
        data = {
            _method: 'PUT',
            ...params.value,
        };

    } else {

        url = route('admin.artisan.store');
        data = params.value;

    }

    Swal.fire({
        title: '保存',
        text: '保存します。よろしいですか？',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '保存',
        cancelButtonText: 'キャンセル',
    }).then((result) => {

        if (result.isConfirmed) {

            isLoading.value = true;

            axios.post(url, data)
                .then((response) => {

                    if (response.data.result === true) {

                        Swal.fire({
                            title: '保存しました',
                            icon: 'success',
                        }).then(() => {

                            if(isModeCreate.value === true) {

                                location.reload();

                            }

                        });

                    }

                })
                .catch((error) => {

                    errors.value = getErrorMessages(error);

                })
                .finally(() => isLoading.value = false);

        }

    });

};

// Password
const onMakePasswordClick = () => {

    const newPassword = getRandomPassword(props.passwordLength);
    params.value.password = newPassword;
    params.value.password_confirmation = newPassword;

    Swal.fire({
        title: 'パスワードを自動生成しました',
        text: '「'+ newPassword +'」',
        icon: 'info',
    });

};
const defaultPassword = computed(() => {

    return getRandomPassword(props.passwordLength);

});

// Others
const onCancel = () => {

    router.visit(route('admin.artisan.index'));

};

</script>

<template>
    <Head title="職人追加・編集" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-indigo-50 leading-tight">職人追加・編集</h2>
        </template>

        <div class="sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="p-5 sm:p-10 text-gray-900">
                        <div class="mb-5">
                            <InputLabel value="店舗名" />
                            <TextInput
                                type="text"
                                class="mt-1 block w-full sm:w-96"
                                v-model="params.name"
                                autocomplete="name"
                            />
                            <InputError :message="errors['name']" />
                        </div>
                        <div class="mb-5">
                            <InputLabel value="メールアドレス" />
                            <TextInput
                                type="text"
                                class="mt-1 block w-full sm:w-96"
                                v-model="params.email"
                                autocomplete="email"
                            />
                            <InputError :message="errors['email']" />
                        </div>
                        <div class="mb-5" v-if="isModeEdit">
                            <label>
                                <input type="checkbox" v-model="params.has_password" class="mr-1" />
                                <span class="text-sm">
                                    パスワードを変更する
                                </span>
                            </label>
                        </div>
                        <div class="mt-6" v-if="params.has_password">
                            <div class="mb-5">
                                <div class="flex">
                                    <InputLabel value="パスワード" class="mr-3" />
                                    <div class="flex-grow pl-5">
                                        <a class="text-indigo-500 hover:underline text-sm" href="#" @click.prevent="onMakePasswordClick">自動生成する</a>
                                    </div>
                                </div>
                                <TextInput
                                    type="password"
                                    class="mt-1 block w-96"
                                    v-model="params.password"
                                    autocomplete="email"
                                />
                                <InputError :message="errors['password']" />
                                <small>
                                    例： {{ defaultPassword}}
                                </small>
                            </div>
                            <div class="mb-5">
                                <InputLabel value="パスワード（確認）" class="mr-1" />
                                <TextInput
                                    type="password"
                                    class="mt-1 block w-96"
                                    v-model="params.password_confirmation"
                                    autocomplete="email"
                                />
                                <InputError :message="errors['password']" />
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" v-model="params.has_notification" class="mr-1" />
                                    <span class="text-sm">
                                        新しい認証情報を店舗にメール送信する
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <FooterButtons
            @yes-click="onSubmit"
            @no-click="onCancel">
            <template v-slot:left>
                <SubButton size="md" :href="route('admin.artisan.input')" @click="onCancel">新規追加</SubButton>
            </template>
        </FooterButtons>

        <div v-if="isLoading">
            <LoadingModal :show="isLoading" />
        </div>

    </AuthenticatedLayout>

</template>
