/**
 * Hand-drawn marks for hover states.
 *
 * Every call returns a different path, so a control never draws the same
 * squiggle twice — the point is that it reads as drawn by hand in the moment
 * rather than as a static asset being switched on.
 *
 * Paths are authored in a 100×16 box and rendered with
 * `preserveAspectRatio="none"`, so callers must set `vector-effect` to keep the
 * stroke even once the box is stretched to the width of a label.
 */
type Mark = 'underline' | 'double' | 'zigzag' | 'sweep';

const marks: Mark[] = ['underline', 'double', 'zigzag', 'sweep'];

/** Random number in [min, max). */
function between(min: number, max: number): number {
    return min + Math.random() * (max - min);
}

/** `value` nudged by up to `amount` in either direction. */
function jitter(value: number, amount: number): string {
    return (value + between(-amount, amount)).toFixed(2);
}

function underline(): string {
    return [
        `M ${jitter(2, 1.5)} ${jitter(9, 1.5)}`,
        `C ${jitter(24, 6)} ${jitter(5, 2.5)},`,
        `${jitter(44, 6)} ${jitter(13, 2.5)},`,
        `${jitter(66, 6)} ${jitter(8, 2.5)}`,
        `S ${jitter(88, 5)} ${jitter(7, 2.5)},`,
        `${jitter(98, 1.5)} ${jitter(10, 1.5)}`,
    ].join(' ');
}

function double(): string {
    return `${underline()} M ${jitter(6, 2)} ${jitter(14, 1)} C ${jitter(30, 8)} ${jitter(12, 2)}, ${jitter(62, 8)} ${jitter(16, 2)}, ${jitter(94, 2)} ${jitter(13, 1)}`;
}

function zigzag(): string {
    const steps = Math.round(between(4, 7));
    const points: string[] = [`M ${jitter(2, 1)} ${jitter(11, 1)}`];

    for (let step = 1; step <= steps; step += 1) {
        const x = 2 + (96 / steps) * step;
        const high = step % 2 === 0;

        points.push(`L ${jitter(x, 2)} ${jitter(high ? 11 : 5, 1.5)}`);
    }

    return points.join(' ');
}

function sweep(): string {
    // One long arc, as if drawn in a single confident stroke.
    return `M ${jitter(3, 1.5)} ${jitter(12, 1.5)} Q ${jitter(50, 10)} ${jitter(2, 2.5)}, ${jitter(97, 1.5)} ${jitter(10, 1.5)}`;
}

const builders: Record<Mark, () => string> = {
    underline,
    double,
    zigzag,
    sweep,
};

/**
 * A fresh mark. Pass the previous one to guarantee the next hover looks
 * different rather than landing on the same family twice in a row.
 */
export function randomScribble(previous?: Mark): { mark: Mark; path: string } {
    const pool = previous ? marks.filter((mark) => mark !== previous) : marks;
    const mark = pool[Math.floor(Math.random() * pool.length)];

    return { mark, path: builders[mark]() };
}

export type { Mark };
