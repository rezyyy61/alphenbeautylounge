<template>
    <div v-if="visible" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl p-10 w-full max-w-5xl relative max-h-[90vh] overflow-y-auto">
            <button @click="handleClose" class="absolute top-5 right-6 text-gray-400 hover:text-red-400 text-2xl">✕</button>

            <h3 class="text-2xl font-bold text-[#9e8356] mb-6 text-center">Afspraak</h3>
            <p class="text-center text-lg font-semibold text-[#4a4137] mb-4">
                Je maakt een afspraak voor:
                <span class="text-[#9e8356]">{{ props.selectedService?.title ?? '---' }}</span>
            </p>

            <SelectDayTime
                v-if="!userDetailsStep"
                :selected-service="props.selectedService"
                @selected="handleSelected"
                @dayChanged="handleDayChanged"
            />

            <UserDetailsForm
                v-if="userDetailsStep"
                :selected-day="selectedDay"
                :selected-time="selectedTime"
                :selected-service="props.selectedService"
                @close="handleClose"
                @back="userDetailsStep = false"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import SelectDayTime from './SelectDayTime.vue'
import UserDetailsForm from './UserDetailsForm.vue'
import { fetchActiveBlockedPeriods } from "@/composables/notices.js"

const props = defineProps({
    visible: Boolean,
    selectedService: Object
})

const emit = defineEmits(['close'])

const selectedDay = ref('')
const selectedTime = ref('')
const userDetailsStep = ref(false)
const blockedPeriods = ref([])

function handleSelected(day, time) {
    selectedDay.value = day
    selectedTime.value = time
    userDetailsStep.value = true
}

function handleDayChanged(day) {
    selectedDay.value = day
}

watch(selectedDay, async (val) => {
    if (!val) return blockedPeriods.value = []

    try {
        const all = await fetchActiveBlockedPeriods()
        blockedPeriods.value = all.filter(p => isDateInRange(val, p.start_date, p.end_date))
    } catch (e) {
        blockedPeriods.value = []
    }
})

function isDateInRange(target, start, end = null) {
    const t = new Date(target).toISOString().split('T')[0]
    const s = new Date(start).toISOString().split('T')[0]
    const e = end ? new Date(end).toISOString().split('T')[0] : s
    return t >= s && t <= e
}

function resetState() {
    selectedDay.value = ''
    selectedTime.value = ''
    userDetailsStep.value = false
    blockedPeriods.value = []
}

function handleClose() {
    resetState()
    emit('close')
}
</script>
