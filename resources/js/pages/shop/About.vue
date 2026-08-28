<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ActionButton from '@/components/store/ActionButton.vue';
import SectionHeading from '@/components/store/SectionHeading.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import { science } from '@/routes';

const principles = [
    'Използваме само свежи или замразени плодове — без плодов концентрат.',
    'Без консерванти, синтетични оцветители, аромати и емулгатори.',
    'Запазваме антиоксидантната активност на плодовете, които използваме.',
    'Всички продукти са по оригиналната технология на проф. Христо Крачанов.',
] as const;

const factory = [
    { term: 'Пловдив', detail: 'Собствена фабрика на ул. „Рогошко шосе“.' },
    {
        term: '1 351 м²',
        detail: 'Разгъната застроена площ, строена от 2017 г.',
    },
    { term: '262 т', detail: 'Годишен капацитет.' },
    { term: 'Без отпадък', detail: 'Пресованият плод става чай или фураж.' },
] as const;
</script>

<template>
    <Head title="За нас" />

    <SectionShell>
        <SectionHeading
            eyebrow="За нас"
            headline="Семейна компания от Пловдив."
            subheadline="Витанеа е създадена през 2003 г. от проф. д-р Христо Крачанов и доц. Мария Крачанова. Днес я води дъщеря им д-р Благовеста Крачанова."
        />

        <div class="mt-16 grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-7">
                <p class="max-w-[64ch] text-lg text-pretty text-plum-900/85">
                    Ние сме единственият производител на българския пазар на
                    функционални напитки, чиято функционалност идва от самия
                    плод. Има подобни продукти, но при тях полезното е добавено
                    — витамини, минерали, екстракти. При нас то вече е в плода.
                </p>
                <p
                    class="mt-6 max-w-[64ch] text-lg text-pretty text-plum-900/85"
                >
                    Фабриката ни в Пловдив е проектирана от другата дъщеря на
                    основателите — архитект Милена Крачанова — от рециклируеми
                    материали и с мисъл за енергийна ефективност.
                </p>

                <ul role="list" class="mt-10 grid gap-4">
                    <li
                        v-for="principle in principles"
                        :key="principle"
                        class="border-t border-plum-950/15 pt-4 text-base text-pretty text-plum-900/80"
                    >
                        {{ principle }}
                    </li>
                </ul>

                <div class="mt-10">
                    <ActionButton :href="science()" with-arrow>
                        Науката зад Vitanea
                    </ActionButton>
                </div>
            </div>

            <div class="lg:col-span-5">
                <dl class="grid gap-6">
                    <div
                        v-for="item in factory"
                        :key="item.term"
                        class="border-t border-plum-950/15 pt-5"
                    >
                        <dt
                            class="font-display text-2xl font-semibold text-plum-950 tabular-nums"
                        >
                            {{ item.term }}
                        </dt>
                        <dd
                            class="mt-2 max-w-[44ch] text-base text-pretty text-plum-900/80"
                        >
                            {{ item.detail }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </SectionShell>
</template>
