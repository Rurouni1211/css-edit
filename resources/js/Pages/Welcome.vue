<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    userTypes: {
        type: Array,
    },
});

</script>

<template>
    <Head title="ログイントップ" />

    <div
        class="sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div>
            <div v-for="userType in userTypes" :key="userType.value">
                <Link
                    :href="route(userType.value +'.login')"
                    class="font-semibold mr-5 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >{{ userType.label }}ログイン</Link>
            </div>
            <hr class="my-3">
            <div>
                <Link
                    :href="route('customer.register')"
                    class="font-semibold mr-5 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >お客様登録</Link>
            </div>
            <!-- <div>
                <Link
                    :href="route('contact.create')"
                    class="font-semibold mr-5 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >問い合わせ</Link>
            </div> -->
            <!-- <hr class="my-3">
            <div>
                <Link
                    :href="route('others.terms_and_conditions')"
                    class="font-semibold mr-5 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >利用規約</Link>
            </div>
            <div>
                <Link
                    :href="route('others.privacy_policy')"
                    class="font-semibold mr-5 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >プライバシーポリシー</Link>
            </div>
            <div>
                <Link
                    :href="route('others.store_details')"
                    class="font-semibold mr-5 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >特定商取引上の表記</Link>
            </div>
            <div>
                <Link
                    :href="route('others.about_me')"
                    class="font-semibold mr-5 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >コンセプト</Link>
            </div>
            <div>
                <Link
                    :href="route('others.faq')"
                    class="font-semibold mr-5 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >よくある質問</Link>
            </div> -->
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
