<script setup>
import Input from "@/Pages/Admin/Material/Input.vue";

const props = defineProps({
    accept: {
        type: String,
        default: 'image/jpeg,image/png',
    },
    multiple: {
        type: Boolean,
        default: false,
    }
});
const emit = defineEmits(['change']);
const onChangeFile = (e) => {

    emit('change', e);

};

</script>

<template>
    <label class="cursor-pointer bg-indigo-500 text-white px-3 py-1 text-sm text-nowrap">
        <slot>ファイル選択</slot>
        <input
            type="file"
            class="hidden"
            :accept="accept"
            @change="onChangeFile"
            :multiple="multiple"
        />
    </label>
</template>
