<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SectionHeading from '@/components/store/SectionHeading.vue';
import SectionShell from '@/components/store/SectionShell.vue';

const details = [
    { term: 'Телефон', lines: ['+359 32 644 016', '+359 884 524 995'] },
    { term: 'Имейл', lines: ['order@vitanea.com'] },
    {
        term: 'Производствена база',
        lines: ['гр. Пловдив', 'Рогошко шосе, м. Гъсти могили'],
    },
    {
        term: 'Седалище',
        lines: ['гр. Пловдив', 'ул. „Д-р Г. М. Димитров“ № 20, ет. 1'],
    },
    {
        term: 'Фирма',
        lines: ['ВИТАНЕА ООД', 'ЕИК 115796742'],
    },
] as const;
</script>

<template>
    <Head title="Контакти" />

    <SectionShell>
        <SectionHeading
            headline="Контакти"
            subheadline="Работим в делнични дни. Ще отговорим възможно най-бързо."
        />

        <dl class="mt-14 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="detail in details"
                :key="detail.term"
                class="border-t border-plum-950/15 pt-5"
            >
                <dt
                    class="font-mono text-sm tracking-wide text-plum-900/75 uppercase"
                >
                    {{ detail.term }}
                </dt>
                <dd class="mt-3 grid gap-1">
                    <span
                        v-for="line in detail.lines"
                        :key="line"
                        class="block text-base text-plum-950"
                    >
                        {{ line }}
                    </span>
                </dd>
            </div>
        </dl>
    </SectionShell>
</template>
