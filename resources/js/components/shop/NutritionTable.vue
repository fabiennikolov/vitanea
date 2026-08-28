<script setup lang="ts">
import { computed } from 'vue';
import type { Nutrition } from '@/types/catalog';

type Props = {
    nutrition: Nutrition;
};

const { nutrition } = defineProps<Props>();

const rows = computed(() =>
    [
        nutrition.fruitContentPercent !== null
            ? {
                  term: 'Плодово съдържание',
                  detail: `минимум ${nutrition.fruitContentPercent}%`,
              }
            : null,
        nutrition.kcalPer100 !== null
            ? {
                  term: 'Калоричност',
                  detail: `${nutrition.kcalPer100} kcal / 100 мл`,
              }
            : null,
        nutrition.kjPer100 !== null
            ? {
                  term: 'Енергийна стойност',
                  detail: `${nutrition.kjPer100} kJ / 100 мл`,
              }
            : null,
    ].filter((row) => row !== null),
);
</script>

<template>
    <dl v-if="rows.length" class="grid gap-3">
        <div
            v-for="row in rows"
            :key="row.term"
            class="flex items-baseline justify-between gap-4 border-b border-plum-950/10 pb-3"
        >
            <dt class="text-base text-plum-900/80">{{ row.term }}</dt>
            <dd class="shrink-0 text-base text-plum-950 tabular-nums">
                {{ row.detail }}
            </dd>
        </div>
    </dl>
</template>
