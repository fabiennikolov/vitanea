<script setup lang="ts">
import { cn } from '@/lib/utils';

/**
 * Eyebrow + headline + subheadline. Per the heading-group rules the wrapper
 * carries no width constraint — each text element is constrained individually
 * in `ch` so the measure stays right at every size.
 */
type Props = {
    eyebrow?: string;
    headline: string;
    subheadline?: string;
    align?: 'left' | 'center';
    tone?: 'dark' | 'light';
};

const {
    eyebrow = '',
    subheadline = '',
    align = 'left',
    tone = 'dark',
} = defineProps<Props>();
</script>

<template>
    <div :class="cn(align === 'center' && 'text-center')">
        <p
            v-if="eyebrow"
            :class="
                cn(
                    'font-mono text-sm tracking-wide uppercase',
                    tone === 'light' ? 'text-cream-100/80' : 'text-plum-900/80',
                )
            "
        >
            {{ eyebrow }}
        </p>

        <h2
            :class="
                cn(
                    'max-w-[30ch] font-display text-4xl font-black tracking-tight text-balance sm:text-5xl',
                    eyebrow && 'mt-5',
                    align === 'center' && 'mx-auto',
                    tone === 'light' ? 'text-cream-50' : 'text-plum-950',
                )
            "
        >
            {{ headline }}
        </h2>

        <p
            v-if="subheadline"
            :class="
                cn(
                    'mt-5 max-w-[48ch] text-lg text-pretty',
                    align === 'center' && 'mx-auto',
                    tone === 'light' ? 'text-cream-100/85' : 'text-plum-900/85',
                )
            "
        >
            {{ subheadline }}
        </p>
    </div>
</template>
