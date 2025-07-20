<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import GoldButton from "@/Components/Buttons/GoldButton.vue";
import { onMounted } from "vue";

const props = defineProps({
    orderUniqueId: {
        type: String,
        required: true,
    }
});

// usePageを使ってappNameを取得
const appName = usePage().props.appName;

onMounted(() => {
    // コンテンツが表示されたときにフェードインアニメーション
    const elements = document.querySelectorAll('.fade-in');
    elements.forEach((el, index) => {
        setTimeout(() => {
            el.classList.add('visible');
        }, 200 * index);
    });
});
</script>

<template>
    <Head title="購入完了" />
    
    <GuestLayout>
        <!-- 高級感のある背景とコンテンツ全体のスタイリングを適用 -->
        <div class="completion-page w-full min-h-auto flex flex-col pt-[40px] pb-[60px]">
            <div class="sm:w-[93%] lg:w-[100%] max-w-[1280px] mx-auto lg:px-7">
                <!-- エレガントな区切り線 -->
                <div class="divider-elegant fade-in"></div>
                
                <!-- Completion Header - より洗練されたデザイン -->
                <div class="flex justify-center mb-10 fade-in">
                    <h1 class="text-3xl font-bold font-serif text-gray-800 tracking-wider">ご注文ありがとうございます</h1>
                </div>
                
                <!-- Order Details - 高級感あるスタイル -->
                <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-lg p-8 mb-12 border border-gray-100 fade-in">
                    <div class="flex flex-col items-center">
                        <!-- 高級バッグの代表的な画像 -->
                        <div class="mb-8 fade-in">
                            <img src="/images/collection-bag-1.png" alt="Premium Bag Collection" class="w-40 h-auto mx-auto" />
                        </div>
                        
                        <div class="mb-8 text-center fade-in">
                            <div class="text-gold mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-serif font-medium text-gray-800">注文が完了しました</h2>
                            <p class="text-gray-600 mt-3 font-light tracking-wider">注文番号: <span class="font-medium">{{ orderUniqueId }}</span></p>
                        </div>
                        
                        <div class="text-center text-gray-600 max-w-md mx-auto mb-8 fade-in">
                            <p class="mb-2">
                                商品を最高の状態でお届けするため、熟練の職人が丁寧に準備を進めます。
                            </p>
                            <p>
                                この度は、{{ appName }}の製品をお選びいただき、誠にありがとうございます。
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- エレガントな区切り線 -->
                <div class="divider-elegant mb-8 fade-in"></div>
                
                <!-- Action Buttons - スタイリッシュなボタン -->
                <div class="flex justify-center gap-6 fade-in">
                    <GoldButton size="md" href="/">
                        トップページへ戻る
                    </GoldButton>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
.completion-page {
    background-image: 
        linear-gradient(to right, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7)),
        url('/images/handmadepaper.webp');
    background-size: cover;
}

.text-gold {
    color: #D4AF37;
}

.divider-elegant {
    height: 1px;
    background: linear-gradient(to right, transparent, #D4AF37, transparent);
    margin: 2rem auto;
    width: 60%;
    max-width: 400px;
}

.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.8s ease-out, transform 0.8s ease-out;
}

.fade-in.visible {
    opacity: 1;
    transform: translateY(0);
}
</style>