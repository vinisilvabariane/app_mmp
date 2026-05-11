document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('.question-config-form');
    forms.forEach((form, index) => initQuestionForm(form, index));
});

function initQuestionForm(form, index) {
    const optionsContainer = form.querySelector('[data-role="options-container"]');
    const affectsContainer = form.querySelector('[data-role="affects-container"]');
    const addOptionButton = form.querySelector('[data-action="add-option"]');
    const addAffectButton = form.querySelector('[data-action="add-affect"]');
    const questionTypeSelect = form.querySelector('select[name="question_type"]');
    const optionsSection = form.querySelector('[data-role="options-section"]');
    const textConfigSection = form.querySelector('[data-role="text-config-section"]');
    const scaleConfigSection = form.querySelector('[data-role="scale-config-section"]');
    const metrics = parseJson(form.dataset.metrics, []);

    if (addOptionButton && optionsContainer) {
        addOptionButton.addEventListener('click', () => {
            appendOptionRow(optionsContainer);
            syncOptionSelects(form);
        });
    }

    if (addAffectButton && affectsContainer) {
        addAffectButton.addEventListener('click', () => {
            appendAffectRow(affectsContainer, metrics);
            syncOptionSelects(form);
        });
    }

    form.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-action="remove-row"]');
        if (!removeButton) {
            return;
        }

        const row = removeButton.closest('[data-row]');
        if (row) {
            row.remove();
            syncOptionSelects(form);
        }
    });

    form.addEventListener('input', (event) => {
        if (event.target.matches('input[name="option_values[]"], input[name="option_labels[]"]')) {
            syncOptionSelects(form);
        }
    });

    const syncType = () => {
        const questionType = questionTypeSelect?.value || '';
        const isMultipleChoice = questionType === 'multipla_escolha';
        const isTextQuestion = questionType === 'dissertativa';
        const isScaleQuestion = questionType === 'intensidade_1_5';

        if (optionsSection) {
            optionsSection.hidden = !isMultipleChoice;
            optionsSection.querySelectorAll('input').forEach((input) => {
                input.disabled = !isMultipleChoice;
            });
        }

        if (textConfigSection) {
            textConfigSection.hidden = !isTextQuestion;
            textConfigSection.querySelectorAll('input, select, textarea').forEach((field) => {
                field.disabled = !isTextQuestion;
            });
        }

        if (scaleConfigSection) {
            scaleConfigSection.hidden = !isScaleQuestion;
            scaleConfigSection.querySelectorAll('input').forEach((field) => {
                field.disabled = !isScaleQuestion;
            });
        }

        if (addOptionButton) {
            addOptionButton.disabled = !isMultipleChoice;
        }

        syncOptionSelects(form);
    };

    questionTypeSelect?.addEventListener('change', syncType);
    syncType();
}

function appendOptionRow(container) {
    const row = document.createElement('div');
    row.className = 'dashboard-config-row';
    row.dataset.role = 'option-row';
    row.dataset.row = 'option';
    row.innerHTML = `
        <div class="dashboard-config-grid dashboard-config-grid-options">
            <div>
                <label class="form-label">Valor</label>
                <input type="text" name="option_values[]" class="form-control" placeholder="ex: publica">
            </div>
            <div>
                <label class="form-label">Rotulo</label>
                <input type="text" name="option_labels[]" class="form-control" placeholder="ex: Rede publica">
            </div>
            <div class="dashboard-config-row-action">
                <button type="button" class="btn btn-outline-danger" data-action="remove-row">Remover</button>
            </div>
        </div>
    `;
    container.appendChild(row);
}

function appendAffectRow(container, metrics) {
    const row = document.createElement('div');
    row.className = 'dashboard-config-row';
    row.dataset.row = 'affect';
    row.innerHTML = `
        <div class="dashboard-config-grid dashboard-config-grid-affects">
            <div>
                <label class="form-label">Metrica</label>
                <select name="affect_metric_ids[]" class="form-select" required>
                    ${buildMetricOptions(metrics)}
                </select>
            </div>
            <div>
                <label class="form-label">Opcao relacionada</label>
                <select name="affect_option_values[]" class="form-select" data-role="affect-option-select">
                    <option value="">Pergunta inteira</option>
                </select>
            </div>
            <div>
                <label class="form-label">Peso</label>
                <input type="number" step="0.01" name="affect_weights[]" class="form-control" value="1">
            </div>
            <div>
                <label class="form-label">Impacto</label>
                <input type="text" name="affect_impact_types[]" class="form-control" value="sum" placeholder="sum">
            </div>
            <div class="dashboard-config-row-action">
                <button type="button" class="btn btn-outline-danger" data-action="remove-row">Remover</button>
            </div>
        </div>
    `;
    container.appendChild(row);
}

function syncOptionSelects(form) {
    const optionSelects = form.querySelectorAll('[data-role="affect-option-select"]');
    const options = getQuestionOptions(form);
    const isMultipleChoice = form.querySelector('select[name="question_type"]')?.value === 'multipla_escolha';

    optionSelects.forEach((select) => {
        const currentValue = select.value || select.dataset.selected || '';
        const values = ['<option value="">Pergunta inteira</option>'];

        options.forEach((option) => {
            values.push(`<option value="${escapeHtml(option.value)}">${escapeHtml(option.label)} (${escapeHtml(option.value)})</option>`);
        });

        select.innerHTML = values.join('');
        select.disabled = !isMultipleChoice;
        select.value = isMultipleChoice ? currentValue : '';
        delete select.dataset.selected;
    });
}

function getQuestionOptions(form) {
    const values = Array.from(form.querySelectorAll('input[name="option_values[]"]'));
    const labels = Array.from(form.querySelectorAll('input[name="option_labels[]"]'));

    return values.map((input, index) => ({
        value: input.value.trim(),
        label: labels[index]?.value.trim() || ''
    })).filter((option) => option.value !== '' && option.label !== '');
}

function buildMetricOptions(metrics) {
    const options = ['<option value="">Selecione</option>'];
    metrics.forEach((metric) => {
        options.push(
            `<option value="${escapeHtml(metric.id)}">${escapeHtml(metric.name)} (${escapeHtml(metric.metric_key)})</option>`
        );
    });
    return options.join('');
}

function parseJson(raw, fallback) {
    try {
        return raw ? JSON.parse(raw) : fallback;
    } catch (error) {
        return fallback;
    }
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
