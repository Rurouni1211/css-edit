<template>
    <Head title="CONCEPT | WAMORE" />
    <GuestLayout>
        <div class="concept-page">
            <div
                class="absolute top-20 left-4 md:left-14 z-10 text-[15px] text-main-600"
            >
                <span>TOP</span>
                <span class="mx-1">&gt;</span>
                <span>CONCEPT</span>
            </div>
            <!-- コレクションタイトル -->
            <div class="py-6 pt-24 text-center">
                <h1 class="text-xl uppercase tracking-widest">CONCEPT</h1>
            </div>

            <div class="mb-2 md:mb-12">
                <div class="mx-auto">
                    <div class="p-4">
                        <div
                            class="bg-white shadow-sm h-56 md:h-72 flex items-center justify-center"
                        >
                            <h2
                                class="text-2xl md:text-3xl font-light tracking-wider text-main-600"
                            >
                                {{ appName }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <section class="mb-14 md:mb-16 px-4">
                <div class="hidden md:block">
                    <h2
                        class="text-left mb-6 md:mb-4 text-lg tracking-widest extended-underline md:text-[20px]"
                    >
                        {{ appName }}
                    </h2>
                </div>
                <div class="block md:hidden">
                    <h2
                        class="text-left mb-6 md:mb-4 text-lg tracking-widest extended-underline"
                    >
                        {{ appName }}
                    </h2>
                </div>
                <div
                    class="text-xs md:text-[17px] font-normal leading-relaxed mb-12"
                >
                    <p>
                        あなたの「欲しい」を、世界にひとつだけの形に。
                        最先端AR技術で、理想のデザインを自由自在にカスタマイズ。
                        選び抜かれた上質なレザーを、熟練の職人が一つひとつ丁寧に仕上げます。
                    </p>
                </div>
            </section>

            <!-- AR CUSTOMIZE セクション -->
            <section class="relative mb-14 md:mb-16 px-4 md:py-32">
                <!-- スマホ表示時のタイトル -->

                <div
                    class="flex flex-row gap-8 md:gap-8 w-full max-w-full overflow-hidden"
                >
                    <div
                        class="w-1/2 shrink-3 md:w-1/2 order-1 md:order-1 mb-6 md:mb-0 pt-6"
                    >
                        <img
                            src="/images/tablet.png"
                            alt="ARタブレット"
                            class="w-full h-auto md:w-auto mx-0 md:mx-auto md:h-[500px] object-cover"
                        />
                    </div>
                    <div class="w-1/2 shrink-0 md:w-1/2 order-2 md:order-2">
                        <h2 class="mb-6 md:hidden md:text-[20px]">
                            <span
                                class="text-lg uppercase tracking-widest inline-block extended-underline-reverse"
                            >
                                AR CUSTOMIZE
                            </span>
                        </h2>
                        <!-- デスクトップ表示時のみ表示 -->
                        <div class="hidden md:block md:pt-20">
                            <h2
                                class="text-lg uppercase tracking-widest mb-4 extended-underline-reverse"
                            >
                                AR CUSTOMIZE
                            </h2>
                        </div>
                        <div
                            class="text-xs md:text-[17px] font-normal leading-relaxed"
                        >
                            <p class="mb-4">
                                あなたのアイデアが、AR技術によってその場で形になります。<br
                                    class="hidden md:block"
                                />
                                画面上で色やパーツを自在に組み合わせ、360度どこからでも細部まで確認。<br />
                                世界にひとつだけのデザインを、まるで職人と一緒に作り上げるような感覚でお楽しみいただけます。<br /><br />
                                “自分だけの”という贅沢を、ぜひご体験ください。
                            </p>
                        </div>
                        <div class="text-[15px] mt-14 md:mt-4">
                            <a
                                href="https://dominate.co.jp/"
                                class="text-gray-500 underline"
                                target="_blank"
                            >
                                ARカラーコンフィギレーターの開発、<br />使用についてのお問い合わせはこちらから
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CRAFT セクション -->
            <section class="mb-14 md:mb-16 px-4 md:py-32">
                <!-- スマホ表示時のタイトル -->
                <h2
                    class="text-lg uppercase tracking-widest mb-6 md:hidden extended-underline"
                >
                    CRAFT
                </h2>
                <div
                    class="flex flex-row gap-8 md:gap-8 w-full max-w-full overflow-hidden"
                >
                    <!-- モバイルでは2番目に表示する画像、PC(md以上)では右側50%幅 -->
                    <div
                        class="w-full md:w-1/2 order-2 md:order-2 mb-6 md:mb-0"
                    >
                        <img
                            src="/images/craft.jpg"
                            alt="ARタブレット"
                            class="w-full md:w-auto mx-0 md:mx-auto h-auto md:h-[500px] object-cover"
                        />
                    </div>
                    <!-- モバイルでは3番目に表示するテキスト、PC(md以上)では左側50%幅 -->
                    <div class="w-full md:w-1/2 order-1 md:order-1 md:pt-20">
                        <div
                            class="text-xs md:text-[15px] font-normal leading-relaxed"
                        >
                            <!-- デスクトップ表示時のみ表示 -->
                            <div class="hidden md:block">
                                <h2
                                    class="text-lg uppercase tracking-widest mb-4 extended-underline"
                                >
                                    CRAFT
                                </h2>
                            </div>
                            <p>
                                このたび、私たちのブランドは新たな店舗をオープンいたしました。<br />
                                店内では、熟練職人が一つひとつ丹念に仕上げた最高品質のレザー製品を手に取り、その肌触りや美しさをご体感いただけます。<br /><br />
                                さらに、最新のAR機能を活用したオリジナルバッグのデザイン体験もお楽しみいただけます。レザーの質感や色合いを自由に組み合わせ、理想のアイテムを生み出す新しいショッピングの楽しみを提供します。<br /><br />
                                皆様のご来店を心よりお待ちしております。
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- LEATHER セクション -->
            <section class="mb-14 md:mb-16 px-4 md:py-24">
                <!-- スマホ表示時のタイトル -->

                <div
                    class="flex flex-row gap-8 md:gap-8 w-full max-w-full overflow-hidden"
                >
                    <!-- モバイルでは画像が2番目、デスクトップでは左側に配置 -->
                    <div
                        class="w-full md:w-1/2 order-1 md:order-1 mb-6 md:mb-0 pt-4"
                    >
                        <img
                            src="/images/leather.jpg"
                            alt="ARタブレット"
                            class="w-full md:w-auto mx-0 md:mx-auto h-auto md:h-[500px] object-cover"
                        />
                    </div>
                    <!-- モバイルではテキストが3番目、デスクトップでは右側に配置 -->
                    <div class="w-full md:w-1/2 order-2 md:order-2 md:pt-28">
                        <h2 class="mb-6 md:hidden md:text-[20px]">
                            <span
                                class="text-lg uppercase tracking-widest inline-block extended-underline-reverse"
                            >
                                Leather
                            </span>
                        </h2>
                        <div class="text-[15px] font-normal leading-relaxed">
                            <!-- デスクトップ表示時のみ表示 -->
                            <div class="hidden md:block">
                                <h2
                                    class="text-lg uppercase tracking-widest mb-6 extended-underline-reverse"
                                >
                                    LEATHER
                                </h2>
                            </div>
                            <p>
                                私たちのブランドが誇るのは、熟練した職人による最高品質のレザー。<br />
                                素材へのこだわりと、魂を込めたクラフトマンシップ。<br />
                                そのすべてが、あなただけの特別な一品となってお手元に届きます。
                            </p>
                            <div class="extended-underline-reverse"></div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="text-center pb-20 text-sm">
                <a
                    href="#"
                    class="cursor-pointer inline-flex items-center gap-1"
                    aria-label="戻る"
                >
                    <span>&lt;</span>
                    <span>戻る</span>
                </a>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";

const page = usePage();
// アプリ名を取得して大文字に変換
const appName = page.props.appName?.toUpperCase() || "WAMORE";
</script>

<style scoped>
.concept-page {
    color: #3c3d3e;
}

h2 {
    letter-spacing: 0.1em;
    font-weight: normal;
}

img {
    max-width: 100%;
}

/* 下線を左側のみ画面外まで伸ばし、右側はテキスト幅に合わせる */
.extended-underline {
    position: relative;
    display: inline-block;
}

/* md:hidden との競合を解決する */
@media (min-width: 768px) {
    .md\:hidden.extended-underline {
        display: none !important;
    }
}

.extended-underline::after {
    content: "";
    position: absolute;
    height: 2px;
    background-color: #d0d1d2;
    bottom: -6px;
    width: calc(100% + 10px + 1000px);
    right: 0;
    left: -1000px;
    z-index: 1;
}

/* 下線を右側のみ画面外まで伸ばし、左側はテキスト幅に合わせるスタイル */
.extended-underline-reverse {
    position: relative;
    display: inline-block;
}

/* md:hidden との競合を解決する */

.extended-underline-reverse::after {
    content: "";
    position: absolute;
    height: 2px;
    background-color: #d0d1d2;
    bottom: -6px;
    width: calc(100% + 1000px); /* テキストの幅 + 右側に伸ばす分 */
    left: -10px; /* 左端はテキストに合わせる */
    right: -1000px; /* 右側だけ伸ばす */
    z-index: 1;
}

@media (min-width: 768px) {
    .extended-underline::after {
        bottom: -2px;
    }

    .extended-underline-reverse::after {
        bottom: -2px;
    }
}
</style>
