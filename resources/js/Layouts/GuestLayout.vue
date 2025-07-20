<script setup>
import {usePage} from '@inertiajs/vue3';
import GuestHeaderSection from '@/Components/Guest/GuestHeaderSection.vue';
import GuestFooterSection from '@/Components/Guest/GuestFooterSection.vue';
import GuestBreadcrumbs from '@/Components/Guest/GuestBreadcrumbs.vue';
import { computed } from 'vue';
import FixedContent from '@/Components/Store/FixedContent.vue';

const page = usePage();

// 現在のコンポーネント名を取得して、トップページかどうかを判断
const currentComponent = computed(() => page.component || '');
const isTopPage = computed(() => currentComponent.value === 'Top');

// パンくずリストのプロパティ
const props = defineProps({
    breadcrumbs: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <!-- ヘッダー (トップページかつモバイルの場合のみ非表示) -->
        <div :class="[isTopPage ? 'hidden md:block' : '']">
          <GuestHeaderSection />
        </div>

        <!-- パンくずリスト -->
        <GuestBreadcrumbs :items="breadcrumbs" />

        <!-- メインコンテンツ -->
        <main class="flex-grow overflow-hidden">
            <div class="container max-w-screen-xl px-0 mx-auto">
                <slot />
            </div>
        </main>

        <!-- フッター -->
        <GuestFooterSection />

        <!-- Fixed コンテンツ -->
        <FixedContent position="topRight">
            <div class="rounded-l-full bg-main-600 bg-opacity-70 text-white pr-1 pl-3 py-1.5 text-[9px] md:text-xs mt-[16px] md:mt-[13px]">
                プレオープン実施中
            </div>
        </FixedContent>

    </div>
</template>
