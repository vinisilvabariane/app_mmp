document.addEventListener('DOMContentLoaded', function () {
    'use strict'

    const QUESTION_DEFINITIONS = Array.isArray(window.FORM_QUESTION_DEFINITIONS)
        ? window.FORM_QUESTION_DEFINITIONS
        : []

    const form = document.getElementById('education-interest-form')
    const track = document.getElementById('wizard-track')
    const prevBtn = document.getElementById('wizard-prev')
    const nextBtn = document.getElementById('wizard-next')
    const submitBtn = document.getElementById('wizard-submit')
    const progressFill = document.getElementById('wizard-progress-fill')
    const progressText = document.getElementById('wizard-progress-text')
    const stepper = document.getElementById('wizard-stepper')

    if (!form || !track || !prevBtn || !nextBtn || !submitBtn || !stepper) {
        return
    }

    const buildScaleOptions = (question) => {
        const labels = Array.isArray(question.escala) && question.escala.length === 5
            ? question.escala
            : ['1', '2', '3', '4', '5']

        return labels.map((label, index) => ({
            value: String(index + 1),
            label
        }))
    }

    const applyAttributes = (element, attributes = {}) => {
        Object.entries(attributes).forEach(([key, value]) => {
            element.setAttribute(key, value)
        })
    }

    const renderDissertativa = (question) => {
        const inputType = question.input || 'textarea'

        if (inputType === 'textarea') {
            const textarea = document.createElement('textarea')
            textarea.name = question.id
            textarea.className = 'form-control wizard-textarea'
            if (question.obrigatoria) {
                textarea.required = true
            }
            applyAttributes(textarea, question.attributes)
            return textarea
        }

        const input = document.createElement('input')
        input.type = inputType
        input.name = question.id
        input.className = 'form-control'
        if (question.obrigatoria) {
            input.required = true
        }
        applyAttributes(input, question.attributes)
        return input
    }

    const renderMultiplaEscolha = (question, options) => {
        const optionsWrap = document.createElement('div')
        optionsWrap.className = 'wizard-options'

        const inputType = question.multipla ? 'checkbox' : 'radio'
        const inputName = question.multipla ? `${question.id}[]` : question.id

        options.forEach((option, index) => {
            const label = document.createElement('label')
            label.className = 'wizard-option'

            const input = document.createElement('input')
            input.type = inputType
            input.name = inputName
            input.value = option.value
            if (question.obrigatoria && index === 0) {
                input.required = true
            }

            const text = document.createElement('span')
            text.textContent = option.label

            label.appendChild(input)
            label.appendChild(text)
            optionsWrap.appendChild(label)
        })

        return optionsWrap
    }

    const renderQuestionField = (question) => {
        if (question.tipo === 'dissertativa') {
            return renderDissertativa(question)
        }

        if (question.tipo === 'intensidade_1_5') {
            return renderMultiplaEscolha(question, buildScaleOptions(question))
        }

        if (question.tipo === 'multipla_escolha') {
            return renderMultiplaEscolha(question, question.opcoes || [])
        }

        throw new Error(`Tipo de pergunta nao suportado: ${question.tipo}`)
    }

    const buildSteps = () => {
        track.innerHTML = ''

        QUESTION_DEFINITIONS.forEach((question, index) => {
            const step = document.createElement('fieldset')
            step.className = `wizard-step${index === 0 ? ' active' : ''}`
            step.dataset.step = String(index + 1)
            step.dataset.questionId = question.id

            const legend = document.createElement('legend')
            legend.className = 'wizard-question'
            legend.textContent = question.enunciado

            step.appendChild(legend)
            step.appendChild(renderQuestionField(question))
            track.appendChild(step)
        })
    }

    buildSteps()

    const steps = Array.from(track.querySelectorAll('.wizard-step'))
    if (steps.length === 0) {
        return
    }

    let current = 0
    const total = steps.length
    const stepButtons = []

    const escapeSelectorValue = (value) => {
        if (window.CSS && typeof window.CSS.escape === 'function') {
            return window.CSS.escape(value)
        }

        return String(value).replace(/["\\]/g, '\\$&')
    }

    const showToast = (type, message) => {
        if (typeof toastr === 'undefined') {
            console[type === 'error' ? 'error' : 'log'](message)
            return
        }

        toastr.options = {
            closeButton: true,
            progressBar: true,
            newestOnTop: true,
            timeOut: 3200,
            extendedTimeOut: 700,
            preventDuplicates: true
        }

        toastr[type](message)
    }

    const getQuestionText = (step) => {
        const legend = step.querySelector('.wizard-question')
        return String(legend?.textContent || '').replace(/\s+/g, ' ').trim()
    }

    const getRequiredFields = (step) => {
        return Array.from(step.querySelectorAll('input[required], textarea[required], select[required]'))
    }

    const inspectStep = (index) => {
        const step = steps[index]
        const requiredFields = getRequiredFields(step)
        const allFields = Array.from(step.querySelectorAll('input, textarea, select'))
        const processedGroups = new Set()

        for (const field of requiredFields) {
            const tagName = field.tagName.toLowerCase()
            const isRadio = field.type === 'radio'
            const isCheckbox = field.type === 'checkbox'
            const fieldId = field.name || `${tagName}-${index}`

            if ((isRadio || isCheckbox) && processedGroups.has(fieldId)) {
                continue
            }

            if (isRadio || isCheckbox) {
                processedGroups.add(fieldId)
                const selector = `input[name="${escapeSelectorValue(field.name)}"]`
                const group = Array.from(step.querySelectorAll(selector))
                const checked = group.some((input) => input.checked)

                if (!checked) {
                    return {
                        valid: false,
                        required: true,
                        answered: false,
                        kind: isRadio ? 'radio' : 'checkbox',
                        fields: group
                    }
                }

                continue
            }

            if (!String(field.value || '').trim()) {
                return {
                    valid: false,
                    required: true,
                    answered: false,
                    kind: 'field',
                    fields: [field]
                }
            }
        }

        const answered = allFields.some((field) => {
            if (field.type === 'radio' || field.type === 'checkbox') {
                return field.checked
            }

            return String(field.value || '').trim() !== ''
        })

        return {
            valid: true,
            required: requiredFields.length > 0,
            answered,
            kind: null,
            fields: []
        }
    }

    const getStepErrorNode = (step) => {
        let node = step.querySelector('.wizard-inline-error')
        if (!node) {
            node = document.createElement('p')
            node.className = 'wizard-inline-error'
            node.hidden = true
            step.appendChild(node)
        }
        return node
    }

    const clearStepErrors = (step) => {
        step.classList.remove('has-error')

        step.querySelectorAll('.wizard-field-error').forEach((field) => {
            field.classList.remove('wizard-field-error')
        })

        step.querySelectorAll('.wizard-option.is-invalid').forEach((option) => {
            option.classList.remove('is-invalid')
        })

        const errorNode = step.querySelector('.wizard-inline-error')
        if (errorNode) {
            errorNode.hidden = true
            errorNode.textContent = ''
        }
    }

    const focusField = (field) => {
        const target = field.closest('label') || field
        if (typeof target.scrollIntoView === 'function') {
            target.scrollIntoView({ behavior: 'smooth', block: 'center' })
        }
        if (typeof field.focus === 'function') {
            field.focus({ preventScroll: true })
        }
    }

    const validateStep = (index, options = {}) => {
        const { focusOnError = false, showToastOnError = false } = options
        const step = steps[index]
        const state = inspectStep(index)

        clearStepErrors(step)

        if (state.valid) {
            return { valid: true }
        }

        step.classList.add('has-error')

        if (state.kind === 'radio' || state.kind === 'checkbox') {
            state.fields.forEach((input) => input.closest('.wizard-option')?.classList.add('is-invalid'))

            const message = `A pergunta ${index + 1} e obrigatoria. Selecione uma opcao para continuar.`
            const errorNode = getStepErrorNode(step)
            errorNode.textContent = 'Selecione uma opcao obrigatoria para seguir.'
            errorNode.hidden = false

            if (focusOnError && state.fields[0]) {
                focusField(state.fields[0])
            }
            if (showToastOnError) {
                showToast('error', message)
            }

            return {
                valid: false,
                message,
                firstField: state.fields[0] || null
            }
        }

        const field = state.fields[0]
        field?.classList.add('wizard-field-error')

        const message = `A pergunta ${index + 1} e obrigatoria. Preencha o campo antes de continuar.`
        const errorNode = getStepErrorNode(step)
        errorNode.textContent = 'Este campo obrigatorio ainda nao foi preenchido.'
        errorNode.hidden = false

        if (focusOnError && field) {
            focusField(field)
        }
        if (showToastOnError) {
            showToast('error', message)
        }

        return {
            valid: false,
            message,
            firstField: field || null
        }
    }

    const isStepCompleted = (index) => inspectStep(index).valid

    const collectInvalidRequiredSteps = () => {
        return steps.reduce((invalid, step, index) => {
            const state = inspectStep(index)
            if (state.required && !state.valid) {
                invalid.push(index)
            }
            return invalid
        }, [])
    }

    const updateStepMeta = () => {
        steps.forEach((step, index) => {
            let meta = step.querySelector('.wizard-question-meta')
            const required = getRequiredFields(step).length > 0

            if (!meta) {
                meta = document.createElement('p')
                meta.className = 'wizard-question-meta'
                const legend = step.querySelector('.wizard-question')
                legend?.insertAdjacentElement('afterend', meta)
            }

            if (!required) {
                meta.textContent = inspectStep(index).answered ? 'Opcional respondida' : 'Pergunta opcional'
                return
            }

            meta.textContent = isStepCompleted(index) ? 'Obrigatoria preenchida' : 'Obrigatoria'
        })
    }

    const buildStepper = () => {
        stepper.innerHTML = ''

        steps.forEach((step, index) => {
            const button = document.createElement('button')
            button.type = 'button'
            button.className = 'wizard-step-indicator'
            button.textContent = String(index + 1)
            button.setAttribute('aria-label', `Ir para a pergunta ${index + 1}`)
            button.title = getQuestionText(step)
            button.addEventListener('click', () => {
                current = index
                updateWizard()
                const firstInput = step.querySelector('input, textarea, select')
                if (firstInput) {
                    focusField(firstInput)
                }
            })
            stepButtons.push(button)
            stepper.appendChild(button)
        })
    }

    const updateStepper = () => {
        const invalidSteps = new Set(collectInvalidRequiredSteps())
        stepButtons.forEach((button, index) => {
            const state = inspectStep(index)
            button.classList.toggle('is-current', index === current)
            button.classList.toggle('is-complete', !invalidSteps.has(index) && state.answered)
            button.classList.toggle('is-invalid', invalidSteps.has(index))
        })
    }

    const updateWizard = () => {
        steps.forEach((step, index) => {
            step.classList.toggle('active', index === current)
            step.setAttribute('aria-hidden', index === current ? 'false' : 'true')
        })
        track.style.transform = `translateX(-${current * 100}%)`
        const progress = ((current + 1) / total) * 100
        if (progressFill) {
            progressFill.style.width = `${progress}%`
        }
        const pendingRequired = collectInvalidRequiredSteps().length
        if (progressText) {
            progressText.textContent = pendingRequired > 0
                ? `Pergunta ${current + 1} de ${total} - ${pendingRequired} obrigatoria(s) pendente(s)`
                : `Pergunta ${current + 1} de ${total} - tudo preenchido`
        }

        prevBtn.disabled = current === 0

        const isLast = current === total - 1
        nextBtn.classList.toggle('d-none', isLast)
        submitBtn.classList.toggle('d-none', !isLast)

        updateStepMeta()
        updateStepper()
    }

    prevBtn.addEventListener('click', () => {
        if (current > 0) {
            current -= 1
            updateWizard()
        }
    })

    nextBtn.addEventListener('click', () => {
        const validation = validateStep(current, {
            focusOnError: true,
            showToastOnError: true
        })

        updateWizard()

        if (!validation.valid) {
            return
        }

        if (current < total - 1) {
            current += 1
            updateWizard()
        }
    })

    form.addEventListener('submit', (event) => {
        event.preventDefault()

        const invalidSteps = collectInvalidRequiredSteps()

        if (invalidSteps.length > 0) {
            current = invalidSteps[0]
            validateStep(current, {
                focusOnError: true,
                showToastOnError: false
            })
            updateWizard()

            const plural = invalidSteps.length > 1 ? 'perguntas obrigatorias pendentes' : 'pergunta obrigatoria pendente'
            showToast('error', `Existem ${invalidSteps.length} ${plural}. Revise os itens destacados.`)
            return
        }

        showToast('success', 'Formulario enviado com sucesso!')

        form.reset()
        steps.forEach(clearStepErrors)
        current = 0
        updateWizard()
    })

    form.addEventListener('input', (event) => {
        const step = event.target.closest('.wizard-step')
        if (!step) {
            return
        }

        clearStepErrors(step)
        updateWizard()
    })

    form.addEventListener('change', (event) => {
        const step = event.target.closest('.wizard-step')
        if (!step) {
            return
        }

        clearStepErrors(step)
        updateWizard()

        if (event.target.matches('input[type="radio"]')) {
            const stepIndex = steps.indexOf(step)
            if (stepIndex === current && stepIndex < total - 1 && validateStep(stepIndex).valid) {
                window.setTimeout(() => {
                    current += 1
                    updateWizard()
                }, 180)
            }
        }
    })

    document.addEventListener('keydown', (event) => {
        if (!event.altKey) {
            return
        }

        if (event.key === 'ArrowRight' && current < total - 1) {
            event.preventDefault()
            nextBtn.click()
        }

        if (event.key === 'ArrowLeft' && current > 0) {
            event.preventDefault()
            prevBtn.click()
        }
    })

    buildStepper()
    updateWizard()
})
