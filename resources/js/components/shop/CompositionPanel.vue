<script setup lang="ts">
import { computed } from 'vue';
import type { Ingredient } from '@/types/catalog';

/**
 * The site's central argument, and the reason the data model has an `is_added`
 * column rather than a marketing sentence.
 *
 * Competitors build functional drinks by adding vitamins and minerals to a
 * fruit base. Vitanea's function comes from the fruit itself. That is a claim
 * about an *absence*, which a conventional shop has no vocabulary for — so it
 * gets the slot a discount ribbon would normally occupy, and it is rendered
 * from the ingredient list rather than asserted alongside it.
 *
 * Nothing here says what the product does to the person drinking it. Under EU
 * Reg. 1924/2006 aronia carries no authorised health claims, so composition is
 * the only honest ground to stand on — and it happens to be the stronger one.
 */
type Props = {
    ingredients: Ingredient[];
    hasNothingAdded: boolean;
};

const { ingredients } = defineProps<Props>();

const fromTheFruit = computed(() =>
    ingredients.filter((ingredient) => !ingredient.isAdded),
);

const added = computed(() =>
    ingredients.filter((ingredient) => ingredient.isAdded),
);

const typicalCompetitor = [
    'Плодова основа от концентрат',
    'Витамин C (добавен)',
    'Цинк (добавен)',
    'Ароматизант',
    'Консервант',
];
</script>

<template>
    <section
        class="rounded-[1.75rem] bg-cream-50/70 p-8 ring-1 ring-plum-950/10 lg:p-10"
    >
        <h2 class="font-mono text-sm tracking-wide text-plum-900/80 uppercase">
            Състав
        </h2>

        <div class="mt-8 grid gap-10 lg:grid-cols-2 lg:gap-12">
            <div>
                <p class="font-display text-2xl font-semibold text-plum-950">
                    Vitanea
                </p>

                <ul role="list" class="mt-5 grid gap-3">
                    <li
                        v-for="ingredient in fromTheFruit"
                        :key="ingredient.name"
                        class="flex items-baseline justify-between gap-4 border-b border-plum-950/10 pb-3 text-base text-plum-950"
                    >
                        <span>{{ ingredient.name }}</span>
                        <span
                            v-if="ingredient.percentage !== null"
                            class="shrink-0 font-mono text-plum-900/80 tabular-nums"
                        >
                            {{ ingredient.percentage }}%
                        </span>
                    </li>

                    <li
                        v-for="ingredient in added"
                        :key="ingredient.name"
                        class="flex items-baseline justify-between gap-4 border-b border-plum-950/10 pb-3 text-base text-plum-900/75"
                    >
                        <span>+ {{ ingredient.name }}</span>
                        <span
                            v-if="ingredient.percentage !== null"
                            class="shrink-0 font-mono tabular-nums"
                        >
                            {{ ingredient.percentage }}%
                        </span>
                    </li>
                </ul>

                <p
                    v-if="hasNothingAdded"
                    class="mt-6 font-display text-2xl font-semibold text-balance text-plum-950"
                >
                    Нищо друго.
                </p>
                <p
                    v-else
                    class="mt-6 max-w-[44ch] text-base text-pretty text-plum-900/80"
                >
                    Функцията идва от плода. Останалото е само това, което
                    рецептата изисква.
                </p>
            </div>

            <div class="lg:border-l lg:border-plum-950/10 lg:pl-12">
                <p class="font-display text-2xl font-semibold text-plum-900/60">
                    Типична функционална напитка
                </p>

                <ul role="list" class="mt-5 grid gap-3">
                    <li
                        v-for="item in typicalCompetitor"
                        :key="item"
                        class="border-b border-plum-950/5 pb-3 text-base text-plum-900/65"
                    >
                        {{ item }}
                    </li>
                </ul>

                <p
                    class="mt-6 max-w-[44ch] text-base text-pretty text-plum-900/60"
                >
                    Функцията се добавя след това — витамини и минерали, внесени
                    в основата.
                </p>
            </div>
        </div>
    </section>
</template>
