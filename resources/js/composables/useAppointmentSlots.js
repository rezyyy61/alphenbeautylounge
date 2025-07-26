// useAppointmentSlots.js
import { ref, computed } from 'vue'
import { generateSlots } from '@/utils/scheduler.js'
import { parseLocalDate, dayOfWeekToKey } from '@/utils/date'
import { fetchActiveBlockedPeriods } from '@/composables/notices.js'
import axios from 'axios'

export function useAppointmentSlots(schedule) {
    const selectedDay = ref('')
    const takenTimes = ref([])
    const blockedPeriods = ref([])

    const blockedTimes = computed(() => {
        return blockedPeriods.value
            .filter(b => {
                const s = b.start_date
                const e = b.end_date || s
                return selectedDay.value >= s && selectedDay.value <= e && b.start_time && b.end_time
            })
            .flatMap(b => generateSlots(b.start_time, b.end_time))
    })

    const isDayFullyBlocked = computed(() => {
        return blockedPeriods.value.some(b => {
            const s = b.start_date
            const e = b.end_date || s
            return selectedDay.value >= s && selectedDay.value <= e && !b.start_time && !b.end_time
        })
    })

    async function fetchAll(day, serviceId) {
        if (!day || !serviceId) return
        selectedDay.value = day
        try {
            const [taken, blocks] = await Promise.all([
                axios.get(`/api/appointments/taken/${day}`).then(r => r.data),
                fetchActiveBlockedPeriods()
            ])
            takenTimes.value = taken
            blockedPeriods.value = blocks
        } catch {
            takenTimes.value = []
            blockedPeriods.value = []
        }
    }

    return {
        selectedDay,
        takenTimes,
        blockedPeriods,
        blockedTimes,
        isDayFullyBlocked,
        fetchAll
    }
}
