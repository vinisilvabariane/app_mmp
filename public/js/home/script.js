/**
 * Efeito de transicao entre sections da home:
 * - Revela ao entrar na viewport
 * - Suporte a fallback quando IntersectionObserver nao existe
 */
const homeSections = document.querySelectorAll('.home-page .hero-panel, .home-page .hero-info-cards')
if (homeSections.length > 0) {
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible')
                    }   
                })
            },
            {
                root: null,
                threshold: 0.28
            }
        )

        homeSections.forEach((section) => observer.observe(section))
    } else {
        homeSections.forEach((section) => section.classList.add('is-visible'))
    }
}

/**
 * Parallax suave nas sections da home.
 * Usa RAF para manter performance no scroll.
 */
const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)')
const shouldAnimateParallax = !reducedMotionQuery.matches
if (shouldAnimateParallax && homeSections.length > 0) {
    let ticking = false

    const applyParallax = () => {
        const viewportHeight = window.innerHeight || 1

        homeSections.forEach((section, index) => {
            const rect = section.getBoundingClientRect()
            const center = rect.top + rect.height / 2
            const distanceFromViewportCenter = center - viewportHeight / 2
            const normalized = distanceFromViewportCenter / viewportHeight
            const speed = 16 + index * 4
            const offset = Math.max(-48, Math.min(48, normalized * speed))
            section.style.setProperty('--parallax-offset', `${offset.toFixed(2)}px`)
        })

        ticking = false
    }

    const onScrollParallax = () => {
        if (!ticking) {
            window.requestAnimationFrame(applyParallax)
            ticking = true
        }
    }

    applyParallax()
    window.addEventListener('scroll', onScrollParallax, { passive: true })
    window.addEventListener('resize', onScrollParallax)
}
