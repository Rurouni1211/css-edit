<script setup>

import MaterialButton from "@/Components/Buttons/MaterialButton.vue";
import ColorButton from "@/Components/Buttons/ColorButton.vue";
import {Head} from "@inertiajs/vue3";
import {computed, nextTick, onMounted, ref, watch} from "vue";
import {getSketchfabColor, getTargetMaterials, initSketchfab} from "@/Mixins/SketchfabMixin.js";
import BaseButton from "@/Components/Buttons/BaseButton.vue";
import YesNoButtons from "@/Components/Store/YesNoButtons.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import VerticalSeparator from "@/Components/UI/VerticalSeparator.vue";
import axios from "axios";
import LoadingModal from "@/Components/LoadingModal.vue";
import Swal from "sweetalert2";

const props = defineProps({
    product: {
        type: Object,
    },
    materialCombinationGroups: {
        type: Array,
    },
});
const product = props.product;
const materialCombinationGroups = props.materialCombinationGroups;
const productMaterialCombinationGroups = product.material_combination.groups;

// Common
onMounted(() => {

    const el = document.getElementById('api-frame');
    const uid = product.sketchfab_model_key;
    initSketchfab(el, uid)
        .then(setSketchfabApi);

});

// Scroll
const resetScroll = (elementId) => {

    const scrollSection = document.getElementById(elementId);

    if(scrollSection) {

        scrollSection.scrollTo({ left: 0 });

    }

};
const onScrollButtonClick = (elementId, direction) => {

    const scrollSection = document.getElementById(elementId);
    const scrollAmount = 150;
    const maxScrollLeft = scrollSection.scrollWidth - scrollSection.clientWidth;

    if (direction === 'left' && scrollSection.scrollLeft > 0) {

        scrollSection.scrollBy({ left: -scrollAmount, behavior: 'smooth' });

    } else if (direction === 'right' && scrollSection.scrollLeft < maxScrollLeft) {

        scrollSection.scrollBy({ left: scrollAmount, behavior: 'smooth' });

    }

};

// Sketchfab
let sketchfabApi = {};
let materials = [];
let nodes = [];
const setSketchfabApi = (data) => {

    sketchfabApi = data.api;
    materials = data.materials;
    nodes = data.nodes;

};

// Product
const loading = ref(false);
const errorMessage = ref('');
const onSave = () => {
    Swal.confirm('normal', 'この内容で確定してもよろしいですか？')
        .then((result) => {
            if (result.isConfirmed) {
                loading.value = true;
                errorMessage.value = '';

                const url = route('store.product.store');
                const data = {
                    productId: product.id,
                    components: params.value,
                };
                axios.post(url, data)
                    .then(response => {
                        if (response.data.result === true) {

                            const orderUniqueId = response.data.order_unique_id;
                            window.location.href = route('store.product.complete', { orderUniqueId });

                        }
                    })
                    .catch(error => {
                        const errors = error.response.data.errors;
                        const messages = [];
                        for (const key in errors) {
                            const errorMessages = errors[key];
                            for(const error of errorMessages) {
                                messages.push(`・${error}`);
                            }
                        }
                        Swal.fire({
                            title: '入力エラー',
                            html: `<div class="text-left text-sm">${messages.join('<br>')}</div>`,
                            icon: 'error',
                        });
                    })
                    .finally(() => {
                        loading.value = false;
                    });
            }
        });
};
const productTitle = computed(() => {

    return `${product.name} - ${product.material_combination.label}`;

});

// Material Group(Component)
const currentGroupKey = ref('');
const inputType = ref('');
const currentGroupName = computed(() => {
    const group = productMaterialCombinationGroups.find((group) => {
        return group.key === currentGroupKey.value;
    });

    return _.get(group, 'label', '');
});
const onMaterialGroupClick = (group) => {

    const key = group.key;
    const requiredInputs = group.required_inputs;
    resetScroll('material-section');
    currentGroupKey.value = key;

    if(requiredInputs.includes('color_id') && ! requiredInputs.includes('material_id')) { // 色の選択だけの場合

        onMaterialClick({
            material: {
                id: group.materials[0].id,
                key: group.materials[0].key,
            }
        });

    }

};
onMounted(() => {

    currentGroupKey.value = productMaterialCombinationGroups[0].key;

});

