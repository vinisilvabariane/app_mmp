const USE_MOCK = true

export async function apiGet(url) {
    if (USE_MOCK) {
        return mockRouter(url)
    }

    const response = await fetch(url)
    return response.json()
}

// MOCK ROUTER (simula backend)
import { getMockAlunos, getMockAlunoById, getMockDashboard } from '../mocks/alunos.mock.js'

function mockRouter(url) {
    if (url === '/api/alunos') return getMockAlunos()

    if (url.startsWith('/api/alunos/')) {
        const id = url.split('/').pop()
        return getMockAlunoById(id)
    }

    if (url === '/api/dashboard') return getMockDashboard()
}