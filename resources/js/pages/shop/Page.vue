<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SectionHeading from '@/components/store/SectionHeading.vue';
import SectionShell from '@/components/store/SectionShell.vue';

type Props = {
    heading: string;
    sections?: { heading: string; body: string[] }[];
};

const { sections = [] } = defineProps<Props>();
</script>

<template>
    <Head :title="heading" />

    <SectionShell>
        <SectionHeading :headline="heading" />

        <div class="mt-14 grid gap-10">
            <section v-for="section in sections" :key="section.heading">
                <h2
                    class="font-display text-2xl font-semibold text-balance text-plum-950"
                >
                    {{ section.heading }}
                </h2>
                <div class="mt-4 grid gap-4">
                    <p
                        v-for="(paragraph, position) in section.body"
                        :key="position"
                        class="max-w-[72ch] text-base text-pretty text-plum-900/85"
                    >
                        {{ paragraph }}
                    </p>
                </div>
            </section>
        </div>
    </SectionShell>
</template>
