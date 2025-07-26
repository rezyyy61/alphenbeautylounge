<template>
    <div class="bg-white border border-[#e7dbc6] rounded-2xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-[#4a4137] mb-5 flex items-center gap-2">
            <span>🔒</span> Geblokkeerde tijden
        </h2>

        <template v-if="notices.length">
            <ul class="divide-y divide-gray-100 text-sm">
                <li v-for="notice in notices" :key="notice.id" class="py-4">
                    <div class="flex justify-between items-start">
                        <div class="space-y-1">
                            <div class="text-[#4a4137] font-semibold">
                                📅 {{ formatDate(notice.start_date) }}
                                <span v-if="notice.start_time && notice.end_time">
                  van {{ notice.start_time }} tot {{ notice.end_time }}
                </span>
                                <span v-else class="italic">hele dag</span>
                            </div>
                            <div class="text-gray-500 text-xs">
                                {{ notice.message || 'Geen reden opgegeven' }}
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </template>

        <template v-else>
            <div class="text-sm text-gray-500 italic flex items-center gap-2">
                <span>✅</span> Geen blokkades voor deze dag.
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { fetchActiveBlockedPeriods } from '@/composables/notices.js'

const props = defineProps({
    day: String // Expected format: YYYY-MM-DD
})

const notices = ref([])

function isDateInRange(target, start, end = null) {
    const t = new Date(target).toISOString().split('T')[0]
    const s = new Date(start).toISOString().split('T')[0]
    const e = end ? new Date(end).toISOString().split('T')[0] : s
    return t >= s && t <= e
}

function formatDate(dateStr) {
    const date = new Date(dateStr)
    return date.toLocaleDateString('nl-NL', {
        weekday: 'short',
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    })
}

watch(
    () => props.day,
    async (val) => {
        if (!val) {
            notices.value = []
            return
        }

        try {
            const all = await fetchActiveBlockedPeriods()
            notices.value = all.filter(period => isDateInRange(val, period.start_date, period.end_date))
        } catch (e) {
            console.error('❌ Fout bij ophalen van blokkades:', e)
            notices.value = []
        }
    },
    { immediate: true }
)
</script>

<style scoped>
::-webkit-scrollbar {
    width: 6px;
}
::-webkit-scrollbar-thumb {
    background-color: #c4b69a;
    border-radius: 4px;
}
</style>
