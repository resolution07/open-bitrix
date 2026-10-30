(function () {
    'use strict';

    const root = document.documentElement;
    const themeToggle = document.getElementById('theme-toggle');
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const header = document.querySelector('.site-header');
    const progressBar = document.getElementById('reading-progress-bar');

    /* ---------- Тема ---------- */

    function applyTheme(theme) {
        root.dataset.theme = theme;
        try {
            localStorage.setItem('signal-theme', theme);
        } catch (error) {}

        if (themeToggle) {
            themeToggle.setAttribute(
                'aria-label',
                theme === 'dark' ? 'Включить светлую тему' : 'Включить тёмную тему'
            );
        }
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            applyTheme(root.dataset.theme === 'dark' ? 'light' : 'dark');
        });
    }

    /* ---------- Мобильное меню ---------- */

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function () {
            const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', String(!isOpen));
            menuToggle.setAttribute('aria-label', isOpen ? 'Открыть меню' : 'Закрыть меню');
            mobileMenu.hidden = isOpen;
        });

        mobileMenu.addEventListener('click', function (event) {
            if (event.target.closest('a')) {
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.setAttribute('aria-label', 'Открыть меню');
                mobileMenu.hidden = true;
            }
        });
    }

    /* ---------- Состояние шапки ---------- */

    function updateHeader() {
        if (!header) {
            return;
        }
        header.classList.toggle('is-scrolled', window.scrollY > 12);
    }

    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });

    /* ---------- Прогресс чтения ---------- */

    function updateReadingProgress() {
        if (!progressBar) {
            return;
        }

        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = docHeight > 0 ? Math.min(100, Math.max(0, (scrollTop / docHeight) * 100)) : 0;
        progressBar.style.width = progress + '%';
    }

    if (progressBar) {
        updateReadingProgress();
        window.addEventListener('scroll', updateReadingProgress, { passive: true });
        window.addEventListener('resize', updateReadingProgress);
    }

    /* ---------- Копирование ссылки ---------- */

    document.querySelectorAll('[data-copy-link]').forEach(function (button) {
        button.addEventListener('click', async function () {
            const originalLabel = button.dataset.copyLink || 'Скопировать';
            try {
                await navigator.clipboard.writeText(window.location.href);
                button.textContent = 'Ссылка скопирована';
                window.setTimeout(function () {
                    button.textContent = originalLabel;
                }, 2200);
            } catch (error) {
                window.prompt('Скопируйте ссылку', window.location.href);
            }
        });
    });

    /* ---------- Подписка (демонстрационный сценарий) ---------- */

    const subscribeForm = document.getElementById('subscribe-form');
    const subscribeNote = document.getElementById('subscribe-note');

    if (subscribeForm && subscribeNote) {
        subscribeForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const input = subscribeForm.querySelector('input[type="email"]');
            const email = input ? input.value.trim() : '';

            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                subscribeNote.textContent = 'Укажите корректный e-mail.';
                subscribeNote.classList.remove('is-success');
                if (input) {
                    input.focus();
                }
                return;
            }

            subscribeNote.textContent = 'Готово: адрес ' + email + ' добавлен в демонстрационный список.';
            subscribeNote.classList.add('is-success');
            subscribeForm.reset();
        });
    }

    /* ---------- Мягкое появление карточек ---------- */

    const revealItems = document.querySelectorAll('.hero-card, .post-card, .related__grid .post-card');

    if ('IntersectionObserver' in window && revealItems.length > 0) {
        revealItems.forEach(function (item) {
            item.classList.add('reveal');
        });

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

        revealItems.forEach(function (item) {
            observer.observe(item);
        });
    }
})();
