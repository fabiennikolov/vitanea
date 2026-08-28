<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { about, aronia, contact, home, science } from '@/routes';
import { delivery, privacy, returns, terms } from '@/routes/pages';
import { index as productsIndex } from '@/routes/products';
import { index as seriesIndex } from '@/routes/series';

/**
 * Company details are the real registered ones — a Bulgarian webshop is
 * required to publish trader identity, and the current site does not.
 */
const columns = [
    {
        heading: 'Продукти',
        links: [
            { label: 'Всички продукти', href: productsIndex() },
            { label: 'Серии', href: seriesIndex() },
        ],
    },
    {
        heading: 'Витанеа',
        links: [
            { label: 'За нас', href: about() },
            { label: 'Науката', href: science() },
            { label: 'Защо арония?', href: aronia() },
            { label: 'Контакти', href: contact() },
        ],
    },
    {
        heading: 'Обслужване',
        links: [
            { label: 'Доставка', href: delivery() },
            { label: 'Общи условия', href: terms() },
            { label: 'Поверителност', href: privacy() },
            { label: 'Връщане на стока', href: returns() },
        ],
    },
] as const;

const currentYear = new Date().getFullYear();
</script>

<template>
    <footer class="bg-plum-900 text-cream-100">
        <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20">
            <div class="grid gap-12 lg:grid-cols-12 lg:gap-8">
                <div class="lg:col-span-4">
                    <Link :href="home()" aria-label="Начална страница">
                        <img
                            src="/images/logo.png"
                            alt="Vitanea"
                            class="h-7 w-auto brightness-0 invert"
                            width="360"
                            height="36"
                        />
                    </Link>

                    <p
                        class="mt-5 max-w-[42ch] text-base text-pretty text-cream-100/80"
                    >
                        Функционални напитки от истински български плодове, по
                        оригиналната технология на проф. Христо Крачанов.
                    </p>

                    <ul
                        role="list"
                        class="mt-8 grid gap-2 text-base text-cream-100/80"
                    >
                        <li>гр. Пловдив, Рогошко шосе, м. Гъсти могили</li>
                        <li>
                            <a
                                href="tel:+35932644016"
                                class="hover:text-cream-50"
                            >
                                +359 32 644 016
                            </a>
                        </li>
                        <li>
                            <a
                                href="mailto:order@vitanea.com"
                                class="hover:text-cream-50"
                            >
                                order@vitanea.com
                            </a>
                        </li>
                    </ul>
                </div>

                <div
                    v-for="column in columns"
                    :key="column.heading"
                    class="lg:col-span-2"
                >
                    <h2
                        class="font-mono text-sm tracking-wide text-cream-100/75 uppercase"
                    >
                        {{ column.heading }}
                    </h2>
                    <ul role="list" class="mt-5 grid gap-3">
                        <li
                            v-for="link in column.links"
                            :key="link.label"
                            class="text-base"
                        >
                            <Link
                                :href="link.href"
                                class="text-cream-100/85 hover:text-cream-50"
                            >
                                {{ link.label }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <div class="lg:col-span-2">
                    <h2
                        class="font-mono text-sm tracking-wide text-cream-100/75 uppercase"
                    >
                        Последвайте ни
                    </h2>
                    <ul role="list" class="mt-5 grid gap-3">
                        <li class="text-base">
                            <a
                                href="https://www.facebook.com/ARONIAJUICES"
                                target="_blank"
                                rel="noreferrer"
                                class="text-cream-100/85 hover:text-cream-50"
                            >
                                Facebook
                            </a>
                        </li>
                        <li class="text-base">
                            <a
                                href="https://www.instagram.com/vitanea_aronia/"
                                target="_blank"
                                rel="noreferrer"
                                class="text-cream-100/85 hover:text-cream-50"
                            >
                                Instagram
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="mt-16 flex flex-col gap-2 border-t border-cream-100/15 pt-8 text-base text-cream-100/75 sm:flex-row sm:items-center sm:justify-between"
            >
                <p>
                    © {{ currentYear }} ВИТАНЕА ООД · ЕИК
                    <span class="tabular-nums">115796742</span>
                </p>
                <p>Доставки само на територията на България.</p>
            </div>
        </div>
    </footer>
</template>
