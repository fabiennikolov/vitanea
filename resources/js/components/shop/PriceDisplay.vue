<script setup lang="ts">
import { cn } from '@/lib/utils';

/**
 * Euro is the primary currency with lev alongside, matching Bulgaria's
 * changeover rules and what Vitanea already shows today.
 */
type Props = {
    price: string;
    priceBgn?: string | null;
    from?: boolean;
    size?: 'sm' | 'lg';
};

const { from = false, size = 'sm' } = defineProps<Props>();
</script>

<template>
    <div>
        <p
            :class="
                cn(
                    'font-display font-black text-plum-950 tabular-nums',
                    size === 'lg' ? 'text-4xl' : 'text-2xl',
                )
            "
        >
            <span v-if="from" class="text-lg font-semibold text-plum-900/75"
                >от </span
            >{{ price }}
        </p>
        <p
            v-if="priceBgn"
            class="mt-0.5 text-base text-plum-900/75 tabular-nums"
        >
            {{ priceBgn }}
        </p>
    </div>
</template>
