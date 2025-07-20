<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import Pagination from "@/Components/Pagination.vue";
import NavLink from "@/Components/NavLink.vue";
import DeleteButton from "@/Components/Buttons/DeleteButton.vue";
import NormalButton from "@/Components/Buttons/NormalButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SearchInput from "@/Components/SearchInput.vue";
import ResponsiveTable from "@/Components/ResponsiveTable.vue";

// Common
const productCategories = ref([]);
const productCategoryLinks = ref({});
onMounted(() => {

    getProductCategories();

});

// Product category
const getProductCategories = () => {

    productCategories.value = [];
    productCategoryLinks.value = {};

    const page = currentPage.value;
    const url = route('admin.product_category.list', {page: page});
    const data = {
        params: {
            q: searchParams.value.keyword,
        }
    };

    axios.get(url, data)
        .then((response) => {

            productCategories.value = response.data.data;
            productCategoryLinks.value = response.data.meta.links;

        });

};
const onMovePage = (page) => {

    currentPage.value = page;
    getProductCategories();

}
const onDelete = (productCategory) => {

    const url = route('admin.product_category.destroy', productCategory.id);

    Swal.confirm('delete')
        .then((result) => {

            if (result.isConfirmed) {

                axios.delete(url)
                    .then((response) => {

                        if(response.data.result === true) {

                            Swal.fire(
                                '削除しました',
                                '選択した店舗を削除しました。',
                                'success'
                            );
                            location.reload();

                        }

                    })
                    .catch((error) => {

                        const errorMessage = error.response.data.message;

                        Swal.fire(
                            'エラーが発生しました',
                            errorMessage,
                            'error'
                        );

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
    getProductCategories();

};
const onSearchClear = () => {

    searchParams.value.keyword = '';
    currentPage.value = 1;
    getProductCategories();

};

</script>

<template>
    <Head title="カテゴリ管理（360°VR）" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-indigo-50 leading-tight">カテゴリ管理<small>（360°VR）</small></h2>
        </template>

        <div class="py-3 sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-right mb-3">
                    <NormalButton size="xs" :href="route('admin.product_category.input')">新規登録</NormalButton>
                </div>
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="p-5 sm:p-10 text-gray-900">
                        <!-- search form -->
                        <SearchInput
                            v-model="searchParams.keyword"
                            placeholder="カテゴリ名"
                            @search="onSearch"
                            @clear="onSearchClear" />
                        <ResponsiveTable class="mb-4">
                            <table class="bg-white w-full text-sm">
                                <thead class="bg-indigo-600 text-white text-xs">
                                <tr>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold">カテゴリ名</th>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold w-24">表示順</th>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold w-1">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="category in productCategories" :key="category.id">
                                    <tr class="odd:bg-gray-50 hover:bg-indigo-100">
                                        <td class="border border-gray-300 p-2 text-nowrap">{{ category.name }}</td>
                                        <td class="border border-gray-300 p-2 text-center text-nowrap">
                                            {{ category.sort_number }}
                                        </td>
                                        <td class="border border-gray-300 p-2 text-nowrap">
                                            <NormalButton size="xs" class="mr-2" :href="route('admin.product_category.input', category.id)">編集</NormalButton>
                                            <DeleteButton size="xs" @click="onDelete(category)">削除</DeleteButton>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-if="productCategories.length === 0">
                                    <td class="border border-gray-300 p-4 text-center" colspan="3">データがありません</td>
                                </tr>
                                </tbody>
                            </table>
                        </ResponsiveTable>
                        <Pagination :links="productCategoryLinks" @move-page="onMovePage" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
