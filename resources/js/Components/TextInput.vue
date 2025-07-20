<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'text',
    },
});
const type = props.type;
const model = defineModel({
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <template v-if="type === 'textarea'">
        <textarea
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1.5"
            v-model="model"
            ref="input"
        ></textarea>
    </template>
    <template v-else>
        <input
            :type="type"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1.5"
            v-model="model"
            ref="input"
        />
    </template>
</template>
