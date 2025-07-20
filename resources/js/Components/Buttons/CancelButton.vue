<script setup>

// Common
import {computed} from "vue";
import BaseButton from "@/Components/Buttons/BaseButton.vue";

const props = defineProps({
    size: {
        type: String,
        default: 'md',
    },
    href: {
        type: String,
        default: '',
    },
    target: {
        type: String,
        default: '_self',
    },
});
const emit = defineEmits([
    'click',
]);
const onClick = () => {

    emit('click');

};
const classNames = computed(() => {

    let classNames = [
        'inline-flex',
        'items-center',
        'border',
        'border-transparent',
        'font-medium',
        'text-white',
        'bg-gray-500',
        'hover:bg-gray-600',
        'focus:outline-none',
        'focus:ring-2',
        'focus:ring-offset-2',
        'focus:ring-gray-500',
        'transition',
        'ease-in-out',
        'duration-150',
    ]

    if(props.size === 'xs') {

        classNames.push('px-3', 'py-1', 'text-xs');

    } else if(props.size === 'sm') {

        classNames.push('px-4', 'py-1.5', 'text-xs');

    } else if(props.size === 'md') {

        classNames.push('px-6', 'py-2', 'text-sm');

    } else if(props.size === 'lg') {

        classNames.push('px-6', 'py-2', 'text-lg');

    }

    return classNames;

});

</script>

<template>
    <BaseButton
        :href="href"
        :classNames="classNames"
        :target="target"
        @click="onClick">
        <slot>キャンセル</slot>
    </BaseButton>
</template>
