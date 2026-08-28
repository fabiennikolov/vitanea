<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ActionButton from '@/components/store/ActionButton.vue';
import BlobField from '@/components/store/BlobField.vue';
import SectionHeading from '@/components/store/SectionHeading.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import { aronia } from '@/routes';
import { index as productsIndex } from '@/routes/products';

/**
 * Everything on this page is either a matter of public record (his academic
 * posts, patents and publications) or a citation of published research.
 *
 * Nothing here is a health claim. Aronia has no authorised claims under EU
 * Reg. 1924/2006, so the science is presented as *what was researched*, never
 * as what the product will do for the reader. That constraint turns out to
 * make the stronger page anyway.
 */
const timeline = [
    {
        year: '1956',
        title: 'Постъпва във ВИХВП, Пловдив',
        body: 'Започва изследвания върху технологията на слънчогледовия пектин при проф. Стойко Стойков.',
    },
    {
        year: '1967',
        title: 'Оглавява катедрата',
        body: 'Ръководител на катедрата по органична химия и биохимия — пост, който заема до 1992 г., а след това ръководи катедрата по органична химия до 1999 г.',
    },
    {
        year: '1981',
        title: 'Лаборатория по биологично активни въглехидрати',
        body: 'Оглавява единствената лаборатория в България, работеща интензивно върху растителни полизахариди — филиал на ИОХ при БАН, с 28 души екип.',
    },
    {
        year: '1982',
        title: 'Доктор на химическите науки',
        body: 'Защитава докторска дисертация върху получаването, реакционната способност и физико-химичните свойства на нативни и модифицирани пектини.',
    },
    {
        year: '1984',
        title: 'Професор — и първите напитки от арония',
        body: 'Избран за професор по органична химия. След шест години изследвания започва производството на диетични напитки от арония.',
    },
    {
        year: '1998',
        title: 'Натурални сокове',
        body: 'Започва производството на натурални сокове от антоцианинови плодове — арония, къпина и ягода.',
    },
    {
        year: '2003',
        title: 'Основава Витанеа',
        body: 'Заедно със съпругата си доц. Мария Крачанова създава семейната компания, която и днес произвежда по неговата технология.',
    },
] as const;

const record = [
    { value: '150+', label: 'научни публикации' },
    { value: '40', label: 'изобретения' },
    { value: '9', label: 'чуждестранни патента' },
    { value: '17', label: 'ръководени дисертации' },
] as const;
</script>

