<script setup>

import NormalButton from "@/Components/Buttons/NormalButton.vue";
import CancelButton from "@/Components/Buttons/CancelButton.vue";
import SubButton from "@/Components/Buttons/SubButton.vue";
import {onMounted, ref} from "vue";

// Common
const props = defineProps({
    isPreparing: {
        type: Boolean,
        default: false,
    },
    showConfirm: {
        type: Boolean,
        default: false,
    }
});
const emit = defineEmits([
    'yes-click',
    'no-click',
    'confirm-click',
]);

// Events
const onYesClick = () => {

    emit('yes-click');

};
const onNoClick = () => {

    emit('no-click');

};
const onConfirmClick = () => {

    emit('confirm-click');

};

// Scroll
const showScrollToTop = ref(false);
const onClickScrollToTop = () => {

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

};
onMounted(() => {

    window.addEventListener('scroll', () => {

        showScrollToTop.value = window.scrollY > 100;

    });

});

</script>

<template>
    <div class="fixed bottom-0 right-0 w-full md:grid grid-cols-2 items-center justify-center px-5 py-2 border-t-4 border-gray-300 bg-gray-300">
        <div class="flex-1 items-center flex mb-3 md:mb-0">
            <slot name="left"></slot>
            <a href="#" class="text-gray-600 ml-5" v-if="showScrollToTop" @click.prevent="onClickScrollToTop">
                <i class="fa fa-arrow-circle-up mr-0.5 text-sm"></i>
                <span class="text-xs">トップへ</span>
            </a>
        </div>
        <div class="md:text-right pb-2 sm:pb-0">
            <span class="pr-5 text-sm text-red-600" v-if="isPreparing">
            保存するまで反映されません
        </span>
            <slot name="right"></slot>
            <SubButton v-if="showConfirm" class="mr-2" @click="onConfirmClick">確認</SubButton>
            <CancelButton class="mr-2" @click="onNoClick">キャンセル</CancelButton>
            <NormalButton @click="onYesClick">送信する</NormalButton>
        </div>
    </div>
</template>
