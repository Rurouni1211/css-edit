<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {computed, nextTick, onMounted, ref, watch} from "vue";
import { getSketchfabClient } from "@/Utils/Sketchfab.js";
import LoadingModal from "@/Components/LoadingModal.vue";
import {getErrorMessages, scrollToElement, scrollToFirstError} from "@/Utils/Ajax.js";
import FooterButtons from "@/Components/FooterButtons.vue";
import NormalButton from "@/Components/Buttons/NormalButton.vue";
import NumberFormatLabel from "@/Components/NumberFormatLabel.vue";
import _ from "lodash";
import SearchableSelect from "@/Components/SearchableSelect.vue";
import InputDescription from "@/Components/InputDescription.vue";
import SubButton from "@/Components/Buttons/SubButton.vue";
import ResponsiveTable from "@/Components/ResponsiveTable.vue";
import Swal from "sweetalert2";

// Common
const props = defineProps({
    product: {
        type: Object,
        default() {
            return {};
        },
    },
    shops: {
        type: Array,
        required: true,
    },
    productCategories: {
        type: Array,
        required: true,
    },
    materials: {
        type: Array,
        required: true,
    },
    componentGroups: { // 存在する全てのコンポーネントデータ
        type: Array,
        required: true,
    },
    materialCombinationTypes: {
        type: Array,
        required: true,
    },
    allComponentGroupTypeKeys: {
        type: Object,
        required: true,
    }
});
const isLoading = ref(false);
const isModeCreate = computed(() => {

    return Number(props.product.id) === 0;

});
const isModeEdit = computed(() => {

    return Number(props.product.id) > 0;

});
onMounted(() => {

    initSketchfabClient();

    if(isModeEdit.value === true) { // Edit

        const product = props.product;

        params.value.product = {
            category_id: product.category_id || '',
            name: product.name || '',
            product_code: product.product_code || '',
            shop_id: product.shop_id || '',
            sort_number: (product.sort_number >= 0) ? product.sort_number: '',
            sketchfab_model_key: product.sketchfab_model_key || '',
            material_combination_type: product.material_combination_type || '',
        };
        params.value.components = product.components.map((component) => {

            return {
                key: component.key,
                groupKey: component.group_key,
                name: component.name,
                is_active: component.is_active,
                materials: component.materials,
            };

        });

        const componentKeys = product.components.map((component) => {

            return component.key;

        });
        const filteredComponentGroups = props.componentGroups.filter((componentGroup) => {

            return componentKeys.includes(componentGroup.value);

        });

        setComponents(filteredComponentGroups);

    }

});

// Form
const defaultParams = {
    product: {
        category_id: '',
        name: '',
        product_code: '',
        shop_id: '',
        sort_number: '',
        sketchfab_model_key: '',
        material_combination_type: '',
    },
    components: [],
};
const params = ref(_.cloneDeep(defaultParams));
const errors = ref({});
const isShowingNumberFormatLabel = ref(false);
let lastParamsComponents = [];
const clearParams = () => {

    params.value = _.cloneDeep(defaultParams);
    errors.value = {};
    isShowingSketchfabViewer.value = false;

};
const setLastParamsComponents = () => {

    if(params.value.components.length > 0) {

        lastParamsComponents = _.cloneDeep(params.value.components);

    }

};
const onSketchfabModelKeyInput = () => {

    setLastParamsComponents();
    isShowingSketchfabViewer.value = false;

};
const onExtractSketchfabKey = () => {

    const message = 'URLを入力してください。';
    const label = 'SketchfabのURL';
    Swal.input(message, label)
        .then((result) => {

            if(result.isConfirmed) {

                const url = route('admin.product.extract_unique_id');
                const data = { url: result.value };

                axios.post(url, data)
                    .then((response) => {

                        params.value.product.sketchfab_model_key = response.data.unique_id;

                        nextTick(() => {

                            onRetrieveSketchfabModel();

                        });

                    })
                    .catch(() => {

                        Swal.error('ユニークIDの取得に失敗しました。');

                    });

            }

        });

};
const onSubmit = () => {

    Swal.confirm('normal')
        .then(result => {

            if (result.isConfirmed) {

                isLoading.value = true;
                const data = params.value;
                let filteredData = {
                    _method: 'POST',
                    product: data.product,
                    components: data.components.map((component) => {

                        return {
                            key: component.key,
                            is_active: component.is_active,
                            materials: component.materials.map((material) => {

                                return {
                                    material_id: material.material_id,
                                    is_active: material.is_active,
                                    amount: material.amount,
                                    colors: material.colors.map((color) => {

                                        return {
                                            material_color_id: color.material_color_id,
                                            is_active: color.is_active,
                                        };

                                    }),
                                    normal_maps: material.normal_maps.map((normalMap) => {

                                        return {
                                            material_normal_map_id: normalMap.material_normal_map_id,
                                            is_active: normalMap.is_active,
                                        };

                                    }),
                                };

                            }),
                        };

                    }),
                };
                let url = '';

                if(isModeEdit.value === true) {

                    url = route('admin.product.update', props.product.id);
                    filteredData._method = 'PUT';

                } else {

                    url = route('admin.product.store');

                }

                axios.post(url, filteredData)
                    .then((response) => {

                        if(response.data.result === true) {

                            if(isModeCreate.value === true) { // Create

                                clearParams();

                            }

                            errors.value = {};

                            Swal.success();

                        } else {

                            Swal.error();

                        }

                    })
                    .catch((error) => {

                        componentKeyword.value = '';
                        errors.value = getErrorMessages(error);
                        Swal.error();

                        nextTick(() => {

                            scrollToFirstError();

                        });

                    })
                    .finally(() => isLoading.value = false);

            }

        });

};

