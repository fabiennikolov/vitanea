<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <!--
      An aronia berry with its calyx star — the fruit that goes into almost
      every Vitanea product. Uses `currentColor` so it inherits whatever
      surface it sits on.
    -->
    <svg viewBox="0 0 64 64" :class="className" v-bind="$attrs">
        <circle cx="32" cy="32" r="26" fill="currentColor" />
        <path
            d="M32 21.5 L34.65 28.49 L42.1 28.84 L36.28 33.51 L38.25 40.72 L32 36.62 L25.75 40.72 L27.72 33.51 L21.9 28.84 L29.35 28.49 Z"
            class="fill-cream-100"
        />
    </svg>
</template>
