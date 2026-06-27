jQuery(document).ready(function($) {
    if (typeof gsap === 'undefined') return;

    function resolveDynamicTag(tag) {
        if (!tag || typeof tag === 'undefined') return '#';
        tag = tag.trim();
        if (tag.startsWith('{{') && tag.endsWith('}}')) {
            return tag;
        }
        return tag;
    }

    function initFluidNavbar() {
        const navbar = document.getElementById('fgnNavbar');
        const navOpen = document.getElementById('fgnNavOpen');
        const openBtn = document.getElementById('fgnOpenBtn');
        const navLabel = document.getElementById('fgnNavLabel');

        if (!navbar || !navOpen) return;

        let isOpen = false;
        let menuTl = null;

        // ===== Proximity-based opacity effect (desktop only) =====
        const pill = navbar.querySelector('.fgn-navbar-pill');

        // Read settings from data attributes
        const PROXIMITY_ENABLED = navbar.dataset.proximityEnabled !== 'no' && navbar.dataset.proximityEnabled !== '';
        const PROXIMITY_RADIUS = parseInt(navbar.dataset.proximityRadius, 10) || 350;
        const MIN_OPACITY = parseFloat(navbar.dataset.proximityMinOpacity) || 0.25;
        const MAX_OPACITY = 1;
        let proximityRaf = null;
        let mouseX = 0;
        let mouseY = 0;
        let currentOpacity = MAX_OPACITY;

        // Touch / hover:none means mobile/tablet — skip proximity entirely
        const hoverMQ = window.matchMedia ? window.matchMedia('(hover: hover) and (pointer: fine)') : null;
        const isProximityEnabled = PROXIMITY_ENABLED && hoverMQ && hoverMQ.matches;

        if (isProximityEnabled)
        {
            function updateProximityOpacity() {
                if (!pill) return;

                const rect = pill.getBoundingClientRect();
                const centerX = rect.left + rect.width / 2;
                const centerY = rect.top + rect.height / 2;

                const dx = mouseX - centerX;
                const dy = mouseY - centerY;
                const distance = Math.sqrt(dx * dx + dy * dy);

                // Clamp & normalise: 0 = on top of nav, 1 = at max radius
                const t = Math.min(distance / PROXIMITY_RADIUS, 1);

                // Ease the t value for a more natural falloff (quadratic)
                const eased = t * t;

                const targetOpacity = MAX_OPACITY - (MAX_OPACITY - MIN_OPACITY) * eased;

                // Smoothly tween to the target so it never jumps
                gsap.to(pill, {
                    opacity: targetOpacity,
                    duration: 0.4,
                    ease: 'power2.out',
                    overwrite: 'auto'
                });

                currentOpacity = targetOpacity;
                proximityRaf = null;
            }

            function onMouseMove(e) {
                mouseX = e.clientX;
                mouseY = e.clientY;
                // Throttle via rAF so we don't fire GSAP tweens every single mouse event
                if (proximityRaf === null && !isOpen) {
                    proximityRaf = requestAnimationFrame(updateProximityOpacity);
                }
            }

            function resetProximity() {
                if (proximityRaf !== null) {
                    cancelAnimationFrame(proximityRaf);
                    proximityRaf = null;
                }
                gsap.to(pill, { opacity: MAX_OPACITY, duration: 0.3, ease: 'power2.out', overwrite: 'auto' });
            }

            // When cursor enters the pill itself, snap to full opacity immediately
            pill.addEventListener('mouseenter', function() {
                gsap.to(pill, { opacity: MAX_OPACITY, duration: 0.25, ease: 'power2.out', overwrite: 'auto' });
            });

            // Wire/diswire if the browser reports media query changes (e.g. docked tablet)
            hoverMQ.addEventListener('change', function (e) {
                e.matches ? $(document).on('mousemove.fgn', onMouseMove) : ($(document).off('mousemove.fgn'), resetProximity());
            });

            $(document).on('mousemove.fgn', onMouseMove);
        }

        // Resolve dynamic links
        $('.dynamic-link').each(function() {
            var tag = $(this).data('dynamic-tag');
            if (tag) {
                var resolved = resolveDynamicTag(tag);
                $(this).attr('href', resolved);
            }
        });

        function buildMenuTimeline() {
            const links = navOpen.querySelectorAll('.fgn-menu-links a');
            const inlinePair = navOpen.querySelectorAll('.fgn-menu-inline-pair a');
            const secondary = navOpen.querySelector('.fgn-menu-secondary');
            const cta = navOpen.querySelectorAll('.fgn-menu-cta');

            // Set initial states
            gsap.set(links, { y: '110%', opacity: 0 });
            gsap.set(inlinePair, { y: '110%', opacity: 0 });
            gsap.set([secondary, ...cta], { opacity: 0, y: 12 });

            // Build a layered timeline with smooth staggered reveals
            const tl = gsap.timeline({
                paused: true,
                defaults: { ease: 'power3.out' }
            });

            // Phase 1: Menu links slide up with spring-like stagger
            tl.to(links, {
                y: '0%',
                opacity: 1,
                duration: 0.55,
                stagger: {
                    each: 0.07,
                    from: 'start'
                },
                ease: 'back.out(1.4)'
            });

            // Phase 2: Inline pair (if present) follows
            if (inlinePair.length) {
                tl.to(inlinePair, {
                    y: '0%',
                    opacity: 1,
                    duration: 0.5,
                    stagger: 0.06,
                    ease: 'back.out(1.4)'
                }, '-=0.3');
            }

            // Phase 3: Secondary links & CTAs fade in with gentle lift
            tl.to([secondary, ...cta], {
                opacity: 1,
                y: 0,
                duration: 0.45,
                stagger: {
                    each: 0.08,
                    from: 'start'
                },
                ease: 'power2.out'
            }, '-=0.25');

            return tl;
        }

        function toggleMenu() {
            isOpen = !isOpen;
            openBtn.classList.toggle('is-open', isOpen);
            openBtn.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
            navbar.classList.toggle('menu-open', isOpen);

            if (isOpen) {
                navOpen.classList.add('visible');
                // Disable proximity effect while menu is open (desktop only)
                if (isProximityEnabled && pill) {
                    gsap.to(pill, { opacity: 1, duration: 0.3, overwrite: 'auto' });
                }
                // Small delay so the overlay fade-in starts before the panel animates
                menuTl = buildMenuTimeline();
                gsap.delayedCall(0.08, function() {
                    if (menuTl && isOpen) menuTl.play();
                });
            } else {
                // Re-enable proximity effect when closing (desktop only)
                if (isProximityEnabled && pill) {
                    gsap.to(pill, { opacity: MAX_OPACITY, duration: 0.3, overwrite: 'auto' });
                }
                if (menuTl) {
                    menuTl.reverse().then(function() {
                        navOpen.classList.remove('visible');
                    });
                } else {
                    navOpen.classList.remove('visible');
                }
            }
        }

        function closeMenu() {
            if (!isOpen) return Promise.resolve();
            return new Promise(function(resolve) {
                isOpen = false;
                openBtn.classList.remove('is-open');
                openBtn.setAttribute('aria-label', 'Open menu');
                navbar.classList.remove('menu-open');
                if (menuTl) {
                    menuTl.reverse().then(function() {
                        navOpen.classList.remove('visible');
                        resolve();
                    });
                } else {
                    navOpen.classList.remove('visible');
                    resolve();
                }
            });
        }

        // Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') closeMenu();
        });

        // Click outside to close
        $(document).on('pointerdown', function(e) {
            if (!isOpen) return;
            if (navbar.contains(e.target)) return;
            if ($(e.target).closest('.fgn-menu-panel').length) return;
            closeMenu();
        });

        // Scroll to close
        var scrollCloseTimer = null;
        function handleScrollClose(e) {
            if (!isOpen) return;
            if ($('.fgn-menu-panel:hover').length) return;
            if (e.type === 'keydown') {
                var scrollKeys = [32, 33, 34, 35, 36, 38, 40];
                if (scrollKeys.indexOf(e.which) === -1) return;
            }
            if (scrollCloseTimer) return;
            scrollCloseTimer = setTimeout(function() { scrollCloseTimer = null; }, 200);
            closeMenu();
        }
        $(window).on('wheel.fgn touchmove.fgn scroll.fgn keydown.fgn', handleScrollClose);

        // Hamburger click
        $('#fgnOpenBtn').on('click', function(e) {
            e.preventDefault();
            toggleMenu();
        });

        // Menu link clicks
        $('.fgn-menu-links a').on('click', function(e) {
            var href = $(this).attr('href');
            if (href && href.startsWith('#') && href.length > 1 && !$(this).hasClass('dynamic-link')) {
                e.preventDefault();
                closeMenu().then(function() {
                    var target = $(href);
                    if (target.length) {
                        $('html, body').animate({ scrollTop: target.offset().top }, 600, 'swing');
                    }
                });
            } else {
                closeMenu();
            }
        });

        // CTA link clicks
        $('.fgn-menu-cta').on('click', function(e) {
            var href = $(this).attr('href');
            if (href && href.startsWith('#') && href.length > 1 && !$(this).hasClass('dynamic-link')) {
                e.preventDefault();
                closeMenu().then(function() {
                    var target = $(href);
                    if (target.length) {
                        $('html, body').animate({ scrollTop: target.offset().top }, 600, 'swing');
                    }
                });
            }
        });

        // Hover label update with smooth text swap
        $('.fgn-menu-links a').on('mouseenter', function() {
            gsap.to(navLabel, {
                opacity: 0,
                duration: 0.15,
                onComplete: function() {
                    navLabel.textContent = $(this).text();
                    gsap.to(navLabel, { opacity: 1, duration: 0.2 });
                }.bind(this)
            });
        });

        $('.fgn-menu-links a').on('mouseleave', function() {
            var defaultLabel = navLabel.dataset.default || 'Menu';
            gsap.to(navLabel, {
                opacity: 0,
                duration: 0.15,
                onComplete: function() {
                    navLabel.textContent = defaultLabel;
                    gsap.to(navLabel, { opacity: 1, duration: 0.2 });
                }
            });
        });
    }

    // Initialize
    initFluidNavbar();

    // Re-init on Elementor preview refresh
    if (window.elementorFrontend) {
        window.elementorFrontend.hooks.addAction('frontend/element_ready/widget', function() {
            initFluidNavbar();
        });
    }
});