// Components
const componentSearchInput = ref();
const setComponents = (componentGroups) => {

    let components = [];

    componentGroups.forEach((componentGroup) => {

        const componentKey = componentGroup.value;

        if(retrievingModelType === 'keep_data') { // 構成パーツ設定を保持する場合

            const targetComponent = lastParamsComponents.find((component) => {

                return component.key === componentKey;

            });

            if(targetComponent) {

                components.push(targetComponent);

            }

        } else {

            const componentName = componentGroup.label;
            const componentGroupKey = componentGroup.group_key;
            const targetComponent = getTargetComponent(componentKey);
            let componentMaterials = [];

            props.materials.forEach((material, index) => {

                if(index > 0 && componentGroupKey === 'logo') return; // ロゴパーツの素材は1つだけなので、indexが1以上は不要

                const targetComponentMaterial = (retrievingModelType !== 'none')
                    ? null
                    : getTargetComponentMaterial(targetComponent, material);
                const componentColors = material.colors
                    .map((color) => {

                        const targetComponentMaterialColor = getTargetComponentMaterialColor(targetComponentMaterial, color);
                        return {
                            material_color_id: color.id,
                            name: color.name,
                            is_active: _.get(targetComponentMaterialColor, 'is_active', false),
                        };

                    });
                const componentNormalMaps = material.normal_maps
                    .map((normalMap) => {

                        const targetComponentMaterialNormalMap = getTargetComponentMaterialNormalMap(targetComponentMaterial, normalMap);
                        return {
                            material_normal_map_id: normalMap.id,
                            name: normalMap.name,
                            is_active: _.get(targetComponentMaterialNormalMap, 'is_active', false),
                        };

                    });

                componentMaterials.push({
                    material_id: material.id,
                    name: material.name,
                    is_active: _.get(targetComponentMaterial, 'is_active', false),
                    amount: _.get(targetComponentMaterial, 'amount', ''),
                    colors: componentColors,
                    normal_maps: componentNormalMaps,
                });

            });

            components.push({
                key: componentKey,
                name: componentName,
                group_key: componentGroupKey,
                is_active: _.get(targetComponent, 'is_active', true),
                materials: componentMaterials
            });

        }

    });

    params.value.components = components;
    retrievingModelType = 'none';

};
const getTargetComponent = (checkingKey) => {

    return params.value.components.find((component) => {

        return component.key === checkingKey;

    });

};
const onComponentSearchClear = () => {

    componentKeyword.value = '';
    componentSearchInput.value.focus();

};
const components = computed(() => {

    return params.value.components;

});
const filteredComponents = computed(() => {

    return components.value
        .filter((component) => {

            const checkingKeyword = componentKeyword.value.toLowerCase();
            const keyword1 = component.name;
            const keyword2 = component.key.toLowerCase();

            return (
                keyword1.includes(checkingKeyword) ||
                keyword2.includes(checkingKeyword)
            );

        });

});
const hasComponents = computed(() => {

    return components.value.length > 0;

});

