<script setup>

import {computed, onMounted, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import FullPageModal from "@/Components/FullPageModal.vue";
import TextInput from "@/Components/TextInput.vue";
import NormalButton from "@/Components/Buttons/NormalButton.vue";
import CancelButton from "@/Components/Buttons/CancelButton.vue";
import ResponsiveTable from "@/Components/ResponsiveTable.vue";
import CopyToClipboard from "@/Components/CopyToClipboard.vue";

// Common
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
const guard = props.guard;
const page = usePage();
const userType = page.props.multiAuthGuard;
const orderStatuses = page.props.orderStatuses;
onMounted(() => {

    initSearchParams();
    getOrders();

});

// Order
const orders = ref([]);
const orderLinks = ref([]);
let currentPage = 1;
const getOrders = () => {

    const url = route(`${guard}.order.list`);
    const data = {
        params: {
            page: currentPage,
            ...searchParams.value,
        }
    };

    axios.get(url, data)
        .then((response) => {

            orders.value = response.data.data;
            orderLinks.value = response.data.meta.links;

        });

}

// Search
const searchParams = ref({
    customer_name: '',
    order_unique_id: '',
    status: '',
    order_type: '',
    product_name: '',
});
const searchInputOrderId = ref();
const initSearchParams = () => {

    const urlSearchParams = new URLSearchParams(window.location.search);
    searchParams.value.order_unique_id = urlSearchParams.get('order_unique_id');

};
const onSearch = () => {

    currentPage = 1;
    getOrders();

};
const onMovePage = (page) => {

    currentPage = page;
    getOrders();

};
const onClearSearchClick = () => {

    searchParams.value = {
        customer_name: '',
        order_unique_id: '',
        status: '',
        order_type: '',
        product_name: '',
    };
    searchInputOrderId.value.focus();
    getOrders();

};

// Data filter
const shouldShowAmount = computed(() => {

    const validUserTypes = ['admin', 'shop'];

    return validUserTypes.includes(userType);

});
const shouldShowShop = computed(() => {

    const validUserTypes = ['admin', 'artisan'];

    return validUserTypes.includes(userType);

});

// Modal
const showModal = ref(false);

// Status
const onStatusClick = (order) => {

    Swal.fire({
        title: 'ステータス変更',
        input: 'select',
        inputOptions: _.fromPairs(orderStatuses.map((orderStatus) => {

            return [orderStatus.value, orderStatus.label];

        })),
        inputPlaceholder: 'ステータスを選択してください',
        showCancelButton: true,
        confirmButtonText: '変更',
        cancelButtonText: 'キャンセル',
        showLoaderOnConfirm: true,
        reverseButtons: true,
        preConfirm: (status) => {

            const url = route(`${guard}.order.update_status`, {
                order: order.id,
            });
            const data = {
                status: status,
            };

            return axios.put(url, data)
                .then((response) => {

                    if (response.data.result === true) {

                        getOrders();
                        Swal.close();

                        Swal.fire({
                            icon: 'success',
                            title: 'ステータスを変更しました',
                        })

                    }

                })
                .catch((error) => {

                    const firstErrorMessages = error.response.data.errors['status'][0];

                    Swal.showValidationMessage(
                        `エラー: ${firstErrorMessages}`
                    );

                });

        },
    });

};

// Confirmation
const currentOrder = ref({});
const onDetailsClick = (order) => {

    currentOrder.value = order;
    showModal.value = true;

}

// Helper functions
const isProduct = (order) => {
    return order.order_type === 'product';
}

const isItem = (order) => {
    return order.order_type === 'item';
}

</script>

<template>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <!-- <div>
            <label for="name" class="block text-sm font-medium text-gray-700">お客様の名前</label>
            <TextInput ref="searchInputName" model-value="searchParams.customer_name" class="block w-full" v-model="searchParams.customer_name" @keydown.enter="onSearch" />
        </div> -->
        <div>
            <label for="order-id" class="block text-sm font-medium text-gray-700">注文ID</label>
            <TextInput ref="searchInputOrderId" model-value="searchParams.customer_name" class="block w-full" v-model="searchParams.order_unique_id" @keydown.enter="onSearch" />
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">ステータス</label>
            <select
                id="status"
                name="status"
                class="block w-full px-3 py-2 bg-white border border-gray-300 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                v-model="searchParams.status">
                <option value="">選択してください</option>
                <option v-for="orderStatus in orderStatuses" :key="orderStatus.value" :value="orderStatus.value">{{ orderStatus.label }}</option>
            </select>
        </div>
        <div>
            <label for="order-type" class="block text-sm font-medium text-gray-700">注文タイプ</label>
            <select
                id="order-type"
                name="order_type"
                class="block w-full px-3 py-2 bg-white border border-gray-300 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                v-model="searchParams.order_type">
                <option value="">選択してください</option>
                <option v-for="orderType in orderTypes" :key="orderType.value" :value="orderType.value">{{ orderType.label }}</option>
            </select>
        </div>
        <div>
            <label for="product-name" class="block text-sm font-medium text-gray-700">商品名</label>
            <TextInput model-value="searchParams.customer_name" class="block w-full" v-model="searchParams.product_name" @keydown.enter="onSearch" />
        </div>
    </div>
    <div class="text-right mt-4 mb-6">
        <NormalButton size="xs" class="mr-1.5" @click="onSearch">注文検索</NormalButton>
        <CancelButton size="xs" @click="onClearSearchClick">リセット</CancelButton>
    </div>


    <ResponsiveTable class="mb-4">
        <table class="w-full text-xs">
            <thead class="bg-main-600 text-white text-xs">
            <tr>
                <th class="border border-gray-300 px-3 py-1 text-nowrap font-bold">注文タイプ</th>
                <th class="border border-gray-300 px-3 py-1 text-nowrap font-bold">商品名</th>
                <th class="border border-gray-300 px-3 py-1 text-nowrap font-bold">注文ID</th>
                <th class="border border-gray-300 px-3 py-1 text-nowrap font-bold">ステータス</th>
                <!-- <th class="border border-gray-300 px-3 py-1 text-nowrap font-bold">お客様</th>
                <th class="border border-gray-300 px-3 py-1 text-nowrap font-bold">店舗</th> -->
                <th class="border border-gray-300 px-3 py-1 text-nowrap font-bold" v-if="shouldShowAmount">合計金額</th>
                <th class="border border-gray-300 px-3 py-1 text-nowrap font-bold">注文日時</th>
                <th class="border border-gray-300 px-3 py-1 text-nowrap font-bold">詳細</th>
            </tr>
            </thead>
            <tbody>
            <template v-for="order in orders" :key="order.id">
                <tr class="odd:bg-gray-50 hover:bg-main-100" :class="{ 'bg-gray-100': order.status === 'completed' }">
                    <td class="border border-gray-300 px-3 py-1 text-nowrap">{{ order.order_type_label }}</td>
                    <td class="border border-gray-300 px-3 py-1 text-nowrap">{{ order.product_name }}</td>
                    <td class="border border-gray-300 px-3 py-1 text-nowrap">{{ order.order_unique_id }}</td>
                    <td class="border border-gray-300 px-3 py-1 text-nowrap text-center">
                        <a href="#" class="text-main-500 hover:underline" @click.prevent="onStatusClick(order)">{{ order.status_label }}</a>
                    </td>
                    <!-- <td class="border border-gray-300 px-3 py-1 text-nowrap">
                        <div v-if="order.customer_name">{{ order.customer_name }}</div>
                        <div class="text-center text-main-400" v-else>-</div>
                    </td>
                    <td class="border border-gray-300 px-3 py-1 text-nowrap">
                        <div v-if="order.shop?.name">{{ order.shop?.name }}</div>
                        <div class="text-center text-main-400" v-else>-</div>
                    </td> -->
                    <td class="border border-gray-300 px-3 py-1 text-right text-nowrap" v-if="shouldShowAmount">
                        {{ order.component?.total_amount_including_tax_text }}円
                    </td>
                    <td class="border border-gray-300 px-3 py-1 text-nowrap text-center">{{ order.created_date }}</td>
                    <td class="border border-gray-300 px-3 py-1 text-center text-nowrap">
                        <NormalButton size="xs" @click="onDetailsClick(order)">詳細</NormalButton>
                    </td>
                </tr>
            </template>
            <tr v-if="orders.length === 0">
                <td class="border border-gray-300 p-4 text-center" colspan="11">データがありません</td>
            </tr>
            </tbody>
        </table>
    </ResponsiveTable>
    <Pagination :links="orderLinks" @move-page="onMovePage" />
    <FullPageModal size="lg" v-model="showModal">
        <template #header>
            <div>
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold">注文詳細</h2>
                    <div class="text-sm ml-3 flex items-center">
                        <span class="font-medium text-main-700 px-2 py-1">
                            {{currentOrder.order_unique_id}}
                        </span>
                        <CopyToClipboard :text="currentOrder.order_unique_id" />
                    </div>
                </div>
                <div class="flex items-center mt-2">
                    <span class="px-4 py-1 bg-indigo-100 text-xs text-main-600 rounded-full text-sm font-medium">
                        {{ currentOrder.status_label }}
                    </span>
                    <span class="ml-2 text-main-600 text-xs">{{ currentOrder.order_type_label }}</span>
                </div>
            </div>
        </template>
        <div class="space-y-5">
            
            <!-- 基本情報 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-7 text-sm">
                <div class="bg-main-100 rounded-t overflow-hidden">
                    <div class="bg-main-600 px-4 py-2">
                        <h3 class="text-white font-medium text-sm">基本情報</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <!-- <div class="flex items-start">
                            <span class="text-main-500 w-24 flex-shrink-0 font-semibold">お客様:</span>
                            <span class="font-medium">{{currentOrder.customer_name}}</span>
                        </div> -->
                        <div class="flex items-start">
                            <span class="text-main-500 w-24 flex-shrink-0 font-semibold">注文日時:</span>
                            <span class="text-main-700">{{currentOrder.created_date}}</span>
                        </div>
                    </div>
                </div>
                
                <!-- 売上情報 -->
                <div class="bg-main-100 rounded-t overflow-hidden">
                    <div class="bg-main-600 px-4 py-2">
                        <h3 class="text-white font-medium text-sm">売上情報</h3>
                    </div>
                    <div class="p-4 space-y-3">
                    <!-- カスタマイズ品の場合 -->
                    <template v-if="isProduct(currentOrder)">
                        <div class="flex items-start mb-2">
                            <span class="text-main-500 w-24 flex-shrink-0 font-semibold">商品名:</span>
                            <span class="text-main-700">{{currentOrder.product_name}}</span>
                        </div>
                    </template>
                    <!-- 完成品の場合 -->
                    <template v-else-if="isItem(currentOrder)">
                        <div class="flex items-start">
                            <span class="text-main-500 w-24 flex-shrink-0 font-semibold">商品名:</span>
                            <span class="font-medium">{{currentOrder.item_name}}</span>
                        </div>
                    </template>
                    
                    <!-- 金額情報 -->
                    <div v-if="currentOrder.component" class="mt-2 pt-2 border-t border-main-200">
                        <div class="flex items-start">
                            <span class="text-main-500 w-24 flex-shrink-0 font-semibold">合計金額:</span>
                            <div>
                                <div class="text-main-700 text-lg leading-none">{{currentOrder.component?.total_amount_including_tax_text}}円</div>
                                <div class="text-xs text-main-600 mt-3 px-2 py-1 bg-main-200">
                                    <span class="inline-block">本体: {{currentOrder.component?.total_amount_text}}円</span>
                                    <span class="text-main-700inline-block ml-3">税: {{currentOrder.component?.consumption_tax_text}}円</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            
            <!-- コンポーネント詳細（カスタマイズ品の場合） -->
            <div v-if="isProduct(currentOrder) && currentOrder.components?.length" class="bg-main-100 rounded-t overflow-hidden">
                <div class="bg-main-600 px-4 py-2">
                    <h3 class="text-white font-medium text-sm">構成パーツ<small>（{{ currentOrder.components.length }}件）</small></h3>
                </div>
                <div class="p-0">
                    <table class="w-full text-xs">
                        <tbody>
                            <tr v-for="(component,index) in currentOrder.components" :key="component.id" 
                               class="border-b border-main-100 last:border-0 hover:bg-main-50"
                               :class="{'bg-main-50': index % 2 === 0, 'bg-white': index % 2 === 1}">
                                <td class="w-48 py-2 px-4 pr-4 text-nowrap pr-10">{{ (index+1) }}. {{ component.key_name }}</td>
                                <td class="py-2 px-2 pr-4">
                                    <div v-if="component.component_group_type === 'main' || component.component_group_type === 'sub'">
                                        <!--素材、色-->
                                        {{ component.parameters.material?.data?.name }}（{{ component.parameters.color?.data?.name }}）
                                    </div>
                                    <div v-if="component.component_group_type === 'common'">
                                        <!--色-->
                                        {{ component.parameters.color?.data?.name }}
                                    </div>
                                    <div v-if="component.component_group_type === 'logo'">
                                        <!--有無-->
                                        <div v-if="component.parameters.logo === true">
                                            ロゴあり
                                        </div>
                                        <div class="text-main-500" v-else-if="component.parameters.logo === false">
                                            ロゴなし
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ new Intl.NumberFormat().format(component.amount) }}円
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </FullPageModal>
</template>
