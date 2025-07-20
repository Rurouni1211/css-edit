<script setup>
import {usePage} from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { footerLinks as footerLinksData } from '@/Utils/Navigation.js';
const page = usePage();
const appName = page.props.appName.toUpperCase();
const footerLinks = ref(footerLinksData);
const year = new Date().getFullYear();
</script>

<template>
  <footer class="bg-[#808080] text-main-50 pt-10 pb-4">
    <div class="px-8 lg:container mx-auto px-4">
      <div class="flex flex-col md:flex-row">
        <!-- ロゴ部分 -->
        <div class="mb-4 md:mb-0 w-2/5">
          <Link href="/" class="inline-block">
            <h3 class="font-normal text-2xl mb-6 tracking-widest font-montserrat [text-shadow:_0_1px_5px_rgb(0_0_0_/_10%)] text-main-50">{{ appName }}</h3>
          </Link>
        </div>

        <!-- フッターナビゲーション -->
        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-16 text-nowrap">
          <div v-for="(sectionLinks,index) in footerLinks" :key="index" class="col-span-1">
            <ul class="space-y-2 text-sm">
              <li v-for="link in sectionLinks" :key="link.name" class="mb-2">
                <a v-if="link.url" :href="link.url" class="hover:underline text-main-50" :target="link.target || '_self'">
                  {{ link.name }}
                </a>
                <div v-else class="text-main-50">
                  {{ link.name }}
                </div>
                <p v-if="link.content">
                  <span class="text-xs text-main-50 mt-1" v-html="link.content"></span>
                </p>
              </li>
            </ul>
          </div>
        </div>
      </div>
      
      <!-- ソーシャルメディアリンクとコピーライト -->
      <div class="mt-12">
        <div class="text-xs text-main-50 text-center">{{ `©${year} ${appName}` }}</div>
      </div>
    </div>
  </footer>
</template>

<style>

footer svg path {
fill: #fafafa !important;
}

</style>