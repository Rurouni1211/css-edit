<script setup>

// Common
import {computed, ref} from "vue";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        default: '',
    },
    target: {
        type: String,
        default: '_self',
    },
    classNames: {
        type: Object,
        default: () => {},
    },
});
const emit = defineEmits([
    'click',
]);

// Event
const onClick = () => {

    emit('click');

};

// Button type
const href = ref(props.href);
const hasHref = computed(() => {

    return href.value !== '';

});
const buttonType = computed(() => {

    if(! hasHref.value) {

        return 'button';

    } else if(props.target === '_blank') {

        return 'a';

    } else {

        return 'link';

    }

});
const isButtonTypeButton = computed(() => {

    return buttonType.value === 'button';

});
const isButtonTypeLink = computed(() => {

    return buttonType.value === 'link';

});
const isButtonTypeATag = computed(() => {

    return buttonType.value === 'a';

});

</script>

<template>
    <button
        :class="classNames"
        v-if="isButtonTypeButton"
        @click="onClick">
        <slot>実行する</slot>
    </button>
    <Link
        :class="classNames"
        :href="href"
        v-else-if="isButtonTypeLink">
        <slot>実行する</slot>
    </Link>
    <a
        :class="classNames"
        :href="href"
        v-else-if="isButtonTypeATag"
        target="_blank">
        <slot>実行する</slot>
    </a>
</template>
