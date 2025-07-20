<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {ref,computed, nextTick, onMounted} from "vue";
import LoadingModal from "@/Components/LoadingModal.vue";
import {getErrorMessages, scrollToFirstError} from "@/Utils/Ajax.js";
import TextInput from "@/Components/TextInput.vue";
import ColorPalette from "@/Components/ColorPalette.vue";
import FooterButtons from "@/Components/FooterButtons.vue";
import {getFormData} from "@/Mixins/FormMixin.js";
import FileInput from "@/Components/FileInput.vue";
import DeleteButton from "@/Components/Buttons/DeleteButton.vue";
import FileInfo from "@/Components/FileInfo.vue";
import NormalButton from "@/Components/Buttons/NormalButton.vue";
import DragAndDrop from "@/Components/DragAndDrop.vue";
import ResponsiveTable from "@/Components/ResponsiveTable.vue";
import InputDescription from "@/Components/InputDescription.vue";
import SubButton from "@/Components/Buttons/SubButton.vue";

// Common
const props = defineProps({
    material: {
        type: Object,
        default() {
            return {};
        },
    },
    materialButtonKeys: {
        type: Array,
        default() {
            return [];
        },
    },
});
const isModeCreate = computed(() => {

    return (! props.material.id);

});
const isModeEdit = computed(() => {

    return (props.material.id);

});
const isLoading = ref(false);
onMounted(() => {

    if (props.material.id) {

        const specularMap = props.material.specular_map;

        params.value = props.material;
        params.value.specular_map = {
            id: _.get(specularMap, 'id', null),
            original_filename: _.get(specularMap, 'original_filename', ''),
            filename: _.get(specularMap, 'filename', ''),
            url: _.get(specularMap, 'url', ''),
            file: null,
        };

    }

});

// Form
const params = ref({
    name: '',
    key: '',
    glossiness: '',
    specular: '',
    specular_map: {
        file: null,
    },
    colors: [],
    normal_maps: [],
    textures: [],
});
const errors = ref({});
const deletingIds = ref({
    colors: [],
    color_files: [],
    normal_maps: [],
    normal_map_files: [],
    specular_maps: [],
});
const onPrepareSubmit = () => {

    const isValidKey = props.materialButtonKeys.some((key) => {

        return params.value.key.startsWith(key);

    });
    if (!isValidKey) {

        const message = '<div class="text-sm">現在の「キー」では販売ページの素材画像が有効になりません。<br>よろしいですか？</div>';
        Swal.confirm('normal', message)
            .then((result) => {

                if (result.isConfirmed) {

                    onSubmit();

                }

            });

    } else {

        onSubmit();

    }

};
const onSubmit = () => {

    let url = '';
    let data = {
        method: '',
        url: '',
        deleting_ids: {},
    };

    if(isModeEdit.value) {

        url = route('admin.material.update', {id: props.material.id});
        data = {
            _method: 'PUT',
            ...params.value,
            ...{deleting_ids: deletingIds.value},
        };

    } else {

        url = route('admin.material.store');
        data = {
            ...params.value,
            ...{deleting_ids: deletingIds.value},
        };

    }

    Swal.confirm()
        .then((result) => {

            if (result.isConfirmed) {

                isLoading.value = true;
                const formData = getFormData(data);

                axios.post(url, formData)
                    .then((response) => {

                        if (response.data.result === true) {

                            params.value = response.data.material;
                            errors.value = {};
                            deletingIds.value = {
                                colors: [],
                                color_files: [],
                                normal_maps: [],
                                normal_map_files: [],
                                specular_maps: [],
                            };
                            footerIsPreparing.value = false;

                            Swal.success()
                                .then(() => {

                                    if(isModeCreate.value === true) {

                                        location.reload();

                                    }

                                });

                        }

                    })
                    .catch((error) => {

                        const statusCode = error.response.status;

                        if(statusCode === 413) {

                            alert('送信ファイルサイズが大きすぎます。');
                            return;

                        }

                        errors.value = getErrorMessages(error);

                        nextTick(() => {

                            scrollToFirstError();

                        });

                    })
                    .finally(() => isLoading.value = false);

            }

        });

};

