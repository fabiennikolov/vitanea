<script setup lang="ts">
import { Beaker, Leaf, Recycle, Snowflake, Sprout, Factory } from '@lucide/vue';
import SectionHeading from '@/components/store/SectionHeading.vue';
import SectionShell from '@/components/store/SectionShell.vue';

/**
 * The client's four pillars plus the production principles published on their
 * own За нас page. Every line is a statement about how the product is made —
 * none is a claim about what it does to you, which EU Reg. 1924/2006 would
 * not permit for aronia.
 */
const benefits = [
    {
        icon: Leaf,
        title: 'Функцията е в плода',
        body: 'Не добавяме витамини и минерали, за да направим напитката функционална. Полезността идва от самия плод.',
    },
    {
        icon: Sprout,
        title: 'Само истински плодове',
        body: 'Свежи или замразени български плодове. Без плодов концентрат — никога.',
    },
    {
        icon: Snowflake,
        title: 'Студено пресоване',
        body: 'Технология, която запазва активните вещества на плода, вместо да ги разрушава.',
    },
    {
        icon: Beaker,
        title: 'Оригинална технология',
        body: 'Всички продукти се произвеждат по технологията на проф. Христо Крачанов, основателя на Витанеа.',
    },
    {
        icon: Factory,
        title: 'Собствена фабрика',
        body: 'Произвеждаме всичко сами, в нашата фабрика в Пловдив.',
    },
    {
        icon: Recycle,
        title: 'Без отпадък',
        body: 'Пресованият плод става чай или фураж. Нищо не се изхвърля.',
    },
] as const;
</script>

<template>
    <SectionShell ground="sage">
        <SectionHeading
            eyebrow="Как го правим"
            headline="Шест решения, които не сме променяли."
        />

        <ul
            role="list"
            class="mt-14 grid gap-x-10 gap-y-12 sm:grid-cols-2 lg:grid-cols-3"
        >
            <li v-for="benefit in benefits" :key="benefit.title">
                <component
                    :is="benefit.icon"
                    class="size-6 shrink-0 stroke-plum-800"
                />
                <h3
                    class="mt-4 font-display text-xl font-semibold text-balance text-plum-950"
                >
                    {{ benefit.title }}
                </h3>
                <p
                    class="mt-2 max-w-[44ch] text-base text-pretty text-plum-900/80"
                >
                    {{ benefit.body }}
                </p>
            </li>
        </ul>
    </SectionShell>
</template>
