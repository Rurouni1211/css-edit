<script setup>
const props = defineProps({
  position: {
    type: String,
    default: 'left',
    validator: (value) => ['left', 'center', 'right'].includes(value)
  }
});

const textAlignment = {
  left: 'text-left',
  center: 'text-center',
  right: 'text-right'
};

import { computed } from 'vue';

const titleClasses = computed(() => {
  return `text-xl text-main-700 font-bold opacity-95 tracking-wider ${textAlignment[props.position]}`;
});

const underlineClasses = computed(() => {
  const baseClasses = 'h-[2px] bg-main-300 opacity-90';
  return props.position === 'center' ? `${baseClasses} max-w-xl mx-auto` : `${baseClasses} w-full`;
});
</script>

<template>
  <div class="w-full relative">
    <h2 :class="titleClasses">
      <slot></slot>
    </h2>
    <div :class="underlineClasses" style="margin-top:1px;"></div>
  </div>
</template>
