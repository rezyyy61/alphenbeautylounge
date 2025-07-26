<template>
    <div class="space-y-6">
        <div class="space-y-3">
            <p class="font-semibold text-[#9e8356] text-lg">Kies een dag:</p>

            <div class="sm:hidden overflow-x-auto flex gap-3 pb-2">
                <button
                    v-for="day in next30Days"
                    :key="day.value"
                    @click="selectedDay = day.value"
                    :class="[
            'flex-shrink-0 rounded-xl border px-4 py-3 text-sm font-medium',
            selectedDay === day.value
              ? 'bg-[#9e8356] text-white'
              : 'bg-white text-[#4a4137]'
          ]"
                >
                    {{ day.label }}
                </button>
            </div>

            <div class="hidden sm:grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <button
                    v-for="day in next30Days"
                    :key="day.value"
                    @click="selectedDay = day.value"
                    :class="[
            'rounded-xl border px-4 py-3 text-sm font-medium',
            selectedDay === day.value
              ? 'bg-[#9e8356] text-white'
              : 'bg-white text-[#4a4137]'
          ]"
                >
                    {{ day.label }}
                </button>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, computed } from 'vue'

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

function toLocalISO(d) {
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
}

function formatDate(d) {
    const days = ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag']
    return `${days[d.getDay()]} ${String(d.getDate()).padStart(2, '0')}-${String(d.getMonth() + 1).padStart(2, '0')}-${d.getFullYear()}`
}

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

const dayLabel = computed(() => next30Days.value.find(d => d.value === selectedDay.value)?.label ?? '')
</script>