// Component materials
const getTargetComponentMaterial = (component, material = null) => {

    if(! component || ! material) { // タイミングによっては、nullがはいることがある

        return null;

    }

    if(component.groupKey === 'logo') { // ロゴパーツは1つのみ

        return component.materials[0];

    }

    if(component && component.materials) {

        return component.materials.find((checkingMaterial) => {

            return Number(checkingMaterial.material_id) === Number(material.id);

        });

    }

    return null;

};
const hasIsActiveMaterial = (componentMaterials) => {

    return componentMaterials.some((material) => {

        return material.is_active;

    });

};
const getIsActiveMaterial = (component, material) => {

    const componentMaterial = getTargetComponentMaterial(component, material);

    return _.get(componentMaterial, 'is_active', false);

};
const onIsActiveMaterialChange = (component, material) => {

    let componentMaterial = getTargetComponentMaterial(component, material);
    componentMaterial.is_active = ! componentMaterial.is_active;

};
const getMaterialAmount = (component, material) => {

    const componentMaterial = getTargetComponentMaterial(component, material);

    return _.get(componentMaterial, 'amount', '');

};
const onMainMaterialAmountInput = (event, component, material) => {

    const componentMaterial = getTargetComponentMaterial(component, material);
    componentMaterial.amount = Number(event.target.value);

};
const onLogoMaterialAmountInput = (event, component) => {

    component.materials[0].amount = Number(event.target.value);

};

// Component color
const getTargetComponentMaterialColor = (componentMaterial, color) => {

    if(componentMaterial && componentMaterial.colors) {

        return componentMaterial.colors.find((checkingColor) => {

            return Number(checkingColor.material_color_id) === Number(color.id);

        });

    }

    return null;

};
const getIsActiveMaterialColor = (component, material, color) => {

    const componentMaterial = getTargetComponentMaterial(component, material);
    const componentMaterialColor = getTargetComponentMaterialColor(componentMaterial, color);

    return _.get(componentMaterialColor, 'is_active', false);

};
const onIsActiveMaterialColorChange = (component, material, color) => {

    const componentMaterial = getTargetComponentMaterial(component, material);
    const componentMaterialColor = getTargetComponentMaterialColor(componentMaterial, color);

    componentMaterialColor.is_active = ! componentMaterialColor.is_active;

};

// Component Normal map
const getTargetComponentMaterialNormalMap = (componentMaterial, normalMap) => {

    if(componentMaterial && componentMaterial.normal_maps) {

        return componentMaterial.normal_maps.find((checkingNormalMap) => {

            return Number(checkingNormalMap.material_normal_map_id) === Number(normalMap.id);

        });

    }

    return null;

};
const getIsActiveMaterialNormalMap = (component, material, normalMap) => {

    const componentMaterial = getTargetComponentMaterial(component, material);
    const componentMaterialNormalMap = getTargetComponentMaterialNormalMap(componentMaterial, normalMap);

    return _.get(componentMaterialNormalMap, 'is_active', false);

};
const onIsActiveMaterialNormalMapChange = (component, material, normalMap) => {

    const componentMaterial = getTargetComponentMaterial(component, material);

    componentMaterial.normal_maps.forEach((normalMap) => {

        normalMap.is_active = false;

    });

    nextTick(() => {

        const componentMaterialNormalMap = getTargetComponentMaterialNormalMap(componentMaterial, normalMap);
        componentMaterialNormalMap.is_active = true;

    });

};

