import axios from 'axios'

const apiPrefix = '/api/blocked-periods'

export async function fetchBlockedPeriods() {
    try {
        const res = await axios.get(apiPrefix)
        return Array.isArray(res.data.data) ? res.data.data : []
    } catch (error) {
        console.error('❌ Fout bij ophalen van alle geblokkeerde periodes:', error)
        return []
    }
}

export async function fetchActiveBlockedPeriods() {
    try {
        const res = await axios.get(`${apiPrefix}/active`)
        return Array.isArray(res.data) ? res.data : []
    } catch (error) {
        console.error('❌ Fout bij ophalen van actieve geblokkeerde periodes:', error)
        return []
    }
}

export async function createBlockedPeriod(payload) {
    try {
        const res = await axios.post(apiPrefix, payload)
        return res.data
    } catch (error) {
        console.error('❌ Fout bij aanmaken van geblokkeerde periode:', error)
        throw error.response?.data || error
    }
}

export async function updateBlockedPeriod(id, payload) {
    try {
        const res = await axios.put(`${apiPrefix}/${id}`, payload)
        return res.data
    } catch (error) {
        console.error(`❌ Fout bij updaten van geblokkeerde periode ID ${id}:`, error)
        throw error.response?.data || error
    }
}

export async function deleteBlockedPeriod(id) {
    try {
        const res = await axios.delete(`${apiPrefix}/${id}`)
        return res.data
    } catch (error) {
        console.error(`❌ Fout bij verwijderen van geblokkeerde periode ID ${id}:`, error)
        throw error.response?.data || error
    }
}