// Color, Texture
const onAddColorClick = () => {

    params.value.colors.push({
        name: '',
        texture_file: null,
        color_code: '',
    });

};
const onDeleteColorClick = (color, index) => {

    Swal.confirm('delete')
        .then((result) => {

            if(result.isConfirmed) {

                const colorId = color.id;
                params.value.colors.splice(index, 1);

                if(colorId) {

                    footerIsPreparing.value = true;
                    deletingIds.value.colors.push(colorId);

                }

            }

        })

};
const onColorTextureFileChange = (e, index) => {

    const file = e.target.files[0];

    if(file) {

        const color = params.value.colors[index];
        color.texture_file = file;

    }

};
const onColorTextureDrop = (e, index) => {

    const file = e.dataTransfer.files[0];

    if(file) {

        onColorTextureFileChange({target: {files: [file]}}, index);

    }

};
const onColorTextureDeleteFile = (index) => {

    Swal.confirm('delete')
        .then((result) => {

            if(result.isConfirmed) {

                const targetColor = params.value.colors[index];

                if(targetColor.id) {

                    deletingIds.value.color_files.push(targetColor.id);
                    deletingIds.value.color_files = _.uniq(deletingIds.value.color_files);
                    targetColor.original_texture_filename = '';
                    targetColor.texture_filename = '';
                    onColorTextureClearFile(index);
                    footerIsPreparing.value = true;

                }

            }

        });

};
const onColorTextureClearFile = (index) => {

    params.value.colors[index].texture_file = null;

};

// Normal map
const onAddNormalMapClick = () => {

    params.value.normal_maps.push({
        name: '',
        file: null,
    });

};
const onDeleteNormalMapClick = (normal_map, index) => {

    Swal.confirm('delete')
        .then((result) => {

            if(result.isConfirmed) {

                const normalMapId = normal_map.id;
                params.value.normal_maps.splice(index, 1);

                if(normalMapId) {

                    footerIsPreparing.value = true;
                    deletingIds.value.normal_maps.push(normal_map.id);

                }

            }

        });

};
const onNormalMapFileChange = (e, index) => {

    const file = e.target.files[0];

    if(file) {

        const normal_map = params.value.normal_maps[index];
        normal_map.file = file;

    }

};
const onNormalMapDrop = (e, index) => {

    const file = e.dataTransfer.files[0];

    if(file) {

        onNormalMapFileChange({target: {files: [file]}}, index);

    }

};
const onNormalMapDeleteFile = (index) => {

    Swal.confirm('delete')
        .then((result) => {

            if(result.isConfirmed) {

                const targetNormalMap = params.value.normal_maps[index];

                if(targetNormalMap.id) {

                    deletingIds.value.normal_map_files.push(targetNormalMap.id);
                    deletingIds.value.normal_map_files = _.uniq(deletingIds.value.normal_map_files);
                    targetNormalMap.original_filename = '';
                    targetNormalMap.filename = '';
                    onNormalMapClearFile(index);
                    footerIsPreparing.value = true;

                }

            }

        });

};
const onNormalMapClearFile = (index) => {

    params.value.normal_maps[index].file = null;

};

// Specular Map
const onSpecularMapsFileChange = (e) => {

    const file = e.target.files[0];

    if(file) {

        params.value.specular_map.file = file;

    }

};
const onSpecularMapDeleteFile = (specularMapId) => {

    Swal.confirm('delete')
        .then((result) => {

            if(result.isConfirmed) {

                deletingIds.value.specular_maps.push(specularMapId);
                params.value.specular_map.original_filename = '';
                params.value.specular_map.filename = '';
                onSpecularMapClearFile();
                footerIsPreparing.value = true;

            }

        });

};
const onSpecularMapClearFile = () => {

    params.value.specular_map.file = null;

};

// Footer
const footerIsPreparing = ref(false);

// Available Key
const onAvailableKeyClick = () => {

    let message = '';

    props.materialButtonKeys.forEach((key, index) => {

        message += `<div class="text-base text-left">・${key}`;

        if(index === 0) {

            message += `<small>（例：${key}<strong>abc</strong>）</small>`;

        }

        message += '</div>';

    });

    Swal.fire({
        title: '以下から始まる文字列は画像が有効になります。',
        html: message,
        icon: 'info',
        confirmButtonText: 'OK',
    });

};