// 素材のコピー
const isMainComponentKey = (componentKey) => {

    const mainComponentKeys = props.allComponentGroupTypeKeys.main;

    return mainComponentKeys.includes(componentKey);

};
const onMaterialCopyClick = (component) => {

    const copyingComponentKeys = props.allComponentGroupTypeKeys.main;
    const options = {};

    copyingComponentKeys.forEach((key) => {

        const targetComponent = getTargetComponent(key);

        if(targetComponent) {

            options[key] = `${targetComponent.name}（${targetComponent.key}）`;

        }

    });

    Swal.fire({
        title: '素材構成をコピー',
        input: 'select',
        inputOptions: options,
        inputPlaceholder: 'コピー元の構成パーツを選ぶ',
        showCancelButton: true,
        confirmButtonText: '実行',
        cancelButtonText: 'キャンセル',
        reverseButtons: true,
    })
    .then(function(result) {
        if (result.isConfirmed) {

            if(! result.value) {

                Swal.error('コピー元の構成パーツを選択してください。');

            } else {

                const selectedKey = result.value;
                const copiedComponent = getTargetComponent(component.key);
                const copyingComponent = getTargetComponent(selectedKey);

                copiedComponent.materials = _.cloneDeep(copyingComponent.materials);

            }

        }
    });

};

// Sketchfab
let sketchfabClient = null;
let retrievingModelType = 'none';
const isRetrievingSketchfabModel = ref(false);
const isShowingSketchfabViewer = ref(false);
const initSketchfabClient = () => {

    const iframe = document.getElementById('sketchfab-iframe');
    sketchfabClient = getSketchfabClient(iframe);

};
const initSketchfabViewer = (modelKey) => {

    if(sketchfabClient) {

        isRetrievingSketchfabModel.value = true;

        sketchfabClient.init(modelKey, {
                success(api) {

                    initSketchfabViewerSuccess(api);

                },
                error() {

                    Swal.error('データ取得できませんでした。Sketchfab IDを確認してください。');
                    isRetrievingSketchfabModel.value = false;

                },

            }
        );

    }

};
const initSketchfabViewerSuccess = (api) => {

    api.start();
    api.addEventListener('viewerready', () => {

        api.getMaterialList((err, materials) => { // マテリアルの取得

            if (! err) {

                const componentGroups = getActiveComponentGroups(materials);
                setComponents(componentGroups);

                isShowingSketchfabViewer.value = true;
                isRetrievingSketchfabModel.value = false;

            }

        });

    });

};
const onRetrieveSketchfabModel = () => {

    setLastParamsComponents();
    const sketchfabModelKey = params.value.product.sketchfab_model_key;

    if(! sketchfabModelKey) {

        Swal.error('Sketchfab ユニークIDを入力してください。');
        return;

    }

    Swal.fire({
        title: '確認',
        text: '現在の「構成パーツ設定」はどうしますか？',
        showDenyButton: true,
        showCancelButton: true,
        denyButtonText: '破棄する',
        confirmButtonText: '保持したままにする',
        cancelButtonText: '閉じる',
        reverseButtons: true
    })
    .then((result) => {

        if(result.isDenied || result.isConfirmed) {

            if(result.isDenied) {

                retrievingModelType = 'clear_data';

            } else if(result.isConfirmed) {

                retrievingModelType = 'keep_data';

            }

            initSketchfabViewer(sketchfabModelKey);

        }

    });

}
const componentKeyword = ref('');
const isComponentKeywordTyping = ref(false);
const getActiveComponentGroups = (materials) => { // 構成パーツを取得する

    let componentGroups = [];

    props.componentGroups.forEach((materialGroup) => {

        const groupKey = materialGroup.value;

        for(const key in materials) {

            const material = materials[key];
            const materialName = material.name;

            if(materialName.includes(groupKey)) {

                componentGroups.push(materialGroup);

            }

        }

    });

    return _.uniq(componentGroups);
};
watch(
    () => componentKeyword.value,
    (newValue) => {

        if(! newValue) return;

        isComponentKeywordTyping.value = false;

        setTimeout(() => {

            if(isComponentKeywordTyping.value === false) {

                const el = document.querySelector('#table');
                scrollToElement(el);

            }

        }, 1000);

    }
);

// Confirmation button
const showConfirm = computed(() => {

    return isModeEdit.value === true;

});

// Others
const onCancel = () => {

    Swal.confirm('cancel', 'もし入力した内容がある場合、破棄されます。<br>よろしいですか？')
        .then(result => {

            if (result.isConfirmed) {

                router.visit(route('admin.product.index'));

            }

        });

};
const onConfirmClick = () => {

    window.open(props.product.detail_url, '_blank');

};

