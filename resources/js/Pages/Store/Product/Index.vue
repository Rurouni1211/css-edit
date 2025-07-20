<template>
    <Head title="Item Select" />
    <GuestLayout>
        <div class="px-4 md:px-32 py-8">
            <!-- ページヘッダー -->
            <div class="text-center mb-4">
                <h1 class="text-xl font-normal tracking-wider mb-3">
                    Item Select
                </h1>
                <p class="text-xs text-gray-600 mb-16">
                    カスタマイズするモデルを選びください。
                </p>
                <!-- Item Select Bar with Responsive Visibility -->
                <div class="flex justify-center items-center gap-2 mb-2">
                    <!-- Arrows always shown -->
                    <button class="text-lg text-gray-500">&lt;</button>

                    <!-- First 3 buttons: always visible -->
                    <button class="border px-4 py-1 text-xs">Accessory</button>
                    <button class="border px-4 py-1 text-xs">Accessory</button>
                    <button class="bg-gray-500 text-white px-4 py-1 text-xs">
                        Bag
                    </button>

                    <!-- Hidden on mobile, shown on md+ -->
                    <button class="border px-4 py-1 text-xs hidden md:block">
                        Wallet
                    </button>
                    <button class="border px-4 py-1 text-xs hidden md:block">
                        Wallet
                    </button>

                    <!-- Arrows always shown -->
                    <button class="text-lg text-gray-500">&gt;</button>
                </div>

                <!-- Filter Tool Line -->
                <div class="flex justify-end items-center mb-2 px-4">
                    <div class="flex items-center text-xs text-gray-600">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-6 6V21l-4-2v-8.293l-6-6A1 1 0 013 6V4z"
                            />
                        </svg>
                        FILTER
                    </div>
                </div>

                <!-- Underline -->
                <div class="border-b border-gray-300 mb-8"></div>
            </div>

            <!-- 【変更】商品情報はこちらを変更してください -->
            <!-- ※変更後は、データ統合する必要があるため、必ず開発者へのご連絡をお願いいたしますm__m -->

            <!-- 商品リスト -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-8 px-2">
                <!-- カスタム商品1 -->
                <Link href="/store/product/1">
                    <ProductListItem
                        name="MITSU-S 0001"
                        src="/images/collection-bag-1.png"
                        :price="80000"
                    />
                </Link>

                <!-- カスタム商品2 -->
                <Link href="/store/product/1">
                    <ProductListItem
                        name="MITSU-M 0002"
                        src="/images/collection-bag-2.png"
                        :price="80000"
                    />
                </Link>

                <!-- カスタム商品3 -->
                <Link href="/store/product/1">
                    <ProductListItem
                        name="MITSU-S 0003"
                        src="/images/collection-bag-3.png"
                        :price="80000"
                    />
                </Link>

                <!-- カスタム商品4 -->
                <Link href="/store/product/1">
                    <ProductListItem
                        name="MITSU-M 0001"
                        src="/images/collection-bag-1.png"
                        :price="80000"
                    />
                </Link>

                <!-- カスタム商品5 -->
                <Link href="/store/product/1">
                    <ProductListItem
                        name="MITSU-S 0002"
                        src="/images/collection-bag-2.png"
                        :price="80000"
                    />
                </Link>

                <!-- カスタム商品6 -->
                <Link href="/store/product/1">
                    <ProductListItem
                        name="MITSU-M 0003"
                        src="/images/collection-bag-3.png"
                        :price="80000"
                    />
                </Link>

                <!-- カスタム商品7 -->
                <Link href="/store/product/1">
                    <ProductListItem
                        name="MITSU-M 0001"
                        src="/images/collection-bag-1.png"
                        :price="80000"
                    />
                </Link>

                <!-- カスタム商品8 -->
                <Link href="/store/product/1">
                    <ProductListItem
                        name="MITSU-M 0002"
                        src="/images/collection-bag-2.png"
                        :price="80000"
                    />
                </Link>
            </div>
            <!-- Mobile Responsive Bottom Nav -->
            <div
                class="w-full bg-[#f6f6f6] py-8 relative text-gray-500 text-[11px]"
            >
                <div class="flex justify-between px-6 items-end">
                    <!-- Left: Back -->
                    <div class="flex flex-col items-center">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 mb-1"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                        <span>前の画面へ</span>
                    </div>

                    <!-- Center: Load More -->
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 flex flex-col items-center"
                    >
                        <span class="mb-1 text-[11px]">もっと見る</span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-3 h-3"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import ProductListItem from "@/Components/Store/ProductListItem.vue";
</script>
