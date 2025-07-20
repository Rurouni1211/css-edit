<template>

    <div class="sm:flex md:items-center sm:mb-3">
        <div>
            <TextInput ref="inputElement" type="search" class="w-full md:w-80 mr-2 mb-2 sm:mb-0" :placeholder="placeholder" v-model="searchKeyword" @input="onInput" @keydown.enter="onSearch" />
        </div>
        <div class=" mb-4 sm:mb-0 text-right sm:text-left">
            <NormalButton size="sm" class="mr-1 text-nowrap" @click="onSearch">検索</NormalButton>
            <CancelButton size="sm" class="text-nowrap" @click="onClear">クリア</CancelButton>
        </div>
    </div>

</template>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import NormalButton from "@/Components/Buttons/NormalButton.vue";
import {ref} from "vue";
import CancelButton from "@/Components/Buttons/CancelButton.vue";

const props = defineProps({
    modelValue: {
        required: true,
    },
    placeholder: {
        type: String,
        default: '検索キーワード',
    }
});
const emit = defineEmits([
    'update:modelValue',
    'search',
    'clear',
]);

const searchKeyword = ref(props.modelValue);
const inputElement = ref(null);

// Events
const onSearch = () => {

    emit('search', searchKeyword.value);

};
const onClear = () => {

    searchKeyword.value = '';
    emit('clear');
    inputElement.value.focus();

};
const onInput = () => {

    emit('update:modelValue', searchKeyword.value);

};

</script>
