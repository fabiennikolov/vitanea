<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
</script>

<template>
    <section class="bg-sage-200">
        <div class="mx-auto max-w-7xl px-6 py-20 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-2xl text-center">
                <h2
                    class="mx-auto max-w-[30ch] font-display text-3xl font-black tracking-tight text-balance text-plum-950 sm:text-4xl"
                >
                    Новите продукти, преди останалите.
                </h2>
                <p
                    class="mx-auto mt-5 max-w-[48ch] text-lg text-pretty text-plum-900/85"
                >
                    Веднъж месечно. Без реклами.
                </p>

                <form class="mx-auto mt-8 flex max-w-md gap-3" @submit.prevent>
                    <label for="newsletter-email" class="sr-only"
                        >Имейл адрес</label
                    >
                    <input
                        id="newsletter-email"
                        type="email"
                        name="email"
                        autocomplete="email"
                        placeholder="вашият имейл"
                        class="min-w-0 flex-1 rounded-full bg-cream-50 px-5 py-3.5 text-base text-plum-950 ring-1 ring-plum-950/15 focus:ring-2 focus:ring-plum-900 focus:outline-none"
                    />
                    <button
                        type="submit"
                        aria-label="Абонирай се"
                        class="grid size-12 shrink-0 place-items-center rounded-full bg-plum-900 text-cream-50 hover:bg-plum-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900"
                    >
                        <ArrowRight class="size-5 shrink-0" />
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>
