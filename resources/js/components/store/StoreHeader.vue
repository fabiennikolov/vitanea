<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Search, ShoppingBag, User } from '@lucide/vue';
import { computed, ref } from 'vue';
import SearchOverlay from '@/components/store/SearchOverlay.vue';
import { home, login } from '@/routes';
import { index as cartIndex } from '@/routes/cart';

const searchOpen = ref(false);

const page = usePage();
const cartCount = computed(() => page.props.cartCount ?? 0);

const actionClasses =
    'relative rounded-full p-2.5 text-plum-900/80 transition-colors hover:bg-cream-200 hover:text-plum-950 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900';
</script>

<template>
    <header
        class="sticky top-0 z-30 border-b border-plum-950/0 bg-cream-100 transition-colors"
    >
        <!--
          Fixed height: the pull-down menu parks its handle directly beneath
          this bar, so the two have to agree on where the bar ends.
        -->
        <nav
            class="mx-auto flex h-[4.5rem] max-w-7xl items-center gap-4 px-6 lg:px-8"
            aria-label="Основна навигация"
        >
            <div class="flex flex-1 items-center">
                <button
                    type="button"
                    aria-label="Търсене"
                    aria-haspopup="dialog"
                    :aria-expanded="searchOpen"
                    :class="actionClasses"
                    @click="searchOpen = true"
                >
                    <Search class="size-5 shrink-0" />
                    <span
                        class="absolute top-1/2 left-1/2 size-[max(100%,3rem)] -translate-1/2 pointer-fine:hidden"
                        aria-hidden="true"
                    />
                </button>
            </div>

            <Link
                :href="home()"
                aria-label="Начална страница"
                class="rounded-sm focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-plum-900"
            >
                <img
                    src="/images/logo.png"
                    alt="Vitanea"
                    class="h-6 w-auto translate-x-18 lg:h-7"
                    width="360"
                    height="36"
                />
            </Link>

            <div class="flex flex-1 items-center justify-end gap-1">
                <Link
                    :href="login()"
                    aria-label="Профил"
                    :class="[actionClasses, 'max-lg:hidden']"
                >
                    <User class="size-5 shrink-0" />
                </Link>

                <Link
                    :href="cartIndex()"
                    :aria-label="`Количка, ${cartCount} продукта`"
                    :class="actionClasses"
                >
                    <ShoppingBag class="size-5 shrink-0" />
                    <span
                        v-if="cartCount > 0"
                        class="absolute top-0.5 right-0.5 grid size-4 animate-in place-items-center rounded-full bg-berry-600 font-mono text-[0.625rem] text-cream-50 tabular-nums duration-300 zoom-in"
                    >
                        {{ cartCount }}
                    </span>
                    <span
                        class="absolute top-1/2 left-1/2 size-[max(100%,3rem)] -translate-1/2 pointer-fine:hidden"
                        aria-hidden="true"
                    />
                </Link>
            </div>
        </nav>

        <SearchOverlay v-model="searchOpen" />
    </header>
</template>
