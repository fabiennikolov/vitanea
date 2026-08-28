<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ActionButton from '@/components/store/ActionButton.vue';
import SectionHeading from '@/components/store/SectionHeading.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import { index } from '@/routes/products';

type Props = {
    categories: {
        name: string;
        slug: string;
        description: string | null;
        productsCount: number;
        image: string | null;
    }[];
};

defineProps<Props>();

const tilts = [
    '-rotate-[0.8deg]',
    'rotate-[0.6deg]',
    '-rotate-[0.4deg]',
] as const;

const grounds = [
    'bg-linear-to-b from-cream-100 to-cream-300',
    'bg-linear-to-b from-sage-100 to-sage-300',
    'bg-linear-to-b from-cream-200 to-cream-400',
] as const;
</script>

<template>
    <SectionShell>
        <div class="flex flex-wrap items-end justify-between gap-8">
            <SectionHeading eyebrow="Продукти" headline="Какво произвеждаме" />
            <ActionButton :href="index()" variant="link">
                Виж всички продукти
            </ActionButton>
        </div>

        <ul
            role="list"
            class="mt-12 grid gap-x-6 gap-y-10 sm:grid-cols-3 lg:gap-x-8"
        >
            <li v-for="(category, position) in categories" :key="category.slug">
                <Link
                    :href="index({ query: { category: category.slug } })"
                    view-transition
                    class="group block rounded-[2rem] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-plum-900"
                >
                    <!--
                      The tile is the picture: the product fills it to the
                      bottom edge and the name sits on top of it, so a row of
                      these reads as three photographs rather than three boxes
                      with something small in the middle.
                    -->
                    <div
                        :class="[
                            'relative transform-gpu overflow-hidden rounded-[2rem] transition-[transform,translate,rotate,scale,box-shadow] duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:-translate-y-1.5 group-hover:rotate-0 group-hover:shadow-xl group-hover:shadow-plum-950/10 motion-reduce:transform-none',
                            position === 1
                                ? 'aspect-[4/5] sm:mt-10'
                                : 'aspect-[4/5]',
                            grounds[position % grounds.length],
                            tilts[position % tilts.length],
                        ]"
                    >
                        <h3
                            class="relative z-10 px-5 pt-7 text-center font-display text-3xl font-black tracking-tight text-balance text-plum-950 sm:text-2xl lg:text-3xl xl:text-4xl"
                        >
                            {{ category.name }}
                        </h3>

                        <img
                            v-if="category.image"
                            :src="category.image"
                            alt=""
                            class="absolute inset-x-0 -bottom-[2%] mx-auto h-[74%] w-[92%] object-contain object-bottom transition-transform duration-500 group-hover:scale-105 motion-reduce:transform-none"
                            loading="lazy"
                        />
                    </div>
                    <p
                        v-if="category.description"
                        class="mt-2 max-w-[40ch] text-base text-pretty text-plum-900/80"
                    >
                        {{ category.description }}
                    </p>
                    <p
                        class="mt-3 font-mono text-sm tracking-wide text-plum-900/75 uppercase"
                    >
                        <span class="tabular-nums">{{
                            category.productsCount
                        }}</span>
                        продукта
                    </p>
                </Link>
            </li>
        </ul>
    </SectionShell>
</template>
