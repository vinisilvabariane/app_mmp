<?php $asideBasePath = isset($basePath) ? (string) $basePath : ''; ?>
<aside id="aside-panel">
    <div class="aside-header">
        <h4><i class="bi bi-chat-dots me-2 aside-icon"></i> Assistente</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary" id="chatbot-reset" type="button">
                <i class="bi bi-arrow-counterclockwise"></i>
            </button>
            <button class="btn btn-sm btn-outline-secondary" id="closeAside" type="button">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>
    <div class="chatbot-wrap" id="chatbot-panel" data-endpoint="<?= $asideBasePath ?>/chat/message">
        <div id="chatbot-messages" class="chatbot-messages" aria-live="polite">
            <div class="chat-msg bot" data-role="assistant">Escolha uma pergunta rapida sobre o sistema para eu te ajudar aqui no painel.</div>
        </div>
        <div class="chatbot-quick" id="chatbot-quick">
            <button type="button" class="btn btn-sm btn-outline-primary chatbot-quick-btn" data-question="Explique rapidamente como usar o sistema Map My Path.">
                Como usar o sistema?
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary chatbot-quick-btn" data-question="Quais sao as principais areas ou paginas do sistema e para que servem?">
                O que tem em cada pagina?
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary chatbot-quick-btn" data-question="Como preencher o formulario para obter uma orientacao melhor?">
                Como preencher o formulario?
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary chatbot-quick-btn" data-question="Que tipo de ajuda eu posso pedir no chat principal desta plataforma?">
                O que posso perguntar?
            </button>
        </div>
        <div class="aside-chat-hint">
            Para perguntas abertas, use a pagina completa de chat.
        </div>
    </div>
</aside>
