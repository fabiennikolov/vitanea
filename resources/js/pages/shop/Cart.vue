<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Minus, Plus, X } from '@lucide/vue';
import ActionButton from '@/components/store/ActionButton.vue';
import SectionHeading from '@/components/store/SectionHeading.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import { destroy, update } from '@/routes/cart';
import { create } from '@/routes/checkout';
import { index as productsIndex, show } from '@/routes/products';

type Line = {
    variantId: number;
    productName: string;
    productSlug: string;
    variantLabel: string;
    image: string | null;
    quantity: number;
    inStock: boolean;
    unitPrice: string;
    lineTotal: string;
};

type Props = {
    lines: Line[];
    subtotal: string;
    subtotalBgn: string;
    freeShippingThreshold: string;
    freeShippingRemaining: string | null;
};

defineProps<Props>();

function setQuantity(variantId: number, quantity: number): void {
    router.patch(
        update().url,
        { variant_id: variantId, quantity },
        { preserveScroll: true, preserveState: true },
    );
}

function remove(variantId: number): void {
    router.delete(destroy(variantId).url, { preserveScroll: true });
}
</script>

<template>
    <Head title="Количка" />

    <SectionShell>
        <SectionHeading headline="Количка" />

        <div
            v-if="lines.length"
            class="mt-14 grid gap-12 lg:grid-cols-12 lg:gap-16"
        >
            <ul role="list" class="grid gap-8 lg:col-span-7">
                <li
                    v-for="line in lines"
                    :key="line.variantId"
                    class="flex gap-5 border-b border-plum-950/10 pb-8"
                >
                    <Link
                        :href="show(line.productSlug)"
                        class="grid size-28 shrink-0 place-items-center rounded-2xl bg-cream-200"
                    >
                        <img
                            v-if="line.image"
                            :src="line.image"
                            alt=""
                            class="size-full object-contain p-2"
                        />
                    </Link>

                    <div class="flex min-w-0 flex-1 flex-col">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <h2
                                    class="font-display text-xl font-black tracking-tight text-plum-950"
                                >
                                    <Link :href="show(line.productSlug)">
                                        {{ line.productName }}
                                    </Link>
                                </h2>
                                <p
                                    class="mt-1 font-mono text-sm tracking-wide text-plum-900/75 uppercase"
                                >
                                    {{ line.variantLabel }}
                                </p>
                                <p
                                    v-if="!line.inStock"
                                    class="mt-2 text-base text-berry-600"
                                >
                                    Изчерпан — премахнете го, за да продължите.
                                </p>
                            </div>

                            <button
                                type="button"
                                :aria-label="`Премахни ${line.productName}`"
                                class="shrink-0 rounded-full p-2 text-plum-900/70 hover:bg-cream-200 hover:text-plum-950"
                                @click="remove(line.variantId)"
                            >
                                <X class="size-4 shrink-0" />
                            </button>
                        </div>

                        <div
                            class="mt-auto flex flex-wrap items-center justify-between gap-4 pt-5"
                        >
                            <div
                                class="flex items-center rounded-full bg-cream-50 ring-1 ring-plum-950/15"
                            >
                                <button
                                    type="button"
                                    aria-label="Намали количеството"
                                    class="grid size-9 place-items-center rounded-full text-plum-900 hover:bg-cream-200"
                                    @click="
                                        setQuantity(
                                            line.variantId,
                                            line.quantity - 1,
                                        )
                                    "
                                >
                                    <Minus class="size-4 shrink-0" />
                                </button>
                                <span
                                    class="w-9 text-center text-base text-plum-950 tabular-nums"
                                >
                                    {{ line.quantity }}
                                </span>
                                <button
                                    type="button"
                                    aria-label="Увеличи количеството"
                                    class="grid size-9 place-items-center rounded-full text-plum-900 hover:bg-cream-200"
                                    @click="
                                        setQuantity(
                                            line.variantId,
                                            line.quantity + 1,
                                        )
                                    "
                                >
                                    <Plus class="size-4 shrink-0" />
                                </button>
                            </div>

                            <p class="text-lg text-plum-950 tabular-nums">
                                {{ line.lineTotal }}
                            </p>
                        </div>
                    </div>
                </li>
            </ul>

            <aside class="lg:col-span-5">
                <div
                    class="rounded-[1.75rem] bg-cream-50/70 p-8 ring-1 ring-plum-950/10"
                >
                    <h2
                        class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
                    >
                        Обобщение
                    </h2>

                    <dl class="mt-6 grid gap-3">
                        <div class="flex items-baseline justify-between gap-4">
                            <dt class="text-base text-plum-900/80">
                                Междинна сума
                            </dt>
                            <dd class="text-lg text-plum-950 tabular-nums">
                                {{ subtotal }}
                            </dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4">
                            <dt class="text-base text-plum-900/80">В лева</dt>
                            <dd class="text-base text-plum-900/75 tabular-nums">
                                {{ subtotalBgn }}
                            </dd>
                        </div>
                    </dl>

                    <p
                        v-if="freeShippingRemaining"
                        class="mt-6 max-w-[44ch] text-base text-pretty text-plum-900/80"
                    >
                        Още {{ freeShippingRemaining }} до безплатна доставка до
                        офис на Еконт или Спиди.
                    </p>
                    <p
                        v-else
                        class="mt-6 max-w-[44ch] text-base text-pretty text-plum-950"
                    >
                        Имате безплатна доставка до офис на куриер.
                    </p>

                    <div class="mt-8">
                        <ActionButton :href="create()" with-arrow>
                            Към поръчката
                        </ActionButton>
                    </div>
                </div>
            </aside>
        </div>

        <div v-else class="mt-14">
            <p class="max-w-[48ch] text-lg text-pretty text-plum-900/80">
                Количката е празна.
            </p>
            <div class="mt-8">
                <ActionButton :href="productsIndex()" with-arrow>
                    Разгледай продуктите
                </ActionButton>
            </div>
        </div>
    </SectionShell>
</template>
