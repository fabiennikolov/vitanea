<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { LoaderCircle, Plus } from '@lucide/vue';
import { computed, ref } from 'vue';
import PriceDisplay from '@/components/shop/PriceDisplay.vue';
import StockBadge from '@/components/shop/StockBadge.vue';
import { store } from '@/routes/cart';
import { show } from '@/routes/products';
import type { Product } from '@/types/catalog';

type Props = {
    product: Product;
    /**
     * Position in the grid. Rotating the ground colour is what makes the
     * catalog read as a run of colour blocks rather than one flat wash —
     * a product sitting alone in an empty tile is the look this replaces.
     */
    index?: number;
};

const { product, index = 0 } = defineProps<Props>();

/**
 * Nothing in the grid sits perfectly straight. The tilt is tiny and tied to
 * the grid position, so the page reads as hand-placed rather than random, and
 * each tile straightens under the pointer.
 */
const tilts = [
    '-rotate-[0.7deg]',
    'rotate-[0.5deg]',
    '-rotate-[0.35deg]',
    'rotate-[0.8deg]',
] as const;

const grounds = [
    'bg-cream-200',
    'bg-sage-200',
    'bg-plum-800',
    'bg-cream-300',
] as const;

const ground = computed(() => grounds[index % grounds.length]);

const tilt = computed(() => tilts[index % tilts.length]);

const adding = ref(false);

/**
 * A product with one size can go straight into the cart. One with several
 * cannot — picking a size is a decision, so those send you to the product
 * page instead of guessing on your behalf.
 */
const soleVariant = computed(() =>
    product.variants.length === 1 && product.variants[0].inStock
        ? product.variants[0]
        : null,
);

function addToCart(): void {
    if (soleVariant.value === null) {
        return;
    }

    router.post(
        store().url,
        { variant_id: soleVariant.value.id, quantity: 1 },
        {
            preserveScroll: true,
            preserveState: true,
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
    <article
        :class="[
            'group relative flex flex-col',
            !product.inStock && 'opacity-75',
        ]"
    >
        <!--
          Flat ground, no blob: the product is bottom-aligned so it stands on
          the tile edge and fills the frame, rather than floating in the middle
          of empty space with a decorative shape behind it.
        -->
        <div
            :class="[
                'relative transform-gpu overflow-hidden rounded-[2rem] transition-[transform,translate,rotate,scale] duration-[600ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:-translate-y-1.5 group-hover:rotate-0 motion-reduce:transform-none',
                ground,
                tilt,
            ]"
        >
            <span
                class="pointer-events-none absolute inset-0 rounded-[2rem] opacity-0 shadow-xl shadow-plum-950/15 transition-opacity duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:opacity-100"
                aria-hidden="true"
            />

            <img
                v-if="product.image"
                :src="product.image"
                alt=""
                class="aspect-square w-full origin-bottom transform-gpu object-contain object-bottom px-[4%] pt-[7%] transition-[transform,translate,rotate,scale] duration-[600ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.05] motion-reduce:transform-none"
                loading="lazy"
            />
        </div>

        <div class="mt-5 flex flex-1 flex-col">
            <p
                class="font-mono text-sm tracking-wide text-plum-900/75 uppercase"
            >
                {{ product.category.name }}
            </p>

            <h3
                class="mt-2 font-display text-2xl font-black tracking-tight text-balance text-plum-950"
            >
                <!--
                  Stretched link: the whole card is clickable, while the action
                  button below stays a real, separately clickable control.
                -->
                <Link
                    :href="show(product.slug)"
                    view-transition
                    class="rounded-sm transition-colors after:absolute after:inset-0 after:content-[''] hover:text-plum-800 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-plum-900"
                >
                    {{ product.name }}
                </Link>
            </h3>

            <p
                v-if="product.shortDescription"
                class="mt-2 max-w-[44ch] text-base text-pretty text-plum-900/80"
            >
                {{ product.shortDescription }}
            </p>

            <div class="mt-4 flex flex-wrap items-center gap-x-3 gap-y-2">
                <span
                    v-if="product.hasNothingAdded"
                    class="rounded-full bg-sage-200 px-3 py-1 font-mono text-sm tracking-wide text-plum-900 uppercase"
                >
                    Нищо добавено
                </span>
                <StockBadge v-if="!product.inStock" :in-stock="false" />
            </div>

            <div class="mt-auto flex items-end justify-between gap-3 pt-6">
                <PriceDisplay
                    v-if="product.fromPrice"
                    :price="product.fromPrice"
                    :price-bgn="product.fromPriceBgn"
                    :from="product.hasMultipleVariants"
                />

                <!-- relative + z-10 lifts these above the stretched link. -->
                <button
                    v-if="soleVariant"
                    type="button"
                    :disabled="adding"
                    :aria-label="`Добави ${product.name} в количката`"
                    class="relative z-10 inline-flex shrink-0 items-center gap-2 rounded-full bg-plum-900 py-3 pr-5 pl-4 text-base font-bold text-cream-50 transition-colors hover:bg-plum-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900 disabled:opacity-70"
                    @click="addToCart"
                >
                    <LoaderCircle
                        v-if="adding"
                        class="size-4 shrink-0 animate-spin"
                    />
                    <Plus v-else class="size-4 shrink-0" />
                    Купи
                </button>

                <Link
                    v-else-if="product.inStock"
                    :href="show(product.slug)"
                    view-transition
                    class="relative z-10 shrink-0 rounded-full py-3 pr-5 pl-5 text-base font-bold text-plum-950 ring-1 ring-plum-950/15 transition-colors hover:bg-cream-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900"
                >
                    Избери размер
                </Link>
            </div>
        </div>
    </article>
</template>
