<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-[#4a4137]">🛑 Beheer geblokkeerde tijden</h2>
            <button
                @click="$emit('create')"
                class="bg-[#9e8356] text-white px-4 py-2 rounded-xl hover:bg-[#bfa477] transition"
            >
                ➕ Nieuwe blokkade
            </button>
        </div>

        <div v-if="loading" class="text-gray-500 italic text-center py-8">
            Laden...
        </div>

        <div v-else-if="blockedPeriods.length">
            <table class="w-full text-sm border">
                <thead class="bg-[#f5f2ea] text-[#4a4137]">
                <tr>
                    <th class="p-3 text-left">Startdatum</th>
                    <th class="p-3 text-left">Einddatum</th>
                    <th class="p-3 text-left">Tijd</th>
                    <th class="p-3 text-left">Bericht</th>
                    <th class="p-3 text-left">Acties</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="block in blockedPeriods"
                    :key="block.id"
                    class="border-b hover:bg-gray-50 transition"
                >
                    <td class="p-3">{{ formatDate(block.start_date) }}</td>
                    <td class="p-3">{{ block.end_date ? formatDate(block.end_date) : '—' }}</td>
                    <td class="p-3">
                            <span v-if="block.start_time && block.end_time">
                                {{ block.start_time }} - {{ block.end_time }}
                            </span>
                        <span v-else>Hele dag</span>
                    </td>
                    <td class="p-3 text-gray-600">{{ block.message || 'Geen bericht' }}</td>
                    <td class="p-3 space-x-2">
                        <button
                            @click="$emit('edit', block)"
                            class="text-blue-600 hover:underline"
                        >
                            Bewerk
                        </button>
                        <button
                            @click="confirmDelete(block)"
                            class="text-red-600 hover:underline"
                        >
                            Verwijder
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="text-gray-500 italic text-center py-8">
            Geen blokkades gevonden.
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import {
    fetchBlockedPeriods,
    deleteBlockedPeriod
} from '@/composables/notices.js'

const props = defineProps({
    refreshKey: Number
})

const blockedPeriods = ref([])
const loading = ref(true)

watch(() => props.refreshKey, () => {
    loadBlockedPeriods()
})

function formatDate(dateStr) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' }
    return new Date(dateStr).toLocaleDateString('nl-NL', options)
}

async function loadBlockedPeriods() {
    loading.value = true
    blockedPeriods.value = await fetchBlockedPeriods()
    loading.value = false
}

async function confirmDelete(block) {
    const confirmed = confirm('Weet je zeker dat je deze blokkade wilt verwijderen?')
    if (!confirmed) return

    try {
        await deleteBlockedPeriod(block.id)
        await loadBlockedPeriods()
    } catch (e) {
        alert('Verwijderen mislukt. Probeer het opnieuw.')
    }
}

onMounted(loadBlockedPeriods)
</script>

