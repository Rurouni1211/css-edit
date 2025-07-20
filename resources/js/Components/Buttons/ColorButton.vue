<script setup>

import {computed} from "vue";

const props = defineProps({
    color: {
        type: Object,
    },
    selected: {
        type: Boolean,
        default: false,
    }
});
const emit = defineEmits(['click']);

// Common
const classNames = computed(() => {

    const baseClassNames = 'p-6 shadow-md cursor-pointer relative m-2';

    if(type.value === 'color_code') {

        return `${baseClassNames} bg-[${colorCode.value}]`;

    }

    return baseClassNames;

});
const styles = computed(() => {

    let styles = {
        borderRadius: '21%',
    };

    if(type.value === 'texture') {

        styles = {
            ...styles,
            backgroundImage: `url(${props.color.small_texture_url})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
        };

    }

    return styles;

});
const colorCode = computed(() => {

    return props.color.color_code;

});
const type = computed(() => {

    return props.color.texture_url
        ? 'texture'
        : 'color_code';

});
const colorId = computed(() => {

    return props.color.id;

});
const colorName = computed(() => {

    return props.color.name;

});

// Events
const onclick = () => {

    emit('click', {
        color: props.color
    });

};

</script>

<template>

    <div class="relative">
        <img 
            v-if="selected" 
            src="/images/color-selected.png" 
            style="width:63px; height:63px;mask-image: radial-gradient(circle, #000 70%, transparent 100%);" 
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-0 opacity-80" 
            alt="Selected background" />
        <div
            :class="classNames"
            :style="styles"
            :color-id="colorId"
            :color-name="colorName"
            :type="type"
            class="relative z-10"
            @click="onclick">
        </div>
    </div>

</template>
