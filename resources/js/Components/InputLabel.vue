<script setup>
import {computed} from "vue";

const props = defineProps({
    value: {
        type: String,
    },
    subTitle: {
        type: String,
    },
    borderColor: {
        type: String,
        default: '#D1D5DB',
    },
});

const styles = computed(() => {

    return {
        borderLeft: `4px solid ${props.borderColor}`,
        padding: '0.1rem 0 0.15rem 0.4rem',
        marginBottom: '10px',
    }

});

</script>

<template>
    <label class="block text-sm text-gray-800 font-bold rounded-sm" :style="styles">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
        <span v-if="subTitle" class="text-xs font-normal text-gray-600">{{ subTitle }}</span>
    </label>
</template>
