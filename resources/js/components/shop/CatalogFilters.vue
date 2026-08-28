<script setup lang="ts">
import { Check } from '@lucide/vue';
import { cn } from '@/lib/utils';
import type { CatalogFilters, FruitTag } from '@/types/catalog';

/**
 * One filter definition, rendered in two places: a sidebar on desktop and a
 * drawer on mobile. Keeping it in a single component is what stops the two
 * from drifting apart.
 */
type Props = {
    filters: CatalogFilters;
    categories: { name: string; slug: string }[];
    fruits: FruitTag[];
    seriesOptions: { name: string; slug: string }[];
};

const { filters } = defineProps<Props>();

const emit = defineEmits<{
    apply: [changes: Partial<CatalogFilters>];
}>();

function toggle(key: 'category' | 'series', slug: string): void {
    emit('apply', { [key]: filters[key] === slug ? null : slug });
}

function isFruitSelected(slug: string): boolean {
    return (filters.fruit ?? []).includes(slug);
}

function toggleFruit(slug: string): void {
    const current = filters.fruit ?? [];
    const next = current.includes(slug)
        ? current.filter((item) => item !== slug)
        : [...current, slug];

    emit('apply', { fruit: next.length ? next : null });
}
</script>

<template>
    <div class="grid gap-8">
        <div>
            <h3
                class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
            >
                Вид
            </h3>
            <ul role="list" class="mt-4 grid gap-1">
                <li v-for="category in categories" :key="category.slug">
                    <button
                        type="button"
                        :aria-pressed="filters.category === category.slug"
                        :class="
                            cn(
                                '-mx-2 w-full rounded-xl px-2 py-2.5 text-left text-base transition-colors sm:py-1.5',
                                filters.category === category.slug
                                    ? 'bg-plum-900 text-cream-50'
                                    : 'text-plum-900/85 hover:bg-cream-200 hover:text-plum-950',
                            )
                        "
                        @click="toggle('category', category.slug)"
                    >
                        {{ category.name }}
                    </button>
                </li>
            </ul>
        </div>

        <div>
            <h3
                class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
            >
                Плод
                <span class="text-plum-900/60">· може повече от един</span>
            </h3>
            <ul role="list" class="mt-4 flex flex-wrap gap-2">
                <li v-for="fruit in fruits" :key="fruit.slug">
                    <button
                        type="button"
                        :aria-pressed="isFruitSelected(fruit.slug)"
                        :class="
                            cn(
                                'inline-flex items-center gap-1.5 rounded-full py-2 text-base ring-1 transition-colors sm:py-1.5',
                                isFruitSelected(fruit.slug)
                                    ? 'bg-plum-900 py-2 pr-3 pl-3.5 text-cream-50 ring-plum-900'
                                    : 'px-3.5 text-plum-900/85 ring-plum-950/15 hover:bg-cream-200 hover:ring-plum-950/35',
                            )
                        "
                        @click="toggleFruit(fruit.slug)"
                    >
                        {{ fruit.name }}
                        <Check
                            v-if="isFruitSelected(fruit.slug)"
                            class="size-3.5 shrink-0"
                        />
                    </button>
                </li>
            </ul>
        </div>

        <div>
            <h3
                class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
            >
                Серия
            </h3>
            <ul role="list" class="mt-4 grid gap-1">
                <li v-for="option in seriesOptions" :key="option.slug">
                    <button
                        type="button"
                        :aria-pressed="filters.series === option.slug"
                        :class="
                            cn(
                                '-mx-2 w-full rounded-xl px-2 py-2.5 text-left text-base transition-colors sm:py-1.5',
                                filters.series === option.slug
                                    ? 'bg-plum-900 text-cream-50'
                                    : 'text-plum-900/85 hover:bg-cream-200 hover:text-plum-950',
                            )
                        "
                        @click="toggle('series', option.slug)"
                    >
                        {{ option.name }}
                    </button>
                </li>
            </ul>
        </div>

        <label
            class="flex cursor-pointer items-center gap-3 text-base text-plum-900/80"
        >
            <input
                type="checkbox"
                :checked="Boolean(filters.in_stock)"
                class="size-4.5 shrink-0 accent-plum-900 sm:size-4"
                @change="
                    emit('apply', { in_stock: filters.in_stock ? null : true })
                "
            />
            Само в наличност
        </label>
    </div>
</template>