</script>

<style>

    @media (max-width: 640px) {
        #table {
            min-width: 1000px;
        }
    }

</style>

<template>
    <Head title="商品追加・編集（360°VR）" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-indigo-50 leading-tight">商品追加・編集（360°VR）</h2>
        </template>

        <div class="sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="container mx-auto p-5 sm:p-10 text-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div>
                                <div class="sm:mb-5">
                                    <InputLabel value="商品名" />
                                    <TextInput
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="params.product.name"
                                        autocomplete="name"
                                    />
                                    <InputError :message="errors['product.name']" />
                                </div>
                                <div class="mb-5">
                                    <InputLabel value="商品コード" sub-title="（重複しない文字列）" />
                                    <TextInput
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="params.product.product_code"
                                        autocomplete="product_code"
                                    />
                                    <InputError :message="errors['product.product_code']" />
                                </div>
                                <div class="mb-5">
                                    <InputLabel value="販売店舗" />
                                    <SearchableSelect :items="props.shops" v-model="params.product.shop_id" />
                                    <InputError :message="errors['product.shop_id']" />
                                </div>
                                <div class="mb-4">
                                    <InputLabel value="並び順" />
                                    <TextInput
                                        type="text"
                                        class="mt-1 block"
                                        v-model="params.product.sort_number"
                                        autocomplete="product_code"
                                    />
                                    <InputDescription>
                                        店舗内での表示順。数字が大きいほど上に表示されます。
                                    </InputDescription>
                                    <InputError :message="errors['product.sort_number']" />
                                </div>
                                <div class="flex">
                                    <div>
                                        <InputLabel value="Sketchfab ID" />
                                    </div>
                                    <div class="flex-grow text-xs pt-1">
                                        （<a href="#" class="text-indigo-800 hover:underline text-xs" @click="onExtractSketchfabKey">URLから取得</a>）
                                    </div>
                                </div>
                                <div class="md:flex mb-5 md:mb-0">
                                    <div>
                                        <TextInput
                                            type="text"
                                            class="mt-0 mr-1 w-72"
                                            v-model="params.product.sketchfab_model_key"
                                            @input="onSketchfabModelKeyInput"
                                        />
                                        <InputError :message="errors['product.sketchfab_model_key']" />
                                    </div>
                                    <div class="flex-grow">
                                        <NormalButton
                                            size="sm"
                                            class="mt-0.5 ml-0.5"
                                            @click="onRetrieveSketchfabModel">モデル取得</NormalButton>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-5">
                                    <InputLabel value="カテゴリ" />
                                    <div class="text-sm">
                                        <div class="mb-1.5" v-for="category in productCategories">
                                            <label class="flex items-center">
                                                <input type="radio" v-model="params.product.category_id" :value="category.id" />
                                                <span class="ml-2" v-text="category.name"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <InputError :message="errors['product.category_id']" />
                                </div>
                                <div class="mb-10">
                                    <InputLabel value="素材組み合わせ" />
                                    <div class="text-sm">
                                        <div class="mb-1.5" v-for="type in materialCombinationTypes">
                                            <label class="flex items-center">
                                                <input type="radio" v-model="params.product.material_combination_type" :value="type.value" />
                                                <span class="ml-2" v-text="type.label"></span>：<small class="ml-2" v-text="type.description"></small>
                                            </label>
                                        </div>
                                    </div>
                                    <InputError :message="errors['product.material_combination_type']" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <div class="my-5" v-show="isShowingSketchfabViewer">
                                <iframe src="" id="sketchfab-iframe" allow="autoplay; fullscreen; xr-spatial-tracking" xr-spatial-tracking execution-while-out-of-viewport execution-while-not-rendered web-share allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
                            </div>
                        </div>
                        <div>
                            <InputLabel :value="`構成パーツ`" :sub-title="`（${filteredComponents.length}/${components.length}件表示）`" />
                        </div>
                        <div class="mb-5">
                            <InputError :message="errors['keys']" />
                            <div v-if="hasComponents">
                                <ResponsiveTable class="mb-4">
                                    <table id="table" class="w-full">
                                        <thead class="text-xs bg-indigo-600">
                                        <tr>
                                            <th class="p-2 border border-gray-300 text-left text-indigo-50 font-bold text-nowrap text-xs">名前</th>
                                            <th class="p-2 border border-gray-300 text-left text-indigo-50 font-bold text-xs">構成パーツ設定</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(component,index) in filteredComponents" :key="component.key">
                                            <td class="relative border border-gray-300 p-3 align-top text-nowrap w-1/4">
