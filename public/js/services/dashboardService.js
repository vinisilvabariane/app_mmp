import { apiGet } from './api.js'

export function getDashboard() {
    return apiGet('/api/dashboard')
}