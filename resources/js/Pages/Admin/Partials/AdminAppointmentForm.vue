<!-- AdminAppointmentForm.vue -->
<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

const props = defineProps({
    day: String,
    time: String
})

const emit = defineEmits(['close', 'created', 'saved'])

const name = ref('')
const phone = ref('')
const email = ref('')
const note = ref('')
const service_id = ref(null)
const services = ref([])
const isSubmitting = ref(false)

onMounted(async () => {
    const res = await axios.get('/services')
    services.value = res.data.filter(s => s.parent_id !== null)
})

const submit = async () => {
    try {
        if (!name.value || !phone.value || !email.value || !service_id.value) {
            Swal.fire('Vul alle verplichte velden in.', '', 'warning')
            return
        }
        isSubmitting.value = true
        await axios.post('/api/appointments', {
            name: name.value,
            phone: phone.value,
            email: email.value,
            notes: note.value,
            service_id: service_id.value,
            day: props.day,
            time: props.time
        })

        Swal.fire('Succesvol opgeslagen!', '', 'success')
        emit('saved')
        emit('close')
    } catch (err) {
        Swal.fire('Fout', err.response?.data?.message || 'Mislukt', 'error')
    } finally {
        isSubmitting.value = false
    }
}
</script>

<template>
    <teleport to="body">
    <div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
        <div v-if="isSubmitting" class="absolute inset-0 bg-white/70 z-50 flex items-center justify-center rounded-xl">
            <div class="flex flex-col items-center">
                <svg class="animate-spin h-8 w-8 text-[#9e8356]" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                <span class="mt-3 text-[#4a4137] font-medium">Even geduld...</span>
            </div>
        </div>

        <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="text-xl font-bold text-[#9e8356]">➕ Nieuwe afspraak</h2>
                <button @click="$emit('close')" class="text-gray-400 hover:text-red-500 text-2xl">×</button>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">👤 Naam</label>
                    <input v-model="name" class="w-full border px-3 py-2 rounded" placeholder="Naam" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">📞 Telefoon</label>
                    <input v-model="phone" class="w-full border px-3 py-2 rounded" placeholder="Telefoon" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">📧 Email</label>
                    <input v-model="email" type="email" class="w-full border px-3 py-2 rounded" placeholder="Email" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">💇 Kies service</label>
                    <select v-model="service_id" class="w-full border px-3 py-2 rounded">
                        <option disabled value="">Selecteer...</option>
                        <option v-for="s in services" :key="s.id" :value="s.id">{{ s.title }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">📝 Notitie</label>
                    <textarea v-model="note" class="w-full border px-3 py-2 rounded" placeholder="Optioneel..."></textarea>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t">
                <button @click="submit" class="bg-[#9e8356] hover:bg-[#8b724d] text-white px-4 py-2 rounded shadow">
                    Opslaan
                </button>
            </div>
        </div>
    </div>
    </teleport>
</template>