<template>
    <Head title="Науката" />

    <SectionShell>
        <BlobField>
            <div
                class="absolute -top-64 -right-64 size-[46rem] blob-a bg-sage-200/60"
            />
        </BlobField>

        <SectionHeading
            eyebrow="Науката"
            headline="Бащата на аронията."
            subheadline="Проф. д.х.н. Христо Крачанов е химикът, който превръща аронията в земеделска култура в България — и основателят на Витанеа."
        />

        <div class="mt-16 grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-7">
                <p class="max-w-[64ch] text-lg text-pretty text-plum-900/85">
                    Аронията е трудна за ядене — стипчива до степен, в която
                    самият проф. Крачанов признава, че за четиридесет години
                    работа с нея е изял „не повече от 200–300 грама“. Точно
                    затова посвещава години на въпроса как полезното в плода да
                    стигне до хората в питейна форма.
                </p>
                <p
                    class="mt-6 max-w-[64ch] text-lg text-pretty text-plum-900/85"
                >
                    Отговорът е технология за извличане на сок, която запазва
                    активните вещества на плода, вместо да ги разрушава. По
                    същата технология Витанеа произвежда и днес.
                </p>
            </div>

            <div class="lg:col-span-5">
                <dl class="grid gap-x-10 gap-y-8 sm:grid-cols-2">
                    <div
                        v-for="item in record"
                        :key="item.label"
                        class="border-t border-plum-950/15 pt-4"
                    >
                        <dt class="sr-only">{{ item.label }}</dt>
                        <dd>
                            <span
                                class="block font-display text-4xl font-semibold text-plum-950 tabular-nums"
                            >
                                {{ item.value }}
                            </span>
                            <span
                                class="mt-1.5 block font-mono text-sm tracking-wide text-plum-900/75 uppercase"
                            >
                                {{ item.label }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </SectionShell>

    <SectionShell ground="sage">
        <h2
            class="max-w-[30ch] font-display text-3xl font-black tracking-tight text-balance text-plum-950 sm:text-4xl"
        >
            Пътят до бутилката
        </h2>

        <ol role="list" class="mt-14 grid gap-y-10">
            <li
                v-for="entry in timeline"
                :key="entry.year"
                class="grid gap-x-10 gap-y-3 border-t border-plum-950/15 pt-6 sm:grid-cols-12"
            >
                <p
                    class="font-mono text-sm tracking-wide text-plum-900/80 uppercase tabular-nums sm:col-span-2"
                >
                    {{ entry.year }}
                </p>
                <h3
                    class="font-display text-xl font-semibold text-balance text-plum-950 sm:col-span-4"
                >
                    {{ entry.title }}
                </h3>
                <p
                    class="max-w-[64ch] text-base text-pretty text-plum-900/85 sm:col-span-6"
                >
                    {{ entry.body }}
                </p>
            </li>
        </ol>
    </SectionShell>

    <SectionShell>
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-6">
                <h2
                    class="max-w-[30ch] font-display text-3xl font-black tracking-tight text-balance text-plum-950 sm:text-4xl"
                >
                    Защо аронията е и в ягодовия нектар
                </h2>

                <p
                    class="mt-6 max-w-[60ch] text-lg text-pretty text-plum-900/85"
                >
                    Сред публикациите на проф. Крачанов има една, която обяснява
                    нещо, което иначе изглежда като съвпадение: изследване върху
                    влиянието на сок и екстракт от арония върху
                    <span class="text-plum-950">
                        стабилността на пигментите в сока от малина и
                        ягода</span
                    >.
                </p>

                <p
                    class="mt-5 max-w-[60ch] text-lg text-pretty text-plum-900/85"
                >
                    Аронията не е просто добавена към другите плодове. Чрез
                    копигментация тя задържа антоцианините им — затова я има в
                    почти всеки наш продукт.
                </p>

                <div class="mt-10">
                    <ActionButton :href="aronia()" with-arrow>
                        Защо арония?
                    </ActionButton>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div
                    class="rounded-[1.75rem] bg-cream-50/70 p-8 ring-1 ring-plum-950/10"
                >
                    <p
                        class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
                    >
                        Публикувани изследвания
                    </p>

                    <ul role="list" class="mt-6 grid gap-5">
                        <li class="border-b border-plum-950/10 pb-5">
                            <p class="text-base text-pretty text-plum-950">
                                Технологии за натурални плодови сокове от
                                арония, къпина и други горски плодове
                            </p>
                            <p class="mt-1 text-base text-plum-900/75">
                                Внедрени в редовно производство.
                            </p>
                        </li>
                        <li class="border-b border-plum-950/10 pb-5">
                            <p class="text-base text-pretty text-plum-950">
                                Антоцианинови плодове и антиоксидантна активност
                            </p>
                            <p class="mt-1 text-base text-plum-900/75">
                                Синергизъм и копигментация при антоцианиновите
                                плодове.
                            </p>
                        </li>
                        <li>
                            <p class="text-base text-pretty text-plum-950">
                                Denev, Kratchanova и др. (2018)
                            </p>
                            <p class="mt-1 text-base text-plum-900/75">
                                Анализ на 23 проби българска арония,
                                <span class="italic">Journal of Chemistry</span
                                >.
                            </p>
                        </li>
                    </ul>

                    <p
                        class="mt-6 max-w-[52ch] text-base text-pretty text-plum-900/75"
                    >
                        Изследванията описват плода. Те не са твърдения за
                        здравословен ефект на конкретен продукт.
                    </p>
                </div>
            </div>
        </div>
    </SectionShell>

    <SectionShell ground="plum">
        <div class="max-w-2xl">
            <h2
                class="max-w-[26ch] font-display text-3xl font-black tracking-tight text-balance text-cream-50 sm:text-4xl"
            >
                Всеки продукт е по тази технология.
            </h2>
            <div class="mt-10">
                <ActionButton :href="productsIndex()" tone="light" with-arrow>
                    Разгледай продуктите
                </ActionButton>
            </div>
        </div>
    </SectionShell>
</template>
