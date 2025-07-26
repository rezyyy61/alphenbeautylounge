<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import Papa from 'papaparse'
import { saveAs } from 'file-saver'

const props = defineProps({
    userId: Number,
    visible: Boolean,
})

const emit = defineEmits(['close'])

const user = ref(null)
const appointments = ref([])
const loading = ref(false)
const totalSpent = ref(0)

watch(() => props.userId, async (id) => {
    if (!id || !props.visible) return

    loading.value = true

    try {
        const res = await axios.get(`/users/${id}`)
        user.value = res.data.user
        appointments.value = res.data.appointments
        totalSpent.value = res.data.total_spent
    } catch (err) {
        console.error('❌ Fout bij ophalen gebruiker details:', err)
    } finally {
        loading.value = false
    }
}, { immediate: true })

const exportAppointmentsCsv = () => {
    if (!appointments.value.length) return

    const csvData = appointments.value.map(a => ({
        Datum: new Date(a.start_time).toLocaleDateString('nl-NL'),
        Tijd: new Date(a.start_time).toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit' }),
        Dienst: a.service?.title || '—',
        Prijs: a.service?.price ? `€${Number(a.service.price).toFixed(2)}` : '—',
        Notitie: a.note || '',
    }))

    const csv = Papa.unparse(csvData)
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' })
    const name = user.value?.name?.replace(/\s+/g, '-').toLowerCase() || 'klant'
    saveAs(blob, `afspraken-${name}-${new Date().toISOString().slice(0, 10)}.csv`)
}
</script>

<template>
    <transition name="fade">
        <div v-if="visible" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
            <div class="bg-white w-full max-w-3xl rounded-xl shadow-2xl p-6 relative max-h-[90vh] overflow-y-auto">

                <button @click="emit('close')" class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-2xl">×</button>

                <div v-if="loading" class="text-center text-gray-400 p-10">⏳ Laden...</div>

                <div v-else>
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-xl font-bold text-[#9e8356]">👤 {{ user.name }}</h2>
                        <button
                            @click="exportAppointmentsCsv"
                            class="text-sm bg-[#9e8356] hover:bg-[#bfa477] text-white px-3 py-2 rounded shadow m-4"
                        >
                            ⬇️ Export CSV
                        </button>
                    </div>

                    <p class="text-gray-600 mb-4">
                        📧 {{ user.email }}<br />
                        📞 {{ user.phone || '—' }}
                    </p>

                    <h3 class="font-semibold mb-2 text-[#4a4137]">🗓️ Afspraken ({{ appointments.length }})</h3>

                    <ul class="divide-y border border-[#e7dbc6] rounded-lg mb-4 max-h-[300px] overflow-y-auto pr-2">
                        <li v-for="appt in appointments" :key="appt.id" class="p-3 text-sm space-y-1">
                            <div class="flex justify-between font-medium text-[#4a4137]">
                                <span>{{ appt.service?.title || 'Onbekend' }}</span>
                                <span>{{ new Date(appt.start_time).toLocaleString('nl-NL') }}</span>
                            </div>
                            <div class="text-gray-600">
                                💰 {{ appt.service?.price ? `€${Number(appt.service.price).toFixed(2)}` : 'Prijs op aanvraag' }}
                                <span v-if="appt.note" class="ml-2 italic text-gray-500">📝 {{ appt.note }}</span>
                            </div>
                        </li>
                    </ul>

                    <div class="text-right font-semibold text-[#9e8356]">
                        💵 Totale uitgave: €{{ Number(totalSpent).toFixed(2) }}
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
