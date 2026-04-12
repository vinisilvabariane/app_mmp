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
    const quickBtns = document.querySelectorAll('.chatbot-quick-btn')
    const resetBtn = document.getElementById('chatbot-reset')

    if (!messagesEl || quickBtns.length === 0) {
        return
    }

    const initialMarkup = messagesEl.innerHTML

    const addMsg = (text, role) => {
        const item = document.createElement('div')
        item.className = `chat-msg ${role === 'assistant' ? 'bot' : 'user'}`
        item.dataset.role = role
        item.textContent = text
        messagesEl.appendChild(item)
        messagesEl.scrollTop = messagesEl.scrollHeight
        return item
    }

    const resetChat = () => {
        messagesEl.innerHTML = initialMarkup
    }

    const ask = (question, answer) => {
        const q = String(question || '').trim()
        const a = String(answer || '').trim()
        if (q === '' || a === '') return

        addMsg(q, 'user')
        addMsg(a, 'assistant')
    }

    quickBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            ask(btn.dataset.question || '', btn.dataset.answer || '')
        })
    })

    if (resetBtn) {
        resetBtn.addEventListener('click', resetChat)
    }
})()
