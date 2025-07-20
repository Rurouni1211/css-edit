<script setup>

import {computed} from "vue";

const props = defineProps({
    value: {
        required: true,
    },
    size: {
        type: String,
        default: 'md',
    },
});
const classNames = computed(() => {

    const sizes = {
        'sm': 'bg-gray-200 text-black rounded px-1.5 text-sm',
        'md': 'bg-gray-200 text-black rounded px-1.5 text-base',
        'lg': 'bg-gray-200 text-black rounded px-1.5 text-lg',
    };

    return sizes[props.size] || sizes['md'];

});

</script>

<template>
    <span :class="classNames" v-if="value > 0">{{ new Intl.NumberFormat().format(value) }}</span>
</template>
