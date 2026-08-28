/**
 * Field and button treatments for the auth screens.
 *
 * These are spelled out rather than taken from the shadcn tokens because the
 * auth cards are always cream, whatever theme the visitor arrives in — a
 * token-driven field turns dark-on-cream and disappears. The `dark:` repeats
 * are deliberate: they pin the value against the theme rather than following
 * it, including where a component underneath ships its own `dark:` classes.
 */
export const authField =
    'w-full rounded-2xl border-0 bg-cream-50 px-4 py-3 text-base text-plum-950 ring-1 ring-plum-950/15 transition-shadow placeholder:text-plum-900/40 focus:ring-2 focus:ring-plum-900 focus:outline-none dark:bg-cream-50 dark:text-plum-950 dark:placeholder:text-plum-900/40';

export const authLabel = 'text-base font-bold text-plum-950 dark:text-plum-950';

export const authButton =
    'mt-2 inline-flex w-full items-center justify-center gap-3 rounded-full bg-plum-900 px-6 py-4 text-lg font-bold text-cream-50 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-0.5 hover:bg-plum-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900 active:scale-[0.98] disabled:opacity-70 motion-reduce:hover:translate-y-0';

export const authQuietButton =
    'inline-flex w-full items-center justify-center gap-3 rounded-full bg-sage-200 px-6 py-4 text-lg font-bold text-plum-950 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-0.5 hover:bg-sage-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900 active:scale-[0.98] disabled:opacity-70 motion-reduce:hover:translate-y-0';

export const authLink =
    'rounded-sm font-bold text-berry-600 underline decoration-berry-600/40 underline-offset-4 transition-colors hover:decoration-berry-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900';
