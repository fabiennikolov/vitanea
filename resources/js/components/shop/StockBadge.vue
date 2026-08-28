<script setup lang="ts">
import { cn } from '@/lib/utils';

/**
 * Half the real catalog is out of stock, so this is prominent rather than
 * incidental — and out-of-stock products still get a page, because a
 * back-in-stock signal is worth more than a dead end.
 */
type Props = {
    inStock: boolean;
    tone?: 'solid' | 'quiet';
};

const { tone = 'quiet' } = defineProps<Props>();
</script>

<template>
    <span
        :class="
            cn(
                'inline-flex items-center gap-2 font-mono text-sm tracking-wide uppercase',
                tone === 'solid' && 'rounded-full px-3 py-1.5',
                inStock
                    ? tone === 'solid'
                        ? 'bg-sage-200 text-plum-900'
                        : 'text-plum-900/80'
                    : tone === 'solid'
                      ? 'bg-cream-200 text-plum-900/75'
                      : 'text-plum-900/60',
            )
        "
    >
        <span
            :class="
                cn(
                    'size-1.5 shrink-0 rounded-full',
                    inStock ? 'bg-plum-800' : 'bg-plum-900/30',
                )
            "
            aria-hidden="true"
        />
        {{ inStock ? 'В наличност' : 'Изчерпан' }}
    </span>
</template>
