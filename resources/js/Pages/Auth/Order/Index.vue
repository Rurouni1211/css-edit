<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import OrderList from "@/Components/OrderList.vue";

const props = defineProps({
    guard: {
        type: String,
        required: true,
    },
    orderTypes: {
        type: Array,
        required: true,
    },
});

</script>

<template>
    <Head title="注文管理" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-indigo-50 leading-tight">注文管理</h2>
        </template>
        <div class="sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="p-5 sm:p-10">
                        <OrderList :guard="guard" :order-types="orderTypes" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
