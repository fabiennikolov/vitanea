<script setup lang="ts">
import type { LucideIcon } from '@lucide/vue';
import { cn } from '@/lib/utils';

/**
 * A single composition fact. These carry statements about what is *in* a
 * product — never about what it does to the person drinking it, which EU
 * Reg. 1924/2006 does not permit for aronia.
 */
type Props = {
    label: string;
    icon?: LucideIcon;
    tone?: 'dark' | 'light';
};

const { tone = 'dark' } = defineProps<Props>();
</script>

<template>
    <span
        :class="
            cn(
                'inline-flex items-center gap-2.5 rounded-full py-2 pr-4',
                icon ? 'pl-2' : 'pl-4',
                tone === 'light'
                    ? 'bg-cream-50/10 ring-1 ring-cream-50/20'
                    : 'bg-cream-50/70 ring-1 ring-plum-950/10',
            )
        "
    >
        <component
            :is="icon"
            v-if="icon"
            :class="
                cn(
                    'size-5 shrink-0',
                    tone === 'light' ? 'stroke-cream-100' : 'stroke-plum-800',
                )
            "
        />
        <span
            :class="
                cn(
                    'font-mono text-sm tracking-wide uppercase',
                    tone === 'light' ? 'text-cream-100' : 'text-plum-900',
                )
            "
        >
            {{ label }}
        </span>
    </span>
</template>
