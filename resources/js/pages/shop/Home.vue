<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Droplets, Grape, Sprout } from '@lucide/vue';
import BenefitGrid from '@/components/home/BenefitGrid.vue';
import CategoryCards from '@/components/home/CategoryCards.vue';
import NewsletterForm from '@/components/home/NewsletterForm.vue';
import ProductInFocus from '@/components/home/ProductInFocus.vue';
import ScienceBlock from '@/components/home/ScienceBlock.vue';
import TrustRow from '@/components/home/TrustRow.vue';
import ProductCard from '@/components/shop/ProductCard.vue';
import ActionButton from '@/components/store/ActionButton.vue';
import BlobField from '@/components/store/BlobField.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import SpecPill from '@/components/store/SpecPill.vue';
import { science } from '@/routes';
import { index as productsIndex } from '@/routes/products';
import type { Product } from '@/types/catalog';

type Props = {
    focus: Product | null;
    featured: Product[];
    categories: {
        name: string;
        slug: string;
        description: string | null;
        productsCount: number;
        image: string | null;
    }[];
};

defineProps<Props>();

/**
 * Every claim here is a composition fact printed on the product itself —
 * "342 зрънца арония" and the Krachanov technology are on the carton. Under
 * EU Reg. 1924/2006 aronia carries no authorised health claims, so the hero
 * states what is *in* the bottle and never what it does to you.
 */
const specifications = [
    { icon: Grape, label: '342 зрънца арония' },
    { icon: Droplets, label: 'Студено пресован' },
    { icon: Sprout, label: 'Без концентрат' },
] as const;
</script>

<template>
    <Head title="Функционални напитки от истински плодове" />

    <!--
      Full-bleed hero. The plum field runs edge to edge and carries the
      headline itself, rather than sitting inside the page gutter as a
      decorative shape — the product stands on the band's bottom edge.
    -->
    <section
        class="relative isolate flex flex-col justify-end overflow-hidden bg-plum-800 text-cream-100 lg:min-h-[44rem]"
    >
        <BlobField>
            <div
                class="absolute -top-48 -right-64 size-[52rem] blob-a bg-plum-700/70 max-lg:-top-32 max-lg:-right-72 max-lg:size-[36rem]"
            />
            <div
                class="absolute -bottom-72 -left-40 size-[34rem] blob-b bg-aronia-950/40"
            />
        </BlobField>

        <div
            class="relative mx-auto grid w-full max-w-7xl min-w-0 grid-cols-1 items-end gap-x-8 gap-y-14 px-6 pt-16 pb-20 lg:grid-cols-12 lg:px-8 lg:pt-24 lg:pb-0"
        >
            <div
                class="animate-in duration-700 fill-mode-both fade-in slide-in-from-bottom-4 lg:col-span-5 lg:pb-28"
            >
                <p
                    class="font-mono text-sm tracking-wide text-cream-100/80 uppercase"
                >
                    Природа × Наука
                </p>

                <h1
                    class="mt-6 max-w-[20ch] font-display text-5xl font-black tracking-tight text-balance text-cream-50 sm:text-6xl lg:text-7xl"
                >
                    Рестартирай отвътре.
                </h1>

                <p
                    class="mt-6 max-w-[48ch] text-lg text-pretty text-cream-100/80"
                >
                    Функционалността идва от самия плод — не от нещо добавено
                    към него.
                </p>

                <div class="mt-10 flex flex-wrap items-center gap-x-8 gap-y-4">
                    <ActionButton
                        :href="productsIndex()"
                        tone="light"
                        with-arrow
                    >
                        Разгледай продуктите
                    </ActionButton>

                    <ActionButton :href="science()" variant="link" tone="light">
                        Науката зад Vitanea
                    </ActionButton>
                </div>
            </div>

            <!-- No bottom padding on lg: the bottle stands on the band edge. -->
            <div
                class="relative animate-in duration-1000 fill-mode-both [animation-delay:150ms] fade-in lg:col-span-4"
            >
                <img
                    src="/images/products/juce-aronia.png"
                    alt="Сок от арония 100% на Vitanea, стъклена бутилка 250 мл"
                    class="relative mx-auto block w-full max-w-sm drop-shadow-2xl"
                    width="603"
                    height="688"
                />
            </div>

            <div
                class="animate-in duration-700 fill-mode-both [animation-delay:300ms] fade-in slide-in-from-bottom-4 lg:col-span-3 lg:pb-28"
            >
                <p
                    class="font-mono text-sm tracking-wide text-cream-100/75 uppercase"
                >
                    Vitanea / Арония 100%
                </p>

                <ul role="list" class="mt-5 grid justify-items-start gap-3">
                    <li
                        v-for="specification in specifications"
                        :key="specification.label"
                    >
                        <SpecPill
                            :icon="specification.icon"
                            :label="specification.label"
                            tone="light"
                        />
                    </li>
                </ul>

                <p
                    class="mt-8 max-w-[40ch] text-base text-pretty text-cream-100/80"
                >
                    По оригиналната технология на проф. Христо Крачанов.
                </p>
            </div>
        </div>
    </section>

    <TrustRow />

    <CategoryCards :categories="categories" />

    <ProductInFocus v-if="focus" :product="focus" />

    <BenefitGrid />

    <ScienceBlock />

    <SectionShell v-if="featured.length">
        <div class="flex flex-wrap items-end justify-between gap-8">
            <h2
                class="max-w-[30ch] font-display text-3xl font-black tracking-tight text-balance text-plum-950 sm:text-4xl"
            >
                В наличност сега
            </h2>
            <ActionButton :href="productsIndex()" variant="link">
                Виж всички продукти
            </ActionButton>
        </div>

        <div class="mt-12 grid gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
            <ProductCard
                v-for="(product, index) in featured"
                :key="product.id"
                :product="product"
                :index="index"
            />
        </div>
    </SectionShell>

    <NewsletterForm />
</template>
