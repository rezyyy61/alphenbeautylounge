<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'

const allServices = ref([])
const parentServices = ref([])
const showForm = ref(false)
const isEditing = ref(false)
const formTitle = ref('')
const imagePreview = ref(null)

const isLocal = window.location.hostname === 'localhost'
const assetBase = isLocal ? window.assetUrl : `${window.assetUrl}public/`
const getAsset = (file = '') => `${assetBase}images/services/${file}`

const form = ref({
    id: null,
    title: '',
    description: '',
    image: '',
    price: '',
    parent_id: null,
    duration: '',
})

const handleFileUpload = (e) => {
    const file = e.target.files[0]
    if (file) {
        form.value.image = file
        imagePreview.value = URL.createObjectURL(file)
    }
}

const fetchServices = async () => {
    try {
        const res = await axios.get('/services')
        allServices.value = res.data
        parentServices.value = allServices.value.filter(s => s.parent_id === null)
    } catch (e) {
        console.error('❌ Fout bij laden services:', e)
    }
}

const resetForm = () => {
    form.value = {
        id: null,
        title: '',
        description: '',
        image: '',
        price: '',
        parent_id: null,
        duration: '',
    }
    imagePreview.value = null
    isEditing.value = false
    showForm.value = false
}

const openCreateForm = (parentId = null) => {
    resetForm()
    form.value.parent_id = parentId
    formTitle.value = parentId ? 'Subservice toevoegen' : 'Nieuwe service toevoegen'
    showForm.value = true
    nextTick(() => {
        const formEl = document.getElementById('service-form')
        if (formEl) {
            formEl.scrollIntoView({ behavior: 'smooth', block: 'start' })
        }
    })

}

const openEditForm = (service) => {
    form.value = {
        ...service,
        image: '',
        duration: service.duration || '',
    }
    imagePreview.value = service.image ? getAsset(service.image) : null
    isEditing.value = true
    formTitle.value = 'Service bewerken'
    showForm.value = true

    nextTick(() => {
        const formEl = document.getElementById('service-form')
        if (formEl) {
            formEl.scrollIntoView({ behavior: 'smooth', block: 'start' })
        }
    })
}

const submitForm = async () => {
    if (form.value.parent_id && (!form.value.duration || form.value.duration % 15 !== 0)) {
        alert('Duur moet een veelvoud van 15 minuten zijn (15, 30, 45, ...)')
        return
    }

    const formData = new FormData()
    formData.append('title', form.value.title)

    if (!form.value.parent_id) {
        formData.append('description', form.value.description)
    }

    if (form.value.image instanceof File) {
        formData.append('image', form.value.image)
    }

    if (form.value.parent_id) {
        formData.append('price', form.value.price)
        formData.append('parent_id', form.value.parent_id)
        formData.append('duration', form.value.duration)
    }

    try {
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        if (isEditing.value) {
            await axios.post(`/services/${form.value.id}?_method=PUT`, formData, config)
        } else {
            await axios.post('/services', formData, config)
        }

        await fetchServices()
        resetForm()
    } catch (error) {
        console.error('❌ Fout bij opslaan:', error)
        alert('Er ging iets mis tijdens het opslaan van de service.')
    }
}

const deleteService = async (id) => {
    if (confirm('Weet je zeker dat je dit wilt verwijderen?')) {
        await axios.delete(`/services/${id}`)
        await fetchServices()
    }
}

onMounted(fetchServices)
</script>

<template>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-[#9e8356] mb-6 text-center sm:text-left">🛠️ Beheer Services</h2>

        <div class="flex justify-center sm:justify-start">
            <button
                @click="openCreateForm()"
                class="bg-[#9e8356] text-white px-5 py-3 rounded-full hover:bg-[#bfa477] transition text-sm font-bold mb-4"
            >
                ➕ Nieuwe service toevoegen
            </button>
        </div>

        <div v-if="showForm" id="service-form" class="bg-white border border-[#e7dbc6] rounded-2xl p-4 sm:p-6 shadow-md mb-8">
            <h3 class="text-lg font-semibold mb-4 text-center sm:text-left">{{ formTitle }}</h3>
            <form @submit.prevent="submitForm" class="space-y-4">
                <input v-model="form.title" type="text" placeholder="Titel" class="w-full border p-3 rounded text-sm" required />

                <div v-if="!form.parent_id" class="space-y-2">
                    <div v-if="imagePreview" class="relative w-32 h-32 rounded overflow-hidden border">
                        <img :src="imagePreview" class="w-full h-full object-cover" />
                        <button @click="form.image = ''; imagePreview = null" type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-2 py-1">X</button>
                    </div>
                    <input type="file" @change="handleFileUpload" class="w-full border p-2 rounded text-sm" />
                </div>

                <textarea
                    v-if="!form.parent_id"
                    v-model="form.description"
                    placeholder="Beschrijving"
                    class="w-full border p-3 rounded text-sm"
                ></textarea>

                <input
                    v-if="form.parent_id !== null"
                    v-model="form.price"
                    type="number"
                    step="0.01"
                    placeholder="Prijs (€)"
                    class="w-full border p-3 rounded text-sm"
                />

                <input
                    v-if="form.parent_id !== null"
                    v-model.number="form.duration"
                    type="number"
                    min="15"
                    max="240"
                    step="15"
                    placeholder="Duur (minuten)"
                    class="w-full border p-3 rounded text-sm"
                />

                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <button type="submit" class="bg-[#9e8356] text-white px-4 py-2 rounded hover:bg-[#bfa477] text-sm">
                        {{ isEditing ? 'Opslaan' : 'Toevoegen' }}
                    </button>
                    <button type="button" @click="resetForm" class="text-sm text-gray-500 hover:underline">
                        Annuleren
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-6">
            <div
                v-for="service in parentServices"
                :key="service.id"
                class="bg-white border border-[#e7dbc6] rounded-xl p-4 shadow"
            >
                <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-center gap-4">
                    <div class="flex items-start gap-4">
                        <img
                            v-if="service.image"
                            :src="getAsset(service.image)"
                            alt="img"
                            class="w-16 h-16 object-cover rounded"
                        />
                        <div>
                            <h4 class="text-lg font-bold text-[#9e8356]">{{ service.title }}</h4>
                            <p class="text-sm text-gray-600">{{ service.description }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 text-sm">
                        <button @click="openEditForm(service)" class="text-blue-600 hover:underline">✏️ Bewerken</button>
                        <button @click="deleteService(service.id)" class="text-red-500 hover:underline">🗑️ Verwijderen</button>
                        <button @click="openCreateForm(service.id)" class="text-green-600 hover:underline">➕ Sub</button>
                    </div>
                </div>

                <div class="mt-4 space-y-2 pl-4">
                    <div
                        v-for="child in allServices.filter(c => c.parent_id === service.id)"
                        :key="child.id"
                        class="flex justify-between items-center border rounded-lg px-3 py-2 text-sm bg-[#f9f5ef]"
                    >
                        <span>{{ child.title }}</span>
                        <div class="flex items-center gap-3">
              <span class="text-[#4a4137] font-medium">
                {{ child.price > 0 ? `€${Number(child.price).toFixed(2)}` : 'Prijs op aanvraag' }}
                <span v-if="child.duration" class="ml-2 text-gray-500">• {{ child.duration }} min</span>
              </span>
                            <button @click="openEditForm(child)" class="text-blue-500 hover:underline">✏️</button>
                            <button @click="deleteService(child.id)" class="text-red-500 hover:underline">🗑️</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