// Others
const onCancel = () => {

    router.visit(route('admin.material.index'));

};

</script>

<template>
    <Head title="素材の追加・編集" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-indigo-50 leading-tight">素材の追加・編集</h2>
        </template>

        <div class="sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="p-5 sm:p-10 text-gray-900">
                        <div class="container mx-auto mb-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-5">
                                <div class="p-0">
                                    <InputLabel value="素材名" />
                                    <TextInput
                                        type="text"
                                        name="name"
                                        class="mt-1 block w-full"
                                        v-model="params.name"
                                    />
                                    <InputError :message="errors['name']" />
                                </div>
                                <div class="p-0">
                                    <InputLabel value="キー" subTitle="（空白は不可）" />
                                    <TextInput
                                        type="text"
                                        name="name"
                                        class="mt-1 block w-full"
                                        v-model="params.key"
                                    />
                                    <InputError :message="errors['key']" />
                                    <div class="text-gray-500">
                                        <small>
                                            ※<a href="#" @click.prevent="onAvailableKeyClick">画像が有効になるキーのサンプル</a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-5">
                                <div>
                                    <InputLabel value="光沢度" sub-title="（Glossiness）" />
                                    <TextInput
                                        type="text"
                                        name="glossiness"
                                        class="mt-1 block w-full"
                                        v-model="params.glossiness"
                                    />
                                    <InputDescription>
                                        範囲は0~1。少数第3位まで（例：0.047）
                                    </InputDescription>
                                    <InputError :message="errors['glossiness']" />
                                </div>
                                <div>
                                    <InputLabel value="鏡面反射" sub-title="（Specular）" />
                                    <TextInput
                                        type="text"
                                        name="specular"
                                        class="mt-1 block w-full"
                                        v-model="params.specular"
                                    />
                                    <InputDescription>
                                        範囲は0~1。少数第3位まで（例：0.047）
                                    </InputDescription>
                                    <InputError :message="errors['specular']" />
                                </div>
                            </div>
                            <div class="mb-5">
                                <div>
                                    <InputLabel value="鏡面反射画像" sub-title="（Specular Map）" />
                                    <DragAndDrop @drop="onSpecularMapsFileChange">
                                        <div class="flex items-center">
                                            <div class="mr-2 mb-1">
                                                <FileInput @change="onSpecularMapsFileChange" />
                                            </div>
                                            <div class="text-xs" v-if="params.specular_map">
                                                <FileInfo
                                                    :original-filename="params.specular_map.original_filename"
                                                    :url="params.specular_map.url"
                                                    :file="params.specular_map.file"
                                                    @delete-file="onSpecularMapDeleteFile(params.specular_map.id)"
                                                    @clear-file="onSpecularMapClearFile"
                                                />
                                            </div>
                                        </div>
                                    </DragAndDrop>
                                    <InputError :message="errors['specular_map']" />
                                </div>
                                <div class="w-full"></div>
                            </div>
                        </div>
                        <!-- カラー -->
                        <div class="mb-5">
                            <div class="flex">
                                <div class="flex-grow">
                                    <InputLabel value="カラー" :sub-title="`（${params.colors.length}件）`" />
                                </div>
                                <div class="py-0 text-sm mt-0.5">
                                    <NormalButton size="xs" class="mb-1" @click="onAddColorClick">追加する</NormalButton>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table w-full mb-3" v-if="params.colors.length > 0">
                                    <thead class="bg-gray-200 text-sm">
                                    <tr>
                                        <th class="border px-4 py-2">名前</th>
                                        <th class="border px-4 py-2">カラーコード</th>
                                        <th class="border px-4 py-2 w-full">テクスチャ・ファイル<small class="font-normal text-gray-700">（ドロップ可）</small></th>
                                        <th class="border px-4 py-2"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(color, index) in params.colors" :key="index">
                                        <td class="border p-2 align-top">
                                            <TextInput
                                                type="text"
                                                class="w-64"
                                                v-model="color.name"
                                            />
                                            <InputError :message="errors[`colors.${index}.name`]" />
                                        </td>
                                        <td class="border p-2 align-top">
                                            <div class="flex">
                                                <div class="flex items-center pr-3" v-if="color.color_code">
                                                    <ColorPalette :size="20" :color-code="color.color_code" />
                                                </div>
                                                <div>
                                                    <TextInput
                                                        type="text"
                                                        class="w-40"
                                                        v-model="color.color_code"
                                                        placeholder="例: #FFFFFF"
                                                    />
                                                </div>
                                            </div>
                                            <InputError :message="errors[`colors.${index}.color_code`]" />
                                        </td>
                                        <td class="border text-nowrap">
                                            <DragAndDrop class="p-3" @drop="onColorTextureDrop($event, index)">
                                                <div class="flex items-center">
                                                    <div class="mr-2">
                                                        <FileInput @change="onColorTextureFileChange($event, index)" />
                                                    </div>
                                                    <div class="text-xs">
                                                        <FileInfo
                                                            :original-filename="color.original_texture_filename"
                                                            :url="color.texture_url"
                                                            :file="color.texture_file"
                                                            @delete-file="onColorTextureDeleteFile(index)"
                                                            @clear-file="onColorTextureClearFile(index)"
                                                        />
                                                    </div>
                                                </div>
                                                <InputError :message="errors[`colors.${index}.texture_file`]" />
                                            </DragAndDrop>
                                        </td>
                                        <td class="border p-2 text-nowrap">
                                            <DeleteButton size="xs" @click="onDeleteColorClick(color, index)">削除</DeleteButton>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <InputError :message="errors['colors']" />
                            </div>
                        </div>
                        <!-- ノーマルマップ -->
                        <div class="mb-5">
                            <div class="flex">
                                <div class="flex-grow">
                                    <InputLabel value="ノーマルマップ" :sub-title="`（${params.normal_maps.length}件）`" />
                                </div>
                                <div class="py-0 text-sm mt-0.5">
                                    <NormalButton size="xs" class="mb-1" @click="onAddNormalMapClick">追加する</NormalButton>
                                </div>
                            </div>
                            <ResponsiveTable>
                                <table class="table w-full text-sm" v-if="params.normal_maps.length > 0">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th class="border px-4 py-2">名前</th>
                                            <th class="border px-4 py-2 w-full">ファイル<small class="font-normal text-gray-700">（ドロップ可）</small></th>
                                            <th class="border px-4 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(normal_map, index) in params.normal_maps" :key="index">
                                        <td class="border p-2 align-top">
                                            <TextInput
                                                type="text"
                                                name="`normal_maps[${index}][name]`"
                                                class="w-64"
                                                v-model="normal_map.name"
                                            />
                                            <InputError :message="errors[`normal_maps.${index}.name`]" />
                                        </td>
                                        <td class="border text-nowrap">
                                            <DragAndDrop class="p-3" @drop="onNormalMapDrop($event, index)">
                                                <div class="flex items-center">
                                                    <div class="mr-2">
                                                        <FileInput @change="onNormalMapFileChange($event, index)" />
                                                    </div>
                                                    <div class="text-xs">
                                                        <FileInfo
                                                            :original-filename="normal_map.original_filename"
                                                            :url="normal_map.url"
                                                            :file="normal_map.file"
                                                            @delete-file="onNormalMapDeleteFile(index)"
                                                            @clear-file="onNormalMapClearFile(index)"
                                                        />
                                                    </div>
                                                </div>
                                                <InputError :message="errors[`normal_maps.${index}.file`]" />
                                            </DragAndDrop>
                                        </td>
                                        <td class="border p-2 text-nowrap">
                                            <DeleteButton size="xs" @click="onDeleteNormalMapClick(normal_map, index)">削除</DeleteButton>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <InputError :message="errors['normal_maps']" />
                            </ResponsiveTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <FooterButtons
            :is-preparing="footerIsPreparing"
            @yes-click="onPrepareSubmit"
            @no-click="onCancel">
            <template v-slot:left>
                <SubButton size="md" :href="route('admin.material.input')" @click="onCancel">新規追加</SubButton>
            </template>
        </FooterButtons>

        <div v-if="isLoading">
            <LoadingModal :show="isLoading" />
        </div>

    </AuthenticatedLayout>

</template>
