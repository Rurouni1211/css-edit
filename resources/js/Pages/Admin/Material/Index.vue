<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import Pagination from "@/Components/Pagination.vue";
import NormalButton from "@/Components/Buttons/NormalButton.vue";
import DeleteButton from "@/Components/Buttons/DeleteButton.vue";
import ResponsiveTable from "@/Components/ResponsiveTable.vue";
import TextInput from "@/Components/TextInput.vue";
import SearchInput from "@/Components/SearchInput.vue";

// Common
const materials = ref([]);
const materialLinks = ref({});
onMounted(() => {

    getMaterials();

});

// Material
const getMaterials = () => {

    const page = currentPage.value;
    materials.value = [];
    materialLinks.value = {};

    const url = route('admin.material.list', {page: page});
    const data = {
        params: {
            q: searchParams.value.keyword,
        }
    };

    axios.get(url, data)
        .then((response) => {

            materials.value = response.data.data;
            materialLinks.value = response.data.meta.links;

        });

};
const onMovePage = (page) => {

    currentPage.value = page;
    getMaterials();

};
const onDelete = (material) => {

    Swal.confirm('delete')
        .then((result) => {

            if (result.isConfirmed) {

                const url = route('admin.material.destroy', {material: material});
                const data = {
                    _method: 'DELETE',
                };
                axios.post(url, data)
                    .then((response) => {

                        if(response.data.result === true) {

                            getMaterials();

                        }

                    })
                    .catch((error) => {

                        const message = error.response.data.message;
                        Swal.error(message);

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
    getMaterials();

};
const onSearchClear = () => {

    searchParams.value.keyword = '';
    currentPage.value = 1;
    getMaterials();

};

// Others
const onCreate = () => {

    router.visit(route('admin.material.input'));

}

</script>

<template>
    <Head title="素材管理（360°VR）" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-indigo-50 leading-tight">素材管理<small>（360°VR）</small></h2>
        </template>

        <div class="py-3 sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-right mb-3">
                    <NormalButton size="xs" @click="onCreate">新規登録</NormalButton>
                </div>
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="p-5 sm:p-10 text-gray-900">
                        <!-- search form -->
                        <SearchInput
                            v-model="searchParams.keyword"
                            placeholder="素材名"
                            @search="onSearch"
                            @clear="onSearchClear" />
                        <ResponsiveTable class="mb-4">
                            <table class="bg-white w-full text-sm">
                                <thead class="bg-indigo-600 text-white text-xs text-gray-600">
                                <tr>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold">素材名</th>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold">キー</th>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold">光沢度</th>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold">鏡面反射</th>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold">鏡面反射<small>（画像）</small></th>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold">カラー</th>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold">ノーマルマップ</th>
                                    <th class="border border-gray-300 p-2 text-nowrap font-bold w-1">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="material in materials" :key="material.id">
                                    <tr class="odd:bg-gray-50 hover:bg-indigo-100">
                                        <td class="border border-gray-300 p-2 min-w-60">{{ material.name }}</td>
                                        <td class="border border-gray-300 p-2">{{ material.key }}</td>
                                        <td class="border border-gray-300 p-2 text-center text-nowrap">{{ material.glossiness }}</td>
                                        <td class="border border-gray-300 p-2 text-center text-nowrap">{{ material.specular }}</td>
                                        <td class="border border-gray-300 p-2 text-center text-nowrap">
                                            <div v-if="material.specular_map">
                                                <a :href="material.specular_map.url" class="text-indigo-600 hover:underline text-xs py-1 text-nowrap" target="_blank">確認</a>
                                            </div>
                                            <div class="text-gray-400" v-else>-</div>
                                        </td>
                                        <td class="border border-gray-300 p-2 text-center text-nowrap">{{ material.colors.length }}件</td>
                                        <td class="border border-gray-300 p-2 text-center text-nowrap">{{ material.normal_maps.length }}件</td>
                                        <td class="border border-gray-300 p-2 text-nowrap">
                                            <NormalButton size="xs" class="mr-1.5" :href="route('admin.material.input', material)">編集</NormalButton>
                                            <DeleteButton size="xs" @click="onDelete(material)">削除</DeleteButton>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-if="materials.length === 0">
                                    <td class="border border-gray-300 p-4 text-center" colspan="8">データがありません</td>
                                </tr>
                                </tbody>
                            </table>
                        </ResponsiveTable>
                        <Pagination :links="materialLinks" @move-page="onMovePage" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
