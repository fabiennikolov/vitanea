<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Search, X } from '@lucide/vue';
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';
import AroniaWatcher from '@/components/auth/AroniaWatcher.vue';
import PriceDisplay from '@/components/shop/PriceDisplay.vue';
import { index as productsIndex, show } from '@/routes/products';
import { suggest } from '@/routes/search';
import type { Product } from '@/types/catalog';

/**
 * Full-screen search.
 *
 * Suggestions come from a plain JSON endpoint rather than an Inertia visit:
 * the query runs on every keystroke, and a visit per keystroke would push
 * history entries and re-render the page sitting behind the overlay. Enter is
 * what performs a real visit, landing on the filtered catalog.
 */
const open = defineModel<boolean>({ required: true });

const term = ref('');
const results = ref<Product[]>([]);
const searching = ref(false);
const field = ref<HTMLInputElement | null>(null);

let debounce: ReturnType<typeof setTimeout> | undefined;
let inFlight: AbortController | undefined;

/** The berry follows the caret along the field, as it does on the login form. */
const gaze = computed(() => Math.min(term.value.length / 22, 1));

const hasTerm = computed(() => term.value.trim().length > 0);

async function fetchSuggestions(): Promise<void> {
    const query = term.value.trim();

    if (query === '') {
        results.value = [];
        searching.value = false;

        return;
    }

    // A slower connection can return an older keystroke last, so every new
    // request cancels the one before it.
    inFlight?.abort();
    inFlight = new AbortController();
    searching.value = true;

    try {
        const response = await fetch(suggest.url({ query: { q: query } }), {
            headers: { Accept: 'application/json' },
            signal: inFlight.signal,
        });

        if (!response.ok) {
            throw new Error(`Search failed: ${response.status}`);
        }

        const payload = (await response.json()) as { products: Product[] };

        results.value = payload.products;
    } catch (error) {
        if ((error as Error).name === 'AbortError') {
            return;
        }

        results.value = [];
    } finally {
        searching.value = false;
    }
}

watch(term, () => {
    clearTimeout(debounce);
    debounce = setTimeout(fetchSuggestions, 220);
});

watch(open, async (isOpen) => {
    document.body.style.overflow = isOpen ? 'hidden' : '';

    if (!isOpen) {
        return;
    }

    await nextTick();
    field.value?.focus();
});

function close(): void {
    open.value = false;
    term.value = '';
    results.value = [];
}

/** Enter leaves the overlay and lands on the catalog, filtered. */
function submit(): void {
    if (!hasTerm.value) {
        return;
    }

    router.get(productsIndex.url({ query: { q: term.value.trim() } }));
    close();
}

onBeforeUnmount(() => {
    clearTimeout(debounce);
    inFlight?.abort();
    document.body.style.overflow = '';
});
</script>

<template>
    <Teleport to="body">
        <div
            v-if="open"
            class="fixed inset-0 z-[60] flex justify-center overflow-y-auto px-4 py-16 sm:py-24"
            role="dialog"
            aria-modal="true"
            aria-label="Търсене в продуктите"
            @keydown.esc="close"
        >
            <button
                type="button"
                class="fixed inset-0 -z-10 animate-in cursor-default bg-plum-950/70 backdrop-blur-sm duration-300 fade-in"
                aria-label="Затвори търсенето"
                @click="close"
            />

            <div
                class="relative h-fit w-full max-w-2xl animate-in duration-500 ease-out fill-mode-both fade-in slide-in-from-top-4"
            >
                <!--
                  The berry sits behind the field and pokes out of its top
                  corner: the field's own ground is what crops it into a peek.
                -->
                <AroniaWatcher
                    :gaze="gaze"
                    class="pointer-events-none absolute -top-10 right-2 -z-10 w-16 rotate-[14deg] sm:-top-20 sm:right-4 sm:w-32"
                />

                <div
                    class="relative flex items-center gap-3 rounded-[1.75rem] bg-cream-50 px-5 py-4 shadow-2xl ring-1 shadow-plum-950/25 ring-plum-950/10"
                >
                    <Search class="size-6 shrink-0 text-plum-900/60" />
                    <input
                        ref="field"
                        v-model="term"
                        type="search"
                        enterkeyhint="search"
                        placeholder="Търси продукт — арония, сироп, нектар…"
                        class="w-full bg-transparent text-lg font-semibold text-plum-950 placeholder:font-normal placeholder:text-plum-900/40 focus:outline-none"
                        @keydown.enter.prevent="submit"
                    />
                    <button
                        type="button"
                        class="shrink-0 rounded-full p-2 text-plum-900/70 transition-colors hover:bg-cream-200 hover:text-plum-950 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900"
                        aria-label="Затвори търсенето"
                        @click="close"
                    >
                        <X class="size-5" />
                    </button>
                </div>

                <div
                    v-if="hasTerm"
                    class="mt-3 overflow-hidden rounded-[1.75rem] bg-cream-50 shadow-2xl ring-1 shadow-plum-950/20 ring-plum-950/10"
                >
                    <ul
                        v-if="results.length"
                        role="list"
                        class="divide-y divide-plum-950/8"
                    >
                        <li v-for="product in results" :key="product.id">
                            <Link
                                :href="show(product.slug)"
                                class="flex items-center gap-4 px-5 py-3 transition-colors hover:bg-cream-200 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-plum-900"
                                @click="close"
                            >
                                <span
                                    class="grid size-14 shrink-0 place-items-center overflow-hidden rounded-2xl bg-cream-200"
                                >
                                    <img
                                        v-if="product.image"
                                        :src="product.image"
                                        alt=""
                                        class="size-full object-contain p-1.5"
                                    />
                                </span>

                                <span class="min-w-0 flex-1">
                                    <span
                                        class="block truncate font-display font-bold text-plum-950"
                                    >
                                        {{ product.name }}
                                    </span>
                                    <span
                                        class="block font-mono text-xs tracking-wide text-plum-900/70 uppercase"
                                    >
                                        {{ product.category.name }}
                                    </span>
                                </span>

                                <PriceDisplay
                                    v-if="product.fromPrice"
                                    :price="product.fromPrice"
                                    :from="product.hasMultipleVariants"
                                />
                            </Link>
                        </li>
                    </ul>

                    <p
                        v-else-if="!searching"
                        class="px-5 py-6 text-center text-base text-plum-900/80"
                    >
                        Нищо не се намери за „{{ term }}“.
                    </p>

                    <p
                        v-else
                        class="px-5 py-6 text-center text-base text-plum-900/70"
                    >
                        Търсим…
                    </p>

                    <button
                        v-if="results.length"
                        type="button"
                        class="w-full border-t border-plum-950/10 px-5 py-3 text-base font-bold text-plum-950 transition-colors hover:bg-cream-200"
                        @click="submit"
                    >
                        Виж всички резултати
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>