// Params
const getDefaultParams = () => {

    let defaultParams = {};

    productMaterialCombinationGroups.forEach((group) => {

        const defaultMaterial = '';
        const defaultColor = '';

        defaultParams[group.key] = {
            material: defaultMaterial,
            color: defaultColor,
            componentGroupKeys: group.component_groups,
            logo: null,
        };

    });

    return defaultParams;

};
const params = ref(getDefaultParams());
const currentMaterialId = computed(() => {

    const groupKey = currentGroupKey.value;
    return _.get(params.value, `${groupKey}.material.id`, '');

});
const currentColorId = computed(() => {

    const groupKey = currentGroupKey.value;
    return _.get(params.value, `${groupKey}.color.id`, '');

});
const isComponentSelected = (group) => {

    const requiredInputs = group.required_inputs;
    const materialId = _.get(params.value, `${group.key}.material.id`, '');
    const colorId = _.get(params.value, `${group.key}.color.id`, '');
    const logo = _.get(params.value, `${group.key}.logo`, null);

    if(requiredInputs.includes('material_id') && ! materialId) {

        return false;

    } else if(requiredInputs.includes('color_id') && ! colorId) {

        return false;

    } else if(requiredInputs.includes('logo') && logo === null) {

        return false;

    }

    return true;

};

// Material
let additionalTextures = [];
const setMaterial = (material) => {

    sketchfabApi.setMaterial(material, (err) => {

        if(err) console.error('Error setting material:', err);

    });

};
const setAdditionalTexture = (url, uid) => {

    const targetTexture = additionalTextures.find((texture) => {

        return texture.url === url;

    });

    if(! targetTexture) {

        additionalTextures.push({
            url: url,
            uid: uid,
        });

    }

};
const setGlossiness = (material, glossiness) => { // 注：適用はしていないので、setMaterialを呼び出す必要がある

    if(glossiness) {

        material.channels.GlossinessPBR.enable = true;
        material.channels.GlossinessPBR.factor = glossiness;

    }

};
const setSpecular = (material, specular) => { // 注：適用はしていないので、setMaterialを呼び出す必要がある

    if(specular) {

        material.channels.SpecularPBR.enable = true;
        material.channels.SpecularPBR.factor = specular;

    }

};
const setLogo = (node, show) => { // 注：適用はしていないので、setMaterialを呼び出す必要がある

    const instanceId = node.instanceID;

    if(show === true) {

        sketchfabApi.show(instanceId, (err) => {

            if (err) console.error('Error showing node:', err);

        });

    } else {

        sketchfabApi.hide(instanceId, (err) => {

            if (err) console.error('Error showing node:', err);

        });

    }

};
const setNormalMap = (material, url) => {

    const currentNormalMap = _.get(material, 'channels.NormalMap', null);
    material.channels.NormalMap = {
        ...currentNormalMap,
        enable: false,
        texture: null,
    };

    if(url && currentNormalMap) {

        getAdditionTextureUid(url)
            .then((uid) => {

                material.channels.NormalMap.enable = true;
                material.channels.NormalMap.texture = { uid };
                setMaterial(material);

            });

    } else {

        setMaterial(material);

    }

};
const setSpecularMap = (material, url) => {

    const currentSpecularMap = _.get(material, 'channels.SpecularPBR', null);
    material.channels.SpecularPBR = {
        ...currentSpecularMap,
        enable: false,
        texture: null,
    };

    if(url && currentSpecularMap) {

        getAdditionTextureUid(url)
            .then((uid) => {

                material.channels.SpecularPBR.enable = true;
                material.channels.SpecularPBR.texture = { uid };
                setMaterial(material);

            });

    } else {

        setMaterial(material);

    }

};
const getAdditionTextureUid = (url) => {

    return new Promise((resolve, reject) => {

        const targetTexture = additionalTextures.find((texture) => {

            return texture.url === url;

        });

        if(targetTexture) {

            setAdditionalTexture(url, targetTexture.uid);
            resolve(targetTexture.uid);

        } else {

            sketchfabApi.addTexture(url, (err, textureUid) => {

                if(err) reject(err);

                setAdditionalTexture(url, textureUid);
                resolve(textureUid);

            });

        }

    });

};
let lastColorIds = {};
const onMaterialClick = (e) => {

    getAmount();
    const groupKey = currentGroupKey.value;
    const materialId = e.material.id;
    const materialKey = e.material.key;
    params.value[groupKey].material = {
        id: materialId,
        key: materialKey,
    };
    params.value[currentGroupKey.value].color = null;

    const targetMaterial = currentMaterials.value.find((material) => {

        return material.key === e.material.key;

    });

    if(targetMaterial) {

        // Normal map & Specular Map
        const targetMaterials = getTargetMaterials(materials, materialCombinationGroups, groupKey);

        targetMaterials.forEach((material) => {

            // Glossiness
            setGlossiness(material, targetMaterial.glossiness);

            // Specular
            setSpecular(material, targetMaterial.specular);

            // Normal Map
            const normalMapUrl = _.get(targetMaterial, 'normal_map.url', null);
            setNormalMap(material, normalMapUrl);

            // Specular Map
            const specularMapUrl = _.get(targetMaterial, 'specular_map.url', null);
            setSpecularMap(material, specularMapUrl);

        });

    }

    resetScroll('color-section');

};
const currentMaterials = computed(() => {

    const currentGroup = product
        .material_combination
        .groups
        .find((group) => {

            return group.key === currentGroupKey.value;

        });

    if(currentGroup) {

        return currentGroup.materials;

    }

    return [];

});

