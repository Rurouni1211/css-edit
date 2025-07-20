<script setup>
import {ref, watch} from "vue";

// Common
const props = defineProps({
    links: {
        type: Object,
        required: true,
    },
});
const internalLinks = ref(props.links);
watch(
    () => props.links,
    (values) => {

        internalLinks.value = values;

    });
const emit = defineEmits(['move-page']);

// CSS
const getLinkClassNames = (link) => {

    const baseClasses = 'block py-2 px-4 leading-none border-indigo-700';
    const activeClasses = 'bg-indigo-300 text-indigo-800';
    const inactiveClasses = 'bg-indigo-50 hover:bg-indigo-100';

    return link.active === true
        ? `${baseClasses} ${activeClasses}`
        : `${baseClasses} ${inactiveClasses}`;

};

// Event
const onClick = (link) => {

    if (link.url) {

        const matches = link.url.match(/page=(\d+)/);

        if (matches) {

            // emit here
            emit('move-page', matches[1]);

        }

    }
};

</script>

<template>
    <nav>
        <ul class="flex list-none pl-0">
            <li
                v-for="link in internalLinks"
                :key="link.label"
                @click.prevent="onClick(link)"
                class="mr-1.5">
                <a
                    href="#"
                    :class="getLinkClassNames(link)"
                    v-html="link.label">
                </a>
            </li>
        </ul>
    </nav>

</template>
