<script setup lang="ts">
import { ref, watch } from 'vue';
import { randomScribble } from '@/lib/scribble';
import type { Mark } from '@/lib/scribble';

/**
 * A mark that draws itself under a label on hover and un-draws on the way out.
 *
 * The stroke is animated with `pathLength="1"`, so the dash maths is the same
 * whatever shape the generator returns, and a new shape is picked on every
 * entry — the mark is never twice the same.
 */
type Props = {
    active: boolean;
    tone?: 'dark' | 'light';
};

const { active, tone = 'dark' } = defineProps<Props>();

const previous = ref<Mark | undefined>(undefined);
const path = ref(randomScribble().path);

watch(
    () => active,
    (isActive) => {
        if (!isActive) {
            return;
        }

        // Redraw only on entry: changing `d` mid-retract would make the line
        // jump to a different shape while it is still leaving.
        const next = randomScribble(previous.value);

        previous.value = next.mark;
        path.value = next.path;
    },
);
</script>

<template>
    <svg
        class="pointer-events-none absolute inset-x-0 -bottom-2 h-3 w-full overflow-visible"
        viewBox="0 0 100 16"
        preserveAspectRatio="none"
        fill="none"
        aria-hidden="true"
    >
        <path
            :d="path"
            :class="[
                'transition-[stroke-dashoffset] duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] motion-reduce:transition-none',
                tone === 'light' ? 'stroke-cream-50' : 'stroke-berry-600',
            ]"
            :style="{ strokeDashoffset: active ? 0 : 1 }"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            vector-effect="non-scaling-stroke"
            pathLength="1"
            stroke-dasharray="1"
        />
    </svg>
</template>
