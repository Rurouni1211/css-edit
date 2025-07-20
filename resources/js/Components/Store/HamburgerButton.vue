<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import {usePage} from '@inertiajs/vue3';
import { mainMenuItems } from '@/Utils/Navigation.js';

const page = usePage();
const appName = page.props.appName.toUpperCase();
const props = defineProps({
  color: {
    type: String,
    default: '#fff'
  },
  size: {
    type: String,
    default: '6' 
  },
});

// Use the imported menu items
const menuItems = mainMenuItems;

// メニュー関連
const mobileMenuOpen = ref(false);
const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
    if (mobileMenuOpen.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
};
const onClick = () => {
    mobileMenuOpen.value = ! mobileMenuOpen.value;
};

// 画面外クリックでメニューを閉じる
const handleOutsideClick = (event) => {
  const panel = document.getElementById('mobile-menu-panel');
  const button = event.target.closest('button');
  
  // ハンバーガーボタンのクリックはここでは処理しない
  if (button && (button.contains(event.target) || button === event.target)) {
    return;
  }
  
  // パネル外のクリックでメニューを閉じる
  if (mobileMenuOpen.value && panel && !panel.contains(event.target)) {
    mobileMenuOpen.value = false;
    document.body.style.overflow = '';
  }
};

// イベントリスナーの登録と削除
onMounted(() => {
  document.addEventListener('click', handleOutsideClick);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleOutsideClick);
});
</script>

<template>
  <button class="flex items-center p-2" @click="onClick">
    <!-- ハンバーガーアイコン -->
    <svg :class="`w-${props.size} h-${props.size}`" fill="none" :stroke="color" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
  </button>

    <!-- モバイルメニューオーバーレイ -->
    <transition name="fade">
        <div
            v-if="mobileMenuOpen"
            class="fixed inset-0 z-40 bg-black bg-opacity-50"
            @click="onClick"
            aria-hidden="true"
        ></div>
    </transition>

    <!-- モバイルメニュー左サイドパネル -->
    <transition name="slide">
        <div
            v-if="mobileMenuOpen"
            id="mobile-menu-panel"
            class="md:hidden fixed inset-y-0 left-0 z-50 w-64 bg-main-100 shadow-xl"
        >
            <!-- モバイルヘッダー (閉じるボタンとロゴ) -->
            <div class="flex justify-between items-center p-4 bg-main-300">
                <div class="text-center">
                    <span class="text-lg font-normal tracking-widest">{{ appName }}</span>
                </div>
                <!-- 閉じるアイコン -->
                <button class="flex items-center p-2" @click="onClick">
                    <svg :class="`w-${props.size} h-${props.size}`" fill="none" stroke="#333" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- モバイルナビゲーション -->
            <nav class="py-6 px-4">
                <ul class="space-y-4">
                    <li v-for="item in menuItems" :key="item.name">
                        <a
                            :href="item.url"
                            class="block py-2 text-sm text-main-700 hover:text-main-900 uppercase"
                            @click="onClick"
                        >
                            {{ item.name }}
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </transition>   

</template>

<style scoped>
/* フェードアニメーション */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* スライドアニメーション */
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  transform: translateX(-100%);
}
</style>
