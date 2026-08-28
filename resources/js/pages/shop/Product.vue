<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { LoaderCircle, Minus, Plus } from '@lucide/vue';
import { useElementVisibility } from '@vueuse/core';
import { computed, ref } from 'vue';
import CompositionPanel from '@/components/shop/CompositionPanel.vue';
import NutritionTable from '@/components/shop/NutritionTable.vue';
import PriceDisplay from '@/components/shop/PriceDisplay.vue';
import ProductCard from '@/components/shop/ProductCard.vue';
import StockBadge from '@/components/shop/StockBadge.vue';
import VariantSelector from '@/components/shop/VariantSelector.vue';
import BlobField from '@/components/store/BlobField.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import { store } from '@/routes/cart';
import { index } from '@/routes/products';
import type { Product } from '@/types/catalog';

type Props = {
    product: Product;
    related: Product[];
};

const { product } = defineProps<Props>();

const selectedVariantId = ref<number>(
    (product.variants.find((variant) => variant.inStock) ?? product.variants[0])
        ?.id,
);

const selectedVariant = computed(
    () =>
        product.variants.find(
            (variant) => variant.id === selectedVariantId.value,
        ) ?? product.variants[0],
);

const quantity = ref(1);
const adding = ref(false);

/**
 * On a phone the buy button scrolls out of sight while reading the
 * composition. A sticky bar takes over only once it does, so the two are never
 * on screen together.
 */
const inlineBuyButton = ref<HTMLElement | null>(null);
const inlineBuyVisible = useElementVisibility(inlineBuyButton);

/**
 * Product structured data. Offers use the variant prices, and availability
 * reflects real stock — half the catalog is currently sold out, so claiming
 * otherwise would be both wrong and a rich-result violation.
 */
const structuredData = computed(() =>
    JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'Product',
        name: product.name,
        description:
            product.shortDescription ?? product.description ?? undefined,
        image: product.image ? [product.image] : undefined,
        brand: { '@type': 'Brand', name: 'Vitanea' },
        category: product.category.name,
        offers: product.variants.map((variant) => ({
            '@type': 'Offer',
            sku: variant.sku,
            name: variant.label,
            price: (variant.priceMinor / 100).toFixed(2),
            priceCurrency: 'EUR',
            availability: variant.inStock
                ? 'https://schema.org/InStock'
                : 'https://schema.org/OutOfStock',
        })),
    }),
);

function addToCart(): void {
    router.post(
        store().url,
        {
            variant_id: selectedVariantId.value,
            quantity: quantity.value,
        },
        {
            preserveScroll: true,
            onStart: () => {
                adding.value = true;
            },
            onFinish: () => {
                adding.value = false;
            },
        },
    );
}
</script>

