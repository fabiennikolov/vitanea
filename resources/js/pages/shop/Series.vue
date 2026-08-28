<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ProductCard from '@/components/shop/ProductCard.vue';
import SectionHeading from '@/components/store/SectionHeading.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import type { Product } from '@/types/catalog';

type Props = {
    series: {
        name: string;
        slug: string;
        tagline: string | null;
        description: string | null;
        products: Product[];
    }[];
};

defineProps<Props>();
</script>

<template>
    <Head title="Серии" />

    <SectionShell>
        <SectionHeading
            eyebrow="Серии"
            headline="Три линии, една технология."
            subheadline="Разликата между сериите е в рецептата, не в качеството на плода."
        />
    </SectionShell>

    <SectionShell
        v-for="(entry, position) in series"
        :key="entry.slug"
        :ground="position % 2 === 0 ? 'sage' : 'cream'"
    >
        <div class="flex flex-wrap items-end justify-between gap-8">
            <div>
                <p
                    v-if="entry.tagline"
                    class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
                >
                    {{ entry.tagline }}
                </p>
                <h2
                    class="mt-4 max-w-[30ch] font-display text-3xl font-black tracking-tight text-balance text-plum-950 sm:text-4xl"
                >
                    {{ entry.name }}
                </h2>
                <p
                    v-if="entry.description"
                    class="mt-4 max-w-[52ch] text-lg text-pretty text-plum-900/85"
                >
                    {{ entry.description }}
                </p>
            </div>
        </div>

        <div
            v-if="entry.products.length"
            class="mt-12 grid gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3"
        >
            <ProductCard
                v-for="(product, index) in entry.products"
                :key="product.id"
                :product="product"
                :index="index"
            />
        </div>
    </SectionShell>
</template>
