<template>
    <Head :title="`${appName} - トップページ`" />
    <GuestLayout>
        <div class="bg-main-50 min-h-screen shadow-sm">
            <!-- Hero Section -->
            <section class="relative w-full">
                <!-- PC -->
                <div class="w-full overflow-hidden hidden md:block">
                    <img
                        src="/images/pc-top-first-view.jpg"
                        alt="WAMORE first view"
                        class="w-full"
                    />
                </div>
                <!-- モバイル & タブレット -->
                <div class="w-full overflow-hidden md:hidden relative h-screen">
                    <img
                        src="/images/mobile-top-first-view.jpg"
                        alt="WAMORE first view"
                        class="absolute inset-0 w-full h-full object-cover"
                    />
                    <div
                        class="absolute right-0 left-0 top-0 w-full p-2 flex justify-between items-center z-10"
                    >
                        <HamburgerButton color="#fff" />
                        <span
                            class="text-xl font-normal tracking-[0.25rem] text-main-50 [text-shadow:_0_1px_0_rgb(0_0_0_/_30%)]"
                            >{{ appName }}</span
                        >
                        <div class="w-7 h-7"><!-- 空白スペーサー --></div>
                    </div>
                </div>
                <!-- TOP-LEFT LINKS: PRODUCT & CONCEPT -->
                <!-- TOP LEFT: PRODUCT + CONCEPT -->
                <!-- Desktop only: PRODUCT + CONCEPT -->
                <div
                    class="hidden md:flex absolute top-4 left-4 md:top-6 md:left-6 z-20 space-x-4"
                >
                    <a
                        href="#"
                        class="text-main-200 hover:text-main-100 text-[10px] transition-colors opacity-90"
                    >
                        PRODUCT
                    </a>
                    <a
                        href="#"
                        class="text-main-200 hover:text-main-100 text-[10px] transition-colors opacity-90"
                    >
                        CONCEPT
                    </a>
                </div>

                <!-- TOP CENTER: WAMORE -->
                <div
                    class="absolute top-4 md:top-6 left-1/2 transform -translate-x-1/2 z-20"
                >
                    <a
                        href="#"
                        class="text-main-200 hover:text-main-100 text-sm md:text-[20px] transition-colors opacity-90"
                    >
                        WAMORE
                    </a>
                </div>

                <!-- MITSU - 90度回転、左端 -->
                <a
                    href="#"
                    class="absolute left-6 md:left-8 top-1/3 transform rotate-90 origin-left text-main-200 hover:text-main-100 text-sm transition-colors opacity-80"
                >
                    MITSU
                </a>
                <!-- SCROLL - 90度回転、MITSUの下 -->
                <a
                    href="#"
                    class="absolute left-6 md:left-8 top-[85%] md:top-3/4 transform rotate-90 origin-left text-main-200 hover:text-main-100 text-sm transition-colors opacity-80"
                >
                    SCROLL
                </a>
                <div class="absolute left-3 md:left-4 top-[87%] md:top-[76%]">
                    <img
                        src="/images/arrow-down-top-page.png"
                        class="h-52 md:h-auto w-auto"
                        alt="下向き矢印"
                    />
                </div>
                <!-- COLLECTION - -90度回転、右端中央 -->
                <a
                    href="#"
                    class="absolute right-6 md:right-8 top-1/3 transform -rotate-90 origin-right text-main-200 hover:text-main-100 text-sm transition-colors opacity-80"
                >
                    COLLECTION
                </a>
            </section>

            <!-- Concept Section -->
            <section class="pb-14 md:pb-40 bg-main-200">
                <div
                    class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-4"
                >
                    <div
                        class="flex justify-center items-center pt-10 md:pt-40 px-4 md:px-8 lg:px-16"
                    >
                        <img
                            src="/images/tablet.png"
                            alt="ARタブレット"
                            class="max-w-full h-96 object-cover"
                        />
                    </div>

                    <div
                        class="flex flex-col justify-center text-center md:text-left pt-10 md:pt-32 md:pr-40 px-9 md:px-0"
                    >
                        <section-title
                            position="left"
                            class="mb-10 md:block hidden text-[35px]"
                            >CONCEPT</section-title
                        >
                        <section-title
                            position="center"
                            class="mb-10 md:hidden block"
                            >CONCEPT</section-title
                        >
                        <div class="mb-2 md:mb-8 text-[15px]">
                            <h3
                                class="text-2xl md:text-4xl mb-6 md:mb-10 tracking-wider"
                            >
                                AR<small class="px-2.5 md:px-3.5">×</small>革
                            </h3>
                            <p class="leading-relaxed mb-4">
                                私たちのブランドは、AR技術を採用し、自分だけのデザインを作成できる職人の手作業によって仕上げられた質の高いレザーを使用し、
                                一点一点に魂を込めた製品をお届けします。
                            </p>
                            <p class="text-xs leading-relaxed">
                                <Link
                                    :href="route('store.product.index')"
                                    class="text-main-700 underline tracking-wide"
                                >
                                    オリジナルなバッグを作成する
                                </Link>
                            </p>
                        </div>

                        <div class="mt-10 text-xs">
                            <p>
                                <a
                                    href="https://dominate.co.jp/"
                                    class="text-xs underline text-main-700"
                                    target="_blank"
                                >
                                    ARカラーコンフィギュレーターの開発、<br />
                                    使用についてのお問い合わせはこちらから
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Product Section -->
            <section class="py-16 md:py-40 px-4 md:px-8 lg:px-16">
                <div class="max-w-6xl mx-auto">
                    <section-title
                        position="center"
                        class="mb-8 md:mb-16 text-[35px] pb-14"
                        >PRODUCT</section-title
                    >

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <!-- AR Custom Design -->
                        <div class="text-center order-1">
                            <h3
                                class="text-[15px] md:text-xl mb-3 font-bold tracking-wider"
                            >
                                AR CUSTOM DESIGN
                            </h3>
                            <p class="text-[10px] md:text-[15px] md:mb-6">
                                AR機能で自分だけのオリジナルデザインを作成する<br />
                                デザイナーが監修するセレクトシリーズ
                            </p>
                            <!-- AR Custom Design Image for Mobile -->
                            <div
                                class="md:hidden flex items-center justify-center mt-4 pb-10"
                            >
                                <div
                                    class="relative w-full aspect-[4/3] max-w-md h-[200px]"
                                >
                                    <Link
                                        :href="route('store.product.index')"
                                        class="absolute inset-0 flex items-center justify-center"
                                    >
                                        <div
                                            class="absolute inset-0 bg-cover bg-center"
                                            style="
                                                background-image: url('/images/product-ar-custom-design.jpg');
                                            "
                                            role="img"
                                            aria-label="AR Custom Design Bag"
                                        ></div>
                                        <img
                                            src="/images/overlay-light.png"
                                            class="absolute inset-0 w-full h-full object-cover"
                                        />
                                        <div
                                            class="absolute bottom-4 left-0 right-0 text-center"
                                        >
                                            <span
                                                class="text-base border-b border-white pb-0 hover:opacity-70 transition text-main-50"
                                            >
                                                オリジナルバッグを作成する
                                            </span>
                                        </div>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Wamore Design -->
                        <div class="text-center order-3">
                            <h3
                                class="text-[15px] md:text-xl mb-3 font-bold tracking-wider"
                            >
                                WAMORE DESIGN
                            </h3>
                            <p class="text-[10px] md:text-[15px] md:mb-6">
                                WAMOREデザイナーが監修するセレクトシリーズ
                            </p>
                            <!-- Wamore Design Image for Mobile -->
                            <div
                                class="md:hidden flex items-center justify-center mt-4 pb-20"
                            >
                                <div
                                    class="relative w-full aspect-[4/3] max-w-md h-[300px]"
                                >
                                    <div
                                        class="absolute inset-0 bg-cover bg-center"
                                        style="
                                            background-image: url('/images/product-wamore-design.jpg');
                                        "
                                        role="img"
                                        aria-label="Wamore Design Bag"
                                    ></div>
                                    <img
                                        src="/images/overlay.png"
                                        alt="WAMOREデザイン - オーバーレイ効果"
                                        class="absolute inset-0 w-full h-full object-cover"
                                    />
                                    <div
                                        class="absolute inset-0 flex items-center justify-center z-10"
                                    >
                                        <div
                                            class="text-center text-white bg-black bg-opacity-70 p-4 rounded"
                                        >
                                            <p
                                                class="font-bold mb-1 text-main-50"
                                            >
                                                2025年7/15日
                                            </p>
                                            <p
                                                class="font-bold mb-2 text-main-50"
                                            >
                                                EC販売開始
                                            </p>
                                            <p class="text-xs text-main-50">
                                                WAMOREの店舗限定で先行販売を行っています。
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Images (hidden on mobile) -->
                    <div
                        class="hidden md:grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-8 mt-0"
                    >
                        <!-- AR Custom Design Image -->
                        <div class="flex items-center justify-center">
                            <div
                                class="relative w-full max-w-[800px] h-[320px]"
                            >
                                <Link
                                    :href="route('store.product.index')"
                                    class="absolute inset-0 flex items-center justify-center"
                                >
                                    <div
                                        class="absolute inset-0 bg-cover bg-center"
                                        style="
                                            background-image: url('/images/product-ar-custom-design.jpg');
                                        "
                                        role="img"
                                        aria-label="AR Custom Design Bag"
                                    ></div>
                                    <img
                                        src="/images/overlay-light.png"
                                        class="absolute inset-0 w-full h-full object-cover"
                                    />
                                    <div
                                        class="absolute bottom-4 left-0 right-0 text-center"
                                    >
                                        <span
                                            class="text-base border-b border-white pb-0 hover:opacity-70 transition text-main-50"
                                        >
                                            オリジナルバッグを作成する
                                        </span>
                                    </div>
                                </Link>
                            </div>
                        </div>

                        <div class="relative flex items-center justify-center">
                            <div
                                class="relative w-full max-w-[800px] h-[320px]"
                            >
                                <div
                                    class="absolute inset-0 bg-cover bg-center"
                                    style="
                                        background-image: url('/images/product-wamore-design.jpg');
                                    "
                                    role="img"
                                    aria-label="Wamore Design Bag"
                                ></div>
                                <img
                                    src="/images/overlay.png"
                                    alt="Wamore Design Bag"
                                    class="absolute inset-0 w-full h-full object-cover"
                                />
                                <div
                                    class="absolute inset-0 flex items-center justify-center text-sm"
                                >
                                    <div
                                        class="text-center text-white bg-opacity-40 p-4 rounded opacity-90"
                                    >
                                        <p class="font-bold mb-1 text-main-50">
                                            2025年7/15日
                                        </p>
                                        <p class="font-bold mb-2 text-main-50">
                                            EC販売開始
                                        </p>
                                        <p class="text-xs text-main-50">
                                            WAMOREの店舗限定で先行販売を行っています。
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Collection Section -->
            <section class="pb-16 md:pb-40 px-10 md:px-32 lg:px-32 bg-main-50">
                <div class="max-w-6xl mx-auto">
                    <section-title position="center" class="mb-5 text-[35px]"
                        >COLLECTION</section-title
                    >
                    <p class="text-[15px] text-center mb-12">
                        私たちのブランドは、職人の手作業によって仕上げられた質の高いレザーを使用し<br
                            class="hidden md:block"
                        />
                        一点一点に魂を込めた製品をお届けします。
                    </p>

                    <div class="grid grid-cols-3 gap-4 md:gap-8">
                        <!-- Collection Item 1 -->
                        <div class="relative text-center">
                            <div class="bg-main-200 p-6 mb-4">
                                <img
                                    src="/images/collection-bag-1.png"
                                    alt="MITSU コレクション - ハンドバッグ1"
                                    class="h-16 sm:h-36 mx-auto mb-8"
                                />
                                <h4
                                    class="absolute text-xs sm:text-sm text-main-600 uppercase left-0 right-0 bottom-8 tracking-wide"
                                >
                                    MITSU
                                </h4>
                            </div>
                        </div>

                        <!-- Collection Item 2 -->
                        <div class="relative text-center">
                            <div class="bg-main-200 p-6 mb-4">
                                <img
                                    src="/images/collection-bag-2.png"
                                    alt="MITSU コレクション - ハンドバッグ2"
                                    class="h-16 sm:h-36 mx-auto mb-8"
                                />
                                <h4
                                    class="absolute text-xs sm:text-sm text-main-600 uppercase left-0 right-0 bottom-8 tracking-wide"
                                >
                                    MITSU
                                </h4>
                            </div>
                        </div>

                        <!-- Collection Item 3 -->
                        <div class="relative text-center">
                            <div class="bg-main-200 p-6 mb-4">
                                <img
                                    src="/images/collection-bag-3.png"
                                    alt="MITSU コレクション - ハンドバッグ3"
                                    class="h-16 sm:h-36 mx-auto mb-8"
                                />
                                <h4
                                    class="absolute text-xs sm:text-sm text-main-600 uppercase left-0 right-0 bottom-8 tracking-wide"
                                >
                                    MITSU
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Material Section -->
            <section class="py-16 md:py-40 bg-main-200 px-4 md:px-32">
                <div class="grid grid-cols-1 md:grid-cols-2 items-start gap-6">
                    <!-- Left column -->
                    <div
                        class="flex flex-col justify-start pt-0 md:pt-1 px-4 md:px-8 lg:px-16 max-w-xl mx-auto"
                    >
                        <section-title
                            position="left"
                            class="font-mincho mb-4 text-[35px] font-semibold"
                            >MATERIAL</section-title
                        >
                        <p
                            class="text-xs leading-relaxed mb-4 text-[15px] font-normal"
                        >
                            私たちは厳選された素材、熟練の技術でバッグを製作しています。<br />
                            一つ一つ丁寧に仕上げていきます。
                        </p>
                    </div>
                    <!-- Right column -->
                    <div>
                        <div class="relative w-full">
                            <img
                                src="/images/ar-tablet-screen.jpg"
                                alt="マテリアル図鑑"
                                class="w-full object-cover"
                            />
                            <div
                                class="absolute inset-0 bg-black bg-opacity-70 flex items-center justify-center z-10"
                            >
                                <div class="text-white text-center px-4">
                                    <p class="font-bold mb-1 text-main-50">
                                        2025年10月下旬
                                    </p>
                                    <p class="font-bold mb-2 text-main-50">
                                        マテリアル図鑑OPEN
                                    </p>
                                    <p class="text-xs text-main-50">
                                        WAMOREの店舗限定で革のサンプルをご覧いただけます。
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Shop Section -->
            <section class="py-16 px-4 px-10 md:px-8 lg:px-16 bg-main-50">
                <div class="max-w-6xl mx-auto">
                    <section-title position="center" class="mb-4 md:mb-16"
                        >SHOP</section-title
                    >
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-12 mb-10 md:mb-14 md:pl-20 items-stretch"
                    >
                        <div class="h-full">
                            <img
                                src="/images/shop.jpg"
                                alt="WAMORE Shop"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div
                            class="flex flex-col justify-between h-full md:pr-28"
                        >
                            <!-- Top: Description -->
                            <div>
                                <p
                                    class="text-[17px] font-normal leading-relaxed mb-4 md:mb-8"
                                >
                                    WAMOREの実店舗にてWAMORE
                                    DESIGNのアイテムを購入できます。
                                    革のサンプルを確認しながらオリジナルバックの作成が可能です。
                                </p>
                            </div>

                            <!-- Bottom: Address -->
                            <div class="text-left text-[17px] font-normal">
                                <dl>
                                    <dt class="font-medium mb-2">
                                        WAMORE 本店
                                    </dt>
                                    <dd class="mb-1">〒060-0061</dd>
                                    <dd class="mb-6">
                                        北海道札幌市中央区南1条西2丁目11
                                        丸井今井札幌本店 大通館3階
                                    </dd>
                                    <dt class="font-medium mb-2">
                                        WAMORE 阪神百貨店 大阪店
                                    </dt>
                                    <dd class="mb-1">〒060-0061</dd>
                                    <dd>
                                        北海道札幌市中央区南1条西2丁目11
                                        丸井今井札幌本店 大通館3階
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import SectionTitle from "@/Components/Guest/SectionTitle.vue";
import HamburgerButton from "@/Components/Store/HamburgerButton.vue";

const page = usePage();
const appName = page.props.appName.toUpperCase();
</script>

<style scoped>
.writing-vertical {
    writing-mode: vertical-rl;
    text-orientation: mixed;
}
</style>
