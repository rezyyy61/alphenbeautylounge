<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

const testimonials = ref([])
const form = reactive({
    name: '',
    service: '',
    text: '',
    rating: 0,
})
const isSubmitting = ref(false)

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/testimonials')
        testimonials.value = Array.isArray(data) ? data : []
    } catch (err) {
        console.error('❌ Error loading testimonials:', err)
    }
})

const avgRating = computed(() => {
    if (!testimonials.value.length) return 0
    return (
        testimonials.value.reduce((s, t) => s + (t.rating || 0), 0) / testimonials.value.length
    ).toFixed(1)
})

async function submitReview() {
    if (!form.name || !form.service || !form.text || !form.rating) return

    isSubmitting.value = true
    try {
        const { data } = await axios.post('/api/testimonials', form)
        testimonials.value.unshift({
            ...data,
            shimmer: true
        })
        Object.assign(form, { name: '', service: '', text: '', rating: 0 })

        await Swal.fire({
            iconHtml: '<div class="swal-icon animate-pulse">✨</div>',
            title: 'Review Verzonden!',
            text: 'Bedankt voor je waardevolle feedback',
            showConfirmButton: false,
            timer: 3000,
            background: '#fbf6ef',
            customClass: {
                title: 'font-serif text-2xl text-[#9e8356]',
                popup: 'rounded-2xl border-2 border-[#e7dbc6] shadow-xl'
            }
        })
    } catch (err) {
        console.error('❌ Review submission failed:', err)
    } finally {
        isSubmitting.value = false
    }
}
</script>

<template>
    <section id="testimonials" class="relative isolate overflow-hidden bg-[#fbf6ef] py-28 px-6">
        <div class="absolute inset-0 opacity-10">
            <div
                v-for="i in 15"
                :key="i"
                class="absolute w-1 h-1 bg-[#9e8356] rounded-full animate-float"
                :style="`
          left: ${Math.random() * 100}%;
          animation-delay: ${Math.random() * 2}s;
          animation-duration: ${4 + Math.random() * 4}s;
        `"
            ></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl space-y-16">
            <header class="text-center relative">
                <div class="absolute inset-x-0 top-1/2 h-[1px] bg-gradient-to-r from-transparent via-[#9e8356] to-transparent"></div>
                <h2 class="relative inline-block bg-[#fbf6ef] px-8 font-serif text-4xl italic text-[#9e8356] drop-shadow-sm md:text-5xl">
                    Schoonheidsverhalen
                </h2>
                <p class="mt-4 text-lg font-light text-[#4a4137] animate-fade-in">
                    Een verzameling van stralende ervaringen ✨
                </p>
            </header>

            <div class="grid gap-12 lg:grid-cols-2">
                <!-- Form -->
                <div class="bg-[#fefcf9] border border-[#e7dbc6] rounded-2xl shadow-lg p-6 sm:p-8 w-full lg:w-full lg:mx-0">
                    <h3 class="text-center text-2xl md:text-3xl font-serif text-[#9e8356] mb-6 italic">Jouw Ervaring ✨</h3>

                    <form @submit.prevent="submitReview" class="space-y-6 text-[#4a4137]">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-[#9e8356]">Naam</label>
                            <input v-model="form.name" type="text" required class="w-full rounded-xl border border-[#e7dbc6] bg-white px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#9e8356]/30 placeholder-gray-400 text-sm" placeholder="Je naam..." />
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-[#9e8356]">Behandeling</label>
                            <input v-model="form.service" type="text" required class="w-full rounded-xl border border-[#e7dbc6] bg-white px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#9e8356]/30 placeholder-gray-400 text-sm" placeholder="Bijv. Gezichtsbehandeling" />
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-[#9e8356]">Ervaring</label>
                            <textarea v-model="form.text" rows="4" required class="w-full rounded-xl border border-[#e7dbc6] bg-white px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#9e8356]/30 placeholder-gray-400 text-sm resize-y" placeholder="Vertel ons over je ervaring..."></textarea>
                        </div>

                        <div class="text-center space-y-2">
                            <label class="text-sm font-medium text-[#9e8356]">Beoordeling</label>
                            <div class="flex justify-center gap-2 text-2xl">
                <span
                    v-for="n in 5"
                    :key="n"
                    @mouseenter="form.rating = n"
                    @click="form.rating = n"
                    class="cursor-pointer transition-transform duration-200"
                    :class="form.rating >= n ? 'text-yellow-400 scale-110' : 'text-[#e7dbc6] hover:text-yellow-300'"
                >★</span>
                            </div>
                        </div>

                        <div class="pt-2 text-center">
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-[#9e8356] to-[#bfa477] px-8 py-3 text-white text-sm font-semibold shadow-md hover:from-[#bfa477] hover:to-[#9e8356] hover:scale-105 transition-all"
                            >
                                <span v-if="!isSubmitting">Verstuur Review</span>
                                <span v-else class="flex items-center gap-2">
                  <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle opacity="0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                  </svg>
                  Verzenden...
                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Testimonials Section -->
                <div class="space-y-8">
                    <div class="relative overflow-hidden rounded-3xl border-2 border-[#e7dbc6] bg-white/80 p-8 shadow-xl backdrop-blur-md">
                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-[#9e8356] opacity-10 blur-xl"></div>

                        <div class="flex flex-col items-center gap-4">
                            <div class="text-5xl font-bold text-[#9e8356] drop-shadow">
                                {{ avgRating }}<span class="text-2xl text-[#4a4137]">/5</span>
                            </div>
                            <div class="flex text-2xl">
                                <span v-for="n in 5" :key="n" class="transition-transform hover:scale-125" :class="n <= avgRating ? 'text-yellow-400' : 'text-[#e7dbc6]'">★</span>
                            </div>
                            <p class="text-center text-sm text-[#4a4137]">Gebaseerd op {{ testimonials.length }} ervaringen</p>
                        </div>
                    </div>

                    <div class="max-h-[460px] space-y-6 overflow-y-auto pr-2 custom-scroll">
                        <article
                            v-for="(t, i) in testimonials"
                            :key="i"
                            class="relative rounded-3xl border-2 border-[#e7dbc6] bg-white p-6 shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl"
                        >
                            <header class="mb-4 flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full bg-[#9e8356] flex items-center justify-center text-white font-bold">
                                    {{ t.name.charAt(0) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-[#9e8356]">{{ t.name }}</h4>
                                    <p class="text-sm italic text-[#4a4137]">{{ t.service }}</p>
                                </div>
                            </header>

                            <div class="mb-4 flex items-center gap-2 text-yellow-400">
                                <span v-for="n in 5" :key="n">{{ n <= t.rating ? '★' : '☆' }}</span>
                            </div>

                            <p class="text-[#4a4137] leading-relaxed">{{ t.text }}</p>

                            <div class="mt-4 text-right text-xs text-[#9e8356]">
                                {{ new Date(t.created_at).toLocaleDateString('nl-NL', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    30% { transform: translateY(-20px) rotate(3deg); }
    60% { transform: translateY(10px) rotate(-3deg); }
}

.custom-scroll::-webkit-scrollbar {
    width: 6px;
}
.custom-scroll::-webkit-scrollbar-thumb {
    background-color: #e7dbc6;
    border-radius: 10px;
}
</style>
