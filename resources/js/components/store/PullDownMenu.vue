<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    computed,
    nextTick,
    onBeforeUnmount,
    onMounted,
    ref,
    watch,
} from 'vue';
import { about, aronia, contact, home, science } from '@/routes';
import { index as cartIndex } from '@/routes/cart';
import { delivery } from '@/routes/pages';
import { index as productsIndex } from '@/routes/products';
import { index as seriesIndex } from '@/routes/series';

/**
 * The whole panel is one sliding element: it sits translated a full viewport
 * height up, and the handle hangs off its bottom edge. So when the panel is
 * parked, the only thing on screen is the handle — and pulling it brings the
 * panel down with it, the way a blind travels with its pull.
 */
const navigation = [
    { label: 'Продукти', href: productsIndex() },
    { label: 'Серии', href: seriesIndex() },
    { label: 'Защо арония?', href: aronia() },
    { label: 'Науката', href: science() },
    { label: 'За нас', href: about() },
] as const;

const secondary = [
    { label: 'Количка', href: cartIndex() },
    { label: 'Доставка', href: delivery() },
    { label: 'Контакти', href: contact() },
] as const;

const open = ref(false);
const handle = ref<HTMLButtonElement | null>(null);
const firstLink = ref<InstanceType<typeof Link> | null>(null);

const label = computed(() => (open.value ? 'Затвори менюто' : 'Отвори менюто'));

function toggle(): void {
    open.value = !open.value;
}

function close(): void {
    open.value = false;
}

function onKeydown(event: KeyboardEvent): void {
    if (event.key === 'Escape' && open.value) {
        close();
    }
}

/**
 * The page behind is marked inert rather than trapped by hand: it keeps Tab,
 * screen readers and pointer events inside the panel with no key handling of
 * our own. The menu is mounted outside that element, so it stays reachable.
 */
function setShellInert(isInert: boolean): void {
    const shell = document.querySelector<HTMLElement>('[data-store-shell]');

    if (!shell) {
        return;
    }

    shell.inert = isInert;
}

watch(open, async (isOpen) => {
    document.body.style.overflow = isOpen ? 'hidden' : '';
    setShellInert(isOpen);

    await nextTick();

    if (isOpen) {
        firstLink.value?.$el?.focus?.();

        return;
    }

    handle.value?.focus();
});

onMounted(() => {
    document.addEventListener('keydown', onKeydown);
});

onBeforeUnmount(() => {
    document.removeEventListener('keydown', onKeydown);
    document.body.style.overflow = '';
    setShellInert(false);
});
</script>

<template>
    <div
        :class="[
            'fixed inset-x-0 top-0 h-[calc(100dvh-4.5rem)] transition-transform duration-[650ms] ease-[cubic-bezier(0.16,1,0.3,1)] will-change-transform motion-reduce:transition-none',
            // Down, it starts at the very top and covers the header. Parked,
            // it retracts until only the header's height is left showing, so
            // the handle comes to rest just below the bar instead of on top
            // of the centred logo — and z-20 lets it hide behind the bar.
            open
                ? 'z-50 translate-y-0'
                : 'z-20 translate-y-[calc(4.5rem-100%)]',
        ]"
    >
        <div
            id="pull-down-menu"
            :inert="!open"
            class="flex h-full flex-col overflow-y-auto bg-plum-900 px-6 pt-10 pb-16 text-cream-100 lg:px-8"
        >
            <Link
                :href="home()"
                aria-label="Начална страница"
                :style="{ transitionDelay: open ? '120ms' : '0ms' }"
                :class="[
                    'transition-[opacity,transform,translate,rotate,scale] duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] motion-reduce:transition-none',
                    open
                        ? 'translate-y-0 opacity-100'
                        : '-translate-y-2 opacity-0',
                ]"
                @click="close"
            >
                <img
                    src="/images/logo.png"
                    alt="Vitanea"
                    class="mx-auto h-7 w-fit translate-x-18 brightness-0 invert"
                    width="360"
                    height="36"
                />
            </Link>

            <nav aria-label="Основна навигация" class="my-auto py-10">
                <ul role="list" class="grid justify-items-center gap-3">
                    <li
                        v-for="(item, position) in navigation"
                        :key="item.label"
                        :style="{
                            transitionDelay: open
                                ? `${180 + position * 70}ms`
                                : '0ms',
                        }"
                        :class="[
                            'transition-[opacity,transform,translate,rotate,scale] duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] motion-reduce:transition-none',
                            open
                                ? 'translate-y-0 opacity-100'
                                : 'translate-y-6 opacity-0',
                        ]"
                    >
                        <Link
                            :ref="
                                (element) => {
                                    if (position === 0) {
                                        firstLink = element as never;
                                    }
                                }
                            "
                            :href="item.href"
                            view-transition
                            class="block rounded-2xl px-4 py-1 font-display text-4xl font-black tracking-tight text-cream-50 transition-[color,transform,translate,rotate,scale] duration-300 ease-out hover:scale-[1.04] hover:text-cream-300 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-cream-50 motion-reduce:hover:scale-100 sm:text-5xl lg:text-6xl"
                            @click="close"
                        >
                            {{ item.label }}
                        </Link>
                    </li>
                </ul>

                <ul
                    role="list"
                    :style="{ transitionDelay: open ? '560ms' : '0ms' }"
                    :class="[
                        'mt-12 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 transition-[opacity,transform,translate,rotate,scale] duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] motion-reduce:transition-none',
                        open
                            ? 'translate-y-0 opacity-100'
                            : 'translate-y-4 opacity-0',
                    ]"
                >
                    <li v-for="item in secondary" :key="item.label">
                        <Link
                            :href="item.href"
                            class="rounded-sm text-base text-cream-100/80 underline decoration-cream-100/30 underline-offset-8 transition-colors hover:text-cream-50 hover:decoration-cream-50 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-cream-50"
                            @click="close"
                        >
                            {{ item.label }}
                        </Link>
                    </li>
                </ul>
            </nav>
        </div>

        <!--
          The handle hangs below the panel, so it reads as the thing you pull
          and it is the only part on screen while the panel is parked.
        -->
        <button
            ref="handle"
            type="button"
            :aria-expanded="open"
            :aria-label="label"
            aria-controls="pull-down-menu"
            class="absolute top-full left-1/2 grid -translate-x-1/2 place-items-center gap-1 rounded-b-[1.5rem] border-t-2 border-dashed border-plum-900/25 bg-cream-50 px-6 pt-2 pb-3 shadow-lg transition-colors hover:bg-cream-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900"
            @click="toggle"
        >
            <span class="grid gap-1" aria-hidden="true">
                <span
                    v-for="line in 3"
                    :key="line"
                    :class="[
                        'block h-0.5 rounded-full bg-plum-900 transition-all duration-300',
                        open && line === 2 ? 'w-3' : 'w-6',
                    ]"
                />
            </span>
            <span
                class="font-mono text-xs tracking-widest text-plum-900 uppercase"
            >
                {{ open ? 'Затвори' : 'Меню' }}
            </span>
        </button>
    </div>
</template>
