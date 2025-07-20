<script setup>
import {onMounted, ref} from 'vue';
import GuestLayout from "@/Layouts/GuestLayout.vue";
import {getErrorMessages, scrollToFirstError} from "@/Utils/Ajax.js";
import {Head, Link} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

// パンくずリスト設定
const breadcrumbs = [
    { name: 'ホーム', url: '/' },
    { name: 'お問い合わせ', url: '/contact/create' }
];

// Common
const props = defineProps({
    customerEmail: {
        type: String,
        default: '',
    },
    customerName: {
        type: String,
        default: '',
    },
    contactSubjectTypes: {
        type: Array,
        default: () => [],
    },
});

onMounted(() => {
    autosize(document.querySelectorAll('textarea'));
});

// Form
// 姓名を分割
const nameParts = props.customerName ? props.customerName.split(' ') : ['', ''];
const params = ref({
    lastName: nameParts[0] || '',
    firstName: nameParts[1] || '',
    phone: '',
    email: props.customerEmail,
    emailConfirmation: props.customerEmail,
    body: '',
});

const errors = ref({});
const files = ref([]);

const onSubmit = () => {
    Swal.fire({
        title: '送信',
        text: "お問い合わせを送信します。よろしいでしょうか。",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'はい',
        cancelButtonText: 'キャンセル'
    })
    .then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();

            // 姓と名を別々に送信
            formData.append('last_name', params.value.lastName);
            formData.append('first_name', params.value.firstName);
            formData.append('phone', params.value.phone);
            formData.append('email', params.value.email);
            formData.append('email_confirmation', params.value.emailConfirmation);
            formData.append('body', params.value.body);

            for (let i = 0; i < files.value.length; i++) {
                formData.append('files[]', files.value[i]);
            }

            const url = route('contact.store');
            axios.post(url, formData)
                .then((response) => {
                    if(response.data.result === true) {
                        Swal.fire(
                            '送信完了',
                            'お問い合わせを受け付けました。できるだけ早くご返信いたします。それまでお待ちくださいませ。',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch((error) => {
                    errors.value = getErrorMessages(error);
                    Swal.fire(
                        '入力エラー',
                        error.response.data.message,
                        'error'
                    ).then(() => {
                        scrollToFirstError();
                    });
                });
        }
    });
};
</script>

<template>
    <Head title="お問い合わせ" />
    <GuestLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-normal">お問い合わせ</h1>
            </div>

            <form @submit.prevent>
                <!-- 姓名 (2カラム) -->
                <div class="flex mb-4 space-x-4">
                    <div class="flex-1">
                        <div class="mb-1 text-sm">姓</div>
                        <input type="text" v-model="params.lastName" class="w-full border py-2 px-3">
                        <InputError :message="errors.last_name" />
                    </div>
                    <div class="flex-1">
                        <div class="mb-1 text-sm">名</div>
                        <input type="text" v-model="params.firstName" class="w-full border py-2 px-3">
                        <InputError :message="errors.first_name" />
                    </div>
                </div>

                <!-- 電話番号 -->
                <div class="mb-4">
                    <div class="mb-1 text-sm">電話番号</div>
                    <input type="tel" v-model="params.phone" placeholder="080-1234-5678" class="w-full border py-2 px-3">
                    <InputError :message="errors.phone" />
                </div>

                <!-- メールアドレス -->
                <div class="mb-4">
                    <div class="mb-1 text-sm">メールアドレス</div>
                    <input type="email" v-model="params.email" placeholder="leather-store@wamore.com" class="w-full border py-2 px-3">
                    <small class="text-red-600">※必ずwamore.cityからの受信を許可しておいてください</small>
                    <InputError :message="errors.email" />
                </div>

                <!-- メールアドレス確認用 -->
                <div class="mb-4">
                    <div class="mb-1 text-sm">メールアドレス確認用</div>
                    <input type="email" v-model="params.emailConfirmation" class="w-full border py-2 px-3">
                    <InputError :message="errors.email_confirmation" />
                </div>

                <!-- お問い合わせ内容 -->
                <div class="mb-6">
                    <div class="mb-1 text-sm">お問い合わせ内容</div>
                    <textarea v-model="params.body" rows="6" class="w-full border py-2 px-3 resize-none"></textarea>
                    <InputError :message="errors.body" />
                </div>

                <!-- 送信ボタン -->
                <div class="mb-4">
                    <button type="button" @click="onSubmit" class="block w-full bg-gray-300 text-gray-700 py-2 px-4">
                        送信 <span class="ml-1">›</span>
                    </button>
                </div>

                <!-- 戻るリンク -->
                <div class="text-center mb-8">
                    <Link href="/" class="text-gray-500 inline-flex items-center">
                        <span class="mr-1">‹</span> 戻る
                    </Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
input, textarea {
    background-color: #f9f9f9;
}
</style>
