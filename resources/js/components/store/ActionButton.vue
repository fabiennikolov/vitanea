<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { InertiaLinkProps } from '@inertiajs/vue3';
import { ArrowRight } from '@lucide/vue';
import { ref } from 'vue';
import ScribbleUnderline from '@/components/store/ScribbleUnderline.vue';
import { cn } from '@/lib/utils';

/**
 * The storefront's only two button treatments. Every page uses exactly one
 * `primary` and pairs it with `link` for the secondary action — a second solid
 * button anywhere on a page means one of them is wrong.
 *
 * The `link` variant carries a drawn mark instead of a CSS underline: it is
 * struck on hover and un-struck on the way out, and the shape differs every
 * time. Pointer and keyboard both drive it, so it is not a mouse-only flourish.
 */
type Props = {
    href: NonNullable<InertiaLinkProps['href']>;
    variant?: 'primary' | 'link';
    tone?: 'dark' | 'light';
    withArrow?: boolean;
};

const {
    variant = 'primary',
    tone = 'dark',
    withArrow = false,
} = defineProps<Props>();

const marked = ref(false);
</script>

<template>
    <Link
        :href="href"
        view-transition
        :class="
            cn(
                'group text-lg font-bold transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] focus-visible:outline-2 focus-visible:outline-offset-2',
                variant === 'primary' &&
                    'inline-flex items-center gap-3 rounded-full py-4 pr-5 pl-8 hover:-translate-y-0.5 active:scale-[0.98] motion-reduce:hover:translate-y-0',
                variant === 'primary' &&
                    (tone === 'light'
                        ? 'bg-cream-50 text-plum-950 hover:bg-cream-100 focus-visible:outline-cream-50'
                        : 'bg-plum-900 text-cream-50 hover:bg-plum-800 focus-visible:outline-plum-900'),
                variant === 'link' && 'relative inline-block',
                variant === 'link' &&
                    (tone === 'light'
                        ? 'text-cream-50 focus-visible:outline-cream-50'
                        : 'text-plum-950 focus-visible:outline-plum-900'),
            )
        "
        @mouseenter="marked = true"
        @mouseleave="marked = false"
        @focus="marked = true"
        @blur="marked = false"
    >
        <slot />

        <ScribbleUnderline
            v-if="variant === 'link'"
            :active="marked"
            :tone="tone"
        />

        <ArrowRight
            v-if="withArrow && variant === 'primary'"
            class="size-5 shrink-0 transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:translate-x-1 motion-reduce:transition-none"
        />
    </Link>
</template>
