<script setup>

// Common
import {computed, ref} from "vue";

const emit = defineEmits([
    'drop',
]);

// Drag and Drop
let dragAndDropCount = ref(0);
const isDragging = computed(() => {

    return dragAndDropCount.value > 0;

});
const classNames = computed(() => {

    if(isDragging.value === true) {

        return [
            'bg-gray-100',
            'border-dashed',
            'border-2',
            'border-gray-400',
        ];

    }

    return [];

});

// Events
const onDragEnter = () => {

    dragAndDropCount.value++;

};
const onDragLeave = () => {

    dragAndDropCount.value--;

};
const onDrop = (e) => {

    dragAndDropCount.value = 0;
    emit('drop', e);

};

</script>

<template>

    <div
        :class="classNames"
        @dragenter.prevent="onDragEnter"
        @dragleave.prevent="onDragLeave"
        @drop.prevent="onDrop"
        @dragover.prevent
    >
        <slot></slot>
    </div>

</template>
