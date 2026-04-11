document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');
    const submitButton = form?.querySelector('button[type="submit"]');
    const submitText = submitButton?.querySelector('.login-submit-text');
    const resetToggleButton = document.getElementById('toggle-reset-password');
    const registerToggleButton = document.getElementById('toggle-register-user');
    const resetPanel = document.getElementById('reset-password-panel');
    const registerPanel = document.getElementById('register-user-panel');
    const resetEmail = document.getElementById('reset-email');
    const resetButton = document.getElementById('request-password-reset');
    const registerButton = document.getElementById('register-user-button');
    const registerFullName = document.getElementById('register-full-name');
    const registerEmail = document.getElementById('register-email');
    const registerPassword = document.getElementById('register-password');
    const registerPasswordConfirm = document.getElementById('register-password-confirm');

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

    const setResetLoading = (loading) => {
        if (!resetButton) {
            return;
        }

        resetButton.disabled = loading;
        resetButton.textContent = loading ? 'Enviando...' : 'Enviar senha temporaria';
    };

    const setRegisterLoading = (loading) => {
        if (!registerButton) {
            return;
        }

        registerButton.disabled = loading;
        registerButton.textContent = loading ? 'Criando...' : 'Criar conta';
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

    resetToggleButton?.addEventListener('click', () => {
        resetPanel?.classList.toggle('d-none');
        registerPanel?.classList.add('d-none');
        resetEmail?.focus();
    });

    registerToggleButton?.addEventListener('click', () => {
        registerPanel?.classList.toggle('d-none');
        resetPanel?.classList.add('d-none');
        registerEmail?.focus();
    });

    resetButton?.addEventListener('click', async () => {
        const email = String(resetEmail?.value || '').trim();

        if (email === '') {
            const message = 'Informe o email para resetar a senha.';
            showError(message);

            if (window.toastr) {
                window.toastr.error(message);
            }

            return;
        }

        hideError();
        setResetLoading(true);

        try {
            const response = await fetch(`${form.dataset.basePath || ''}/login/request-password-reset`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new URLSearchParams({ email }).toString(),
            });

            const payload = await response.json();

            if (!response.ok || !payload.ok) {
                const message = payload.message || 'Nao foi possivel resetar a senha.';
                showError(message);

                if (window.toastr) {
                    window.toastr.error(message);
                }

                return;
            }

            if (window.toastr) {
                window.toastr.success(payload.message || 'Senha temporaria enviada.');
            }

            resetEmail.value = '';
            resetPanel?.classList.add('d-none');
        } catch (error) {
            const message = 'Falha de comunicacao ao solicitar reset.';
            showError(message);

            if (window.toastr) {
                window.toastr.error(message);
            }
        } finally {
            setResetLoading(false);
        }
    });

    registerButton?.addEventListener('click', async () => {
        const payload = {
            register_full_name: String(registerFullName?.value || '').trim(),
            register_email: String(registerEmail?.value || '').trim(),
            register_password: String(registerPassword?.value || ''),
            register_password_confirm: String(registerPasswordConfirm?.value || ''),
        };

        hideError();
        setRegisterLoading(true);

        try {
            const response = await fetch(`${form.dataset.basePath || ''}/login/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new URLSearchParams(payload).toString(),
            });

            const result = await response.json();

            if (!response.ok || !result.ok) {
                const message = result.message || 'Nao foi possivel criar a conta.';
                showError(message);

                if (window.toastr) {
                    window.toastr.error(message);
                }

                return;
            }

            if (window.toastr) {
                window.toastr.success(result.message || 'Conta criada com sucesso.');
            }

            const loginEmailField = form.querySelector('#email');
            const loginPasswordField = form.querySelector('#password');
            if (loginEmailField && registerEmail) loginEmailField.value = registerEmail.value;
            if (loginPasswordField && registerPassword) loginPasswordField.value = registerPassword.value;

            if (registerFullName) registerFullName.value = '';
            if (registerEmail) registerEmail.value = '';
            if (registerPassword) registerPassword.value = '';
            if (registerPasswordConfirm) registerPasswordConfirm.value = '';
            registerPanel?.classList.add('d-none');
        } catch (error) {
            const message = 'Falha de comunicacao ao criar a conta.';
            showError(message);

            if (window.toastr) {
                window.toastr.error(message);
            }
        } finally {
            setRegisterLoading(false);
        }
    });
});
