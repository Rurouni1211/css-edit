<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {computed, nextTick, onMounted, ref} from "vue";
import LoadingModal from "@/Components/LoadingModal.vue";
import {getErrorMessages, scrollToFirstError} from "@/Utils/Ajax.js";
import FooterButtons from "@/Components/FooterButtons.vue";
import _ from "lodash";
import SearchableSelect from "@/Components/SearchableSelect.vue";
import InputDescription from "@/Components/InputDescription.vue";
import ImageInputForm from "@/Components/ImageInputForm.vue";
import {getFormData} from "@/Mixins/FormMixin.js";
import SubButton from "@/Components/Buttons/SubButton.vue";

// Common
const props = defineProps({
    item: {
        type: Object,
        default() {
            return {};
        },
    },
    shops: {
        type: Array,
        required: true,
    },
    itemCategories: {
        type: Array,
        required: true,
    },
});
const isLoading = ref(false);
const isPreparing = ref(false);
const isModeCreate = computed(() => {

    return Number(props.item.id) === 0;

});
const isModeEdit = computed(() => {

    return Number(props.item.id) > 0;

});
onMounted(() => {

    if(isModeEdit.value === true) { // Edit

        const item = props.item;

        params.value = {
            category_id: item.category_id || '',
            name: item.name || '',
            description: item.description || '',
            item_code: item.item_code || '',
            price: item.price || '',
            shop_id: item.shop_id || '',
            sort_number: (item.sort_number >= 0) ? item.sort_number: '',
            images: item.images || [],
        };

    }

});

// Form
const defaultParams = {
    category_id: '',
    name: '',
    description: '',
    item_code: '',
    price: '',
    shop_id: '',
    sort_number: '',
    new_images: [],
    deleted_image_ids: [],
    sort_image_ids: [],
};
const params = ref(_.cloneDeep(defaultParams));
const errors = ref({});
const clearParams = () => {

    params.value = _.cloneDeep(defaultParams);
    errors.value = {};

};
const onSubmit = () => {

    Swal.confirm('normal')
        .then(result => {

            if (result.isConfirmed) {

                isLoading.value = true;
                const formData = getFormData(params.value);
                let url = '';

                if(isModeEdit.value === true) {

                    url = route('admin.item.update', {item: props.item.id});
                    formData.append('_method', 'PUT');

                } else {

                    url = route('admin.item.store');

                }

                axios.post(url, formData)
                    .then((response) => {

                        isLoading.value = false;
                        clearParams();

                        if(response.data.result === true) {

                            const itemId = response.data.item_id;

                            Swal.success('保存しました。')
                                .then(() => {

                                    router.visit(route('admin.item.input', {item: itemId}));

                                });

                        }

                    })
                    .catch(error => {

                        isLoading.value = false;
                        errors.value = getErrorMessages(error);
                        Swal.error();

                        nextTick(() => {

                            scrollToFirstError();

                        });

                    });

            }

        });

};

// Image
const onImageFileChange = (data) => {

    const { newImages, deletedImageIds, sortImageIds } = data;
    params.value.new_images = newImages;
    params.value.deleted_image_ids = deletedImageIds;
    params.value.sort_image_ids = sortImageIds;

    isPreparing.value = true;

};

// Confirmation button
const showConfirm = computed(() => {

    return isModeEdit.value === true;

});

// Others
const onCancel = () => {

    Swal.confirm('cancel', 'もし入力した内容がある場合、破棄されます。<br>よろしいですか？')
        .then(result => {

            if (result.isConfirmed) {

                router.visit(route('admin.item.index'));

            }

        });

};
const onConfirmClick = () => {

    window.open(props.item.detail_url, '_blank');

};

</script>

<template>
    <Head title="商品追加・編集（完成品）" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-indigo-50 leading-tight">商品追加・編集（完成品）</h2>
        </template>

        <div class="sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="p-5 sm:p-10 text-gray-900">
                        <div class="md:flex mb-5">
                            <div class="flex-1">
                                <div class="mb-5">
                                    <InputLabel value="商品名" />
                                    <TextInput
                                        type="text"
                                        class="mt-1 block w-full sm:w-96"
                                        v-model="params.name"
                                        autocomplete="name"
                                    />
                                    <InputError :message="errors.name" />
                                </div>
                                <div class="mb-5">
                                    <InputLabel value="商品コード" sub-title="（重複しない文字列）" />
                                    <TextInput
                                        type="text"
                                        class="mt-1 block w-full sm:w-96"
                                        v-model="params.item_code"
                                        autocomplete="item_code"
                                    />
                                    <InputError :message="errors.item_code" />
                                </div>
                                <div class="mb-5">
                                    <InputLabel value="商品説明" />
                                    <TextInput
                                        type="textarea"
                                        class="mt-1 block w-full sm:w-96"
                                        rows="5"
                                        v-model="params.description"
                                        autocomplete="name"
                                    />
                                    <InputError :message="errors.description" />
                                </div>
                                <div class="mb-5">
                                    <InputLabel value="価格" />
                                    <TextInput
                                        type="number"
                                        class="mt-1 block w-full sm:w-96"
                                        v-model="params.price"
                                        autocomplete="price"
                                    />
                                    <InputError :message="errors.price" />
                                </div>
                                <div class="mb-5 w-full sm:w-96">
                                    <InputLabel value="販売店舗" />
                                    <SearchableSelect :items="props.shops" v-model="params.shop_id" />
                                    <InputError :message="errors.shop_id" />
                                </div>
                                <div class="mb-5">
                                    <InputLabel value="並び順" />
                                    <TextInput
                                        type="number"
                                        class="mt-1 block w-full sm:w-96"
                                        v-model="params.sort_number"
                                        autocomplete="sort_number"
                                    />
                                    <InputDescription>
                                        店舗内での表示順。数字が大きいほど上に表示されます。
                                    </InputDescription>
                                    <InputError :message="errors.sort_number" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="mb-5">
                                    <InputLabel value="カテゴリ" />
                                    <div class="text-sm">
                                        <div class="mb-1.5" v-for="category in itemCategories">
                                            <label class="flex items-center">
                                                <input type="radio" v-model="params.category_id" :value="category.id" />
                                                <span class="ml-2" v-text="category.name"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <InputError :message="errors.category_id" />
                                </div>
                                <div class="mb-20 sm:mb-5">
                                    <InputLabel value="画像" />
                                    <InputError :message="errors.new_images" />
                                    <ImageInputForm :default-images="item.images" @change="onImageFileChange"></ImageInputForm>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <FooterButtons
            :show-confirm="showConfirm"
            :is-preparing="isPreparing"
            @yes-click="onSubmit"
            @no-click="onCancel"
            @confirm-click="onConfirmClick">
            <template v-slot:left>
                <SubButton size="md" :href="route('admin.item.input')" @click="onCancel">新規追加</SubButton>
            </template>
        </FooterButtons>
        <LoadingModal :show="isLoading" />

    </AuthenticatedLayout>

</template>
