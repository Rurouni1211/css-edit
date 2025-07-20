<template>
<div class="flex items-center">
    <div class="inline-block border-r border-main-300 mx-auto" :class="heightClass"></div>
</div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    height: {
        type: String,
        default: 'h-6'
    }
});

const heightClass = computed(() => props.height);
</script>