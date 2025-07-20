<template>
  <div class="product-item mb-5">
    <div class="bg-main-200 mb-1 p-10">
      <img :src="src" :alt="name" class="w-full h-auto object-contain" style="aspect-ratio: 1/1;">
    </div>
    <div class="product-info">
      <div class="product-name text-xs font-medium">{{ name }}</div>
      <div class="product-price text-xs">¥ {{ price.toLocaleString() }}</div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  name: {
    type: String,
    required: true
  },
  src: {
    type: String,
    required: true
  },
  price: {
    type: Number,
    required: true
  }
});
</script>

<style scoped>
.product-item {
  cursor: pointer;
  transition: opacity 0.3s;
}

.product-item:hover {
  opacity: 0.8;
}

.product-info {
  padding: 0.25rem 0;
}

.product-name {
  font-size: 0.85rem;
  line-height: 1.2;
}

.product-price {
  font-size: 0.8rem;
  color: #333;
}
</style>
