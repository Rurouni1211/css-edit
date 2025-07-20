<script setup>
import {computed} from 'vue';
import NormalButton from "@/Components/Buttons/NormalButton.vue";

// Common
const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'md',
    },
});
const emits = defineEmits(['update:modelValue']);

// Events
const closeModal = () => {

    emits('update:modelValue', false);

};

const modalSize = computed(() => {

    const classNames = {
        'lg': 'max-w-4xl w-full',
        'md': 'max-w-lg w-full',
        'sm': 'max-w-md w-full',
    };

    return classNames[props.size] || classNames['md'];

});

</script>

<template>
    <div
        class="fixed inset-0 bg-main-800 bg-opacity-75 flex justify-center items-center"
        v-show="modelValue"
        @click="closeModal"
    >
        <div
            :class="modalSize"
            class="bg-white rounded-lg shadow-lg flex flex-col"
            @click.stop
        >
            <div class="p-6 border-b border-main-200">
                <slot name="header"></slot>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                <slot></slot>
            </div>
            <div class="p-6 border-t border-main-200 te">
                <NormalButton size="sm" @click="closeModal">閉じる</NormalButton>
            </div>
        </div>
    </div>
</template>
