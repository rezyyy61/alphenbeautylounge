<!-- resources/js/Pages/Admin/Appointments.vue -->
<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import { DateTime } from 'luxon'
import AppointmentModal from './AppointmentModal.vue'


const schedule = {
    maandag   : { start: '09:00', end: '17:00' },
    dinsdag   : { start: '09:00', end: '17:00' },
    woensdag  : { start: '09:00', end: '17:00' },
    donderdag : { start: '09:00', end: '17:00' },
    vrijdag   : { start: '09:00', end: '19:00' },
    zaterdag  : { start: '08:30', end: '16:00' },
}

const appointments = ref([])
async function loadAppointments () {
    const res = await axios.get('/appointments')
    appointments.value = res.data.items
}
onMounted(loadAppointments)

const days = ref([])
function generateNext30Days () {
    const today   = DateTime.local().startOf('day')
    const result  = []
    let offset    = 0

    while (result.length < 30) {
        const d = today.plus({ days: offset })
        if (d.weekday !== 7) {
            result.push({
                value: d.toISODate(),
                label: d.setLocale('nl').toLocaleString(DateTime.DATE_FULL)
            })
        }
        offset++
    }

    days.value = result
}
generateNext30Days()

function generateSlotsForDay (dayISO) {
    if (!dayISO) return []

    const dayIdx = new Date(dayISO).getDay()
    const dayKey = [
        'zondag', 'maandag', 'dinsdag', 'woensdag',
        'donderdag', 'vrijdag', 'zaterdag',
    ][dayIdx]

    const cfg = schedule[dayKey]
    if (!cfg) return []

    const result = []
    let [h, m]        = cfg.start.split(':').map(Number)
    const [EH, EM]    = cfg.end.split(':').map(Number)

    while (h < EH || (h === EH && m < EM)) {
        result.push(`${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`)
        m += 15
        if (m >= 60) {
            h++
            m = 0
        }
    }
    return result
}

function getOverlappingAppointment (day, time) {
    const current = DateTime.fromISO(`${day}T${time}`, { zone: 'Europe/Amsterdam' })
    return appointments.value.find(a => {
        if (!a.start_time || !a.end_time) return false
        const start = DateTime.fromISO(a.start_time, { zone: 'Europe/Amsterdam' })
        const end   = DateTime.fromISO(a.end_time,   { zone: 'Europe/Amsterdam' })
        return current >= start && current < end
    })
}
function isSlotAvailable (day, time) {
    return !getOverlappingAppointment(day, time)
}

const modalDay     = ref(null)
const modalVisible = ref(false)
function openModal (day) {
    modalDay.value   = day
    modalVisible.value = true
}

async function deleteAppointment (id) {
    const { isConfirmed } = await Swal.fire({
        title: 'Zeker weten?',
        text: 'Afspraak wordt verwijderd!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Verwijderen',
        cancelButtonText: 'Annuleren',
    })
    if (isConfirmed) {
        await axios.delete(`/appointments/${id}`)
        await loadAppointments()
        Swal.fire('Verwijderd!', '', 'success')
    }
}
</script>

<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-[#9e8356]">
            🗓️ Afspraakoverzicht (30 dagen)
        </h2>

        <!-- Overzicht dagen -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            <div
                v-for="day in days"
                :key="day.value"
                @click="openModal(day.value)"
                class="bg-white border border-[#e7dbc6] rounded-xl shadow p-4 cursor-pointer hover:bg-[#fdfaf5]"
            >
                <div class="text-[#9e8356] font-semibold">📅 {{ day.label }}</div>
                <div class="text-sm text-gray-500">
                    {{ appointments.filter(a => a.start_time.startsWith(day.value)).length }} afspraken
                </div>
            </div>
        </div>

        <!-- Modal -->
        <AppointmentModal
            :visible="modalVisible"
            :day="modalDay"
            :slots="generateSlotsForDay(modalDay)"
            :appointments="appointments"
            @close="modalVisible = false"
            @delete="deleteAppointment"
            @refresh="loadAppointments"
        />
    </div>
</template>
