<script setup lang="ts">
import CompositionPanel from '@/components/shop/CompositionPanel.vue';
import PriceDisplay from '@/components/shop/PriceDisplay.vue';
import ActionButton from '@/components/store/ActionButton.vue';
import BlobField from '@/components/store/BlobField.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import { show } from '@/routes/products';
import type { Product } from '@/types/catalog';

/**
 * Occupies the slot mock v2 gave to "Deal Of The Day". The countdown and the
 * discount are gone: urgency signals commodity competition, which works
 * directly against being the only producer in the category. What replaces
 * them is the ingredient list.
 */
type Props = {
    product: Product;
};

defineProps<Props>();
</script>

<template>
    <SectionShell>
        <BlobField>
            <div
                class="absolute -bottom-80 -left-56 size-[40rem] blob-b bg-cream-200"
            />
        </BlobField>

        <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-5">
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
                        alt=""
                        class="relative size-full object-contain p-[7%] drop-shadow-2xl"
                    />
                </div>
            </div>

            <div class="lg:col-span-7">
                <p
                    class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
                >
                    Продукт във фокус
                </p>

                <h2
                    class="mt-5 max-w-[24ch] font-display text-4xl font-black tracking-tight text-balance text-plum-950 sm:text-5xl"
                >
                    {{ product.name }}
                </h2>

                <p
                    v-if="product.shortDescription"
                    class="mt-5 max-w-[48ch] text-lg text-pretty text-plum-900/85"
                >
                    {{ product.shortDescription }}
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-8">
                    <PriceDisplay
                        v-if="product.fromPrice"
                        :price="product.fromPrice"
                        :price-bgn="product.fromPriceBgn"
                        size="lg"
                    />
                    <ActionButton :href="show(product.slug)" with-arrow>
                        Виж продукта
                    </ActionButton>
                </div>
            </div>
        </div>

        <div v-if="product.ingredients?.length" class="mt-16">
            <CompositionPanel
                :ingredients="product.ingredients"
                :has-nothing-added="product.hasNothingAdded"
            />
        </div>
    </SectionShell>
</template>
