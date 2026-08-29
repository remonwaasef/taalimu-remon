document.addEventListener('DOMContentLoaded', () => {
    const menu = document.querySelector('.mobile-menu');
    const nav = document.querySelector('.desktop-nav');

    if (menu && nav) {
        menu.addEventListener('click', () => {
            const open = menu.getAttribute('aria-expanded') === 'true';
            menu.setAttribute('aria-expanded', String(!open));
            nav.classList.toggle('mobile-open', !open);
        });
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
});
