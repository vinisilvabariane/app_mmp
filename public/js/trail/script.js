document.addEventListener('DOMContentLoaded', () => {
    const url = new URL(window.location.href)
    const exportBtn = document.getElementById('trailExportBtn')
    let latestPayload = {
        trail: null,
        answers: [],
        user: null
    }

    async function loadTrail() {
        try {
            const response = await fetch(`${window.BASE_PATH}/trail/data`, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })

            const payload = await response.json()
            if (!response.ok || !payload.ok) {
                throw new Error(payload.message || 'Nao foi possivel carregar a trilha.')
            }

            latestPayload = payload
            const trail = payload.trail || null
            renderTrailFlow(trail)
            updateStats(trail)
        } catch (error) {
            console.error('Erro ao carregar trilha:', error)
            latestPayload = {
                trail: null,
                answers: [],
                user: null
            }
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
        const checkpoints = Array.isArray(route?.checkpoints) ? route.checkpoints : []
        const prioritizedResources = route?.prioritized_resources || {}
        const metrics = record?.metrics || {}

        fillTrailHeader(record)

        if (!route || stages.length === 0) {
            fillTrailHeader(null)
            container.innerHTML = `
                <section class="trail-empty-card">
                    <span class="trail-kicker">Sem trilha ativa</span>
                    <h3>Nenhuma trilha gerada ainda</h3>
                    <p>Responda o formulario para que a IA monte seu plano de estudo com etapas, checkpoints e recursos recomendados.</p>
                </section>
            `
            return
        }

        const diagnosis = route.diagnosis || {}
        const diagnosisHtml = `
            <section class="trail-card trail-diagnosis-card">
                <div class="trail-card-header">
                    <div>
                        <span class="trail-section-label">Diagnostico</span>
                        <h4>Leitura do momento atual</h4>
                    </div>
                    <span class="dashboard-badge">${escapeHtml(diagnosis.starting_level || 'Nivel inicial nao informado')}</span>
                </div>
                <p class="trail-lead">${escapeHtml(diagnosis.summary || 'Resumo indisponivel.')}</p>
                <div class="trail-metric-grid">
                    ${Object.entries(metrics).map(([key, value]) => `
                        <article class="trail-metric-card">
                            <span>${escapeHtml(formatMetricLabel(key))}</span>
                            <strong>${escapeHtml(String(value))}</strong>
                        </article>
                    `).join('')}
                </div>
                <div class="trail-note-box">
                    <strong>Observacao da IA</strong>
                    <p>${escapeHtml(diagnosis.confidence_note || 'Sem observacao adicional.')}</p>
                </div>
            </section>
        `

        const stagesHtml = stages.map((stage, index) => `
            <article class="trail-stage-card">
                <div class="trail-stage-line" aria-hidden="true"></div>
                <div class="trail-stage-top">
                    <div class="trail-stage-index">${escapeHtml(String(stage.stage_number || index + 1))}</div>
                    <div class="trail-stage-copy">
                        <h4>${escapeHtml(stage.title || 'Etapa')}</h4>
                        <p>${escapeHtml(stage.objective || '')}</p>
                    </div>
                    <div class="trail-stage-hours">${escapeHtml(String(stage.estimated_hours || 0))}h</div>
                </div>
                <div class="trail-stage-grid">
                    <div class="trail-stage-block">
                        <span>Foco de conteudo</span>
                        <strong>${escapeHtml(joinList(stage.content_focus))}</strong>
                    </div>
                    <div class="trail-stage-block">
                        <span>Como estudar</span>
                        <strong>${escapeHtml(joinList(stage.study_actions, ' | '))}</strong>
                    </div>
                    <div class="trail-stage-block">
                        <span>Para avancar</span>
                        <strong>${escapeHtml(stage.advancement_criteria || 'Nao informado')}</strong>
                    </div>
                    <div class="trail-stage-block">
                        <span>Se travar</span>
                        <strong>${escapeHtml(stage.if_struggling || 'Nao informado')}</strong>
                    </div>
                    <div class="trail-stage-block">
                        <span>Se estiver indo muito bem</span>
                        <strong>${escapeHtml(stage.if_excelling || 'Nao informado')}</strong>
                    </div>
                </div>
                <div class="trail-resource-list">
                    ${(Array.isArray(stage.resources) ? stage.resources : []).map((resource) => buildResourceCard(resource)).join('')}
                </div>
            </article>
        `).join('')

        const checkpointsHtml = checkpoints.length > 0
            ? `
                <section class="trail-card">
                    <div class="trail-card-header">
                        <div>
                            <span class="trail-section-label">Checkpoints</span>
                            <h4>Pontos de revisao da trilha</h4>
                        </div>
                    </div>
                    <div class="trail-checkpoint-list">
                        ${checkpoints.map((checkpoint) => `
                            <article class="trail-checkpoint-card">
                                <h5>${escapeHtml(checkpoint.name || 'Checkpoint')}</h5>
                                <p class="trail-checkpoint-when">${escapeHtml(checkpoint.when || '')}</p>
                                <p><strong>Sinais de sucesso:</strong> ${escapeHtml(joinList(checkpoint.success_signals, ' | '))}</p>
                                <p class="mb-0"><strong>Se ainda nao estiver pronto:</strong> ${escapeHtml(joinList(checkpoint.if_not_ready, ' | '))}</p>
                            </article>
                        `).join('')}
                    </div>
                </section>
            `
            : ''

        const alternativeHtml = `
            <section class="trail-card">
                <div class="trail-card-header">
                    <div>
                        <span class="trail-section-label">Plano alternativo</span>
                        <h4>Ajustes para diferentes cenarios</h4>
                    </div>
                </div>
                <div class="trail-alt-grid">
                    <article class="trail-alt-card">
                        <span>Se travar</span>
                        <strong>${escapeHtml(route.alternative_plan?.if_blocked || 'Nao informado')}</strong>
                    </article>
                    <article class="trail-alt-card">
                        <span>Se avancar rapido</span>
                        <strong>${escapeHtml(route.alternative_plan?.if_ahead || 'Nao informado')}</strong>
                    </article>
                    <article class="trail-alt-card">
                        <span>Plano minimo viavel</span>
                        <strong>${escapeHtml(route.alternative_plan?.minimum_viable_plan || 'Nao informado')}</strong>
                    </article>
                </div>
            </section>
        `

        const prioritizedHtml = `
            <section class="trail-card">
                <div class="trail-card-header">
                    <div>
                        <span class="trail-section-label">Recursos priorizados</span>
                        <h4>Materiais e estrategias recomendados</h4>
                    </div>
                </div>
                <div class="trail-resource-columns">
                    ${buildResourceColumn('Disciplinas', prioritizedResources.disciplines)}
                    ${buildResourceColumn('Videos', prioritizedResources.videos)}
                    ${buildResourceColumn('Literatura', prioritizedResources.literature)}
                    <article class="trail-resource-column">
                        <h5>Estrategias de estudo</h5>
                        <ul>
                            ${normalizeArray(prioritizedResources.study_strategies).map((item) => `<li>${escapeHtml(item)}</li>`).join('') || '<li>Nenhuma estrategia priorizada.</li>'}
                        </ul>
                    </article>
                </div>
            </section>
        `

        container.innerHTML = `
            ${diagnosisHtml}
            <section class="trail-card">
                <div class="trail-card-header">
                    <div>
                        <span class="trail-section-label">Etapas</span>
                        <h4>Timeline da rota de estudo</h4>
                    </div>
                </div>
                <div class="trail-stage-list trail-stage-timeline">
                    ${stagesHtml}
                </div>
            </section>
            ${checkpointsHtml}
            ${alternativeHtml}
            ${prioritizedHtml}
        `
    }

    function updateStats(record) {
        const route = record?.route || null
        const stages = Array.isArray(route?.stages) ? route.stages : []
        const total = stages.length
        const totalHours = stages.reduce((sum, stage) => sum + Number(stage.estimated_hours || 0), 0)
        const checkpoints = Array.isArray(route?.checkpoints) ? route.checkpoints : []
        const support = route?.diagnosis?.support_level || '-'

        const timeEl = document.getElementById('totalTime')
        const countEl = document.getElementById('completedCount')
        const checkpointEl = document.getElementById('checkpointCount')
        const supportEl = document.getElementById('supportLevel')

        if (timeEl) {
            timeEl.innerText = `${totalHours}h`
        }
        if (countEl) {
            countEl.innerText = String(total)
        }
        if (checkpointEl) {
            checkpointEl.innerText = String(checkpoints.length)
        }
        if (supportEl) {
            supportEl.innerText = capitalizeText(support)
        }
    }

    function fillTrailHeader(record) {
        const route = record?.route || null
        const titleEl = document.getElementById('trailGoalTitle')
        const summaryEl = document.getElementById('trailGoalSummary')
        const generatedAtEl = document.getElementById('trailGeneratedAt')
        const intensityEl = document.getElementById('trailIntensity')
        const scheduleEl = document.getElementById('trailSchedule')
        const freshNoticeEl = document.getElementById('trailFreshNotice')

        if (freshNoticeEl) {
            freshNoticeEl.classList.toggle('d-none', url.searchParams.get('fresh') !== '1')
        }

        if (!route) {
            if (titleEl) titleEl.innerText = 'Aguardando trilha'
            if (summaryEl) summaryEl.innerText = 'Assim que o processamento terminar, seu plano aparece aqui com foco, ritmo e próximos passos.'
            if (generatedAtEl) generatedAtEl.innerText = '-'
            if (intensityEl) intensityEl.innerText = '-'
            if (scheduleEl) scheduleEl.innerText = '-'
            return
        }

        if (titleEl) titleEl.innerText = route.central_goal || 'Trilha personalizada'
        if (summaryEl) summaryEl.innerText = route.starting_point || route.diagnosis?.summary || 'Plano gerado a partir do formulario respondido.'
        if (generatedAtEl) generatedAtEl.innerText = formatDateTime(record?.created_at || '')
        if (intensityEl) intensityEl.innerText = capitalizeText(route.route_intensity || '-')
        if (scheduleEl) scheduleEl.innerText = route.suggested_schedule || '-'
    }

    function openPrintableExport() {
        const record = latestPayload.trail || null
        const answers = Array.isArray(latestPayload.answers) ? latestPayload.answers : []
        const user = latestPayload.user || {}

        if (!record || !record.route) {
            alert('Nenhuma trilha disponivel para exportar no momento.')
            return
        }

        const route = record.route
        const stages = Array.isArray(route.stages) ? route.stages : []
        const checkpoints = Array.isArray(route.checkpoints) ? route.checkpoints : []
        const diagnosis = route.diagnosis || {}
        const printWindow = window.open('', '_blank', 'width=980,height=860')

        if (!printWindow) {
            alert('Nao foi possivel abrir a janela de exportacao.')
            return
        }

        const html = `
            <!DOCTYPE html>
            <html lang="pt-BR">
            <head>
                <meta charset="UTF-8">
                <title>Trilha de Aprendizagem</title>
                <style>
                    body { font-family: Arial, sans-serif; color: #1f0a1d; margin: 32px; line-height: 1.5; }
                    h1,h2,h3 { margin: 0 0 10px; }
                    .muted { color: #4d5d5f; }
                    .block { border: 1px solid #d7dfd9; border-radius: 14px; padding: 18px; margin-bottom: 18px; }
                    .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
                    .pill { display: inline-block; padding: 6px 10px; border-radius: 999px; background: #edf5eb; margin: 0 8px 8px 0; }
                    .stage { border-left: 4px solid #45936c; padding-left: 14px; margin-bottom: 18px; }
                    .stage h3 { margin-bottom: 6px; }
                    .qa { margin-bottom: 12px; }
                    .qa strong { display: block; margin-bottom: 4px; }
                    ul { margin: 8px 0 0 18px; }
                    @media print { body { margin: 18px; } .no-print { display: none; } }
                </style>
            </head>
            <body>
                <div class="no-print" style="margin-bottom: 16px;">
                    <button onclick="window.print()">Salvar em PDF</button>
                </div>
                <section class="block">
                    <h1>Trilha de Aprendizagem</h1>
                    <p class="muted">${escapeHtml(user.full_name || 'Aluno')} ${user.email ? `- ${escapeHtml(user.email)}` : ''}</p>
                    <p><strong>Objetivo:</strong> ${escapeHtml(route.central_goal || 'Trilha personalizada')}</p>
                    <p><strong>Ponto de partida:</strong> ${escapeHtml(route.starting_point || '-')}</p>
                    <p><strong>Intensidade:</strong> ${escapeHtml(route.route_intensity || '-')}</p>
                    <p><strong>Ritmo sugerido:</strong> ${escapeHtml(route.suggested_schedule || '-')}</p>
                    <p><strong>Gerada em:</strong> ${escapeHtml(formatDateTime(record.created_at || '-'))}</p>
                </section>

                <section class="block">
                    <h2>Diagnostico</h2>
                    <p>${escapeHtml(diagnosis.summary || '-')}</p>
                    <div class="grid">
                        ${Object.entries(record.metrics || {}).map(([key, value]) => `<div class="pill"><strong>${escapeHtml(formatMetricLabel(key))}:</strong> ${escapeHtml(String(value))}</div>`).join('')}
                    </div>
                </section>

                <section class="block">
                    <h2>Respostas do formulario</h2>
                    ${answers.map((item) => `
                        <div class="qa">
                            <strong>${escapeHtml(item.enunciado || '')}</strong>
                            <span>${escapeHtml((item.answers || []).join(', ') || 'Sem resposta registrada')}</span>
                        </div>
                    `).join('')}
                </section>

                <section class="block">
                    <h2>Timeline da trilha</h2>
                    ${stages.map((stage, index) => `
                        <div class="stage">
                            <h3>${escapeHtml(String(stage.stage_number || index + 1))}. ${escapeHtml(stage.title || 'Etapa')}</h3>
                            <p><strong>Objetivo:</strong> ${escapeHtml(stage.objective || '-')}</p>
                            <p><strong>Foco:</strong> ${escapeHtml(joinList(stage.content_focus))}</p>
                            <p><strong>Acoes:</strong> ${escapeHtml(joinList(stage.study_actions, ' | '))}</p>
                            <p><strong>Horas:</strong> ${escapeHtml(String(stage.estimated_hours || 0))}h</p>
                            <p><strong>Para avancar:</strong> ${escapeHtml(stage.advancement_criteria || '-')}</p>
                            <p><strong>Se travar:</strong> ${escapeHtml(stage.if_struggling || '-')}</p>
                            <p><strong>Se estiver indo muito bem:</strong> ${escapeHtml(stage.if_excelling || '-')}</p>
                        </div>
                    `).join('')}
                </section>

                <section class="block">
                    <h2>Checkpoints</h2>
                    ${checkpoints.map((checkpoint) => `
                        <div class="qa">
                            <strong>${escapeHtml(checkpoint.name || 'Checkpoint')}</strong>
                            <span>${escapeHtml(checkpoint.when || '')}</span>
                            <ul>
                                <li><strong>Sinais:</strong> ${escapeHtml(joinList(checkpoint.success_signals, ' | '))}</li>
                                <li><strong>Se nao estiver pronto:</strong> ${escapeHtml(joinList(checkpoint.if_not_ready, ' | '))}</li>
                            </ul>
                        </div>
                    `).join('')}
                </section>
            </body>
            </html>
        `

        printWindow.document.open()
        printWindow.document.write(html)
        printWindow.document.close()
    }

    function buildResourceColumn(title, resources) {
        const items = normalizeArray(resources)
        return `
            <article class="trail-resource-column">
                <h5>${escapeHtml(title)}</h5>
                ${items.length > 0 ? items.map((resource) => buildResourceCard(resource)).join('') : '<p class="mb-0 text-muted">Nenhum recurso priorizado.</p>'}
            </article>
        `
    }

    function buildResourceCard(resource) {
        if (!resource || typeof resource !== 'object') {
            return ''
        }

        const title = escapeHtml(resource.title || 'Recurso')
        const kind = escapeHtml(formatMetricLabel(resource.kind || 'recurso'))
        const reason = escapeHtml(resource.reason || 'Sem justificativa informada.')
        const topic = resource.topic ? `<span>${escapeHtml(resource.topic)}</span>` : ''
        const difficulty = resource.difficulty ? `<span>${escapeHtml(String(resource.difficulty))}</span>` : ''
        const source = resource.source ? `<span>${escapeHtml(resource.source)}</span>` : ''
        const meta = [topic, difficulty, source].filter(Boolean).join('')
        const urlValue = typeof resource.url === 'string' ? resource.url.trim() : ''

        return `
            <article class="trail-resource-card">
                <div class="trail-resource-top">
                    <strong>${title}</strong>
                    <span class="dashboard-badge">${kind}</span>
                </div>
                <p>${reason}</p>
                ${meta !== '' ? `<div class="trail-resource-meta">${meta}</div>` : ''}
                ${urlValue !== '' ? `<a href="${escapeHtml(urlValue)}" target="_blank" rel="noreferrer">Abrir recurso</a>` : ''}
            </article>
        `
    }

    function normalizeArray(value) {
        return Array.isArray(value) ? value : []
    }

    function joinList(value, separator = ', ') {
        const items = normalizeArray(value).map((item) => String(item || '').trim()).filter(Boolean)
        return items.length > 0 ? items.join(separator) : 'Nao informado'
    }

    function formatDateTime(value) {
        if (!value) {
            return '-'
        }

        const date = new Date(value.replace(' ', 'T'))
        if (Number.isNaN(date.getTime())) {
            return value
        }

        return new Intl.DateTimeFormat('pt-BR', {
            dateStyle: 'short',
            timeStyle: 'short'
        }).format(date)
    }

    function capitalizeText(value) {
        const normalized = String(value || '').trim()
        if (!normalized) {
            return '-'
        }

        return normalized.charAt(0).toUpperCase() + normalized.slice(1)
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

    if (exportBtn) {
        exportBtn.addEventListener('click', openPrintableExport)
    }

    loadTrail()
})
