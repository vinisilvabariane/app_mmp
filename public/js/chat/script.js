(function () {
    'use strict'

    const messagesEl = document.getElementById('chat-messages')
    const formEl = document.getElementById('chat-form')
    const inputEl = document.getElementById('chat-message')
    const submitEl = document.getElementById('chat-submit')
    const statusEl = document.getElementById('chat-status')
    const emptyStateEl = document.getElementById('chat-empty-state')
    const quickBtns = document.querySelectorAll('.chat-quick-btn')
    const resetButtons = [
        document.getElementById('chat-reset-top')
    ].filter(Boolean)

    if (!messagesEl || !formEl || !inputEl || !submitEl || !statusEl) {
        return
    }

    const initialMarkup = messagesEl.innerHTML
    let pending = false

    const scrollToBottom = () => {
        messagesEl.scrollTop = messagesEl.scrollHeight
    }

    const syncEmptyState = () => {
        if (!emptyStateEl) {
            return
        }

        const hasMessages = messagesEl.querySelector('.chat-msg') !== null
        emptyStateEl.hidden = hasMessages
    }

    const setStatus = (text) => {
        statusEl.textContent = text
    }

    const setPending = (value) => {
        pending = value
        submitEl.disabled = value
        inputEl.disabled = value
        quickBtns.forEach((btn) => {
            btn.disabled = value
        })

        if (value) {
            submitEl.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Enviando...'
            setStatus('Consultando o assistente...')
            return
        }

        submitEl.innerHTML = '<i class="bi bi-send me-2"></i>Enviar'
    }

    const addMessage = (text, role) => {
        const item = document.createElement('div')
        item.className = `chat-msg ${role === 'assistant' ? 'bot' : 'user'}`
        item.dataset.role = role
        item.textContent = text
        messagesEl.appendChild(item)
        syncEmptyState()
        scrollToBottom()
        return item
    }

    const collectHistory = () => {
        return Array.from(messagesEl.querySelectorAll('.chat-msg')).map((item) => ({
            role: item.dataset.role === 'assistant' ? 'assistant' : 'user',
            text: item.textContent || ''
        }))
    }

    const resetChat = () => {
        if (pending) {
            return
        }

        messagesEl.innerHTML = initialMarkup
        inputEl.value = ''
        setStatus('Envie uma mensagem para comecar.')
        syncEmptyState()
        scrollToBottom()
        inputEl.focus()
    }

    const sendMessage = async (message) => {
        const text = String(message || '').trim()
        if (text === '' || pending) {
            return
        }

        addMessage(text, 'user')
        inputEl.value = ''
        setPending(true)

        const typingEl = addMessage('Pensando...', 'assistant')

        try {
            const response = await fetch(formEl.dataset.endpoint || '', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    message: text,
                    history: collectHistory().slice(0, -1)
                })
            })

            const payload = await response.json()
            typingEl.remove()

            if (!response.ok || !payload.ok) {
                throw new Error(payload.message || 'Falha ao consultar o assistente.')
            }

            addMessage(payload.reply || 'Sem resposta.', 'assistant')
        } catch (error) {
            typingEl.remove()
            addMessage('Nao foi possivel obter resposta do assistente agora.', 'assistant')
            setStatus(error.message || 'Erro ao consultar o assistente.')

            if (window.toastr) {
                window.toastr.error(error.message || 'Erro ao consultar o assistente.')
            }
        } finally {
            setPending(false)
            if (statusEl.textContent === 'Consultando o assistente...') {
                setStatus('Digite uma mensagem e envie para continuar a conversa.')
            }
            inputEl.focus()
        }
    }

    formEl.addEventListener('submit', (event) => {
        event.preventDefault()
        sendMessage(inputEl.value)
    })

    quickBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            sendMessage(btn.dataset.question || '')
        })
    })

    resetButtons.forEach((btn) => {
        btn.addEventListener('click', resetChat)
    })

    syncEmptyState()
    scrollToBottom()
})()
