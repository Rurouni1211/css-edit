<script setup>
import FileInput from "@/Components/FileInput.vue";
import {computed, ref} from "vue";
import draggable from 'vuedraggable'

const props = defineProps({
    defaultImages: {
        type: Array,
        default() {
            return [];
        },
    },
    accept: {
        type: String,
        default: 'image/jpeg,image/png',
    },
});
const emit = defineEmits(['change']);

// Image
const deletedImages = ref([]);
const deletedImageIds = computed(() => {

    return deletedImages.value.map((deletedImage) => {

        return Number(deletedImage.id);

    });

});
const filteredDefaultImages = ref(props.defaultImages); // Draggableがあるので、computedでなくrefで定義

// Add image
let newImages = [];
const previewImageItems = ref([]);
const onFileChange = (e) => {

    const files = e.target.files;

    if(files.length > 0) {

        [].forEach.call(files, (file) => {

            newImages.push(file);

            const reader = new FileReader();
            reader.onload = (e) => {

                const id = Math.random().toString(32).substring(2);
                previewImageItems.value.push({
                    id: id,
                    imageData: e.target.result,
                });

            };
            reader.readAsDataURL(file);

        });

        emit('change', emitData.value);

    }

};

// Delete image
const onDeleteNewImage = (index) => {

    newImages.splice(index, 1);
    previewImageItems.value.splice(index, 1);
    emit('change', emitData.value);

};
const onDeleteDefaultImage = (defaultImage) => {

    Swal.confirm('delete')
        .then((result) => {

            if (result.isConfirmed) {

                deletedImages.value.push(defaultImage);
                filteredDefaultImages.value = props.defaultImages.filter((defaultImage) => {

                    const defaultImageId = Number(defaultImage.id);
                    return ! deletedImageIds.value.includes(defaultImageId);

                });

                emit('change', emitData.value);

            }

        });

};

// Sort image
const onImageSortChange = () => {

    emit('change', emitData.value);

};

// Emit
const emitData = computed(() => {

    return {
        newImages: newImages,
        deletedImageIds: deletedImageIds.value,
        sortImageIds: filteredDefaultImages.value.map((defaultImage) => {

            return Number(defaultImage.id);

        }),
    };

});

</script>

<template>
    <div class="p-5 bg-gray-100">
        <div>
            <FileInput :accept="accept" :multiple="true" @change="onFileChange">画像選択</FileInput>
        </div>
        <template v-if="previewImageItems.length > 0">
            <div class="mb-1 mt-3">
                <small>追加する画像：</small>
            </div>
            <div class="grid grid-cols-4 gap-3 mb-3">
                <div class="relative" v-for="(item,index) in previewImageItems">
                    <img :src="item.imageData" class="w-full">
                    <button class="absolute right-2 top-1.5 rounded-full bg-gray-200 text-black w-4 h-4 grid place-items-center text-sm" style="font-size:1rem;" @click="onDeleteNewImage(index)">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        </template>
        <template v-if="filteredDefaultImages.length > 0">
            <div class="mb-1 mt-3">
                <small>登録済みの画像：</small>
                <span class="text-xs text-gray-500">
                    ドラッグ＆ドロップで並び替えできます
                </span>
            </div>
            <draggable class="grid grid-cols-4 gap-3" v-model="filteredDefaultImages" item-key="id" @change="onImageSortChange">
                <template #item="{ element: defaultImage }">
                    <div class="relative">
                        <a :href="defaultImage.url" target="_blank">
                            <img :src="defaultImage.url" class="w-full">
                        </a>
                        <button class="absolute right-1.5 top-1.5 rounded-full bg-gray-200 text-black w-4 h-4 grid place-items-center text-sm" style="font-size:1rem;" @click="onDeleteDefaultImage(defaultImage)">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </template>
            </draggable>
        </template>
    </div>
</template>
