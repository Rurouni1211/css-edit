<script setup>
import { computed } from 'vue';

const props = defineProps({
  position: {
    type: String,
    default: 'topRight',
    validator: (value) => ['topRight', 'topLeft', 'bottomRight', 'bottomLeft'].includes(value)
  }
});

// 位置に基づいたクラスを計算するcomputedプロパティ
const positionClasses = computed(() => {
  const positions = {
    topRight: 'top-0 right-0',
    topLeft: 'top-0 left-0',
    bottomRight: 'bottom-0 right-0',
    bottomLeft: 'bottom-0 left-0'
  };
  
  return `fixed ${positions[props.position]} z-50`;
});
</script>

<template>
  <div :class="positionClasses">
    <slot></slot>
  </div>
</template>