<template>
    <Head :title="product.name">
        <meta
            v-if="product.shortDescription"
            name="description"
            :content="product.shortDescription"
        />
        <component :is="'script'" type="application/ld+json">
            {{ structuredData }}
        </component>
    </Head>

    <SectionShell>
        <BlobField>
            <div
                class="absolute -top-72 -right-56 size-[42rem] blob-a bg-sage-200/50"
            />
        </BlobField>

        <nav aria-label="Трасе" class="text-base text-plum-900/75">
            <Link :href="index()" class="hover:text-plum-950">Продукти</Link>
            <span aria-hidden="true"> · </span>
            <span>{{ product.category.name }}</span>
        </nav>

        <div class="mt-10 grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-6">
                <div
                    class="relative grid aspect-square place-items-center overflow-hidden rounded-[2.5rem] bg-cream-200/70"
                >
                    <div
                        class="absolute size-[80%] blob-c bg-plum-800"
                        aria-hidden="true"
                    />
                    <img
                        v-if="product.image"
                        :src="product.image"
                        :alt="`${product.name} на Vitanea`"
                        class="relative size-full object-contain p-[7%] drop-shadow-2xl"
                    />
                </div>
            </div>

            <div class="lg:col-span-6">
                <p
                    class="font-mono text-sm tracking-wide text-plum-900/75 uppercase"
                >
                    {{ product.category.name
                    }}<template v-if="product.series">
                        / {{ product.series.name }}</template
                    >
                </p>

                <h1
                    class="mt-4 max-w-[24ch] font-display text-4xl font-black tracking-tight text-balance text-plum-950 sm:text-5xl"
                >
                    {{ product.name }}
                </h1>

                <p
                    v-if="product.shortDescription"
                    class="mt-5 max-w-[48ch] text-lg text-pretty text-plum-900/85"
                >
                    {{ product.shortDescription }}
                </p>

                <div class="mt-8 flex items-center gap-6">
                    <PriceDisplay
                        v-if="selectedVariant"
                        :price="selectedVariant.price"
                        :price-bgn="selectedVariant.priceBgn"
                        size="lg"
                    />
                    <StockBadge
                        :in-stock="selectedVariant?.inStock ?? false"
                        tone="solid"
                    />
                </div>

                <div v-if="product.hasMultipleVariants" class="mt-10">
                    <VariantSelector
                        v-model="selectedVariantId"
                        :variants="product.variants"
                    />
                </div>

                <div
                    ref="inlineBuyButton"
                    class="mt-10 flex flex-wrap items-center gap-4"
                >
                    <div
                        class="flex items-center rounded-full bg-cream-50 ring-1 ring-plum-950/15"
                    >
                        <button
                            type="button"
                            aria-label="Намали количеството"
                            class="grid size-11 place-items-center rounded-full text-plum-900 hover:bg-cream-200 disabled:opacity-40"
                            :disabled="quantity <= 1"
                            @click="quantity = Math.max(1, quantity - 1)"
                        >
                            <Minus class="size-4 shrink-0" />
                        </button>
                        <span
                            class="w-10 text-center text-base text-plum-950 tabular-nums"
                        >
                            {{ quantity }}
                        </span>
                        <button
                            type="button"
                            aria-label="Увеличи количеството"
                            class="grid size-11 place-items-center rounded-full text-plum-900 hover:bg-cream-200"
                            @click="quantity = quantity + 1"
                        >
                            <Plus class="size-4 shrink-0" />
                        </button>
                    </div>

                    <button
                        v-if="selectedVariant?.inStock"
                        type="button"
                        :disabled="adding"
                        class="inline-flex items-center gap-2.5 rounded-full bg-plum-900 px-7 py-4 text-base text-cream-50 transition-all hover:bg-plum-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-70"
                        @click="addToCart"
                    >
                        <LoaderCircle
                            v-if="adding"
                            class="size-4 shrink-0 animate-spin"
                        />
                        {{ adding ? 'Добавяне…' : 'Добави в количката' }}
                    </button>

                    <p
                        v-else
                        class="max-w-[36ch] text-base text-pretty text-plum-900/80"
                    >
                        Този продукт е временно изчерпан.
                    </p>
                </div>

                <div
                    v-if="product.description"
                    class="mt-12 border-t border-plum-950/10 pt-8"
                >
                    <p
                        class="max-w-[60ch] text-base text-pretty text-plum-900/85"
                    >
                        {{ product.description }}
                    </p>
                </div>

                <div v-if="product.nutrition" class="mt-8">
                    <NutritionTable :nutrition="product.nutrition" />
                </div>

                <div
                    v-if="product.usage || product.storage"
                    class="mt-8 grid gap-4"
                >
                    <p
                        v-if="product.usage"
                        class="max-w-[60ch] text-base text-pretty text-plum-900/80"
                    >
                        <span class="text-plum-950">Употреба.</span>
                        {{ product.usage }}
                    </p>
                    <p
                        v-if="product.storage"
                        class="max-w-[60ch] text-base text-pretty text-plum-900/80"
                    >
                        <span class="text-plum-950">Съхранение.</span>
                        {{ product.storage }}
                    </p>
                </div>
            </div>
        </div>

        <div v-if="product.ingredients?.length" class="mt-20">
            <CompositionPanel
                :ingredients="product.ingredients"
                :has-nothing-added="product.hasNothingAdded"
            />
        </div>
    </SectionShell>

    <Transition
        enter-active-class="transition duration-200"
        enter-from-class="translate-y-full"
        leave-active-class="transition duration-200"
        leave-to-class="translate-y-full"
    >
        <div
            v-if="!inlineBuyVisible && selectedVariant?.inStock"
            class="fixed inset-x-0 bottom-0 z-30 border-t border-plum-950/10 bg-cream-100/95 px-6 py-3 backdrop-blur-md lg:hidden"
        >
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="truncate text-base text-plum-900/80">
                        {{ selectedVariant.label }}
                    </p>
                    <p class="text-lg text-plum-950 tabular-nums">
                        {{ selectedVariant.price }}
                    </p>
                </div>

                <button
                    type="button"
                    :disabled="adding"
                    class="inline-flex shrink-0 items-center gap-2 rounded-full bg-plum-900 px-6 py-3.5 text-base text-cream-50 transition-all hover:bg-plum-800 active:scale-[0.98] disabled:opacity-70"
                    @click="addToCart"
                >
                    <LoaderCircle
                        v-if="adding"
                        class="size-4 shrink-0 animate-spin"
                    />
                    {{ adding ? 'Добавяне…' : 'Добави' }}
                </button>
            </div>
        </div>
    </Transition>

    <SectionShell v-if="related.length" ground="sage">
        <h2
            class="max-w-[30ch] font-display text-3xl font-bold tracking-tight text-balance text-plum-950"
        >
            Със същите плодове
        </h2>

        <div class="mt-12 grid gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
            <ProductCard
                v-for="(item, index) in related"
                :key="item.id"
                :product="item"
                :index="index"
            />
        </div>
    </SectionShell>
</template>
