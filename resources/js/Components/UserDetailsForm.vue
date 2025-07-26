<template>
    <form @submit.prevent="confirmModalVisible = true" class="space-y-6 mt-6">
        <div class="border border-[#e7dbc6] bg-[#f9f5ef] text-[#4a4137] rounded-xl px-5 py-4 text-base shadow-inner space-y-2">
            <p><strong>Datum:</strong> {{ dayLabel }}</p>
            <p><strong>Tijd:</strong> {{ selectedTime }}</p>
        </div>
        <div class="flex justify-start">
            <button type="button" @click="emit('back')" class="text-[#9e8356] text-m font-semibold underline hover:text-[#bfa477] transition">
                ← Terug om dag of tijd aan te passen
            </button>
        </div>

        <input v-model="name" type="text" placeholder="Naam" class="w-full border px-5 py-3 rounded-xl text-lg" required />
        <input v-model="phone" type="tel" pattern="[0-9]{6,11}" maxlength="11" placeholder="Bijv. 0612345678" class="w-full border px-5 py-3 rounded-xl text-lg" required />
        <input v-model="email" type="email" placeholder="Email" class="w-full border px-5 py-3 rounded-xl text-lg" required />
        <textarea v-model="notes" placeholder="Extra informatie" class="w-full border px-5 py-3 rounded-xl min-h-[100px] resize-y text-lg" />

        <button type="submit" class="w-full bg-[#9e8356] text-white py-3 rounded-xl hover:bg-[#bfa477] transition text-lg font-semibold">
            Controleren
        </button>

        <div v-if="confirmModalVisible" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-6">
            <div class="relative bg-white/90 rounded-3xl shadow-2xl max-w-3xl w-full p-10 text-[#4a4137]">
                <div class="w-16 h-16 mx-auto mb-6 bg-[#9e8356] text-white flex items-center justify-center rounded-full text-3xl shadow-lg">✓</div>
                <h2 class="text-2xl md:text-3xl font-serif italic text-[#9e8356] text-center mb-4">Bevestig je afspraak</h2>
                <p class="text-center text-base md:text-lg text-gray-700 mb-8">Controleer jouw gegevens</p>
                <div class="grid md:grid-cols-2 gap-6 text-base md:text-lg bg-white rounded-2xl p-6 shadow-inner border border-[#e0d4c2] mb-8">
                    <p><strong>Naam:</strong> {{ name }}</p>
                    <p><strong>Telefoon:</strong> {{ phone }}</p>
                    <p><strong>Email:</strong> {{ email }}</p>
                    <p><strong>Dag:</strong> {{ dayLabel }}</p>
                    <p><strong>Tijd:</strong> {{ selectedTime }}</p>
                    <p><strong>Gekozen service:</strong> {{ selectedService?.title ?? '---' }}</p>
                    <p v-if="notes"><strong>Notitie:</strong> {{ notes }}</p>
                </div>
                <div class="flex flex-col sm:flex-row justify-center gap-6">
                    <button @click="confirmModalVisible = false" class="px-8 py-3 rounded-full border border-gray-400 hover:bg-gray-100 text-base transition">Annuleren</button>
                    <button @click="submitConfirmed" :disabled="isSubmitting" class="px-8 py-3 bg-[#9e8356] text-white rounded-full hover:bg-[#bfa477] text-base transition shadow-md flex items-center justify-center gap-2">
                        <svg v-if="isSubmitting" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <span>{{ isSubmitting ? 'Even geduld...' : 'Bevestigen' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Swal from 'sweetalert2'
import axios from 'axios'

const props = defineProps({
    selectedDay: String,
    selectedTime: String,
    selectedService: Object
})

const emit = defineEmits(['close', 'back'])

const name = ref('')
const phone = ref('')
const email = ref('')
const notes = ref('')
const confirmModalVisible = ref(false)
const isSubmitting = ref(false)

const dayLabel = computed(() => {
    const d = new Date(props.selectedDay)
    const days = ['Zondag','Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag']
    return `${days[d.getDay()]} ${String(d.getDate()).padStart(2, '0')}-${String(d.getMonth() + 1).padStart(2, '0')}-${d.getFullYear()}`
})

async function submitConfirmed() {
    isSubmitting.value = true
    localStorage.setItem('appointmentUser', JSON.stringify({ name: name.value, email: email.value, phone: phone.value }))
    const request = axios.post('/api/appointments', {
        name: name.value,
        phone: phone.value,
        email: email.value,
        notes: notes.value,
        service_id: props.selectedService?.id,
        day: props.selectedDay,
        time: props.selectedTime
    })
    const timeout = new Promise((resolve) => setTimeout(resolve, 5000))
    await Promise.race([request, timeout])
    Swal.fire({
        title: '<span style="color:#9e8356; font-weight:bold;">🎉 Afspraak bevestigd!</span>',
        html: `
      <div style="font-size: 16px; color:#4a4137; line-height: 1.8;">
        <p><strong>Dag:</strong> ${dayLabel.value}</p>
        <p><strong>Tijd:</strong> ${props.selectedTime}</p>
      </div>`,
        icon: 'success',
        iconColor: '#9e8356',
        background: '#fffdf8',
        confirmButtonText: 'Geweldig!',
        confirmButtonColor: '#9e8356',
        customClass: {
            popup: 'rounded-3xl px-6 py-8',
            title: 'text-xl',
            confirmButton: 'text-base font-semibold px-6 py-2'
        },
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    })
    emit('close')
    isSubmitting.value = false
    try {
        await request
        localStorage.setItem('new_appointment', Date.now())
    } catch (err) {
        console.error('❌ Fout bij opslaan:', err)
    }
}

onMounted(() => {
    const saved = localStorage.getItem('appointmentUser')
    if (saved) {
        try {
            const parsed = JSON.parse(saved)
            name.value = parsed.name ?? ''
            email.value = parsed.email ?? ''
            phone.value = parsed.phone ?? ''
        } catch {}
    }
})
</script>
