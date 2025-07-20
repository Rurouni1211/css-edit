<script setup lang="ts">
import { onMounted, ref } from 'vue';

// Common
type PositionItemKey =
    | 'index'
    | 'fontSize'
    | 'fontFamily'
    | 'kerning'
    | 'baseLine';
interface PositionItem {
    index: number;
    fontSize?: number;
    fontFamily?: string;
    kerning?: number;
    baseLine?: number;
}
const props = defineProps<{
    text: string;
    positionItems: PositionItem[];
}>();

// Text position
interface TextSize {
    width: number;
    height: number;
}
const textRef = ref<HTMLElement | null>(null);
let currentX = 0;
let maxHeight = 0;
const getTextSize = (
    char: string,
    fontSize: string,
    fontFamily: string,
): TextSize => {
    const span = document.createElement('span');
    span.style.visibility = 'hidden';
    span.style.position = 'absolute';
    span.style.whiteSpace = 'nowrap';
    if (fontSize) span.style.fontSize = `${fontSize}px`;
    if (fontFamily) span.style.fontFamily = fontFamily;
    span.textContent = char;
    document.body.appendChild(span);
    const width = span.offsetWidth;
    const height = span.offsetHeight;
    document.body.removeChild(span);
    return { width, height };
};
const getStyleString = (
    key: PositionItemKey,
    positionItem: PositionItem | undefined,
): string => {
    if (textRef.value) {
        const styleValue =
            positionItem && key in positionItem
                ? positionItem[key]
                : window.getComputedStyle(textRef.value).getPropertyValue(key);
        return String(styleValue);
    }
    return '';
};
const getStyleNumber = (
    key: PositionItemKey,
    positionItem: PositionItem | undefined,
): number => {
    if (textRef.value) {
        const styleValue =
            positionItem && key in positionItem
                ? positionItem[key]
                : window.getComputedStyle(textRef.value).getPropertyValue(key);
        return Number(styleValue);
    }
    return 0;
};
const addText = (char: string, positionItem: PositionItem | undefined) => {
    if (textRef.value) {
        const fontSize = getStyleString('fontSize', positionItem);
        const fontFamily = getStyleString('fontFamily', positionItem);
        const kerning = getStyleNumber('kerning', positionItem);
        const baseLine = getStyleNumber('baseLine', positionItem);
        const textSize = getTextSize(char, fontSize, fontFamily);
        let newX = currentX;
        let newY = baseLine;

        const textElement = document.createElement('span');
        textElement.textContent = char;
        textElement.style.position = 'absolute';
        textElement.style.fontSize = `${fontSize}px`;
        textElement.style.fontFamily = fontFamily;
        textElement.style.left = `${newX}px`;
        textElement.style.top = `${newY}px`;
        textElement.style.lineHeight = `${textSize.height}px`;
        textRef.value.appendChild(textElement);

        currentX += textSize.width + kerning;

        const newHeight = textSize.height + baseLine;
        maxHeight = Math.max(maxHeight, newHeight);
    }
};
onMounted(() => {
    const text = props.text;
    for (let i = 0; i < text.length; i++) {
        const char = text.charAt(i);
        const positionItem = props.positionItems.find(
            (item) => item.index === i,
        );
        addText(char, positionItem);
    }
    if (textRef.value) {
        textRef.value.style.width = `${currentX}px`;
        textRef.value.style.height = `${maxHeight}px`;
    }
});
</script>

<template>
    <div class="text" ref="textRef"></div>
</template>

<style scoped>
.text {
    position: relative;
}
</style>