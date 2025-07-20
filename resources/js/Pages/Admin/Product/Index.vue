<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import Pagination from "@/Components/Pagination.vue";
import NavLink from "@/Components/NavLink.vue";
import NormalButton from "@/Components/Buttons/NormalButton.vue";
import DeleteButton from "@/Components/Buttons/DeleteButton.vue";
import TextInput from "@/Components/TextInput.vue";
import CancelButton from "@/Components/Buttons/CancelButton.vue";
import SubButton from "@/Components/Buttons/SubButton.vue";
import SearchInput from "@/Components/SearchInput.vue";
import ResponsiveTable from "@/Components/ResponsiveTable.vue";

// Common
const products = ref([]);
const productLinks = ref({});
onMounted(() => {

    getProducts();

});

// Product
const getProducts = () => {

    const page = currentPage.value;
    products.value = [];
    productLinks.value = {};

    const url = route('admin.product.list', {page: page});
    const data = {
        params: {
            q: searchParams.value.keyword,
        }
    };

    axios.get(url, data)
        .then((response) => {

            products.value = response.data.data;
            productLinks.value = response.data.meta.links;

        });

};
const onMovePage = (page) => {

    currentPage.value = page;
    getProducts();

};
const onDuplicate = (product) => {

    const message = `「${product.name}」を複製しますか？`;
    Swal.confirm('normal', message)
        .then((result) => {

            if (result.isConfirmed) {

                const url = route('admin.product.duplicate', {product: product.id});
                axios.post(url)
                    .then((response) => {

                        if(response.data.result === true) {

                            getProducts();

                        }

                    });

            }

        });

};
const onDelete = (product) => {

    Swal.confirm('delete')
        .then((result) => {

            if (result.isConfirmed) {

                const url = route('admin.product.destroy', {product: product.id});
                const data = {
                    _method: 'DELETE',
                };
                axios.post(url, data)
                    .then((response) => {

                        if(response.data.result === true) {

                            getProducts();

                        }

                    });

            }

        });

};

// Search
const currentPage = ref(1);
const searchParams = ref({
    keyword: '',
});
const onSearch = () => {

    currentPage.value = 1;
    getProducts();

};
const onSearchClear = () => {

    searchParams.value.keyword = '';
    currentPage.value = 1;
    getProducts();

};

</script>

<template>
    <Head title="商品管理（360°VR）" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-indigo-50 leading-tight">商品管理<small>（360°VR）</small></h2>
        </template>
        <div class="py-3 sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-right mb-3">
                    <NormalButton size="xs" :href="route('admin.product.input')">新規登録</NormalButton>
                </div>
                <div class="bg-white overflow-hidden shadow-sm mb-4">
                    <div class="p-5 sm:p-10 text-gray-900">
                        <!-- search form -->
                        <SearchInput
                            v-model="searchParams.keyword"
                            placeholder="商品名、商品コード、Sketchfabキー"
                            @search="onSearch"
                            @clear="onSearchClear" />
                        <ResponsiveTable class="mb-4">
                            <table class="bg-white w-full text-sm">
                                <thead class="bg-indigo-600 text-white text-xs">
                                    <tr>
                                        <th class="border border-gray-300 p-2 text-nowrap font-bold">商品名<small>（商品コード）</small></th>
                                        <th class="border border-gray-300 p-2 text-nowrap font-bold">カテゴリ</th>
                                        <th class="border border-gray-300 p-2 text-nowrap font-bold">店舗</th>
                                        <th class="border border-gray-300 p-2 text-nowrap font-bold">Sketchfabキー</th>
                                        <th class="border border-gray-300 p-2 text-nowrap font-bold">組合わせ</th>
                                        <th class="border border-gray-300 p-2 text-nowrap font-bold w-1">確認</th>
                                        <th class="border border-gray-300 p-2 text-nowrap font-bold w-1">複製</th>
                                        <th class="border border-gray-300 p-2 text-nowrap font-bold w-1">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="product in products" :key="product.id">
                                        <tr class="odd:bg-gray-50 hover:bg-indigo-100">
                                            <td class="border border-gray-300 p-2 min-w-60">
                                                <div class="text-xs text-gray-600 mb-0.5">{{ product.product_code }}</div>
                                                <div>{{ product.name }}</div>
                                            </td>
                                            <td class="border border-gray-300 p-2">{{ product.category.name }}</td>
                                            <td class="border border-gray-300 p-2">{{ product.shop.name }}</td>
                                            <td class="border border-gray-300 p-2 text-xs">
                                                <a :href="product.sketchfab_url" class="text-indigo-500 hover:underline" target="_blank">{{ product.sketchfab_model_key }}</a>
                                            </td>
                                            <td class="border border-gray-300 p-2 text-center">{{ product.material_combination_label }}</td>
                                            <td class="border border-gray-300 p-2 text-nowrap">
                                                <SubButton size="xs" :href="product.detail_url" target="_blank">確認</SubButton>
                                            </td>
                                            <td class="border border-gray-300 p-2 text-nowrap">
                                                <CancelButton size="xs" class="mr-1" @click="onDuplicate(product)">複製</CancelButton>
                                            </td>
                                            <td class="border border-gray-300 p-2 text-nowrap">
                                                <NormalButton size="xs" class="mr-1" :href="route('admin.product.input', product.id)">編集</NormalButton>
                                                <DeleteButton size="xs" @click="onDelete(product)">削除</DeleteButton>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr v-if="products.length === 0">
                                        <td class="border border-gray-300 p-4 text-center" colspan="7">データがありません。</td>
                                    </tr>
                                </tbody>
                            </table>
                        </ResponsiveTable>
                        <Pagination :links="productLinks" @move-page="onMovePage" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
