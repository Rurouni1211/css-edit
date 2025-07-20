<template>

    <button
        class="bg-black text-white text-xs px-3 py-1 cursor-pointer hover:opacity-80 focus:outline-none font-shippori"
        :class="classNames"
        @click="onClick">
        <span class="flex items-center justify-center tracking-wider text-sm">
            <slot>送信する</slot>
        </span>
    </button>

</template>

<script setup>

import {computed} from "vue";

// Common
const props = defineProps({
    size: {
        type: String,
        default: 'md',
    },
    selected: {
        type: [Boolean, null],
        default: null,
    },
});
const emits = defineEmits(['click']);

// Computed
const classNames = computed(() => {

    const baseClassNames = [
        'border',
        'font-serif',
        'rounded-none',
        'transition',
        'duration-300',
        'ease-in-out',
        'hover:shadow-lg',
        'focus:outline-none',
        'focus:ring-2',
        'focus:ring-[#D4AF37]',
        'focus:ring-opacity-50'
    ];

    const typeClassNames = {
        selected: [
            'bg-main-800',
            'hover:bg-main-700',
            'text-main-50',
            'hover:text-main-100',
            'border-none'
        ],
        unselected: [
            'bg-main-300',
            'hover:bg-main-400',
            'text-main-900',
            'hover:text-main-800',
            'border-none'
        ],
    }
    const typeClassName = (props.selected === false) ? typeClassNames.unselected : typeClassNames.selected;
    const sizeClassNames = {
        sm: ['text-xs', 'py-1', 'px-3'],
        md: ['text-sm', 'py-2', 'px-5'],
        lg: ['text-base', 'py-3', 'px-7'],
    };
    const sizeClassName = sizeClassNames[props.size] || sizeClassNames.md;

    return [
        ...baseClassNames,
        ...typeClassName,
        ...sizeClassName,
    ];

});

// Events
const onClick = () => {

    emits('click');

};

</script>
