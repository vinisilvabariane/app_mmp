document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.profile-link')
    const tabs = document.querySelectorAll('.tab-content')

    links.forEach((link) => {
        link.addEventListener('click', function (event) {
            event.preventDefault()

            const tab = this.dataset.tab
            if (!tab) {
                return
            }

            links.forEach((item) => item.classList.remove('active'))
            this.classList.add('active')

            tabs.forEach((panel) => panel.classList.remove('active'))

            const target = document.getElementById(`tab-${tab}`)
            if (target) {
                target.classList.add('active')
            }

            localStorage.setItem('activeTab', tab)
        })
    })

    const savedTab = localStorage.getItem('activeTab')
    if (savedTab) {
        const activeLink = document.querySelector(`[data-tab="${savedTab}"]`)
        if (activeLink) {
            activeLink.click()
        }
    }

    let cropper
    const input = document.getElementById('input-image')
    const image = document.getElementById('image-to-crop')
    const preview = document.getElementById('preview-image')
    const previewFallback = document.getElementById('preview-fallback')

    if (input) {
        input.addEventListener('change', (event) => {
            const file = event.target.files[0]
            if (!file) {
                return
            }

            const url = URL.createObjectURL(file)

            image.src = url
            image.style.display = 'block'

            if (cropper) {
                cropper.destroy()
            }

            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 0,
                dragMode: 'move',
                autoCropArea: 1,
                movable: true,
                zoomable: true,
                cropBoxMovable: false,
                cropBoxResizable: false,
                crop() {
                    if (!preview) {
                        return
                    }

                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300
                    })

                    preview.src = canvas.toDataURL()
                    preview.style.display = 'block'
                    if (previewFallback) {
                        previewFallback.style.display = 'none'
                    }
                }
            })
        })
    }

    async function loadTrail() {
        try {
            const response = await fetch(`${window.BASE_PATH}/profile/trail`, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })

            const payload = await response.json()
            if (!response.ok || !payload.ok) {
                throw new Error(payload.message || 'Nao foi possivel carregar a trilha.')
            }

            const trail = payload.trail || null
            renderTrailFlow(trail)
            updateStats(trail)
        } catch (error) {
            console.error('Erro ao carregar trilha:', error)
            renderTrailFlow(null)
            updateStats(null)
        }
    }

    function renderTrailFlow(record) {
        const container = document.getElementById('learningFlow')
        if (!container) {
            return
        }

        container.innerHTML = ''

        const route = record?.route || null
        const stages = Array.isArray(route?.stages) ? route.stages : []
        const metrics = record?.metrics || {}

        if (!route || stages.length === 0) {
            container.innerHTML = `
                <div class="card p-4">
                    <h5 class="mb-2">Nenhuma trilha gerada ainda</h5>
                    <p class="mb-0 text-muted">Responda o formulario para que a IA monte sua trilha de aprendizagem.</p>
                </div>
            `
            return
        }

        const diagnosisHtml = `
            <div class="card p-3 mb-3">
                <h5 class="mb-2">Diagnostico</h5>
                <p class="mb-2">${escapeHtml(route.diagnosis?.summary || 'Resumo indisponivel.')}</p>
                <div class="d-flex flex-wrap gap-2">
                    ${Object.entries(metrics).map(([key, value]) => `
                        <span class="badge text-bg-light border">${escapeHtml(formatMetricLabel(key))}: ${escapeHtml(String(value))}</span>
                    `).join('')}
                </div>
            </div>
        `

        const stagesHtml = stages.map((stage, index) => `
            <div class="card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                    <div>
                        <h5 class="mb-1">${index + 1}. ${escapeHtml(stage.title || 'Etapa')}</h5>
                        <p class="mb-0 text-muted">${escapeHtml(stage.objective || '')}</p>
                    </div>
                    <span class="badge text-bg-primary">${escapeHtml(String(stage.estimated_hours || 0))}h</span>
                </div>
                <p class="mb-2"><strong>Foco:</strong> ${escapeHtml((stage.content_focus || []).join(', '))}</p>
                <p class="mb-2"><strong>Acoes:</strong> ${escapeHtml((stage.study_actions || []).join(' | '))}</p>
                <p class="mb-2"><strong>Para avancar:</strong> ${escapeHtml(stage.advancement_criteria || '')}</p>
                <p class="mb-0"><strong>Se travar:</strong> ${escapeHtml(stage.if_struggling || '')}</p>
            </div>
        `).join('')

        container.innerHTML = `
            <div class="card p-4 mb-3">
                <h4 class="mb-2">${escapeHtml(route.central_goal || 'Trilha personalizada')}</h4>
                <p class="mb-2">${escapeHtml(route.starting_point || '')}</p>
                <p class="mb-0 text-muted">Agenda sugerida: ${escapeHtml(route.suggested_schedule || 'Nao informada')}</p>
            </div>
            ${diagnosisHtml}
            ${stagesHtml}
        `
    }

    function updateStats(record) {
        const route = record?.route || null
        const stages = Array.isArray(route?.stages) ? route.stages : []
        const total = stages.length
        const totalHours = stages.reduce((sum, stage) => sum + Number(stage.estimated_hours || 0), 0)

        const percentEl = document.getElementById('progressPercent')
        const timeEl = document.getElementById('totalTime')
        const countEl = document.getElementById('completedCount')

        if (percentEl) {
            percentEl.innerText = total > 0 ? '100%' : '0%'
        }
        if (timeEl) {
            timeEl.innerText = `${totalHours}h`
        }
        if (countEl) {
            countEl.innerText = String(total)
        }
    }

    function formatMetricLabel(value) {
        return String(value || '')
            .replace(/_score$/i, '')
            .replaceAll('_', ' ')
            .replace(/\b\w/g, (letter) => letter.toUpperCase())
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;')
    }

    loadTrail()
})
