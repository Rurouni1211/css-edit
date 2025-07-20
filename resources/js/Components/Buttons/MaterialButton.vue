<script setup>

import {computed} from "vue";

const props = defineProps({
    material: {
        type: Object,
    },
    selected: {
        type: Boolean,
        default: false
    },
});
const emit = defineEmits(['click']);

// Common
const imageUrl = computed(() => {

    return props.material.button_image_url;

});
const materialKey = computed(() => {

    return props.material.key;

});

// Events
const onclick = () => {

    emit('click', {
        material: props.material,
    });

};

</script>

<template>

    <div class="min-w-[160px] w-[160px] h-[80px] relative flex items-center justify-center">
        <img 
            v-if="selected" 
            src="/images/material-selected.png" 
            style="width:152px; height:68px; margin-top:-3px; margin-left:-2px;mask-image: radial-gradient(circle, #000 75%, transparent 100%);" 
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-0 opacity-85" 
            alt="Selected background" />
        <img 
            v-if="imageUrl"
            :src="imageUrl" 
            class="w-full h-full cursor-pointer p-2 relative z-10" 
            :alt="materialKey" 
            @click="onclick" />
        <button 
            v-else
            type="button"
            class="flex items-center justify-center border rounded-lg w-[136px] h-[56px] mb-[7px] mr-1 bg-main-500 text-main-50 z-10 opacity-95"
            @click="onclick">
            {{ materialKey }}
        </button>
    </div>

</template>
