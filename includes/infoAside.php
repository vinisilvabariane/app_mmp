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
    <div class="chatbot-wrap">
        <div id="chatbot-messages" class="chatbot-messages" aria-live="polite">
            <div class="chat-msg bot" data-role="assistant">Ola. Posso ajudar com duvidas rapidas em qualquer pagina.</div>
        </div>
        <form id="chatbot-form" class="chatbot-form" data-endpoint="<?= $asideBasePath ?>/chat/message">
            <input type="text" id="chatbot-input" class="form-control" placeholder="Digite sua pergunta..." autocomplete="off">
            <button type="submit" class="btn btn-primary" id="chatbot-submit">
                <i class="bi bi-send"></i>
            </button>
        </form>
    </div>
</aside>
