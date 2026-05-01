import { apiGet } from './api.js'

export function getAlunos() {
    return apiGet('/api/alunos')
}

export function getAlunoById(id) {
    return apiGet(`/api/alunos/${id}`)
}