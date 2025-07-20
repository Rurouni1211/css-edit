<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import NavLink from "@/Components/NavLink.vue";
import { usePage } from '@inertiajs/vue3';
const page = usePage();
const multiAuthGuardName = page.props.multiAuthGuardName;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-indigo-50 leading-tight">{{ multiAuthGuardName }}ダッシュボード</h2>
        </template>

        <div class="sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="grid md:grid-cols-4 gap-4 p-0 sm:p-2">
                        <div class="relative p-7 min-h-44">
                            <div class="absolute" style="right:10px;top:73px;opacity:.5;">
                                <img src="/images/illustrations/main.svg" style="height:70px;">
                            </div>
                            <h3 class="font-semibold mb-3 border-b-4 border-indigo-500 pb-1.5 ml-1">メイン<small class="text-gray-600">（共通項目）</small></h3>
                            <ul class="dashboard-nav-link">
                                <li>
                                    <NavLink :href="route('admin.shop.index')">店舗管理</NavLink>
                                </li>
                                <li>
                                    <NavLink :href="route('admin.artisan.index')">職人管理</NavLink>
                                </li>
                                <li>
                                    <NavLink :href="route('admin.order.index')">注文管理</NavLink>
                                </li>
                            </ul>
                        </div>
                        <div class="relative p-7 min-h-44">
                            <div class="absolute" style="right:25px;top:73px;opacity:.5;">
                                <img src="/images/illustrations/product.svg" style="height:70px;">
                            </div>
                            <h3 class="font-semibold mb-3 border-b-4 border-indigo-500 pb-1.5 ml-1">商品<small class="text-gray-600">（360°VR）</small></h3>
                            <ul class="dashboard-nav-link">
                                <li>
                                    <NavLink :href="route('admin.product.index')">商品管理</NavLink>
                                </li>
                                <li>
                                    <NavLink :href="route('admin.material.index')">素材管理</NavLink>
                                </li>
                                <li>
                                    <NavLink :href="route('admin.product_category.index')">カテゴリ管理</NavLink>
                                </li>
                            </ul>
                        </div>
<!--                        <div class="relative p-7 min-h-44">-->
<!--                            <div class="absolute" style="right:25px;top:73px;opacity:.5;">-->
<!--                                <img src="/images/illustrations/item.svg" style="height:70px;">-->
<!--                            </div>-->
<!--                            <h3 class="font-semibold mb-3 border-b-4 border-indigo-500 pb-1.5 ml-1">商品<small class="text-gray-600">（完成品）</small></h3>-->
<!--                            <ul class="dashboard-nav-link">-->
<!--                                <li>-->
<!--                                    <NavLink :href="route('admin.item.index')">商品管理</NavLink>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <NavLink :href="route('admin.item_category.index')">カテゴリ管理</NavLink>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