// Color
const setNewDiffusePBR = (material, newData) => {

    material.channels.DiffusePBR = {
        ...material.channels.DiffusePBR,
        ...newData,
    };
    setMaterial(material);

};
const onColorClick = (e) => {

    getAmount();
    params.value[currentGroupKey.value].color = {
        id: e.color.id,
        key: e.color.color_code,
        url: e.color.texture_url,
    };
    const groupKey = currentGroupKey.value;
    const targetMaterials = getTargetMaterials(materials, materialCombinationGroups, groupKey);

    // 最後に選択したカラーを保存
    const materialKey = params.value[groupKey].material.key;
    _.set(lastColorIds, `${groupKey}.${materialKey}`, e.color);

    targetMaterials.forEach((material) => {

        const textureUrl = e.color.texture_url;

        if(textureUrl) { // テクスチャで指定

            getAdditionTextureUid(textureUrl)
                .then((textureUid) => {

                    setNewDiffusePBR(material, {
                        color: null,
                        texture: {
                            uid: textureUid
                        },
                    });

                });

        } else {

            const color = getSketchfabColor(e.color.color_code);
            setNewDiffusePBR(material, {
                color: color,
                texture: null,
            });

        }

    });

};
const currentColors = computed(() => {

    const targetColor = currentMaterials.value.find((material) => {

        return material.key === params.value[currentGroupKey.value].material.key;

    });

    if(targetColor) {

        return targetColor.colors;

    }

    return [];

});

// Logo
const onLogoChange = (e) => {

    getAmount();
    const isLogoEnabled = e.value;
    const groupKey = currentGroupKey.value;
    params.value[groupKey].logo = isLogoEnabled;

    const targetMaterial = materials.find((material) => {

        return material.name === groupKey;

    });

    if(targetMaterial) {

        const targetMaterialId = targetMaterial.id;
        const targetNode = Object.values(nodes).find((node) => {

            return node.materialID === targetMaterialId;

        });

        if(targetNode) {

            setLogo(targetNode, isLogoEnabled);

        }

    }

};
const currentLogo = computed(() => {

    const key = currentGroupKey.value;
    // TODO: バックエンドから取得
    const logoTypes = [
        'Inside_LogoMetal',
        'Outside_LogoMetal',
    ];

    if(logoTypes.includes(key)) {

        return params.value[key].logo;

    }

    return null;

});

