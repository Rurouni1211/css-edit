<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {computed, onMounted, ref} from "vue";
import LoadingModal from "@/Components/LoadingModal.vue";
import {getErrorMessages} from "@/Utils/Ajax.js";
import FooterButtons from "@/Components/FooterButtons.vue";
import SubButton from "@/Components/Buttons/SubButton.vue";

// Common
const props = defineProps({
    itemCategory: {
        type: Object,
        default() {
            return {};
        },
    },
    itemCategories: {
        type: Object,
        default() {
            return {};
        },
    },
    passwordLength: {
        type: Number,
        default: 16,
    },
});
const isModeCreate = computed(() => {

    return (! props.itemCategory.id);

});
const isModeEdit = computed(() => {

    return (props.itemCategory.id);

});
const isLoading = ref(false);
onMounted(() => {

    if (props.itemCategory.id) {

        params.value = props.itemCategory;

    }

});

// Form
const params = ref({
    name: '',
    sort_number: '',
    is_active: false,
});
const errors = ref({});
const onSubmit = () => {

    let url = '';
    let data = {};

    if(isModeEdit.value) {

        url = route('admin.item_category.update', {item_category: props.itemCategory.id});
        data = {
            _method: 'PUT',
            ...params.value,
        };

    } else {

        url = route('admin.item_category.store');
        data = params.value;

    }

    Swal.confirm('normal')
        .then((result) => {

            if (result.isConfirmed) {

                isLoading.value = true;

                axios.post(url, data)
                    .then((response) => {

                        if (response.data.result === true) {

                            Swal.fire({
                                title: '保存しました',
                                icon: 'success',
                            }).then(() => {

                                location.reload();

                            });

                        }

                    })
                    .catch((error) => {

                        errors.value = getErrorMessages(error);

                    })
                    .finally(() => isLoading.value = false);

            }

        });

};

// Others
const onCancel = () => {

    router.visit(route('admin.item_category.index'));

};

</script>

<template>
    <Head title="カテゴリ（完成品）追加・編集" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-indigo-50 leading-tight">カテゴリ（完成品）追加・編集</h2>
        </template>

        <div class="sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 p-5 sm:p-10">
                        <div class="flex-1  text-gray-900 mb-5 sm:mb-0">
                            <div class="mb-5">
                                <InputLabel value="カテゴリ名" />
                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full sm:w-96"
                                    v-model="params.name"
                                    autocomplete="name"
                                />
                                <InputError :message="errors['name']" />
                            </div>
                            <div class="mb-5">
                                <InputLabel value="表示順" />
                                <TextInput
                                    type="number"
                                    class="mt-1 block w-full sm:w-96"
                                    v-model="params.sort_number"
                                />
                                <InputError :message="errors['sort_number']" />
                            </div>
                            <div>
                                <label>
                                    <div class="flex items-center text-sm">
                                        <input type="checkbox" v-model="params.is_active" class="mr-1" />
                                        <div>
                                            有効にする
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="flex-1 text-sm">
                            <InputLabel value="参考：表示順" />
                            <div class="mb-20 sm:mb-0 bg-gray-100 p-4">
                                <div v-for="category in itemCategories">
                                    <div class="mb-0.5">
                                        <span>【{{ category.sort_number }}】</span>
                                        <span>{{ category.name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <FooterButtons
            @yes-click="onSubmit"
            @no-click="onCancel">
            <template v-slot:left>
                <SubButton size="md" :href="route('admin.item_category.input')" @click="onCancel">新規追加</SubButton>
            </template>
        </FooterButtons>

        <div v-if="isLoading">
            <LoadingModal :show="isLoading" />
        </div>

    </AuthenticatedLayout>

</template>
