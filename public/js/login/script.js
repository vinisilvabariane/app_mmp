document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');
    const submitButton = form?.querySelector('button[type="submit"]');
    const submitText = submitButton?.querySelector('.login-submit-text');

    if (!form || !submitButton || !submitText) {
        return;
    }

    const setLoading = (loading) => {
        submitButton.disabled = loading;
        submitButton.classList.toggle('is-loading', loading);
        submitText.textContent = loading ? 'Entrando...' : submitButton.dataset.idleLabel || 'Entrar';
    };

    const showError = (message) => {
        errorMessage.textContent = message;
        errorMessage.classList.remove('d-none');
    };

    const hideError = () => {
        errorMessage.textContent = '';
        errorMessage.classList.add('d-none');
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideError();
        setLoading(true);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new FormData(form),
            });

            const payload = await response.json();

            if (!response.ok || !payload.ok) {
                const message = payload.message || 'Nao foi possivel autenticar.';
                showError(message);

                if (window.toastr) {
                    window.toastr.error(message);
                }

                return;
            }

            if (window.toastr) {
                window.toastr.success('Login realizado com sucesso.');
            }

            window.location.href = payload.redirect || '/home';
        } catch (error) {
            const message = 'Falha de comunicacao ao autenticar.';
            showError(message);

            if (window.toastr) {
                window.toastr.error(message);
            }
        } finally {
            setLoading(false);
        }
    });
});
