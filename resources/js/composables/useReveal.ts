import { onBeforeUnmount, onMounted, ref } from 'vue';

/**
 * Reveals an element the first time it scrolls into view.
 *
 * Two things it deliberately does not do: it never re-hides an element once
 * shown (a section that fades out as you scroll back up reads as a glitch),
 * and it reveals immediately when motion is reduced or the observer is
 * unavailable — the content must never depend on the animation to appear.
 */
export function useReveal() {
    const target = ref<HTMLElement | null>(null);
    const revealed = ref(false);

    let observer: IntersectionObserver | null = null;

    onMounted(() => {
        const element = target.value;

        const staticFallback =
            !element ||
            typeof IntersectionObserver === 'undefined' ||
            window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (staticFallback) {
            revealed.value = true;

            return;
        }

        observer = new IntersectionObserver(
            (entries) => {
                if (!entries.some((entry) => entry.isIntersecting)) {
                    return;
                }

                revealed.value = true;
                observer?.disconnect();
            },
            // Fire a little before the edge, so the section has settled by the
            // time it is properly in view rather than animating under the nose.
            { rootMargin: '0px 0px -10% 0px' },
        );

        observer.observe(element as HTMLElement);
    });

    onBeforeUnmount(() => {
        observer?.disconnect();
    });

    return { target, revealed };
}
