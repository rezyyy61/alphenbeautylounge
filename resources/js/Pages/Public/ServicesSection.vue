<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import BookingModal from '@/Components/BookingModal.vue'

const allServices = ref([])
const mainServices = ref([])
const activeTab = ref(null)
const modalVisible = ref(false)
const selectedService = ref(null)

const handleBooking = () => {
    modalVisible.value = false
}

onMounted(async () => {
    try {
        const res = await axios.get('/api/services')
        allServices.value = res.data
        mainServices.value = allServices.value.filter(s => s.parent_id === null)
        activeTab.value = mainServices.value[0]?.id ?? null
    } catch (e) {
        console.error('❌ Error fetching services:', e)
    }
})

const getImagePath = (imageName = '') => {
    const isLocal = window.location.hostname === 'localhost'
    const prefix = isLocal ? '' : 'public/'
    return `${window.assetUrl}${prefix}images/services/${imageName || 'default.png'}`
}

const getSubServices = (parentId) =>
    allServices.value.filter(s => s.parent_id === parentId)

const openModal = (service) => {
    selectedService.value = service
    modalVisible.value = true
}
</script>

<template>
    <section class="bg-[#fff9f5] py-12 px-4 md:py-24 md:px-6 relative overflow-hidden" id="services">
        <div class="block md:hidden mb-6">
            <figure class="w-full h-[280px] bg-[#f9f5ef] flex items-center justify-center rounded-2xl shadow-sm">
                <div class="w-[90%] h-[90%] overflow-hidden rounded-xl">
                    <img
                        :src="getImagePath(mainServices.find(s => s.id === activeTab)?.image)"
                        :alt="mainServices.find(s => s.id === activeTab)?.title || 'Service afbeelding'"
                        width="600"
                        height="280"
                        loading="lazy"
                        class="w-full h-full object-cover"
                    />
                </div>
            </figure>
        </div>

        <header class="max-w-4xl mx-auto text-center mb-10 relative z-10">
            <h2 class="text-3xl md:text-5xl font-serif italic text-[#9e8356] mb-3">
                Onze diensten
            </h2>
            <p class="text-[#5e4d33] text-base md:text-lg font-light">
                Ontdek professionele behandelingen zoals knippen, kleuren, make-up en epileren in Alphen aan den Rijn.
            </p>
        </header>

        <nav class="grid grid-cols-2 sm:grid-cols-3 gap-3 md:hidden mb-6">
            <button
                v-for="tab in mainServices"
                :key="tab.id"
                @click="activeTab = tab.id"
                class="w-full px-4 py-3 rounded-full text-sm font-semibold tracking-wide transition-all duration-200 text-center"
                :class="activeTab === tab.id
          ? 'bg-[#9e8356] text-white shadow-md'
          : 'bg-white text-[#4a4137] border border-[#ccc] hover:bg-[#f5f5f5]'"
            >
                {{ tab.title }}
            </button>
        </nav>

        <div class="hidden md:flex justify-center gap-3 mb-10 relative z-10">
            <button
                v-for="tab in mainServices"
                :key="tab.id"
                @click="activeTab = tab.id"
                class="group relative px-8 py-3 rounded-full text-base font-medium transition-all duration-300"
                :class="activeTab === tab.id
          ? 'bg-[#9e8356] text-white'
          : 'bg-white/90 text-[#4a4137] hover:bg-white'"
            >
                <span class="relative z-10">{{ tab.title }}</span>
                <div v-if="activeTab === tab.id" class="absolute inset-0 border-2 border-white/30 rounded-full"></div>
                <div class="absolute inset-0 rounded-full shadow-lg" :class="activeTab === tab.id ? 'shadow-[#9e835633]' : 'shadow-transparent'"></div>
            </button>
        </div>

        <div v-if="activeTab" class="block md:hidden grid grid-cols-1 gap-4">
            <article
                v-for="sub in getSubServices(activeTab)"
                :key="sub.id"
                class="relative bg-[#fefcf9] p-5 rounded-2xl border border-[#e5dcc8] shadow-sm hover:shadow-md transition"
            >
                <h3 class="text-base font-semibold text-[#4a4137] mb-1">{{ sub.title }}</h3>
                <p v-if="sub.description" class="text-sm text-[#5e4d33] opacity-80 mb-3 line-clamp-2">
                    {{ sub.description }}
                </p>
                <div class="flex justify-between items-center">
          <span class="text-sm font-bold text-[#9e8356]">
            {{ sub.price > 0 ? `€${Number(sub.price).toFixed(2)}` : 'Op aanvraag' }}
          </span>
                    <a
                        v-if="sub.title.toLowerCase().includes('make') || sub.price === 0"
                        href="tel:0612345678"
                        class="text-xs bg-[#9e8356] text-white py-2 px-4 rounded-full hover:bg-[#bfa477] transition"
                    >
                        Bel ons
                    </a>
                    <button
                        v-else
                        @click="openModal(sub)"
                        class="text-xs bg-gradient-to-br from-[#9e8356] to-[#7a6847] text-white py-2 px-4 rounded-full hover:from-[#bfa477] hover:to-[#9e8356] transition"
                    >
                        Boeken
                    </button>
                </div>
            </article>
        </div>

        <div
            v-if="activeTab"
            class="hidden md:block max-w-6xl mx-auto bg-white/95 backdrop-blur-sm border border-[#e7dbc6] rounded-[2.5rem] shadow-2xl relative z-10"
        >
            <div class="flex flex-col md:flex-row h-full overflow-hidden">
                <figure class="md:w-1/2 relative aspect-[4/3] overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-[#f9f4ef]/30 z-10"></div>
                    <img
                        :src="getImagePath(mainServices.find(s => s.id === activeTab)?.image)"
                        :alt="mainServices.find(s => s.id === activeTab)?.title || 'Behandeling afbeelding'"
                        width="600"
                        height="400"
                        loading="lazy"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                    <figcaption class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/40 to-transparent">
                        <h3 class="text-3xl font-bold text-white mb-2 drop-shadow-lg">
                            {{ mainServices.find(s => s.id === activeTab)?.title }}
                        </h3>
                        <p class="text-[#e7dbc6] text-sm font-light line-clamp-2">
                            {{ mainServices.find(s => s.id === activeTab)?.description }}
                        </p>
                    </figcaption>
                </figure>

                <div class="md:w-1/2 p-8 flex flex-col space-y-6">
                    <div class="space-y-6 overflow-y-auto max-h-[500px] custom-scroll">
                        <article
                            v-for="sub in getSubServices(activeTab)"
                            :key="sub.id"
                            class="group relative bg-white border border-[#e7dbc6] p-4 rounded-xl transition-all hover:border-[#9e8356] hover:shadow-lg"
                        >
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-[#4a4137] mb-1">
                                        {{ sub.title }}
                                    </h4>
                                    <p v-if="sub.description" class="text-sm text-[#5e4d33] opacity-80 line-clamp-2">
                                        {{ sub.description }}
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-3">
                  <span class="text-sm font-semibold text-[#9e8356] whitespace-nowrap">
                    {{ sub.price > 0 ? `€${Number(sub.price).toFixed(2)}` : 'Prijs op aanvraag' }}
                  </span>
                                    <template v-if="sub.title.toLowerCase().includes('make') || sub.price === 0">
                                        <a href="tel:0612345678" class="flex items-center gap-2 bg-[#9e8356] text-white px-4 py-2 rounded-full text-sm hover:bg-[#bfa477] transition-transform hover:scale-105">
                                            Bel ons
                                        </a>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="openModal(sub)"
                                            class="flex items-center gap-2 bg-gradient-to-br from-[#9e8356] to-[#7a6847] text-white px-4 py-2 rounded-full text-sm hover:from-[#bfa477] hover:to-[#9e8356] transition-transform hover:scale-105"
                                        >
                                            Boeken
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>

        <BookingModal
            :visible="modalVisible"
            :selectedService="selectedService"
            @close="modalVisible = false"
            @submitted="handleBooking"
        />
    </section>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
