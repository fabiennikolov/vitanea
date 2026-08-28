<script setup lang="ts">
import { PackageCheck, ShieldCheck, Truck } from '@lucide/vue';

const items = [
    { icon: Truck, title: 'Еконт и Спиди', body: 'Доставяме в цяла България.' },
    {
        icon: PackageCheck,
        title: 'Безплатна доставка',
        body: 'При поръчки над 80 € до офис на куриер.',
    },
    {
        icon: ShieldCheck,
        title: 'Сигурно плащане',
        body: 'Наложен платеж или банков превод.',
    },
] as const;
</script>

<template>
    <div class="bg-cream-100">
        <ul
            role="list"
            class="mx-auto grid max-w-7xl gap-8 px-6 py-12 sm:grid-cols-3 lg:px-8"
        >
            <li
                v-for="(item, position) in items"
                :key="item.title"
                :class="[
                    'flex items-start gap-4',
                    position > 0 && 'sm:border-l sm:border-plum-950/10 sm:pl-8',
                ]"
            >
                <component
                    :is="item.icon"
                    class="size-5 shrink-0 stroke-plum-800"
                />
                <div>
                    <p class="text-base text-plum-950">{{ item.title }}</p>
                    <p
                        class="mt-1 max-w-[36ch] text-base text-pretty text-plum-900/80"
                    >
                        {{ item.body }}
                    </p>
                </div>
            </li>
        </ul>
    </div>
</template>
