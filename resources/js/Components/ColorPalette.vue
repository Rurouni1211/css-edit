<script setup>
import {computed, ref, toRefs} from 'vue';
const props = defineProps({
    colorCode: {
        type: String,
        default: '#FFFFFF',
    },
    size: {
        type: Number,
        default: 20,
    },
});

// Color code
const refs = toRefs(props);
const colorCode = refs.colorCode;

// Container
const styles = computed(() => {

    const border = (hasColorCode.value === true)
        ? '1px solid #ccc'
        : 'none';
    const targetColorCode = (hasColorCode.value === true)
        ? colorCode.value
        : '#FFFFFF';
    const size = ref(props.size);

    return {
        border: border,
        backgroundColor: targetColorCode,
        width: `${size.value}px`,
        height: `${size.value}px`,
    };

});
const hasColorCode = computed(() => {

    return /^#[0-9a-f]{6}$/i.test(colorCode.value);

});

</script>

<template>

    <div class="" :style="styles">
        <div class="text-center" v-if="! hasColorCode">
            <i class="fa fa-times text-red-600" v-if="! hasColorCode" />
        </div>
    </div>

</template>
