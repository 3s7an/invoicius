import AOS from 'aos';
import 'aos/dist/aos.css';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { onMounted, onUnmounted, type Ref } from 'vue';

gsap.registerPlugin(ScrollTrigger, SplitText);

/**
 * Scroll and intro motion for the public (guest) pages.
 *
 * AOS handles blocks and cards (`data-aos`), GSAP handles what AOS cannot:
 *  - `data-reveal="lines"`   heading lines slide up from a mask (add `data-reveal-instant` above the fold)
 *  - `data-head`             section head: `.g-eyebrow` slides in from the left, `[data-head-lead]` from the right
 *  - `data-count="24"`       number counts up from zero
 *  - `data-stagger`          children with `data-stagger-item` rise in one after another
 *  - `data-intro` / `data-intro-fade`  hero sequence on page load
 *
 * Timing comes from the `--motion-*` tokens in guest.css. Everything is skipped
 * with `prefers-reduced-motion: reduce`.
 */

const REDUCED_MOTION = '(prefers-reduced-motion: reduce)';
const FULL_MOTION = '(prefers-reduced-motion: no-preference)';
const COUNT_DURATION = 1.6;

interface Motion {
    duration: number
    slow: number
    fade: number
    stagger: number
    shift: number
    offset: number
}

let aosStarted = false;

const seconds = (ms: number): number => ms / 1000;

/** Reads the motion tokens; a minifier may rewrite `700ms` as `.7s`. */
function readMotion(root: HTMLElement): Motion {
    const styles = getComputedStyle(root);
    const read = (name: string): number => {
        const raw = styles.getPropertyValue(name).trim();
        const value = parseFloat(raw);

        return /[^m]s$/.test(raw) ? value * 1000 : value;
    };

    return {
        duration: read('--motion-duration'),
        slow: read('--motion-duration-slow'),
        fade: read('--motion-fade'),
        stagger: read('--motion-stagger'),
        shift: read('--motion-shift'),
        offset: read('--motion-offset'),
    };
}

function startAos(motion: Motion): void {
    if (aosStarted) {
        AOS.refreshHard();
        return;
    }

    aosStarted = true;

    AOS.init({
        duration: motion.duration,
        offset: motion.offset,
        once: true,
        disable: () => window.matchMedia(REDUCED_MOTION).matches,
    });

    window.addEventListener('load', () => AOS.refresh());
}

function revealLines(root: HTMLElement, motion: Motion, start: string): void {
    root.querySelectorAll<HTMLElement>('[data-reveal="lines"]').forEach((heading) => {
        const instant = heading.hasAttribute('data-reveal-instant');

        SplitText.create(heading, {
            type: 'lines',
            mask: 'lines',
            linesClass: 'g-line',
            autoSplit: true,
            onSplit: (split) => gsap.from(split.lines, {
                yPercent: 110,
                duration: seconds(motion.slow),
                ease: 'expo.out',
                stagger: seconds(motion.stagger),
                delay: instant ? 0.1 : 0,
                scrollTrigger: instant ? undefined : { trigger: heading, start, once: true },
            }),
        });

        gsap.set(heading, { autoAlpha: 1 });
    });
}

function revealHeads(root: HTMLElement, motion: Motion, start: string): void {
    root.querySelectorAll<HTMLElement>('[data-head]').forEach((head) => {
        const label = head.querySelector('.g-eyebrow');
        const lead = head.querySelector('[data-head-lead]');
        const timeline = gsap.timeline({ scrollTrigger: { trigger: head, start, once: true } });

        const slideIn = (target: Element, from: number) => timeline.fromTo(
            target,
            { autoAlpha: 0, x: from },
            { autoAlpha: 1, x: 0, duration: seconds(motion.duration), ease: 'power3.out', clearProps: 'transform' },
            0,
        );

        if (label) slideIn(label, -motion.shift);
        if (lead) slideIn(lead, motion.shift);
    });
}

/** Returns a cleanup that puts the original numbers back. */
function countUp(root: HTMLElement, start: string): () => void {
    const nodes = [...root.querySelectorAll<HTMLElement>('[data-count]')];
    const originals = nodes.map((node) => node.textContent);

    nodes.forEach((node) => {
        const counter = { value: 0 };

        node.textContent = '0';

        gsap.to(counter, {
            value: Number(node.dataset.count),
            duration: COUNT_DURATION,
            ease: 'power2.out',
            snap: { value: 1 },
            onUpdate: () => {
                node.textContent = String(counter.value);
            },
            scrollTrigger: { trigger: node, start, once: true },
        });
    });

    return () => nodes.forEach((node, index) => {
        node.textContent = originals[index];
    });
}

function revealGroups(root: HTMLElement, motion: Motion, start: string): void {
    root.querySelectorAll<HTMLElement>('[data-stagger]').forEach((group) => {
        const items = group.querySelectorAll('[data-stagger-item]');

        if (!items.length) return;

        // Relative `y` keeps any transform the element already has in CSS.
        gsap.from(items, {
            autoAlpha: 0,
            y: `+=${motion.shift * 1.5}`,
            duration: seconds(motion.duration),
            ease: 'power3.out',
            stagger: seconds(motion.stagger),
            clearProps: 'transform',
            scrollTrigger: { trigger: group, start, once: true },
        });
    });
}

function playIntro(root: HTMLElement, motion: Motion): void {
    const rise = root.querySelectorAll('[data-intro]');
    const fade = root.querySelectorAll('[data-intro-fade]');

    if (rise.length) {
        gsap.fromTo(
            rise,
            { autoAlpha: 0, y: motion.shift },
            {
                autoAlpha: 1,
                y: 0,
                duration: seconds(motion.slow),
                ease: 'power3.out',
                stagger: 0.18,
                delay: 0.15,
                clearProps: 'transform',
            },
        );
    }

    if (fade.length) {
        gsap.fromTo(fade, { autoAlpha: 0 }, { autoAlpha: 1, duration: seconds(motion.fade), stagger: 0.2, delay: 1.1 });
    }
}

export function useGuestMotion(root: Ref<HTMLElement | null>): void {
    let media: gsap.MatchMedia | undefined;

    onMounted(() => {
        const element = root.value;

        if (!element) return;

        const motion = readMotion(element);
        const start = `top bottom-=${motion.offset}px`;

        startAos(motion);

        media = gsap.matchMedia();
        media.add(FULL_MOTION, () => {
            playIntro(element, motion);
            revealLines(element, motion, start);
            revealHeads(element, motion, start);
            revealGroups(element, motion, start);

            return countUp(element, start);
        }, element);

        document.fonts?.ready.then(() => {
            ScrollTrigger.refresh();
            AOS.refresh();
        });
    });

    onUnmounted(() => {
        media?.revert();
    });
}
