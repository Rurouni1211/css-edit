<script setup>

const props = defineProps({
    originalFilename: {
        type: String,
        default: '',
    },
    url: {
        type: String,
        default: '',
    },
    file: {
        type: File,
        default: null,
    },
});
const emit = defineEmits([
    'delete-file',
    'clear-file',
]);

// Events
const onDelete = () => {

    emit('delete-file');

};
const onFileDelete = () => {

    emit('clear-file');

};

</script>

<template>

    <div v-if="originalFilename">
        <span class="mr-1.5">{{ originalFilename }}</span>
        <span v-if="url">
        <a
            :href="url"
            class="text-indigo-600 hover:underline text-xs py-1 text-nowrap"
            target="_blank">確認</a>
        <a href="#" class="text-red-600 ml-1.5 text-nowrap" @click.prevent="onDelete">削除</a>
    </span>
    </div>
    <div class="py-1 text-xs" v-if="file">
        <div class="pl-1" v-if="originalFilename">↓</div>
        <span class="mr-1.5">{{ file.name }}</span>
        <a href="#" class="text-yellow-700 text-nowrap" @click.prevent="onFileDelete">クリア</a>
    </div>

</template>
