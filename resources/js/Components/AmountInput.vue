<script setup>
import {computed, onMounted, ref} from 'vue';
import vNumberInput from "@/Directives/vNumberInput";

const model = defineModel({
    required: true,
});
const props = defineProps({
    size: {
        type: String,
        default: 'md',
    },
});
const inputClasses = computed(() => {

    const sizes = {
        xs: 'text-xs border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 w-full',
        sm: 'text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 w-full',
        md: 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 w-full',
        lg: 'text-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 w-full',
    };

    return sizes[props.size] || sizes.md;

});
const inputStyles = computed(() => {

    const styles = {
        xs: 'top:13px;right:5px;',
        sm: 'top:15px;right:7px;',
        md: 'top:17px;right:8px;',
        lg: 'top:19px;right:8px;',
    }

    return styles[props.size] || styles.md;

});

const input = ref(null);
const commaSeparatedAmount = computed(() => {

    return (model.value)
        ? model.value.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        : '';

});

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="relative border-0">
        <input
            ref="input"
            type="text"
            :class="inputClasses"
            v-number-input
            v-model="model"
        />
        <div class="absolute" :style="inputStyles" v-if="commaSeparatedAmount">
            <span class="px-2.5 py-1 bg-blue-100 font-bold rounded" v-text="commaSeparatedAmount"></span>
        </div>
    </div>
</template>
