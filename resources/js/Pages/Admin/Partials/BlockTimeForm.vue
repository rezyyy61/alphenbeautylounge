<template>
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow space-y-6">
        <h2 class="text-xl font-bold text-[#4a4137]">
            {{ isEditMode ? '✏️ Blokkade bewerken' : '➕ Tijd blokkeren' }}
        </h2>

        <!-- Alert Message -->
        <div
            v-if="conflictWarning"
            class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg"
        >
            ⚠️ Er is al een afspraak gepland in deze periode. Verwijder eerst de bestaande afspraak.
        </div>

        <!-- Start date -->
        <div>
            <label class="block font-medium text-sm mb-1">Startdatum <span class="text-red-500">*</span></label>
            <input type="date" v-model="form.start_date" class="input" required />
        </div>

        <!-- End date toggle -->
        <div>
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="showEndDate" class="accent-[#9e8356]" />
                <span class="text-sm text-[#4a4137]">Blokkeer meerdere dagen</span>
            </label>

            <div v-if="showEndDate" class="mt-2">
                <label class="block font-medium text-sm mb-1">Einddatum</label>
                <input type="date" v-model="form.end_date" class="input" :min="form.start_date" />
            </div>
        </div>

        <!-- Time toggle -->
        <div>
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="showTime" class="accent-[#9e8356]" />
                <span class="text-sm text-[#4a4137]">Specifieke tijd opgeven</span>
            </label>

            <div v-if="showTime" class="mt-3 flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-1">Van</label>
                    <VueTimepicker
                        v-model="form.start_time"
                        format="HH:mm"
                        :minute-step="15"
                        hide-clear-button
                        placeholder="--:--"
                        class="w-full"
                    />
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium mb-1">Tot</label>
                    <VueTimepicker
                        v-model="form.end_time"
                        format="HH:mm"
                        :minute-step="15"
                        hide-clear-button
                        placeholder="--:--"
                        class="w-full"
                    />
                </div>
            </div>
        </div>

        <!-- Message -->
        <div>
            <label class="block font-medium text-sm mb-1">Bericht (optioneel)</label>
            <textarea
                v-model="form.message"
                rows="3"
                class="input resize-none"
                placeholder="Bijv. Vrije dag, vakantie, etc."
            ></textarea>
        </div>

        <!-- Submit / Cancel -->
        <div class="text-right space-x-3">
            <button
                @click="$emit('cancel')"
                type="button"
                class="px-4 py-2 border border-gray-300 text-gray-600 rounded-xl hover:bg-gray-100 transition"
            >
                Annuleren
            </button>
            <button
                @click="handleSubmit"
                :disabled="loading"
                class="bg-[#9e8356] text-white font-semibold px-6 py-3 rounded-xl hover:bg-[#bfa477] transition disabled:opacity-50"
            >
                {{ loading ? 'Opslaan...' : 'Opslaan' }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import VueTimepicker from 'vue3-timepicker'
import 'vue3-timepicker/dist/VueTimepicker.css'
import {
    createBlockedPeriod,
    updateBlockedPeriod
} from '@/composables/notices.js'
import axios from 'axios'

const props = defineProps({
    initial: Object // null for create mode, object for edit
})

const emit = defineEmits(['saved', 'cancel'])

const isEditMode = computed(() => !!props.initial?.id)

const form = ref({
    start_date: '',
    end_date: '',
    start_time: '',
    end_time: '',
    message: ''
})

const showTime = ref(false)
const showEndDate = ref(false)
const loading = ref(false)
const conflictWarning = ref(false)

watch(
    () => props.initial,
    (val) => {
        if (val) {
            form.value = {
                start_date: val.start_date || '',
                end_date: val.end_date || '',
                start_time: val.start_time || '',
                end_time: val.end_time || '',
                message: val.message || ''
            }
            showTime.value = !!(val.start_time && val.end_time)
            showEndDate.value = !!val.end_date
        } else {
            resetForm()
        }
    },
    { immediate: true }
)

async function handleSubmit() {
    conflictWarning.value = false

    if (!form.value.start_date) return alert('Startdatum is verplicht.')

    const payload = {
        start_date: form.value.start_date,
        end_date: showEndDate.value ? form.value.end_date : null,
        start_time: showTime.value ? form.value.start_time : null,
        end_time: showTime.value ? form.value.end_time : null,
        message: form.value.message
    }

    try {
        loading.value = true

        // check conflicts only for creation
        if (!isEditMode.value) {
            const { data } = await axios.get('/api/appointments/conflicts', {
                params: payload
            })

            if (data.conflict) {
                conflictWarning.value = true
                loading.value = false
                return
            }
        }

        if (isEditMode.value) {
            await updateBlockedPeriod(props.initial.id, payload)
        } else {
            await createBlockedPeriod(payload)
        }

        emit('saved')
        resetForm()
    } catch (e) {
        alert('Fout bij opslaan. Controleer invoer.')
    } finally {
        loading.value = false
    }
}

function resetForm() {
    form.value = {
        start_date: '',
        end_date: '',
        start_time: '',
        end_time: '',
        message: ''
    }
    showTime.value = false
    showEndDate.value = false
    conflictWarning.value = false
}
</script>

<style scoped>
.input {
    @apply w-full border border-gray-300 rounded-lg px-4 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-[#9e8356];
}
</style>
