<script setup lang="ts">
import AroniaWatcher from '@/components/auth/AroniaWatcher.vue';

/**
 * The shell every auth screen sits in: one cream card carrying the berry.
 *
 * The card paints its own ground rather than inheriting `bg-background`,
 * because the storefront is light-only while the auth layout follows the
 * theme — without this the brand ink turns invisible under a dark theme.
 */
type Props = {
    /** How far along the field the caret is, 0 to 1. Drives the gaze. */
    gaze?: number;
    /** A password field has focus — the berry looks away. */
    covered?: boolean;
    /** The password is shown as plain text — one eye comes back out. */
    peeking?: boolean;
};

const { gaze = 0.5, covered = false, peeking = false } = defineProps<Props>();
</script>

<template>
    <div class="rounded-[2rem] bg-cream-100 p-7 ring-1 ring-plum-950/10 sm:p-8">
        <div class="mx-auto -mt-2 mb-3 w-32">
            <AroniaWatcher :gaze="gaze" :covered="covered" :peeking="peeking" />
        </div>

        <slot />
    </div>
</template>
