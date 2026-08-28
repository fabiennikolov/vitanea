<script setup lang="ts">
import ActionButton from '@/components/store/ActionButton.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import { science } from '@/routes';

/**
 * Occupies the slot mock v2 gave to a 4.9-star rating panel. That panel cannot
 * be filled honestly — Vitanea has zero reviews across its entire current
 * site — so the same layout carries the thing it actually has instead: a
 * founder with 40 inventions and nine foreign patents.
 *
 * Figures come from his Bulgarian Wikipedia entry and are matters of public
 * record, not health claims.
 */
const stats = [
    { value: '150+', label: 'научни публикации' },
    { value: '40', label: 'изобретения' },
    { value: '9', label: 'чуждестранни патента' },
    { value: '32', label: 'години начело на катедра' },
] as const;
</script>

<template>
    <SectionShell ground="plum">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-6">
                <p
                    class="font-mono text-sm tracking-wide text-cream-100/75 uppercase"
                >
                    Науката
                </p>

                <h2
                    class="mt-5 max-w-[24ch] font-display text-4xl font-black tracking-tight text-balance text-cream-50 sm:text-5xl"
                >
                    Аронията стана известна в България заради един учен.
                </h2>

                <p
                    class="mt-6 max-w-[52ch] text-lg text-pretty text-cream-100/85"
                >
                    Проф. д.х.н. Христо Крачанов ръководи катедрата по органична
                    химия в УХТ Пловдив от 1967 до 1999 г. Той разработва
                    технологията за извличане на сок от арония, която запазва
                    активните вещества на плода — и през 2003 г. основава
                    Витанеа.
                </p>

                <div class="mt-10">
                    <ActionButton :href="science()" tone="light" with-arrow>
                        Науката зад Vitanea
                    </ActionButton>
                </div>
            </div>

            <div class="lg:col-span-6">
                <dl class="grid gap-x-10 gap-y-10 sm:grid-cols-2">
                    <div
                        v-for="stat in stats"
                        :key="stat.label"
                        class="border-t border-cream-100/20 pt-5"
                    >
                        <dt class="sr-only">{{ stat.label }}</dt>
                        <dd>
                            <span
                                class="block font-display text-5xl font-semibold text-cream-50 tabular-nums"
                            >
                                {{ stat.value }}
                            </span>
                            <span
                                class="mt-2 block font-mono text-sm tracking-wide text-cream-100/75 uppercase"
                            >
                                {{ stat.label }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </SectionShell>
</template>
