document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.profile-link');
    const tabs = document.querySelectorAll('.tab-content');

    links.forEach((link) => {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            const tab = this.dataset.tab;
            if (!tab) {
                return;
            }

            links.forEach((item) => item.classList.remove('active'));
            this.classList.add('active');

            tabs.forEach((panel) => panel.classList.remove('active'));

            const target = document.getElementById(`tab-${tab}`);
            if (target) {
                target.classList.add('active');
            }

            localStorage.setItem('activeTab', tab);
        });
    });

    const savedTab = localStorage.getItem('activeTab');
    if (savedTab) {
        const activeLink = document.querySelector(`[data-tab="${savedTab}"]`);
        if (activeLink) {
            activeLink.click();
        }
    }

    let cropper;
    const input = document.getElementById('input-image');
    const image = document.getElementById('image-to-crop');
    const preview = document.getElementById('preview-image');

    if (input) {
        input.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (!file) {
                return;
            }

            const url = URL.createObjectURL(file);

            image.src = url;
            image.style.display = 'block';

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 0,
                dragMode: 'move',
                autoCropArea: 1,
                movable: true,
                zoomable: true,
                cropBoxMovable: false,
                cropBoxResizable: false,
                crop() {
                    if (!preview) {
                        return;
                    }

                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300
                    });

                    preview.src = canvas.toDataURL();
                }
            });
        });
    }

    async function loadTrail() {
        try {
            const response = await fetch(`${window.BASE_PATH}/api/trail`);
            if (!response.ok) {
                throw new Error('API offline');
            }

            const trail = await response.json();
            const normalizedTrail = Array.isArray(trail) ? trail : [];
            renderTrailFlow(normalizedTrail);
            updateStats(normalizedTrail);
        } catch (error) {
            console.error('Erro ao carregar trilha:', error);
            renderTrailFlow([]);
            updateStats([]);
        }
    }

    function renderTrailFlow(trail) {
        const container = document.getElementById('learningFlow');
        if (!container) {
            return;
        }

        container.innerHTML = '';

        trail.forEach((item, index) => {
            const step = document.createElement('div');
            step.className = 'flow-step';

            let statusClass = '';
            if (item.completed) {
                statusClass = 'completed';
            } else if (index > 0 && !trail[index - 1].completed) {
                statusClass = 'locked';
            }

            step.innerHTML = `
                <div class="flow-circle ${statusClass}" data-id="${item.id}">
                    ${index + 1}
                </div>
                <div class="flow-title">${item.title}</div>
                <div class="flow-desc">
                    ${item.description}<br>
                    ${item.duration}h
                </div>
            `;

            container.appendChild(step);

            if (index < trail.length - 1) {
                const line = document.createElement('div');
                line.className = 'flow-line';
                container.appendChild(line);
            }
        });

        bindTrailEvents(trail);
    }

    function bindTrailEvents(trail) {
        document.querySelectorAll('.flow-circle').forEach((circle) => {
            circle.addEventListener('click', function () {
                const id = this.dataset.id;
                const index = trail.findIndex((item) => String(item.id) === String(id));
                const item = trail[index];

                if (!item) {
                    return;
                }

                if (index > 0 && !trail[index - 1].completed) {
                    alert('Complete o anterior primeiro');
                    return;
                }

                item.completed = !item.completed;
                renderTrailFlow(trail);
                updateStats(trail);
            });
        });
    }

    function updateStats(trail) {
        const total = trail.length;
        const completed = trail.filter((item) => item.completed).length;
        const percent = total > 0 ? Math.round((completed / total) * 100) : 0;
        const time = trail
            .filter((item) => item.completed)
            .reduce((sum, item) => sum + Number(item.duration || 0), 0);

        const percentEl = document.getElementById('progressPercent');
        const timeEl = document.getElementById('totalTime');
        const countEl = document.getElementById('completedCount');

        if (percentEl) {
            percentEl.innerText = `${percent}%`;
        }
        if (timeEl) {
            timeEl.innerText = `${time}h`;
        }
        if (countEl) {
            countEl.innerText = String(completed);
        }
    }

    loadTrail();
});
