<script setup>
import { usePage } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { footerLinks as footerLinksData } from "@/Utils/Navigation.js";
const page = usePage();
const appName = page.props.appName.toUpperCase();
const footerLinks = ref(footerLinksData);
const year = new Date().getFullYear();
</script>

<template>
    <footer class="bg-gray-600 text-white text-center py-8">
        <div class="container mx-auto space-y-4">
            <!-- Logo / Brand -->
            <h2 class="text-2xl tracking-widest font-light text-white">
                WAMORE
            </h2>

            <!-- Links -->
            <div class="space-y-1 text-sm">
                <p class="text-white">ブランドについて</p>
                <p class="text-white">お問い合わせ</p>
                <p class="text-white">info@wamore.com</p>
            </div>

            <!-- Social Icons -->
            <div class="flex justify-center space-x-4 pt-2">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-line"></i></a>
            </div>
        </div>

        <!-- Bottom bar -->
        <div class="bg-gray-200 text-gray-500 text-xs py-2 mt-6">© WAMORE</div>
    </footer>
</template>

<style>
footer svg path {
    fill: #fafafa !important;
}
</style>
