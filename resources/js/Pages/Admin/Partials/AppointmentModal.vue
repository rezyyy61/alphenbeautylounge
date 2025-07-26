
<script setup>
import {defineProps, defineEmits, ref, computed, onUnmounted, watch} from 'vue'
import AdminAppointmentForm from '../Partials/AdminAppointmentForm.vue'

const props = defineProps({
    visible: Boolean,
    day: String,
    slots: Array,
    appointments: Array
})
const emit = defineEmits(['close', 'delete', 'refresh'])

const dayAppts = computed(() => props.appointments.filter(a => a.start_time.startsWith(props.day)))
const hhmm     = dateStr => dateStr.slice(11, 16)

function apptAt(slot) {
    return dayAppts.value.find(a => hhmm(a.start_time) === slot) || null
}
function isFree(slot) {
    return !dayAppts.value.some(a => slot >= hhmm(a.start_time) && slot < hhmm(a.end_time))
}


const renderedSlots = computed(() => {
    const ids = new Set()
    return props.slots.filter(s => {
        const a = apptAt(s)
        if (a && !ids.has(a.id)) { ids.add(a.id); return true }
        return isFree(s)
    })
})


const formSlot   = ref(null)
const showForm   = ref(false)
const expanded   = ref(null)
const openForm   = s => { formSlot.value = s; showForm.value = true }
const closeForm  = () => { showForm.value = false; formSlot.value = null }
const toggleCard = s => expanded.value = expanded.value === s ? null : s

const pollingInterval = ref(null)

watch(() => props.visible, (visible) => {
    if (visible) {
        startPolling()
    } else {
        stopPolling()
    }
})

function startPolling() {
    if (pollingInterval.value) return
    pollingInterval.value = setInterval(() => {
        emit('refresh')
    }, 7000)
}

function stopPolling() {
    if (pollingInterval.value) {
        clearInterval(pollingInterval.value)
        pollingInterval.value = null
    }
}

onUnmounted(() => {
    stopPolling()
})

</script>

<template>
    <transition name="fade">
        <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white w-full max-w-6xl mx-auto rounded-2xl shadow-2xl p-8 overflow-y-auto max-h-[90vh] relative">
                <!-- Header -->
                <div class="flex justify-between items-center bg-gray-50 rounded-t-xl px-6 py-4 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">📅 Tijdslots voor {{ day }}</h2>
                    <button @click="emit('close')" class="text-gray-600 hover:text-gray-900 text-3xl">×</button>
                </div>

                <!-- Slot cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-6">
                    <div v-for="slot in renderedSlots" :key="slot"
                         class="relative flex flex-col p-4 border rounded-lg shadow-sm hover:shadow-lg transition overflow-hidden cursor-pointer"
                         :class="isFree(slot) ? 'bg-green-100 border-green-300' : 'bg-red-100 border-red-300'"
                         @click="isFree(slot) ? openForm(slot) : toggleCard(slot)">

                        <!-- Top row -->
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-md text-gray-700 truncate">
                                ⏰ {{ slot }}<span v-if="!isFree(slot)"> - {{ hhmm(apptAt(slot).end_time) }}</span>
                            </h3>
                            <button v-if="isFree(slot)" @click.stop="openForm(slot)" class="text-indigo-600 hover:text-indigo-800 text-xl">➕</button>
                            <button v-else @click.stop="emit('delete', apptAt(slot).id)" class="text-red-600 text-sm hover:underline">🗑</button>
                        </div>

                        <!-- Inline form -->
                        <div class="mt-4">
                            <AdminAppointmentForm
                                v-if="isFree(slot) && showForm && formSlot === slot"
                                :day="day"
                                :time="slot"
                                @close="closeForm"
                                @saved="() => { closeForm(); emit('refresh') }"
                            />
                            <!-- Appointment details -->
                            <div v-else-if="!isFree(slot)">
                                <div v-if="expanded===slot" class="space-y-1 text-sm text-gray-700 mt-2">
                                    <p><strong>👤 Naam:</strong> {{ apptAt(slot).name }}</p>
                                    <p v-if="apptAt(slot).phone"><strong>📞 Telefoon:</strong> {{ apptAt(slot).phone }}</p>
                                    <p v-if="apptAt(slot).email"><strong>📧 Email:</strong> {{ apptAt(slot).email }}</p>
                                    <p v-if="apptAt(slot).service?.title"><strong>💇 Dienst:</strong> {{ apptAt(slot).service.title }}</p>
                                    <p v-if="apptAt(slot).note" class="italic text-gray-600"><strong>📝 Notitie:</strong> {{ apptAt(slot).note }}</p>
                                </div>
                                <div v-else class="text-xs text-gray-700 mt-2">
                                    <p class="truncate"><strong>👤</strong> {{ apptAt(slot).name }}</p>
                                    <p v-if="apptAt(slot).service?.title" class="truncate"><strong>💇</strong> {{ apptAt(slot).service.title }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
