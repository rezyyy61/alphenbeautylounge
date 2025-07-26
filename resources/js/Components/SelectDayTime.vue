<template>
    <div class="space-y-6">
        <div class="space-y-3">
            <p class="font-semibold text-[#9e8356] text-lg">Kies een dag:</p>

            <div class="sm:hidden overflow-x-auto flex gap-3 pb-2 scrollbar-hide">
                <button
                    v-for="(day, index) in next30Days"
                    :key="index"
                    type="button"
                    @click="selectedDay = day.value"
                    :class="[
                        'flex-shrink-0 rounded-xl border px-4 py-3 text-sm font-medium transition shadow-sm',
                        selectedDay === day.value
                          ? 'bg-[#9e8356] text-white border-[#9e8356]'
                          : 'bg-white text-[#4a4137] border-[#e7dbc6] hover:bg-[#f9f5ef]'
                    ]"
                >
                    {{ day.label }}
                </button>
            </div>

            <div class="hidden sm:grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-64 overflow-y-auto pr-1 scrollbar-hide">
                <button
                    v-for="(day, index) in next30Days"
                    :key="index"
                    type="button"
                    @click="selectedDay = day.value"
                    :class="[
                        'rounded-xl border px-4 py-3 text-sm font-medium transition shadow-sm',
                        selectedDay === day.value
                          ? 'bg-[#9e8356] text-white border-[#9e8356]'
                          : 'bg-white text-[#4a4137] border-[#e7dbc6] hover:bg-[#f9f5ef]'
                    ]"
                >
                    {{ day.label }}
                </button>
            </div>
        </div>

        <NoticeList
            v-if="isDayFullyBlocked"
            :day="selectedDay"
            class="mb-4"
        />


        <div v-if="availableTimes.length && !isDayFullyBlocked" class="space-y-3">
            <p class="font-semibold text-[#9e8356] text-lg">Beschikbare tijden voor {{ dayLabel }}</p>
            <div class="flex flex-wrap gap-2">
                <button
                    v-for="time in availableTimes"
                    :key="time"
                    @click="() => handleTimeClick(time)"
                    :disabled="takenTimes.includes(time) || blockedTimes.includes(time)"
                    :class="[
  'px-4 py-2 rounded-xl border text-sm transition shadow-sm min-w-[90px] text-center',
  selectedTime === time
    ? 'bg-[#9e8356] text-white border-[#9e8356]'
    : takenTimes.includes(time) || blockedTimes.includes(time)
      ? 'bg-gray-200 text-gray-500 cursor-not-allowed border-gray-300'
      : 'bg-white text-[#4a4137] border-[#e7dbc6] hover:bg-[#fdf9f3]'
]"

                >
                    {{ time }}
                </button>
            </div>
        </div>

        <div v-if="warning" class="text-sm text-red-500 font-medium">{{ warning }}</div>

        <div v-if="selectedDay && selectedTime" class="border border-[#e7dbc6] bg-[#f9f5ef] text-[#4a4137] rounded-xl px-5 py-4 text-center text-base shadow-inner">
            Gekozen dag: {{ dayLabel }}, Tijd: {{ selectedTime }}
        </div>

        <button
            v-if="selectedDay && selectedTime"
            type="button"
            @click="emit('selected', selectedDay, selectedTime)"
            class="w-full bg-[#9e8356] text-white py-3 rounded-xl hover:bg-[#bfa477] transition text-lg font-semibold"
        >
            Volgende
        </button>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import axios from 'axios'
import { generateSlots, isTimeValid } from '@/utils/scheduler.js'
import {
    toLocalISO,
    formatDate,
    parseLocalDate,
    dayOfWeekToKey
} from '@/utils/date'
import { fetchActiveBlockedPeriods } from '@/composables/notices.js'
import NoticeList from "@/Pages/Public/NoticeList.vue";

const warning = ref('')

const props = defineProps({
    selectedService: Object
})

const emit = defineEmits(['selected'])

const schedule = {
    maandag: { start: '15:30', end: '18:00' },
    dinsdag: { start: '15:30', end: '18:00' },
    woensdag: { start: '15:30', end: '18:00' },
    donderdag: { start: '09:00', end: '18:00' },
    vrijdag: { start: '09:00', end: '19:00' },
    zaterdag: { start: '08:30', end: '16:00' }
}

const selectedDay = ref('')
const selectedTime = ref('')
const takenTimes = ref([])
const blockedPeriods = ref([])

const next30Days = computed(() => {
    const arr = []
    const base = new Date()
    base.setHours(0, 0, 0, 0)
    for (let i = 0; i < 40; i++) {
        const tmp = new Date(base.getTime())
        tmp.setDate(base.getDate() + i)
        if (tmp.getDay() !== 0) arr.push({ value: toLocalISO(tmp), label: formatDate(tmp) })
        if (arr.length >= 30) break
    }
    return arr
})

const isDayFullyBlocked = computed(() => {
    if (!selectedDay.value) return false
    return blockedPeriods.value.some(b => {
        const s = b.start_date
        const e = b.end_date || s
        const t = selectedDay.value
        return t >= s && t <= e && !b.start_time && !b.end_time
    })
})

function getBlockedTimesForDay(day) {
    return blockedPeriods.value
        .filter(b => {
            const s = b.start_date
            const e = b.end_date || s
            return day >= s && day <= e && b.start_time && b.end_time
        })
        .flatMap(b => generateSlots(b.start_time, b.end_time))
}

const availableTimes = computed(() => {
    if (!selectedDay.value || isDayFullyBlocked.value) return []

    const d = parseLocalDate(selectedDay.value)
    const key = dayOfWeekToKey(d.getDay())
    if (!key || !schedule[key]) return []

    let slots = generateSlots(schedule[key].start, schedule[key].end)

    const isToday = d.toDateString() === new Date().toDateString()
    if (isToday) {
        const now = new Date()
        const hh = now.getHours()
        const mm = now.getMinutes()
        const baseMin = mm < 30 ? '00' : '30'
        const nowStr = `${String(hh).padStart(2, '0')}:${baseMin}`
        slots = slots.filter(t => t >= nowStr)
    }

    return slots
})

const dayLabel = computed(() => {
    return next30Days.value.find(d => d.value === selectedDay.value)?.label ?? ''
})

async function fetchTaken(day) {
    try {
        const res = await axios.get(`/api/appointments/taken/${day}`)
        takenTimes.value = res.data
    } catch {
        takenTimes.value = []
    }
}

watch(selectedDay, async (val) => {
    if (val && props.selectedService?.id) {
        await fetchTaken(val)
        blockedPeriods.value = await fetchActiveBlockedPeriods()
    }
})

onMounted(() => {
    const def = next30Days.value[0]?.value
    if (def) selectedDay.value = def
})

const blockedTimes = computed(() => {
    return getBlockedTimesForDay(selectedDay.value)
})


function handleTimeClick(time) {
    if (takenTimes.value.includes(time)) return

    const isValid = isTimeValid(
        time,
        selectedDay.value,
        props.selectedService.duration,
        schedule,
        takenTimes.value,
        parseLocalDate,
        dayOfWeekToKey,
        generateSlots
    )

    if (isValid) {
        selectedTime.value = time
        warning.value = ''
    } else {
        warning.value = `Er is onvoldoende tijd voor deze behandeling van ${props.selectedService.duration} minuten. Kies een ander beschikbaar tijdstip.`
    }
}
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
