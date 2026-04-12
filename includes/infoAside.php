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
    <div class="chatbot-wrap" id="chatbot-panel">
        <div id="chatbot-messages" class="chatbot-messages" aria-live="polite">
            <div class="chat-msg bot" data-role="assistant">Escolha uma pergunta rapida sobre o sistema para eu te ajudar aqui no painel.</div>
        </div>
        <div class="chatbot-quick" id="chatbot-quick">
            <button type="button" class="btn btn-sm btn-outline-primary chatbot-quick-btn" data-question="Como usar o sistema?" data-answer="Use a Home para entender a proposta da plataforma, preencha a pagina Forms para montar sua trilha e use o Chat quando quiser tirar duvidas mais abertas. O melhor ponto de partida para receber direcionamento personalizado e a pagina Forms.">
                Como usar o sistema?
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary chatbot-quick-btn" data-question="O que tem em cada pagina?" data-answer="A Home apresenta o Map My Path, a Forms coleta suas respostas para orientar a trilha, a pagina Chat fica livre para conversa aberta e a area Admin concentra funcoes administrativas. Cada pagina tem um papel diferente no fluxo.">
                O que tem em cada pagina?
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary chatbot-quick-btn" data-question="Como preencher o formulario?" data-answer="Responda com o maximo de contexto real sobre seu momento, objetivos e dificuldades. Quanto mais especificas forem as respostas no formulario, mais util tende a ser a orientacao gerada a partir dele.">
                Como preencher o formulario?
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary chatbot-quick-btn" data-question="O que posso perguntar?" data-answer="No chat principal voce pode tirar duvidas abertas sobre estudos, planejamento, uso da plataforma e proximos passos. Se quiser uma analise mais estruturada sobre seu perfil, va antes para a pagina Forms.">
                O que posso perguntar?
            </button>
        </div>
        <div class="aside-chat-hint">
            Para perguntas abertas, use a pagina completa de chat.
        </div>
    </div>
</aside>
