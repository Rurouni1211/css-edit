<script setup>
import { Link } from '@inertiajs/vue3';
import {usePage} from '@inertiajs/vue3';
import HamburgerButton from '@/Components/Store/HamburgerButton.vue';
import { mainMenuItems } from '@/Utils/Navigation.js';

const page = usePage();
const appName = page.props.appName.toUpperCase();

defineProps({
  menuItems: {
    type: Array,
    default: () => mainMenuItems
  },
  logoUrl: {
    type: String,
    default: null
  }
});
</script>

<template>
  <header class="bg-[#808080] border-b border-[#7c7c7c] shadow-sm">
    <div class="px-3 mx-auto">
      <div class="flex justify-between items-center py-2 md:py-4">
        <!-- 左側のナビゲーションメニュー (PCのみ) -->
        <nav class="hidden lg:flex space-x-6">
          <Link 
            v-for="item in menuItems" 
            :key="item.name" 
            :href="item.url" 
            class="text-main-50 hover:text-main-800 uppercase text-sm tracking-wider"
          >
            {{ item.name }}
          </Link>
        </nav>

        <!-- モバイルとタブレット用のハンバーガーメニューボタン -->
        <div class="lg:hidden">
          <HamburgerButton color="#fff" />
        </div>
        
        <!-- ロゴ部分 (中央配置) -->
        <div class="absolute left-1/2 transform -translate-x-1/2">
          <Link href="/" class="flex items-center justify-center">
            <span class="text-xl font-normal tracking-[0.25rem] text-main-50 [text-shadow:_0_1px_5px_rgb(0_0_0_/_10%)]">{{ appName }}</span>
          </Link>
        </div>

        <!-- 右側のアイコン -->
        <!-- <div class="flex space-x-4">
          <Link href="/search" class="text-gray-700">
            <img src="/images/user.svg" alt="User Icon" class="h-7 w-7" />
          </Link>
          <Link href="/cart" class="text-gray-700">
            <img src="/images/shop.svg" alt="User Icon" class="h-7 w-7" />
          </Link>
        </div> -->
      </div>
    </div>
  </header>
</template>