<!--                                                <div class="absolute top-3.5 right-3">-->
<!--                                                    <button-->
<!--                                                        type="button"-->
<!--                                                        size="xs"-->
<!--                                                        class="text-sm bg-gray-200 text-gray-500 rounded-full px-2 py-1"-->
<!--                                                        v-if="isMainComponentKey(component.key)"-->
<!--                                                        @click="onMaterialCopyClick(component)">-->
<!--                                                        <i class="fa fa-copy"></i>-->
<!--                                                    </button>-->
<!--                                                </div>-->
                                                <label class="flex">
                                                    <!--
                                                    <div class="pr-1">
                                                        <input type="checkbox" class="w-5 h-5 transform scale-90" v-model="component.is_active">
                                                    </div>
                                                    -->
                                                    <div class="text-base pt-1">
                                                        <div>{{ component.name }}</div><div class="text-xs pl-0.5 text-gray-500">{{ component.key }}</div>
                                                    </div>
                                                </label>
                                                <InputError :message="errors[`components.${index}.is_active`]" />
                                            </td>
                                            <td class="border border-gray-300 text-xs align-top p-3 pb-6 w-full">
                                                <!-- ロゴパーツ： App\Enums\ComponentGroupTypeを参照 -->
                                                <template v-if="component.group_key === 'logo'">
                                                    <div class="flex p-2">
                                                        <div class="relative">
                                                            <NumberFormatLabel
                                                                class="absolute"
                                                                size="sm"
                                                                style="top:9px;right:9px;"
                                                                v-if="isShowingNumberFormatLabel"
                                                                :value="component.materials[0].amount" />
                                                            <input
                                                                type="number"
                                                                class="mt-1 mr-1 block w-42 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1"
                                                                :value="component.materials[0].amount"
                                                                min="0"
                                                                @input="onLogoMaterialAmountInput($event, component)"
                                                                @focus="isShowingNumberFormatLabel = true"
                                                                @blur="isShowingNumberFormatLabel = false"/>
                                                        </div>
                                                        <div class="pt-3 text-xs">
                                                            円
                                                        </div>
                                                    </div>
                                                    <InputError :message="errors[`components.${index}`]" />
                                                    <InputError :message="errors[`components.${index}.materials.0`]" />
                                                </template>
                                                <!-- メそれ以外のパーツ： App\Enums\ComponentGroupTypeを参照 -->
                                                <template v-else>
                                                    <div class="flex flex-wrap px-6 pt-3 pb-1">
                                                        <div class="w-1/4 my-1" v-for="material in materials" :key="material.material_id">
                                                            <label class="flex items-center">
                                                                <input
                                                                    type="checkbox"
                                                                    :checked="getIsActiveMaterial(component, material)"
                                                                    @change="onIsActiveMaterialChange(component, material)">
                                                                <span class="ml-1 font-bold" v-text="material.name"></span>
                                                            </label>
                                                        </div>
                                                        <InputError class="w-full" :message="errors[`components.${index}`]" />
                                                    </div>
                                                    <div v-for="(material,index2) in materials" :key="material.id" v-if="hasIsActiveMaterial(component.materials)">
                                                        <div class="text-sm px-4 pt-4" v-if="getIsActiveMaterial(component, material)">
                                                            <div class="bg-indigo-600 text-indigo-50 px-3 pt-1.5 pb-2 text-xs rounded-t font-bold">
                                                                {{ material.name }}
                                                            </div>
                                                            <div class="mt-0 border-gray-300 border-b border-r border-l shadow-md">
                                                                <div class="bg-gray-100 border-b border-indigo-200">
                                                                    <div class="flex px-2 pt-1.5 pb-2.5">
                                                                        <div class="relative">
                                                                            <NumberFormatLabel
                                                                                class="absolute"
                                                                                size="sm"
                                                                                style="top:9px;right:9px;"
                                                                                v-if="isShowingNumberFormatLabel"
                                                                                :value="getMaterialAmount(component, material)" />
                                                                            <input
                                                                                type="number"
                                                                                class="mt-1 mr-1 block w-42 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1"
                                                                                :value="getMaterialAmount(component, material)"
                                                                                min="0"
                                                                                @input="onMainMaterialAmountInput($event, component, material)"
                                                                                @focus="isShowingNumberFormatLabel = true"
                                                                                @blur="isShowingNumberFormatLabel = false"/>
                                                                        </div>
                                                                        <div class="pt-3 text-xs">
                                                                            円
                                                                        </div>
                                                                    </div>
                                                                    <InputError :message="errors[`components.${index}.materials.${index2}`]" />
                                                                </div>
                                                                <div class="pt-4 pb-5 px-4 bg-white">
                                                                    <InputLabel value="カラー" class="ml-1 text-xs" />
                                                                    <div class="flex flex-wrap pl-1">
                                                                        <div class="w-1/4" v-for="color in material.colors" :key="color.id">
                                                                            <label class="flex items-center">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    class="mr-1"
                                                                                    :checked="getIsActiveMaterialColor(component, material, color)"
                                                                                    @change="onIsActiveMaterialColorChange(component, material, color)">
                                                                                {{ color.name }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <InputError :message="errors[`components.${index}.materials.${index2}.colors`]" />
                                                                </div>
                                                                <div class="pb-5 px-4 bg-white">
                                                                    <!-- 本来はラジオボタンだが、運用で対応するのでチェックボックスでいいとの回答 -->
                                                                    <!-- ただし、ご入力が心配なため、念のためチェックはひとつしかできなくしておく -->
                                                                    <InputLabel value="ノーマルマップ" class="ml-1 text-xs" />
                                                                    <div class="flex flex-wrap pl-1">
                                                                        <div class="w-1/3" v-for="normalMap in material.normal_maps" :key="normalMap.id">
                                                                            <label class="flex items-center">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    class="mr-1"
                                                                                    :checked="getIsActiveMaterialNormalMap(component, material, normalMap)"
                                                                                    @change="onIsActiveMaterialNormalMapChange(component, material, normalMap)">
                                                                                {{ normalMap.name }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <InputError :message="errors[`components.${index}.materials.${index2}.normal_maps`]" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </td>
                                        </tr>
                                        <tr v-if="filteredComponents.length === 0">
                                            <td class="border border-gray-300 pl-3 pr-6 py-2 align-top text-nowrap" colspan="2">
                                                <div class="text-sm text-yellow-700">
                                                    パーツが見つかりません。
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </ResponsiveTable>
                            </div>
                            <div class="text-sm text-yellow-700" v-else>
                                パーツがありません。<br>
                                モデルデータを取得してください。
                            </div>
                        </div>
                        <InputError :message="errors['components']" />
                    </div>
                </div>
            </div>
        </div>

        <FooterButtons
            :show-confirm="showConfirm"
            @yes-click="onSubmit"
            @no-click="onCancel"
            @confirm-click="onConfirmClick">
            <template v-slot:left>
                <SubButton size="md" :href="route('admin.product.input')" @click="onCancel">新規追加</SubButton>
                <div class="mb-1 inline-flex relative mx-3 my-1" v-if="hasComponents">
                    <TextInput
                        ref="componentSearchInput"
                        type="text"
                        class="mt-1 block w-52 mb-1"
                        placeholder="構成パーツ・絞り込み"
                        v-model="componentKeyword" />
                    <div class="absolute right-2 top-1" v-if="componentKeyword">
                        <a href="#" class="text-gray-500 text-2xl" @click.prevent="onComponentSearchClear">&times;</a>
                    </div>
                </div>
            </template>
        </FooterButtons>
        <LoadingModal :show="isLoading" />
        <LoadingModal :show="isRetrievingSketchfabModel">読み込み中...</LoadingModal>

    </AuthenticatedLayout>

</template>
