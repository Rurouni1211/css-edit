<template>

    <BaseButton size="md" :selected="yesSelected" @click="onClick(true)">あり</BaseButton>
    <BaseButton size="md" :selected="noSelected" @click="onClick(false)">なし</BaseButton>

</template>
<script setup>

// Common
import BaseButton from "@/Components/Store/BaseButton.vue";
import {computed} from "vue";

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
const emits = defineEmits(['change']);

const yesSelected = computed(() => {

    return props.selected === true;

});
const noSelected = computed(() => {

    return props.selected === false;

});

// Events
const onClick = (value) => {

    emits('change', {
        value: value,
    });

};

</script>
