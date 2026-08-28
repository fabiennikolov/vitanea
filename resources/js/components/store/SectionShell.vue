<script setup lang="ts">
import { useReveal } from '@/composables/useReveal';
import { cn } from '@/lib/utils';

/**
 * The two-element section pattern used across the whole storefront: the outer
 * element owns the ground and vertical rhythm, the inner one owns the max width
 * and horizontal padding. Using it everywhere is what keeps content edges
 * aligned as you scroll the page.
 */
type Props = {
    ground?: 'cream' | 'sage' | 'plum';
    as?: string;
};

const { ground = 'cream', as = 'section' } = defineProps<Props>();

// Every section is a shell, so hooking the reveal here covers the whole
// storefront without a directive on each page.
const { target, revealed } = useReveal();

const grounds: Record<NonNullable<Props['ground']>, string> = {
    cream: 'bg-cream-100 text-plum-950',
    sage: 'bg-sage-200 text-plum-950',
    plum: 'bg-plum-800 text-cream-100',
};
</script>

<template>
    <component
        :is="as"
        :class="
            cn(
                'relative isolate overflow-hidden py-16 lg:py-24',
                grounds[ground],
            )
        "
    >
        <div
            ref="target"
            :class="
                cn(
                    'relative mx-auto max-w-7xl px-6 transition-[opacity,transform,translate,rotate,scale] duration-700 ease-[cubic-bezier(0.16,1,0.3,1)] motion-reduce:transition-none lg:px-8',
                    revealed
                        ? 'translate-y-0 opacity-100'
                        : 'translate-y-6 opacity-0',
                )
            "
        >
            <slot />
        </div>
    </component>
</template>
