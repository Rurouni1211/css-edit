<script setup>
import { Head } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import BaseButton from "@/Components/Buttons/BaseButton.vue";

const props = defineProps({
    order: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <Head title="注文完了" />
    <GuestLayout>
        <div class="w-full min-h-auto flex flex-col mt-[40px]">
            <div class="sm:w-[90%] lg:w-[100%] max-w-[1280px] mx-auto px-4 lg:px-8">
                <div class="bg-white p-8 rounded-lg shadow-md text-center">
                    <h1 class="text-2xl font-bold mb-6 font-shippori">ご注文ありがとうございました</h1>
                    
                    <div class="mb-8 border-2 border-main-800 p-4 rounded bg-main-100">
                        <p class="font-semibold">注文番号</p>
                        <p class="text-xl font-bold my-2">{{ order.order_unique_id }}</p>
                    </div>
                    
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4 font-shippori">注文内容</h2>
                        <div class="flex justify-between border-b pb-2 mb-2">
                            <span>商品名</span>
                            <span>{{ order.product_name }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 mb-2">
                            <span>合計金額</span>
                            <span>￥{{ Number(order.total_amount).toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2 mb-2">
                            <span>消費税</span>
                            <span>￥{{ Number(order.consumption_tax).toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>総合計金額</span>
                            <span>￥{{ Number(order.total_amount_including_tax).toLocaleString() }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <p class="text-sm mb-4">注文内容の確認や変更は、直接お問い合わせください。</p>
                        <BaseButton size="md" @click="$page.props.ziggy.location = '/'">トップページに戻る</BaseButton>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>