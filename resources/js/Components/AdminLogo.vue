<script setup>
import {usePage} from "@inertiajs/vue3";
import TextPosition from '/var/www/html/wamore/resources/js/Components/TextPosition.vue'

const page = usePage();
const multiAuthGuard = page.props.multiAuthGuard;
</script>

<template>
    <a
        :href="route(`${multiAuthGuard}.dashboard`)"
        class="text-main-500 opacity-85">
        <TextPosition
            text="WAMORE Admin System"
            :position-items="[
                { index: 0, fontSize: 22, fontFamily: 'Montserrat', kerning: 2.2, baseLine: -4 },
                { index: 1, fontSize: 22, fontFamily: 'Montserrat', kerning: 2, baseLine: -4 },
                { index: 2, fontSize: 22, fontFamily: 'Montserrat', kerning: 1, baseLine: -4 },
                { index: 3, fontSize: 22, fontFamily: 'Montserrat', kerning: 1, baseLine: -4 },
                { index: 4, fontSize: 22, fontFamily: 'Montserrat', kerning: 1.5, baseLine: -4 },
                { index: 5, fontSize: 22, fontFamily: 'Montserrat', kerning: 1, baseLine: -4 },
                { index: 6, fontSize: 18, fontFamily: 'Montserrat', kerning: 8, baseLine: 0 },
                { index: 7, fontSize: 18, fontFamily: 'Montserrat', kerning: 0.5, baseLine: 0 },
                { index: 8, fontSize: 18, fontFamily: 'Montserrat', kerning: 0.5, baseLine: 0 },
                { index: 9, fontSize: 18, fontFamily: 'Montserrat', kerning: 0, baseLine: 0 },
                { index: 10, fontSize: 18, fontFamily: 'Montserrat', kerning: 0.2, baseLine: 0 },
                { index: 11, fontSize: 18, fontFamily: 'Montserrat', kerning: 0, baseLine: 0 },
                { index: 12, fontSize: 18, fontFamily: 'Montserrat', kerning: 6, baseLine: 0 },
                { index: 13, fontSize: 18, fontFamily: 'Montserrat', kerning: 0.5, baseLine: 0 },
                { index: 14, fontSize: 18, fontFamily: 'Montserrat', kerning: 0.5, baseLine: 0 },
                { index: 15, fontSize: 18, fontFamily: 'Montserrat', kerning: 1.4, baseLine: 0 },
                { index: 16, fontSize: 18, fontFamily: 'Montserrat', kerning: 0.3, baseLine: 0 },
                { index: 17, fontSize: 18, fontFamily: 'Montserrat', kerning: 0, baseLine: 0 },
                { index: 18, fontSize: 18, fontFamily: 'Montserrat', kerning: 0, baseLine: 0 },
            ]"
            />
    </a>
</template>
