document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.profile-link')
    const tabs = document.querySelectorAll('.tab-content')

    links.forEach((link) => {
        link.addEventListener('click', function (event) {
            event.preventDefault()

            const tab = this.dataset.tab
            if (!tab) {
                return
            }

            links.forEach((item) => item.classList.remove('active'))
            this.classList.add('active')

            tabs.forEach((panel) => panel.classList.remove('active'))

            const target = document.getElementById(`tab-${tab}`)
            if (target) {
                target.classList.add('active')
            }

            localStorage.setItem('activeTab', tab)
        })
    })

    const initialTab = localStorage.getItem('activeTab')
    if (initialTab) {
        const activeLink = document.querySelector(`[data-tab="${initialTab}"]`)
        if (activeLink) {
            activeLink.click()
        }
    }

    let cropper
    const input = document.getElementById('input-image')
    const image = document.getElementById('image-to-crop')
    const preview = document.getElementById('preview-image')
    const previewFallback = document.getElementById('preview-fallback')

    if (input) {
        input.addEventListener('change', (event) => {
            const file = event.target.files[0]
            if (!file) {
                return
            }

            const url = URL.createObjectURL(file)

            image.src = url
            image.style.display = 'block'

            if (cropper) {
                cropper.destroy()
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
                        return
                    }

                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300
                    })

                    preview.src = canvas.toDataURL()
                    preview.style.display = 'block'
                    if (previewFallback) {
                        previewFallback.style.display = 'none'
                    }
                }
            })
        })
    }

})
