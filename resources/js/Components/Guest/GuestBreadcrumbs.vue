<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  items: {
    type: Array,
    default: () => []
  }
});

// 最後の項目を判定するためのヘルパー関数
const isLastItem = (index) => {
  return index === props.items.length - 1;
};
</script>

<template>
  <div class="py-2" v-if="items.length > 0">
    <div class="lg:container mx-auto px-4">
      <nav class="flex items-center text-sm" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
          <li v-for="(item, index) in items" :key="item.name" class="inline-flex items-center">
            <svg class="w-3 h-3 text-main-400 mx-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>

            <template v-if="isLastItem(index)">
              <span class="text-main-500" aria-current="page">{{ item.name }}</span>
            </template>
            <template v-else>
              <Link :href="item.url" class="text-main-700 hover:text-main-900">
                {{ item.name }}
              </Link>
            </template>
          </li>
        </ol>
      </nav>
    </div>
  </div>
</template>

<style scoped>
/* パンくずリストのスタイル */
</style>
