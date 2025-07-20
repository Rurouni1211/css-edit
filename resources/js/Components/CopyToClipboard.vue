<script setup>
import Swal from 'sweetalert2';
import { ref } from 'vue';

// テキストをプロパティとして受け取る
const props = defineProps({
    text: {
        type: String,
    }
});

// クリップボードにコピーする関数
function handleCopy() {
    // テキストをクリップボードにコピー
    const textToCopy = props.text || '';
    
    // 新しいClipboard API使用
    navigator.clipboard.writeText(textToCopy)
        .then(() => {
            Swal.success('クリップボードにコピーしました');
        })
        .catch((err) => {
            console.error('クリップボードへのコピーに失敗しました:', err);
        });
}
</script>

<template>
    <button @click="handleCopy" class="inline-flex items-center" title="クリップボードにコピー">
        <i class="fa fa-clone text-main-400 hover:text-main-600"></i>
    </button>
</template>