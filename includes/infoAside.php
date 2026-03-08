<aside id="aside-panel">
    <div class="aside-header">
        <h4><i class="bi bi-chat-dots me-2 aside-icon"></i> Lorem Bot</h4>
        <button class="btn btn-sm btn-outline-secondary" id="closeAside">
            <i class="bi bi-x"></i>
        </button>
    </div>
    <div class="chatbot-wrap">
        <div id="chatbot-messages" class="chatbot-messages" aria-live="polite">
            <div class="chat-msg bot">Lorem ipsum. Digite uma pergunta curta.</div>
        </div>
        <div class="chatbot-quick">
            <button type="button" class="btn btn-sm btn-outline-primary chatbot-quick-btn" data-question="lorem one">Lorem one</button>
            <button type="button" class="btn btn-sm btn-outline-primary chatbot-quick-btn" data-question="lorem two">Lorem two</button>
            <button type="button" class="btn btn-sm btn-outline-primary chatbot-quick-btn" data-question="lorem three">Lorem three</button>
        </div>
        <form id="chatbot-form" class="chatbot-form">
            <input type="text" id="chatbot-input" class="form-control" placeholder="Digite: lorem one, lorem two..." autocomplete="off">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send"></i>
            </button>
        </form>
    </div>
</aside>
