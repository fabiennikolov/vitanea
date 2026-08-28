<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

/**
 * An aronia berry that watches the form being filled in.
 *
 * It follows the caret while an ordinary field is typed in, and covers its
 * eyes the moment focus lands on the password — dropping one leaf to peek only
 * when the field itself has been switched to visible. That is the whole joke:
 * it looks away from the secret unless the person has already revealed it.
 *
 * Drawn inline rather than imported so the eyes, pupils and leaves are real
 * nodes that can be animated; a flat export could only ever sit still. The
 * body is plum rather than red because aronia is near-black — a red berry
 * would read as a cherry, which is the one fruit this brand is not about.
 */
type Props = {
    /** How far along the field the caret is, 0 to 1. Drives the gaze. */
    gaze?: number;
    /** Password field has focus — the berry stops looking. */
    covered?: boolean;
    /** Password is displayed as plain text — one eye comes out to peek. */
    peeking?: boolean;
    class?: HTMLAttributes['class'];
};

const {
    gaze = 0.5,
    covered = false,
    peeking = false,
    class: className,
} = defineProps<Props>();

const blinking = ref(false);

let blinkTimer: ReturnType<typeof setTimeout> | undefined;

/** Blinks at an irregular interval — an even rhythm reads as a machine. */
function scheduleBlink(): void {
    blinkTimer = setTimeout(
        () => {
            blinking.value = true;

            setTimeout(() => {
                blinking.value = false;
                scheduleBlink();
            }, 140);
        },
        2400 + Math.random() * 3600,
    );
}

onMounted(scheduleBlink);

onBeforeUnmount(() => {
    clearTimeout(blinkTimer);
});

/** Pupils travel across the eye as the field fills up. */
const pupilShift = computed(() => -3.6 + Math.min(Math.max(gaze, 0), 1) * 7.2);

const eyeScale = computed(() => (blinking.value && !covered ? 0.12 : 1));

const leftLeaf = computed(() =>
    covered
        ? 'translate(-2px, 9px) rotate(-4deg)'
        : 'translate(-10px, 30px) rotate(-32deg)',
);

const rightLeaf = computed(() =>
    covered && !peeking
        ? 'translate(2px, 9px) rotate(4deg)'
        : 'translate(10px, 30px) rotate(32deg)',
);
</script>

<template>
    <svg
        viewBox="0 0 140 132"
        :class="cn('h-auto w-full', className)"
        role="img"
        aria-label="Илюстрация: зрънце арония"
    >
        <!-- stem and the five-point calyx a real aronia carries on top -->
        <path
            d="M70 30 V16"
            class="stroke-sage-400"
            stroke-width="4"
            stroke-linecap="round"
        />
        <path
            d="M88 12c12-5 24-1 25 5s-9 13-19 14c-8 1-14-2-15-6s2-11 9-13z"
            class="fill-sage-300"
        />
        <path
            d="M92 17c8-3 16-1 18 2"
            class="stroke-sage-400/80"
            stroke-width="2.5"
            stroke-linecap="round"
            fill="none"
        />

        <!-- body -->
        <circle cx="70" cy="76" r="44" class="fill-plum-950" />
        <circle cx="70" cy="76" r="44" class="fill-aronia-950/35" />
        <ellipse
            cx="50"
            cy="54"
            rx="15"
            ry="10"
            class="fill-cream-50/15"
            transform="rotate(-26 50 54)"
        />
        <path
            d="M70 32 l5 9.5 10.5 1.5 -7.6 7.4 1.8 10.4 -9.7-5-9.7 5 1.8-10.4 -7.6-7.4 10.5-1.5z"
            class="fill-sage-400/45"
        />

        <!-- eyes -->
        <g
            :style="{
                transform: `scaleY(${eyeScale})`,
                transformOrigin: '70px 80px',
                transition: 'transform 120ms ease-out',
            }"
        >
            <ellipse cx="55" cy="80" rx="12" ry="13.5" class="fill-cream-50" />
            <ellipse cx="85" cy="80" rx="12" ry="13.5" class="fill-cream-50" />

            <g
                :style="{
                    transform: `translateX(${pupilShift}px)`,
                    transition: 'transform 320ms cubic-bezier(0.16, 1, 0.3, 1)',
                }"
            >
                <circle cx="55" cy="82" r="5.8" class="fill-plum-950" />
                <circle cx="85" cy="82" r="5.8" class="fill-plum-950" />
                <circle cx="53" cy="79.4" r="2" class="fill-cream-50" />
                <circle cx="83" cy="79.4" r="2" class="fill-cream-50" />
            </g>
        </g>

        <!-- leaf hands: up over the eyes while the password is being typed -->
        <g
            class="transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] motion-reduce:transition-none"
            :style="{ transform: leftLeaf, transformOrigin: '34px 104px' }"
        >
            <path
                d="M30 62c14-10 30-7 33 1s-4 20-16 24c-11 4-21 0-24-6s0-14 7-19z"
                class="fill-sage-300"
            />
            <path
                d="M34 70c10-5 20-3 24 2"
                class="stroke-sage-400"
                stroke-width="2.5"
                stroke-linecap="round"
                fill="none"
            />
        </g>

        <g
            class="transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] motion-reduce:transition-none"
            :style="{ transform: rightLeaf, transformOrigin: '106px 104px' }"
        >
            <path
                d="M110 62c-14-10-30-7-33 1s4 20 16 24c11 4 21 0 24-6s0-14-7-19z"
                class="fill-sage-300"
            />
            <path
                d="M106 70c-10-5-20-3-24 2"
                class="stroke-sage-400"
                stroke-width="2.5"
                stroke-linecap="round"
                fill="none"
            />
        </g>
    </svg>
</template>
