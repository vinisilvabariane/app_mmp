(function () {
    'use strict'

    const toggleAsideBtn = document.getElementById('toggleAside')
    const asidePanel = document.getElementById('aside-panel')
    const closeAsideBtn = document.getElementById('closeAside')
    const openAsideInfoBtn = document.getElementById('openAsideInfo')

    const toggleAside = () => {
        if (!asidePanel) return
        asidePanel.classList.toggle('open')
    }

    const closeAside = () => {
        if (!asidePanel) return
        asidePanel.classList.remove('open')
    }

    if (toggleAsideBtn && asidePanel) {
        toggleAsideBtn.addEventListener('click', toggleAside)
    }

    if (openAsideInfoBtn && asidePanel) {
        openAsideInfoBtn.addEventListener('click', toggleAside)
    }

    if (closeAsideBtn && asidePanel) {
        closeAsideBtn.addEventListener('click', closeAside)
    }

    document.addEventListener('click', (event) => {
        if (window.innerWidth <= 768) return
        if (!asidePanel || !toggleAsideBtn) return
        if (!asidePanel.contains(event.target) && !toggleAsideBtn.contains(event.target)) {
            closeAside()
        }
    })

    const messagesEl = document.getElementById('chatbot-messages')
    const formEl = document.getElementById('chatbot-form')
    const inputEl = document.getElementById('chatbot-input')
    const submitEl = document.getElementById('chatbot-submit')
    const resetBtn = document.getElementById('chatbot-reset')

    if (!messagesEl || !formEl || !inputEl || !submitEl) {
        return
    }

    const initialMarkup = messagesEl.innerHTML
    let pending = false

    const addMsg = (text, role) => {
        const item = document.createElement('div')
        item.className = `chat-msg ${role === 'assistant' ? 'bot' : 'user'}`
        item.dataset.role = role
        item.textContent = text
        messagesEl.appendChild(item)
        messagesEl.scrollTop = messagesEl.scrollHeight
        return item
    }

    const setPending = (value) => {
        pending = value
        inputEl.disabled = value
        submitEl.disabled = value

        if (value) {
            submitEl.innerHTML = '<i class="bi bi-hourglass-split"></i>'
            return
        }

        submitEl.innerHTML = '<i class="bi bi-send"></i>'
    }

    const collectHistory = () => {
        return Array.from(messagesEl.querySelectorAll('.chat-msg')).map((item) => ({
            role: item.dataset.role === 'assistant' ? 'assistant' : 'user',
            text: item.textContent || ''
        }))
    }

    const resetChat = () => {
        if (pending) return
        messagesEl.innerHTML = initialMarkup
        inputEl.value = ''
        inputEl.focus()
    }

    const ask = async (question) => {
        const q = String(question || '').trim()
        if (q === '' || pending) return

        addMsg(q, 'user')
        inputEl.value = ''
        setPending(true)

        const typingEl = addMsg('Pensando...', 'assistant')

        try {
            const response = await fetch(formEl.dataset.endpoint || '', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    message: q,
                    history: collectHistory().slice(0, -1)
                })
            })

            const payload = await response.json()
            typingEl.remove()

            if (!response.ok || !payload.ok) {
                throw new Error(payload.message || 'Falha ao consultar o assistente.')
            }

            addMsg(payload.reply || 'Sem resposta.', 'assistant')
        } catch (error) {
            typingEl.remove()
            addMsg('Nao foi possivel obter resposta do assistente agora.', 'assistant')

            if (window.toastr) {
                window.toastr.error(error.message || 'Erro ao consultar o Gemini.')
            }
        } finally {
            setPending(false)
            inputEl.focus()
        }
    }

    formEl.addEventListener('submit', (event) => {
        event.preventDefault()
        ask(inputEl.value)
    })

    if (resetBtn) {
        resetBtn.addEventListener('click', resetChat)
    }
})()
