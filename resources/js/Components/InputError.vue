<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-if="message">
        <p class="input-error text-sm text-red-600 px-1 py-1">
            {{ message }}
        </p>
    </div>
</template>
