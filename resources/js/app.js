import './bootstrap';
import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.data('luxuryShell', () => ({
        scrolled: false,
        mega: false,
        searchOpen: false,
        cartOpen: false,
        accountOpen: false,
        mobileMenu: false,

        init() {
            const onScroll = () => {
                this.scrolled = window.scrollY > 16;
            };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });

            ['cartOpen', 'mobileMenu', 'searchOpen'].forEach((key) => {
                this.$watch(key, () => {
                    const locked = this.cartOpen || this.mobileMenu || this.searchOpen;
                    document.body.classList.toggle('overflow-hidden', locked);
                });
            });
        },

        get categories() {
            return window.__NAV_CATEGORIES__ || [];
        },

        closeOverlays() {
            this.mega = false;
            this.searchOpen = false;
            this.cartOpen = false;
            this.accountOpen = false;
            this.mobileMenu = false;
        },
    }));
});

window.Alpine = Alpine;
Alpine.start();

function initHeroSlider() {
    const root = document.querySelector('[data-hero-slider]');
    if (!root) return;

    const slides = [...root.querySelectorAll('[data-hero-slide]')];
    const dots = [...root.querySelectorAll('[data-hero-dot]')];
    const prev = root.querySelector('[data-hero-prev]');
    const next = root.querySelector('[data-hero-next]');
    if (slides.length === 0) return;

    let index = 0;
    let timer;

    const prefersReduced =
        window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const show = (i) => {
        index = (i + slides.length) % slides.length;
        slides.forEach((el, j) => el.classList.toggle('is-active', j === index));
        dots.forEach((d, j) => {
            d.classList.toggle('bg-amber-500', j === index);
            d.classList.toggle('bg-white/25', j !== index);
        });
    };

    const nextSlide = () => show(index + 1);
    const prevSlide = () => show(index - 1);

    const arm = () => {
        clearInterval(timer);
        if (prefersReduced || slides.length < 2) return;
        timer = setInterval(nextSlide, 7200);
    };

    next?.addEventListener('click', () => {
        nextSlide();
        arm();
    });
    prev?.addEventListener('click', () => {
        prevSlide();
        arm();
    });
    dots.forEach((d, j) =>
        d.addEventListener('click', () => {
            show(j);
            arm();
        }),
    );

    show(0);
    arm();
}

function initProductZoom() {
    const stage = document.querySelector('[data-pdp-zoom]');
    const img = document.querySelector('[data-pdp-zoom-img]');
    if (!stage || !img) return;

    const setTransform = (x, y) => {
        const rx = Math.min(Math.max(x / stage.clientWidth, 0), 1);
        const ry = Math.min(Math.max(y / stage.clientHeight, 0), 1);
        const xp = (0.5 - rx) * 40;
        const yp = (0.5 - ry) * 40;
        img.style.transformOrigin = `${rx * 100}% ${ry * 100}%`;
        img.style.transform = `scale(1.55) translate(${xp}%, ${yp}%)`;
    };

    stage.addEventListener('mousemove', (e) => {
        const r = stage.getBoundingClientRect();
        setTransform(e.clientX - r.left, e.clientY - r.top);
    });
    stage.addEventListener('mouseleave', () => {
        img.style.transform = 'scale(1) translate(0,0)';
        img.style.transformOrigin = 'center center';
    });
}

function initReveal() {
    const els = document.querySelectorAll('.lux-reveal');
    if (!els.length || !('IntersectionObserver' in window)) {
        els.forEach((el) => el.classList.add('is-visible'));
        return;
    }
    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((en) => {
                if (en.isIntersecting) {
                    en.target.classList.add('is-visible');
                    io.unobserve(en.target);
                }
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.08 },
    );
    els.forEach((el) => io.observe(el));
}

function initCardGlow() {
    document.querySelectorAll('[data-lux-card]').forEach((card) => {
        card.addEventListener('mousemove', (e) => {
            const r = card.getBoundingClientRect();
            card.style.setProperty('--mouse-x', `${e.clientX - r.left}px`);
            card.style.setProperty('--mouse-y', `${e.clientY - r.top}px`);
        });
    });
}

function initPdpThumbSwap() {
    document.querySelectorAll('[data-pdp-thumb]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const src = btn.getAttribute('data-pdp-thumb');
            const main = document.querySelector('[data-pdp-zoom-img]');
            if (src && main) main.src = src;
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initHeroSlider();
    initProductZoom();
    initReveal();
    initCardGlow();
    initPdpThumbSwap();
});
