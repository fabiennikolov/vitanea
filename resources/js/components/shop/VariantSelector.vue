<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { Variant } from '@/types/catalog';

/**
 * What collapses the old shop's four separate products all named
 * "Арония плодов сироп със захар" into one product with four sizes.
 */
type Props = {
    variants: Variant[];
};

defineProps<Props>();

const selected = defineModel<number>({ required: true });
</script>

<template>
    <fieldset>
        <legend
            class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
        >
            Разфасовка
        </legend>

        <div class="mt-4 grid gap-3">
            <label
                v-for="variant in variants"
                :key="variant.id"
                :class="
                    cn(
                        'flex cursor-pointer items-center justify-between gap-4 rounded-2xl px-4 py-3.5 ring-1',
                        selected === variant.id
                            ? 'bg-cream-50 ring-plum-900'
                            : 'bg-cream-50/50 ring-plum-950/10 hover:ring-plum-950/25',
                        !variant.inStock && 'opacity-55',
                    )
                "
            >
                <span class="flex items-center gap-3">
                    <input
                        v-model="selected"
                        type="radio"
                        name="variant"
                        :value="variant.id"
                        class="size-4 shrink-0 accent-plum-900"
                    />
                    <span class="text-base text-plum-950">
                        {{ variant.label }}
                    </span>
                </span>

                <span class="shrink-0 text-base text-plum-950 tabular-nums">
                    {{ variant.price }}
                </span>
            </label>
        </div>
    </fieldset>
</template>
