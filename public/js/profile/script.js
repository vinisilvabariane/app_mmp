document.addEventListener('DOMContentLoaded', () => {

    async function loadUser() {
        try {
            const response = await fetch(`${window.BASE_PATH}/public/data/user.json`);
            const user = await response.json();

            const initialsEl = document.getElementById("avatar-initials");
            const imgEl = document.getElementById("avatar-img");

            if (!initialsEl || !imgEl) return;

            function getInitials(name = "") {
                const parts = name.trim().split(" ").filter(Boolean);
                if (parts.length === 0) return "?";
                if (parts.length === 1) return parts[0][0].toUpperCase();
                return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
            }

            if (user.avatar) {
                imgEl.src = user.avatar;
                imgEl.style.display = "block";
                initialsEl.style.display = "none";
            } else {
                initialsEl.textContent = getInitials(user.name);
            }

        } catch (error) {
            console.error("Erro ao carregar usuário:", error);
        }
    }

    loadUser();

    const links = document.querySelectorAll('.profile-link');
    const tabs = document.querySelectorAll('.tab-content');

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const tab = this.dataset.tab;
            if (!tab) return;

            links.forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            tabs.forEach(t => t.classList.remove('active'));

            const target = document.getElementById('tab-' + tab);
            if (target) target.classList.add('active');

            localStorage.setItem('activeTab', tab);
        });
    });

    const savedTab = localStorage.getItem('activeTab');
    if (savedTab) {
        const el = document.querySelector(`[data-tab="${savedTab}"]`);
        if (el) el.click();
    }

    let cropper;
    const input = document.getElementById('input-image');
    const image = document.getElementById('image-to-crop');
    const preview = document.getElementById('preview-image');

    if (input) {
        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const url = URL.createObjectURL(file);

            image.src = url;
            image.style.display = 'block';

            if (cropper) cropper.destroy();

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
    let trail = [];

    try {
        const response = await fetch(`${window.BASE_PATH}/api/trail`);

        if (!response.ok) throw new Error("API offline");

        trail = await response.json();

    } catch (e) {
        console.warn("Usando MOCK...");

        trail = [
            {
                id: 1,
                title: "Fundamentos de HTML",
                description: "Aprenda a estrutura de páginas web",
                duration: 2,
                completed: true
            },
            {
                id: 2,
                title: "CSS Responsivo",
                description: "Layouts modernos e responsivos",
                duration: 3,
                completed: false
            },
            {
                id: 3,
                title: "JavaScript Essencial",
                description: "Lógica e interatividade",
                duration: 4,
                completed: false
            },
            {
                id: 4,
                title: "Projeto Prático",
                description: "Aplicação completa",
                duration: 5,
                completed: false
            }
        ];
    }

    renderTrailFlow(trail);
    updateStats(trail);
}

    function renderTrailFlow(trail) {
        const container = document.getElementById("learningFlow");
        if (!container) return;

        container.innerHTML = "";

        trail.forEach((item, index) => {

            const step = document.createElement("div");
            step.className = "flow-step";

            let statusClass = "";
            if (item.completed) statusClass = "completed";
            else if (index > 0 && !trail[index - 1].completed) statusClass = "locked";

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
                const line = document.createElement("div");
                line.className = "flow-line";
                container.appendChild(line);
            }
        });

        bindTrailEvents(trail);
    }

    function bindTrailEvents(trail) {
        document.querySelectorAll('.flow-circle').forEach(circle => {
            circle.addEventListener('click', function () {

                const id = this.dataset.id;
                const index = trail.findIndex(t => t.id == id);
                const item = trail[index];

                if (!item) return;

                // bloqueio
                if (index > 0 && !trail[index - 1].completed) {
                    alert("Complete o anterior primeiro");
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
        const completed = trail.filter(t => t.completed).length;

        const percent = Math.round((completed / total) * 100);
        const time = trail
            .filter(t => t.completed)
            .reduce((sum, t) => sum + t.duration, 0);

        const percentEl = document.getElementById("progressPercent");
        const timeEl = document.getElementById("totalTime");
        const countEl = document.getElementById("completedCount");

        if (percentEl) percentEl.innerText = percent + "%";
        if (timeEl) timeEl.innerText = time + "h";
        if (countEl) countEl.innerText = completed;
    }

    loadTrail();

});