// Amount
const amounts = ref({});
const getAmount = () => {

    nextTick(() => {

        isStateUpdating.value = true;
        const url = route('store.product.amounts');
        const data = {
            productId: product.id,
            components: params.value,
        };
        axios.post(url, data)
            .then((response) => {

                amounts.value = response.data.amounts;

            })
            .catch(() => {

                Swal.error('商品の構成にエラーがあるため、合計金額を取得できません。');

            })
            .finally(() => {

                isStateUpdating.value = false;

            });

    });

};
const totalAmount = computed(() => {

    const total = _.sum(Object.values(amounts.value));
    return `￥${total.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,')}`;

});

// Updating modal
const isStateUpdating = ref(false);
watch(
    () => currentGroupKey.value,
    (value) => {

        isStateUpdating.value = true;
        const url = route('store.product.group_type', { key: value });
        axios.get(url)
            .then((response) => {

                inputType.value = response.data.group_type;

            })
            .catch((error) => {

                console.error('Error fetching input type:', error);

            })
            .finally(() => {

                isStateUpdating.value = false;

            });

    }
);
</script>

<style scoped>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<template>

    <Head :title="product.name" />
    <GuestLayout>
        <!---Viewer Point--->
        <div class="w-full min-h-auto flex flex-col mt-[20px]">
            <div class="sm:w-[93%] lg:w-[100%] max-w-[1280px] mx-auto lg:px-7">
                <!-- Product Title and Add to Cart -->
                <div class="flex justify-between">
                    <h1 class="text-base font-bold underline font-shippori text-main-700 md:pl-0.5">{{ productTitle }}</h1>
                    <div class="flex flex-col">
                        <BaseButton size="md" @click="onSave">確定する <i class="fas fa-chevron-right ml-2"></i></BaseButton>
                        <div class="text-md border-b border-main-700 font-shippori">{{ totalAmount }}</div>
                    </div>
                </div>
            </div>

            <div class="sm:w-[93%] lg:w-[100%] max-w-[1280px] mx-auto lg:px-8 mb-32 lg:mb-0 mt-4 text-xs md:text-base">
                <div class="relative">
                    <div class="w-3/5 mx-auto min-h-full flex-col">
                        <!----SketchFab IFRAME-->
                        <div class="w-full bg-gray-100">
                            <iframe
                                src=""
                                id="api-frame"
                                class="w-full h-32 sm:h-60 md:h-80 lg:h-96"
                                allow="autoplay; fullscreen; xr-spatial-tracking" xr-spatial-tracking execution-while-out-of-viewport execution-while-not-rendered web-share allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
                        </div>
                    </div>
                    <div class="absolute w-2/5 top-0">
                        <div class="flex flex-col gap-3">
                            <div class="flex flex-col gap-2">
                                <h2 class="font-semibold underline mb-1 font-shippori text-main-700">PARTS</h2>
                                <template v-for="group in productMaterialCombinationGroups" :key="group.key">
                                    <div class="flex items-center gap-2 mb-1">
                                        <input
                                            type="radio"
                                            :id="'radio-' + group.key"
                                            :name="'parts-radio'"
                                            :checked="currentGroupKey === group.key"
                                            @change="onMaterialGroupClick(group)"
                                            class="cursor-pointer">
                                        <label
                                            :for="'radio-' + group.key"
                                            class="cursor-pointer font-shippori">
                                            {{ group.label }}
                                            <!-- <span class="ml-2 opacity-50" v-if="isComponentSelected(group)">
                                                <i class="fa fa-check-circle text-gray-500"></i>
                                            </span> -->
                                        </label>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <!-- Right Side -->
                    <div class="absolute right-0 top-8 sm:top-24 md:top-36 lg:top-44 w-1/5 flex mb-5 flex-col items-end justify-center">
                        <img src="/images/controller.svg" class="h-[100px] opacity-50" />
                    </div>
                </div>

                <!-- 素材 & 色 -->
                <LoadingModal :show="isStateUpdating">Loading...</LoadingModal>
                <div class="flex flex-col items-center justify-center gap-2 mt-6 mb-10">
                    <div class="flex justify-center">
                        <div class="py-0.5 px-2 bg-main-500 opacity-65 text-xs sm:text-sm text-white rounded text-center font-bold font-shippori shadow-md">{{currentGroupName}}</div>
                    </div>
                    <!---- 素材の選択 -->
                    <template v-if="inputType === 'main' || inputType === 'sub'">
                        <VerticalSeparator class="my-2" />
                        <div class="w-full">
                            <h2 class="underline text-main-800 mb-3 text-center font-shippori font-bold">素材</h2>
                            <div class="w-full flex items-center justify-center gap-5">
                                <div>
                                    <a href="#" class="text-sm text-[#808080] p-2" @click.prevent="onScrollButtonClick('material-section', 'left')">
                                        <img src="/images/scroll-left.svg" class="h-5"/>
                                    </a>
                                </div>
                                <div id="material-section" class="flex items-center gap-8 overflow-x-auto whitespace-nowrap hide-scrollbar w-full">
                                    <div class="relative" v-for="material in currentMaterials" :key="material.key">
                                        <MaterialButton :material="material" :selected="currentMaterialId === material.id" @click="onMaterialClick" />
                                    </div>
                                    <div class="text-sm text-center w-full text-gray-500" style="font-family:'Shippori Micho', serif;" v-if="currentMaterials.length === 0">
                                        素材の種類がありません
                                    </div>
                                </div>
                                <div>
                                    <a href="#" class="text-sm text-[#808080] p-2" @click.prevent="onScrollButtonClick('material-section', 'right')">
                                        <img src="/images/scroll-right.svg" class="h-5"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- 色の選択 -->
                    <VerticalSeparator class="my-2" v-if="inputType !== 'logo'" />
                    <div class="w-full" v-if="inputType !== 'logo'">
                        <h2 class="underline text-main-800 mb-3 text-center font-shippori font-bold">革色</h2>
                        <div class="w-full flex items-center justify-center gap-8">
                            <div>
                                <a href="#" class="text-sm text-[#808080] p-2" @click.prevent="onScrollButtonClick('color-section', 'left')">
                                    <img src="/images/scroll-left.svg" class="h-5"/>
                                </a>
                            </div>
                            <div id="color-section" class="flex items-center gap-6 overflow-x-auto whitespace-nowrap hide-scrollbar w-full pb-4">
                                <template v-for="currentColor in currentColors" :key="currentColor.id">
                                    <ColorButton :color="currentColor" :selected="currentColorId === currentColor.id" @click="onColorClick" />
                                </template>
                                <div class="text-sm text-center w-full text-gray-500" style="font-family:'Shippori Micho', serif;" v-if="currentColors.length === 0">
                                    カラーの種類がありません
                                </div>
                            </div>
                            <div>
                                <a href="#" class="text-sm text-[#808080] p-2" @click.prevent="onScrollButtonClick('color-section', 'right')">
                                    <img src="/images/scroll-right.svg" class="h-5"/>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- AdminLogo -->
                    <VerticalSeparator class="my-2" v-if="inputType === 'logo'" />
                    <div class="w-full flex items-center justify-center gap-2" v-if="inputType === 'logo'">
                        <YesNoButtons :selected="currentLogo" @change="onLogoChange" />
                    </div>
                </div>
            </div>
        </div>

    </GuestLayout>
</template>
