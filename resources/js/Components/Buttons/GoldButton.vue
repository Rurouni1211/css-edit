<script setup>
import BaseButton from "@/Components/Buttons/BaseButton.vue";
import {computed} from "vue";

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

// 共通クラス名
const classNames = computed(() => {
    return {
        'px-8 py-3 bg-gold hover:bg-gold-dark transition-all text-white rounded-md font-medium text-center': true,
    };
});
</script>

<template>
    <BaseButton :size="size" :href="href" :target="target" :classNames="classNames">
        <slot></slot>
    </BaseButton>
</template>

<style scoped>
.bg-gold {
    background-color: #D4AF37;
}

.bg-gold-dark {
    background-color: #B8860B;
}
</style>
