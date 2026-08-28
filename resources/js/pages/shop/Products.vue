<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { SlidersHorizontal, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import CatalogFiltersPanel from '@/components/shop/CatalogFilters.vue';
import ProductCard from '@/components/shop/ProductCard.vue';
import SectionHeading from '@/components/store/SectionHeading.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { index } from '@/routes/products';
import type { CatalogFilters, FruitTag, Product } from '@/types/catalog';

type Props = {
    products: Product[];
    filters: CatalogFilters;
    categories: { name: string; slug: string }[];
    fruits: FruitTag[];
    seriesOptions: { name: string; slug: string }[];
};

const { filters, categories, fruits, seriesOptions } = defineProps<Props>();

const sortOptions = [
    { value: 'default', label: 'По подразбиране' },
    { value: 'price-asc', label: 'Цена: ниска към висока' },
    { value: 'price-desc', label: 'Цена: висока към ниска' },
    { value: 'name-asc', label: 'Име: А–Я' },
] as const;

const filtersOpen = ref(false);

/**
 * Filters live in the query string so a filtered catalog is a shareable URL.
 * `preserveState` keeps scroll position as facets are toggled.
 */
function apply(changes: Partial<CatalogFilters>): void {
    const merged = { ...filters, ...changes };
    const next: Record<string, string | string[]> = {};

    for (const [key, value] of Object.entries(merged)) {
        if (
            value === null ||
            value === undefined ||
            value === '' ||
            value === false ||
            (Array.isArray(value) && value.length === 0)
        ) {
            continue;
        }

        next[key] = Array.isArray(value) ? value : String(value);
    }

    router.get(index().url, next, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function clearAll(): void {
    apply({
        q: null,
        category: null,
        fruit: null,
        series: null,
        in_stock: null,
    });
}

/**
 * Applied filters shown as removable chips, so the current state is legible
 * without opening the drawer — the drawer hides it on mobile otherwise.
 */
type Chip = {
    id: string;
    label: string;
    remove: () => void;
};

const activeChips = computed<Chip[]>(() => {
    const chips: Chip[] = [];

    // The search term is a filter like any other, so it is removable from the
    // same row rather than stranded in an overlay the visitor has closed.
    if (filters.q) {
        chips.push({
            id: 'q',
            label: `„${filters.q}“`,
            remove: () => apply({ q: null }),
        });
    }

    const category = categories.find((item) => item.slug === filters.category);

    if (category) {
        chips.push({
            id: `category:${category.slug}`,
            label: category.name,
            remove: () => apply({ category: null }),
        });
    }

    // One chip per selected fruit, each removable on its own.
    for (const slug of filters.fruit ?? []) {
        const fruit = fruits.find((item) => item.slug === slug);

        if (!fruit) {
            continue;
        }

        chips.push({
            id: `fruit:${slug}`,
            label: fruit.name,
            remove: () =>
                apply({
                    fruit: (filters.fruit ?? []).filter(
                        (item) => item !== slug,
                    ),
                }),
        });
    }

    const series = seriesOptions.find((item) => item.slug === filters.series);

    if (series) {
        chips.push({
            id: `series:${series.slug}`,
            label: series.name,
            remove: () => apply({ series: null }),
        });
    }

    if (filters.in_stock) {
        chips.push({
            id: 'in_stock',
            label: 'В наличност',
            remove: () => apply({ in_stock: null }),
        });
    }

    return chips;
});
</script>

<template>
    <Head title="Продукти" />

    <SectionShell>
        <SectionHeading
            eyebrow="Каталог"
            headline="Продукти"
            subheadline="Всичко, което произвеждаме, е от истински плодове. Аронията е в почти всеки от тези продукти — и това има причина."
        />

        <div class="mt-12 grid gap-x-8 gap-y-8 lg:mt-14 lg:grid-cols-12">
            <!-- Desktop sidebar. On mobile the same panel lives in the drawer. -->
            <aside class="max-lg:hidden lg:col-span-3">
                <CatalogFiltersPanel
                    :filters="filters"
                    :categories="categories"
                    :fruits="fruits"
                    :series-options="seriesOptions"
                    @apply="apply"
                />

                <button
                    v-if="activeChips.length"
                    type="button"
                    class="mt-8 text-base text-plum-950 underline decoration-plum-950/30 underline-offset-4 transition-colors hover:decoration-plum-950"
                    @click="clearAll"
                >
                    Изчисти филтрите
                </button>
            </aside>

            <div class="lg:col-span-9">
                <div
                    class="sticky top-[4.5rem] z-10 -mx-6 flex items-center justify-between gap-3 border-b border-plum-950/10 bg-cream-100/90 px-6 py-3 backdrop-blur-sm lg:static lg:mx-0 lg:flex-wrap lg:bg-transparent lg:px-0 lg:pt-0 lg:pb-5 lg:backdrop-blur-none"
                >
                    <div class="flex items-center gap-3">
                        <Sheet v-model:open="filtersOpen">
                            <SheetTrigger as-child>
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-full bg-cream-50 py-2.5 pr-4 pl-3 text-base text-plum-950 ring-1 ring-plum-950/15 transition-colors hover:bg-cream-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900 lg:hidden"
                                >
                                    <SlidersHorizontal
                                        class="size-4 shrink-0"
                                    />
                                    Филтри
                                    <span
                                        v-if="activeChips.length"
                                        class="grid size-5 place-items-center rounded-full bg-plum-900 font-mono text-[0.6875rem] text-cream-50 tabular-nums"
                                    >
                                        {{ activeChips.length }}
                                    </span>
                                </button>
                            </SheetTrigger>

                            <SheetContent
                                side="left"
                                class="w-[88vw] max-w-sm overflow-y-auto bg-cream-100"
                            >
                                <SheetHeader>
                                    <SheetTitle
                                        class="font-display text-2xl font-semibold text-plum-950"
                                    >
                                        Филтри
                                    </SheetTitle>
                                </SheetHeader>

                                <div class="px-4 pb-8">
                                    <CatalogFiltersPanel
                                        :filters="filters"
                                        :categories="categories"
                                        :fruits="fruits"
                                        :series-options="seriesOptions"
                                        @apply="apply"
                                    />

                                    <div class="mt-8 flex flex-wrap gap-3">
                                        <button
                                            type="button"
                                            class="flex-1 rounded-full bg-plum-900 px-5 py-3 text-base text-cream-50 transition-colors hover:bg-plum-800"
                                            @click="filtersOpen = false"
                                        >
                                            Покажи
                                            <span class="tabular-nums">
                                                {{ products.length }}
                                            </span>
                                            продукта
                                        </button>
                                        <button
                                            v-if="activeChips.length"
                                            type="button"
                                            class="rounded-full px-5 py-3 text-base text-plum-950 ring-1 ring-plum-950/15 transition-colors hover:bg-cream-200"
                                            @click="clearAll"
                                        >
                                            Изчисти
                                        </button>
                                    </div>
                                </div>
                            </SheetContent>
                        </Sheet>

                        <p
                            class="shrink-0 text-base text-plum-900/80 tabular-nums max-sm:hidden"
                        >
                            {{ products.length }} продукта
                        </p>
                    </div>

                    <label
                        class="flex min-w-0 items-center gap-2 text-base text-plum-900/80"
                    >
                        <span class="shrink-0 max-lg:sr-only">Подреди</span>
                        <select
                            class="max-w-[13rem] min-w-0 truncate rounded-xl bg-cream-50 px-3 py-2 text-base text-plum-950 ring-1 ring-plum-950/15 transition-shadow focus:ring-2 focus:ring-plum-900 focus:outline-none"
                            @change="
                                apply({
                                    sort: ($event.target as HTMLSelectElement)
                                        .value,
                                })
                            "
                        >
                            <option
                                v-for="option in sortOptions"
                                :key="option.value"
                                :value="option.value"
                                :selected="
                                    option.value === (filters.sort ?? 'default')
                                "
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </label>
                </div>

                <ul
                    v-if="activeChips.length"
                    role="list"
                    class="mt-5 flex flex-wrap gap-2 lg:hidden"
                >
                    <li v-for="chip in activeChips" :key="chip.id">
                        <button
                            type="button"
                            class="inline-flex items-center gap-1.5 rounded-full bg-plum-900 py-1.5 pr-2 pl-3.5 text-base text-cream-50 transition-opacity hover:opacity-85"
                            @click="chip.remove()"
                        >
                            {{ chip.label }}
                            <X class="size-3.5 shrink-0" />
                            <span class="sr-only">Премахни филтъра</span>
                        </button>
                    </li>
                </ul>

                <div
                    v-if="products.length"
                    class="mt-8 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:mt-10 lg:gap-x-8 lg:gap-y-14 2xl:grid-cols-3 2xl:[&>*:nth-child(3n+2)]:mt-12 lg:[&>*:nth-child(even)]:mt-12 2xl:[&>*:nth-child(even)]:mt-0"
                >
                    <ProductCard
                        v-for="(product, index) in products"
                        :key="product.id"
                        :product="product"
                        :index="index"
                    />
                </div>

                <div v-else class="mt-12 max-w-[48ch]">
                    <p class="text-lg text-pretty text-plum-900/80">
                        Няма продукти по тези критерии.
                    </p>
                    <button
                        type="button"
                        class="mt-5 rounded-full bg-plum-900 px-6 py-3 text-base text-cream-50 transition-colors hover:bg-plum-800"
                        @click="clearAll"
                    >
                        Изчисти филтрите
                    </button>
                </div>
            </div>
        </div>
    </SectionShell>
</template>
