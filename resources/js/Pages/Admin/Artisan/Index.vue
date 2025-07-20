<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import Pagination from "@/Components/Pagination.vue";
import TextInput from "@/Components/TextInput.vue";
import NormalButton from "@/Components/Buttons/NormalButton.vue";
import DeleteButton from "@/Components/Buttons/DeleteButton.vue";
import SearchInput from "@/Components/SearchInput.vue";
import ResponsiveTable from "@/Components/ResponsiveTable.vue";

// Common
const artisans = ref([]);
const artisanLinks = ref({});
onMounted(() => {

    getArtisans();

});

// Artisan
const getArtisans = () => {

    const page = currentPage.value;
    const url = route('admin.artisan.list', {page: page});
    const data = {
        params: {
            q: searchParams.value.keyword,
        }
    };

    axios.get(url, data)
        .then((response) => {

            artisans.value = response.data.data;
            artisanLinks.value = response.data.links;

        });

};
const onMovePage = (page) => {

    currentPage.value = page;
    getArtisans();

}
const onDelete = (artisan) => {

    const url = route('admin.artisan.destroy', artisan.id);

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
                    .catch(() => {

                        Swal.fire(
                            'エラーが発生しました',
                            '削除に失敗しました。',
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
    getArtisans();

};
const onSearchClear = () => {

    searchParams.value.keyword = '';
    currentPage.value = 1;
    getArtisans();

};

</script>

<template>
    <Head title="職人管理" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-indigo-50 leading-tight">職人管理</h2>
        </template>

        <div class="py-3 sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-right mb-3">
                    <NormalButton size="xs" :href="route('admin.shop.input')">新規登録</NormalButton>
                </div>
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="p-5 sm:p-10 text-gray-900">
                        <!-- search form -->
                        <SearchInput
                            v-model="searchParams.keyword"
                            placeholder="職人名・メールアドレス"
                            @search="onSearch"
                            @clear="onSearchClear" />
                        <ResponsiveTable class="mb-4">
                            <table class="bg-white w-full text-sm">
                                <thead class="bg-indigo-600 text-white text-xs">
                                <tr>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold">店舗名</th>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold">メールアドレス</th>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold w-1">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="artisan in artisans" :key="artisan.id">
                                    <tr class="odd:bg-gray-50 hover:bg-indigo-100">
                                        <td class="border border-gray-300 p-2 text-nowrap">{{ artisan.name }}</td>
                                        <td class="border border-gray-300 p-2 text-nowrap">
                                            <a  class="underline" :href="`mailto:${artisan.email}`">{{ artisan.email }}</a>
                                        </td>
                                        <td class="border border-gray-300 p-2 text-nowrap">
                                            <NormalButton size="xs" class="mr-2" :href="route('admin.artisan.input', artisan.id)">編集</NormalButton>
                                            <DeleteButton size="xs" @click="onDelete(artisan)">削除</DeleteButton>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-if="artisans.length === 0">
                                    <td class="border border-gray-300 p-4 text-center" colspan="3">データがありません。</td>
                                </tr>
                                </tbody>
                            </table>
                        </ResponsiveTable>
                        <Pagination :links="artisanLinks" @move-page="onMovePage" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
