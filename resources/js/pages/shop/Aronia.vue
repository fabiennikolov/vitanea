<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ActionButton from '@/components/store/ActionButton.vue';
import BlobField from '@/components/store/BlobField.vue';
import SectionHeading from '@/components/store/SectionHeading.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import { science } from '@/routes';
import { index } from '@/routes/products';

/**
 * Composition facts only. Aronia carries no authorised health claims under
 * EU Reg. 1924/2006, so this page describes what the fruit *is* and what our
 * founder researched — never what drinking it will do for the reader.
 */
const facts = [
    {
        term: 'Антоцианини',
        detail: 'Пигментите, които дават на аронията почти черния ѝ цвят.',
    },
    {
        term: 'Сорбитол',
        detail: 'Основната естествена захар в плода — между 61% и 68% от въглехидратите му.',
    },
    {
        term: 'Стипчивост',
        detail: 'Причината аронията рядко се яде сурова — и причината да я произвеждаме като напитка.',
    },
    {
        term: 'Български произход',
        detail: 'Използваме само свежи или замразени български плодове. Без концентрат.',
    },
] as const;
</script>

<template>
    <Head title="Защо арония?" />

    <SectionShell>
        <BlobField>
            <div
                class="absolute -top-56 -right-48 size-[40rem] blob-c bg-plum-800/8"
            />
        </BlobField>

        <SectionHeading
            eyebrow="Плодът"
            headline="Защо арония?"
            subheadline="Защото полезното вече е в плода. Не се налага да добавяме нищо, за да го направим функционален."
        />

        <div class="mt-16 grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-6">
                <p class="max-w-[60ch] text-lg text-pretty text-plum-900/85">
                    Аронията (Aronia melanocarpa) е храст с малки тъмни плодове,
                    които почти никой не яде сурови — вкусът им е твърде
                    стипчив. В България тя става позната благодарение на
                    научните изследвания на проф. Христо Крачанов, който
                    разработва технология за извличането на сока ѝ.
                </p>
                <p
                    class="mt-6 max-w-[60ch] text-lg text-pretty text-plum-900/85"
                >
                    Заради полезните ѝ качества аронията е плодът, който влагаме
                    в почти всички наши продукти. Но има и втора причина:
                    аронията стабилизира пигментите на другите плодове, с които
                    я комбинираме.
                </p>

                <div class="mt-10">
                    <ActionButton :href="science()" with-arrow>
                        Науката зад това
                    </ActionButton>
                </div>
            </div>

            <div class="lg:col-span-6">
                <dl class="grid gap-6">
                    <div
                        v-for="fact in facts"
                        :key="fact.term"
                        class="border-t border-plum-950/15 pt-5"
                    >
                        <dt
                            class="font-display text-xl font-semibold text-plum-950"
                        >
                            {{ fact.term }}
                        </dt>
                        <dd
                            class="mt-2 max-w-[52ch] text-base text-pretty text-plum-900/80"
                        >
                            {{ fact.detail }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </SectionShell>

    <SectionShell ground="sage">
        <div class="max-w-3xl">
            <h2
                class="max-w-[30ch] font-display text-3xl font-black tracking-tight text-balance text-plum-950 sm:text-4xl"
            >
                Какво не пише на нашите етикети
            </h2>
            <p class="mt-6 max-w-[64ch] text-lg text-pretty text-plum-900/85">
                Няма да прочетете, че някой наш продукт лекува или предпазва от
                болести. Такива твърдения са забранени за храни в Европейския
                съюз — и по-важното, ние нямаме нужда от тях.
            </p>
            <p class="mt-5 max-w-[64ch] text-lg text-pretty text-plum-900/85">
                Това, което можем да покажем, е съставът: колко плод има в
                бутилката, откъде идва и какво не сме добавили.
            </p>

            <div class="mt-10">
                <ActionButton :href="index()" variant="link">
                    Виж състава на всеки продукт
                </ActionButton>
            </div>
        </div>
    </SectionShell>
</template>
