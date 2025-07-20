<template>

    <select ref="selectElement">
        <option value="">選択してください（検索可）</option>
        <option v-for="item in items" :value="item.id">{{ item.name }}</option>
    </select>

</template>

<script setup>

import {nextTick, onMounted, ref} from 'vue';
const props = defineProps({
    modelValue: {
        required: true
    },
    items: {
        type: Array,
        required: true
    },
});
const emit = defineEmits(['update:modelValue']);

// TomSelect
const selectElement = ref(null);
let tomSelect = null;
onMounted(() => {

    nextTick(() => {

        tomSelect = new TomSelect(selectElement.value);
        tomSelect.setValue(props.modelValue);
        tomSelect.on('change', () => {

            const selectedValue = tomSelect.getValue();
            emit('update:modelValue', selectedValue);

        });

    });

});

</script>
