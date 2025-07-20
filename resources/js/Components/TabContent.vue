<script setup>

import { ref } from 'vue';

const props = defineProps({
    tabs: {
        type: Array,
        required: true,
    },
    activeTab: {
        type: String,
    },
});

const tabs = ref(props.tabs);
const activeTab = ref(props.activeTab || tabs.value[0].key);

// Class names
const getTabButtonClassNames = (tab) => {

    const isActive = activeTab.value === tab.key;

    if(isActive === true) {

        return 'py-2 px-4 text-gray-600 border-b-2 border-blue-500';

    }

    return 'py-2 px-4 text-gray-600';

};

</script>

<style scoped>

    .tabcontent {
        display: none;
    }

</style>

<template>

    <!-- Tab Buttons -->
    <div class="flex space-x-2 border-b">
        <button
            v-for="tab in tabs"
            :key="tab"
            @click="activeTab = tab.key"
            :class="getTabButtonClassNames(tab)"
        >
            {{ tab.label }}
        </button>
    </div>

    <!-- Tab Contents -->
    <div v-for="tab in tabs" :key="tab.key">
        <slot :name="tab.key" v-if="activeTab === tab.key"></slot>
    </div>

</template>
