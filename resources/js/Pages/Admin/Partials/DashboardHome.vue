<template>
    <div class="p-10 space-y-8">
        <h1 class="text-3xl font-bold text-[#9e8356]">📊 Admin Dashboard</h1>

        <!-- 📊 Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                v-for="stat in stats"
                :key="stat.label"
                class="bg-white border border-[#e7dbc6] rounded-xl shadow p-5 text-center"
            >
                <div class="text-sm text-gray-500">{{ stat.label }}</div>
                <div class="text-2xl font-bold text-[#9e8356]">{{ stat.value }}</div>
            </div>
        </div>

        <!-- 📈 Chart Section -->
        <div class="bg-white border border-[#e7dbc6] rounded-xl shadow p-6">
            <h2 class="text-lg font-bold text-[#4a4137] mb-4">📈 Afspraken deze & volgende week</h2>
            <LineChart :chart-data="chartData" />
        </div>

        <!-- 📅 Afspraken vandaag -->
        <div class="bg-white border border-[#e7dbc6] rounded-xl shadow p-6">
            <h2 class="text-lg font-bold text-[#4a4137] mb-4">📅 Afspraken vandaag</h2>
            <table class="w-full text-sm text-left border-t">
                <thead class="text-gray-500">
                <tr>
                    <th class="py-2">Tijd</th>
                    <th class="py-2">Naam</th>
                    <th class="py-2">Dienst</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="appt in todayAppointments" :key="appt.id" class="border-t">
                    <td class="py-2">{{ appt.time }}</td>
                    <td class="py-2">{{ appt.name }}</td>
                    <td class="py-2">{{ appt.service }}</td>
                </tr>
                <tr v-if="!todayAppointments.length">
                    <td colspan="3" class="py-4 text-center text-gray-400 italic">
                        Geen afspraken vandaag
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- 🏆 Top Klanten -->
        <div class="bg-white border border-[#e7dbc6] rounded-xl shadow p-6">
            <h2 class="text-lg font-bold text-[#4a4137] mb-4">🏆 Top Klanten</h2>
            <ul class="divide-y divide-gray-100">
                <li
                    v-for="user in topCustomers"
                    :key="user.id"
                    class="py-3 flex justify-between items-center hover:bg-[#fdf7ee] transition cursor-pointer"
                    @click="openCustomerModal(user.id)"
                >
                    <div>
                        <div class="font-semibold text-[#4a4137]">{{ user.name }}</div>
                        <div class="text-xs text-gray-500">{{ user.email }}</div>
                    </div>
                    <div class="text-right text-sm">
                        <div>📅 {{ user.total_appointments }} afspraken</div>
                        <div class="text-[#9e8356] font-semibold">
                            💶 €{{ Number(user.total_spent).toFixed(2) }}
                        </div>
                    </div>
                </li>
                <li v-if="!topCustomers.length" class="text-center text-gray-400 italic py-4">
                    Geen top klanten gevonden
                </li>
            </ul>
        </div>
        <!-- 👁️ Modal -->
        <CustomerDetailsModal
            :user-id="selectedUserId"
            :visible="showModal"
            @close="showModal = false"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { format, isToday } from 'date-fns'
import { nl } from 'date-fns/locale'

import LineChart from '@/Components/Charts/LineChart.vue'
import CustomerDetailsModal from '@/Pages/Admin/Partials/CustomerDetailsModal.vue'

const stats = ref([])
const chartData = ref({
    labels: [],
    datasets: [],
})
const todayAppointments = ref([])
const topCustomers = ref([])
const selectedUserId = ref(null)
const showModal = ref(false)

const openCustomerModal = (userId) => {
    selectedUserId.value = userId
    showModal.value = true
}

onMounted(async () => {
    try {
        const res = await axios.get('/dashboard/summary')

        stats.value = res.data.stats
        todayAppointments.value = res.data.today_appointments
        topCustomers.value = res.data.top_customers

        const rawLabels = res.data.chart.labels

        chartData.value = {
            rawLabels,
            labels: rawLabels.map(date => {
                const d = new Date(date)
                const short = format(d, 'dd MMM', { locale: nl })
                return isToday(d) ? `Vandaag - ${short}` : format(d, 'EEE dd MMM', { locale: nl })
            }),
            datasets: [
                {
                    label: 'Afspraken',
                    data: res.data.chart.values,
                    borderColor: '#9e8356',
                    backgroundColor: '#9e8356',
                    fill: false
                }
            ]
        }

    } catch (e) {
        console.error('Fout bij laden dashboard data:', e)
    }
})
</script>

<style scoped></style>
