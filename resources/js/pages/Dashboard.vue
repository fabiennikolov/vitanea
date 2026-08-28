<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Package, ShoppingBag, UserRound } from '@lucide/vue';
import { computed } from 'vue';
import AroniaWatcher from '@/components/auth/AroniaWatcher.vue';
import { dashboard } from '@/routes';
import { index as cartIndex } from '@/routes/cart';
import { index as productsIndex } from '@/routes/products';
import { edit as profileEdit } from '@/routes/profile';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Профил',
                href: dashboard(),
            },
        ],
    },
});

const page = usePage();

const user = computed(() => page.props.auth.user);

/**
 * Shortcuts into pages that already exist, rather than panels of invented
 * numbers — the account area has no data of its own to report yet.
 */
const shortcuts = [
    {
        title: 'Продукти',
        description: 'Разгледай целия каталог.',
        href: productsIndex(),
        icon: Package,
        ground: 'bg-cream-200',
    },
    {
        title: 'Количка',
        description: 'Виж какво си избрал досега.',
        href: cartIndex(),
        icon: ShoppingBag,
        ground: 'bg-sage-200',
    },
    {
        title: 'Данни за профила',
        description: 'Имейл, парола и защита.',
        href: profileEdit(),
        icon: UserRound,
        ground: 'bg-cream-300',
    },
] as const;
</script>

<template>
    <Head title="Профил" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <section
            class="relative isolate overflow-hidden rounded-[2rem] bg-plum-800 px-7 py-8 text-cream-100 sm:px-10 sm:py-10"
        >
            <div class="flex flex-wrap items-center justify-between gap-6">
                <div>
                    <p
                        class="font-mono text-sm tracking-wide text-cream-100/70 uppercase"
                    >
                        Твоят профил
                    </p>
                    <h1
                        class="mt-3 font-display text-3xl font-black tracking-tight text-balance text-cream-50 sm:text-4xl"
                    >
                        Здравей, {{ user.name }}.
                    </h1>
                    <p class="mt-3 max-w-[46ch] text-base text-cream-100/85">
                        Оттук стигаш до каталога, количката и данните си.
                    </p>
                </div>

                <div class="w-28 shrink-0 sm:w-32">
                    <AroniaWatcher :gaze="0.5" />
                </div>
            </div>
        </section>

        <ul role="list" class="grid gap-4 md:grid-cols-3">
            <li v-for="shortcut in shortcuts" :key="shortcut.title">
                <Link
                    :href="shortcut.href"
                    :class="[
                        'group flex h-full flex-col gap-3 rounded-[1.75rem] p-6 ring-1 ring-plum-950/10 transition-[transform,translate,rotate,scale,box-shadow] duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1.5 hover:shadow-xl hover:shadow-plum-950/10 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-plum-900 motion-reduce:hover:translate-y-0',
                        shortcut.ground,
                    ]"
                >
                    <component
                        :is="shortcut.icon"
                        class="size-6 shrink-0 text-plum-900"
                    />
                    <span
                        class="font-display text-xl font-black tracking-tight text-plum-950"
                    >
                        {{ shortcut.title }}
                    </span>
                    <span class="text-base text-plum-900/80">
                        {{ shortcut.description }}
                    </span>
                </Link>
            </li>
        </ul>
    </div>
</template>
