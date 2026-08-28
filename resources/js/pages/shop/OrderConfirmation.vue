<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import ActionButton from '@/components/store/ActionButton.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import { index as productsIndex } from '@/routes/products';

type Props = {
    order: {
        number: string;
        status: string;
        customerName: string;
        customerEmail: string;
        deliveryMethod: string;
        deliveryCity: string;
        deliveryAddress: string;
        paymentMethod: string;
        paymentDescription: string;
        subtotal: string;
        shipping: string;
        total: string;
        totalBgn: string;
        items: {
            name: string;
            variant: string;
            quantity: number;
            lineTotal: string;
        }[];
    };
};

defineProps<Props>();
</script>

<template>
    <Head title="Поръчката е приета" />

    <SectionShell>
        <div class="max-w-2xl">
            <span
                class="grid size-12 place-items-center rounded-full bg-sage-200"
                aria-hidden="true"
            >
                <Check class="size-5 shrink-0 stroke-plum-900" />
            </span>

            <h1
                class="mt-8 max-w-[24ch] font-display text-4xl font-black tracking-tight text-balance text-plum-950 sm:text-5xl"
            >
                Благодарим за поръчката.
            </h1>

            <p class="mt-5 max-w-[48ch] text-lg text-pretty text-plum-900/85">
                Поръчка
                <span class="text-plum-950 tabular-nums">{{
                    order.number
                }}</span>
                е приета. Изпратихме потвърждение на
                {{ order.customerEmail }}.
            </p>
        </div>

        <div class="mt-14 grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-7">
                <h2
                    class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
                >
                    Продукти
                </h2>

                <ul role="list" class="mt-6 grid gap-4">
                    <li
                        v-for="item in order.items"
                        :key="item.name + item.variant"
                        class="flex items-baseline justify-between gap-4 border-b border-plum-950/10 pb-4"
                    >
                        <span class="text-base text-plum-950">
                            {{ item.name }}
                            <span class="text-plum-900/75">
                                · {{ item.variant }} ×
                                <span class="tabular-nums">{{
                                    item.quantity
                                }}</span>
                            </span>
                        </span>
                        <span
                            class="shrink-0 text-base text-plum-950 tabular-nums"
                        >
                            {{ item.lineTotal }}
                        </span>
                    </li>
                </ul>

                <dl class="mt-6 grid gap-3">
                    <div class="flex items-baseline justify-between gap-4">
                        <dt class="text-base text-plum-900/80">
                            Междинна сума
                        </dt>
                        <dd class="text-base text-plum-950 tabular-nums">
                            {{ order.subtotal }}
                        </dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-4">
                        <dt class="text-base text-plum-900/80">Доставка</dt>
                        <dd class="text-base text-plum-950 tabular-nums">
                            {{ order.shipping }}
                        </dd>
                    </div>
                    <div
                        class="flex items-baseline justify-between gap-4 border-t border-plum-950/10 pt-3"
                    >
                        <dt class="text-lg text-plum-950">Общо</dt>
                        <dd class="text-right">
                            <span
                                class="block text-lg text-plum-950 tabular-nums"
                            >
                                {{ order.total }}
                            </span>
                            <span
                                class="block text-base text-plum-900/75 tabular-nums"
                            >
                                {{ order.totalBgn }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <aside class="lg:col-span-5">
                <div
                    class="rounded-[1.75rem] bg-cream-50/70 p-8 ring-1 ring-plum-950/10"
                >
                    <h2
                        class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
                    >
                        Доставка и плащане
                    </h2>

                    <dl class="mt-6 grid gap-5">
                        <div>
                            <dt class="text-base text-plum-900/80">
                                Получател
                            </dt>
                            <dd class="mt-1 text-base text-plum-950">
                                {{ order.customerName }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-base text-plum-900/80">Доставка</dt>
                            <dd class="mt-1 text-base text-plum-950">
                                {{ order.deliveryMethod }} ·
                                {{ order.deliveryCity }},
                                {{ order.deliveryAddress }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-base text-plum-900/80">Плащане</dt>
                            <dd class="mt-1 text-base text-plum-950">
                                {{ order.paymentMethod }}
                            </dd>
                            <dd class="mt-1 text-base text-plum-900/80">
                                {{ order.paymentDescription }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-8">
                        <ActionButton :href="productsIndex()" variant="link">
                            Обратно към продуктите
                        </ActionButton>
                    </div>
                </div>
            </aside>
        </div>
    </SectionShell>
</template>
