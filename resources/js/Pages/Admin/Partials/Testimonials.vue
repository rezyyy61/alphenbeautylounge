<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

const testimonials = ref([])
const loading = ref(true)
const editingReview = ref(null)
const showEditModal = ref(false)
const editForm = ref({ name: '', service: '', text: '', rating: 5 })

onMounted(async () => {
    await loadTestimonials()
})

const loadTestimonials = async () => {
    try {
        const { data } = await axios.get('/api/testimonials')
        testimonials.value = data
    } catch (e) {
        console.error('Fout bij laden van testimonials:', e)
    } finally {
        loading.value = false
    }
}

const deleteTestimonial = async (id) => {
    const confirmed = await Swal.fire({
        title: 'Weet je het zeker?',
        text: 'Deze review wordt permanent verwijderd.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#9e8356',
        cancelButtonColor: '#ccc',
        confirmButtonText: 'Ja, verwijderen',
        cancelButtonText: 'Annuleren'
    })

    if (confirmed.isConfirmed) {
        try {
            await axios.delete(`/api/testimonials/${id}`)
            testimonials.value = testimonials.value.filter(t => t.id !== id)
            Swal.fire('Verwijderd!', 'De review is verwijderd.', 'success')
        } catch (e) {
            console.error('Fout bij verwijderen:', e)
            Swal.fire('Fout', 'Kon de review niet verwijderen.', 'error')
        }
    }
}

const openEditModal = (review) => {
    editingReview.value = review
    editForm.value = { ...review } // clone current values
    showEditModal.value = true
}

const saveChanges = async () => {
    try {
        await axios.put(`/api/testimonials/${editingReview.value.id}`, editForm.value)
        await loadTestimonials()
        showEditModal.value = false
        Swal.fire('Bijgewerkt!', 'De review is aangepast.', 'success')
    } catch (e) {
        console.error('Fout bij bijwerken:', e)
        Swal.fire('Fout', 'Kon de review niet bijwerken.', 'error')
    }
}
</script>

<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-[#9e8356]">💬 Reviews beheren</h2>

        <div v-if="loading" class="text-gray-500 italic">Bezig met laden...</div>
        <div v-else-if="!testimonials.length" class="text-gray-400 italic">Geen reviews gevonden.</div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="review in testimonials"
                :key="review.id"
                class="bg-white border border-[#e7dbc6] rounded-xl p-5 shadow"
            >
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-[#4a4137]">{{ review.name }}</h3>
                    <div class="text-yellow-400 text-sm">
                        <span v-for="n in review.rating" :key="n">★</span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mb-2 italic">Behandeling: {{ review.service }}</p>
                <p class="text-sm text-[#4a4137]">{{ review.text }}</p>

                <div class="mt-4 flex justify-between items-center text-xs">
                    <button @click="openEditModal(review)" class="text-blue-500 hover:underline">✏️ Bewerken</button>
                    <button @click="deleteTestimonial(review.id)" class="text-red-500 hover:underline">🗑️ Verwijderen</button>
                </div>
            </div>
        </div>

        <!-- ✏️ Edit Modal -->
        <div
            v-if="showEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        >
            <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl border border-[#e7dbc6]">
                <h3 class="text-lg font-bold text-[#9e8356] mb-4">Review bijwerken</h3>
                <div class="space-y-4">
                    <input v-model="editForm.name" type="text" class="w-full border p-2 rounded" placeholder="Naam" />
                    <input v-model="editForm.service" type="text" class="w-full border p-2 rounded" placeholder="Behandeling" />
                    <textarea v-model="editForm.text" rows="3" class="w-full border p-2 rounded" placeholder="Review tekst..."></textarea>

                    <div class="flex items-center gap-2">
                        <span class="text-[#4a4137] text-sm">Beoordeling:</span>
                        <span
                            v-for="n in 5"
                            :key="n"
                            @click="editForm.rating = n"
                            class="cursor-pointer text-2xl"
                            :class="editForm.rating >= n ? 'text-yellow-400' : 'text-gray-300'"
                        >
                            ★
                        </span>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button @click="showEditModal = false" class="px-4 py-2 text-sm rounded bg-gray-200 hover:bg-gray-300">
                            Annuleren
                        </button>
                        <button @click="saveChanges" class="px-4 py-2 text-sm rounded bg-[#9e8356] text-white hover:bg-[#bfa477]">
                            Opslaan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
textarea {
    resize: vertical;
}
</style>
