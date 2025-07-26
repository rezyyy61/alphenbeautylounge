<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import Papa from 'papaparse'
import { saveAs } from 'file-saver'
import CustomerDetailsModal from './CustomerDetailsModal.vue'

const users = ref([])
const loading = ref(true)
const search = ref('')
const selectedUserId = ref(null)
const showDetailsModal = ref(false)

const page = ref(1)
const pagination = ref({})

const openDetails = (user) => {
    selectedUserId.value = user.id
    showDetailsModal.value = true
}

const exportCsv = () => {
    const csvData = filteredUsers.value.map(u => ({
        Naam: u.name,
        Email: u.email,
        Telefoon: u.phone || '',
        AantalAfspraken: u.total_appointments,
        LaatsteAfspraak: u.last_appointment || '',
        TotaleUitgave: u.total_spent ? `€${Number(u.total_spent).toFixed(2)}` : '—',
    }))

    const csv = Papa.unparse(csvData)
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' })
    saveAs(blob, `klanten-${new Date().toISOString().slice(0, 10)}.csv`)
}

const fetchUsers = async () => {
    loading.value = true
    try {
        const res = await axios.get('/users', {
            params: { page: page.value }
        })
        users.value = res.data.data
        pagination.value = res.data.meta
    } catch (err) {
        console.error('❌ Fout bij laden van gebruikers:', err)
    } finally {
        loading.value = false
    }
}

const filteredUsers = computed(() => {
    if (!search.value) return users.value
    return users.value.filter(u =>
        u.name.toLowerCase().includes(search.value.toLowerCase()) ||
        u.email.toLowerCase().includes(search.value.toLowerCase())
    )
})

watch(page, fetchUsers, { immediate: true })

watch(page, () => {
    search.value = ''
    fetchUsers()
})

</script>

<template>
    <div class="px-4 sm:px-6 lg:px-10 py-6 space-y-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#9e8356]">👥 Klanten beheren</h1>

        <!-- Search & Export -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
            <input
                v-model="search"
                placeholder="🔍 Zoek klant..."
                class="p-2 border rounded w-full sm:max-w-sm text-sm"
            />
            <button
                @click="exportCsv"
                class="bg-[#9e8356] hover:bg-[#bfa477] text-white text-sm px-4 py-2 rounded shadow w-full sm:w-auto"
            >
                ⬇️ Exporteer CSV
            </button>
        </div>

        <!-- Cards for Mobile -->
        <div class="grid sm:hidden gap-4">
            <div
                v-for="user in filteredUsers"
                :key="user.id"
                class="bg-white rounded-xl shadow border border-[#e7dbc6] p-4 space-y-2"
            >
                <div class="text-[#4a4137] font-bold text-lg">{{ user.name }}</div>
                <div class="text-sm text-gray-600">📧 {{ user.email }}</div>
                <div class="text-sm text-gray-600">📞 {{ user.phone || '—' }}</div>
                <div class="text-sm text-gray-600">📅 Afspraken: {{ user.total_appointments }}</div>
                <div class="text-sm text-gray-600">🕒 Laatste: {{ user.last_appointment || '—' }}</div>
                <div class="text-sm text-[#9e8356] font-semibold">
                    💶 {{ user.total_spent ? `€${Number(user.total_spent).toFixed(2)}` : '—' }}
                </div>
                <div class="pt-2 text-right">
                    <button
                        @click="openDetails(user)"
                        class="text-indigo-600 hover:underline text-sm"
                    >
                        👁️ Details
                    </button>
                </div>
            </div>
        </div>

        <!-- Table for Desktop -->
        <div class="hidden sm:block overflow-x-auto bg-white shadow-xl rounded-xl border border-[#e7dbc6]">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-[#fdf9f3] text-[#4a4137] text-left">
                <tr>
                    <th class="px-4 py-3 font-semibold">Naam</th>
                    <th class="px-4 py-3 font-semibold">Email</th>
                    <th class="px-4 py-3 font-semibold">Telefoon</th>
                    <th class="px-4 py-3 font-semibold text-right">Afspraken</th>
                    <th class="px-4 py-3 font-semibold text-right">Laatste afspraak</th>
                    <th class="px-4 py-3 font-semibold text-right">Totale uitgave</th>
                    <th class="px-4 py-3 font-semibold text-right">Acties</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                <tr
                    v-for="user in filteredUsers"
                    :key="user.id"
                    class="hover:bg-[#fdf7ee] transition"
                >
                    <td class="px-4 py-3 font-medium text-[#4a4137]">{{ user.name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ user.email }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ user.phone || '—' }}</td>
                    <td class="px-4 py-3 text-right">{{ user.total_appointments }}</td>
                    <td class="px-4 py-3 text-right">{{ user.last_appointment ?? '—' }}</td>
                    <td class="px-4 py-3 text-right text-[#9e8356] font-semibold">
                        {{ user.total_spent ? `€${Number(user.total_spent).toFixed(2)}` : '—' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <button
                            @click="openDetails(user)"
                            class="text-indigo-600 hover:underline text-sm"
                        >
                            👁️ Details
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-sm text-gray-600 px-2 sm:px-6 pt-4">
            <button
                @click="page--"
                :disabled="page <= 1"
                class="px-4 py-1 border rounded disabled:opacity-50"
            >
                ← Vorige
            </button>
            <span>Pagina {{ pagination.current_page }} van {{ pagination.last_page }}</span>
            <button
                @click="page++"
                :disabled="page >= pagination.last_page"
                class="px-4 py-1 border rounded disabled:opacity-50"
            >
                Volgende →
            </button>
        </div>

        <!-- Modal -->
        <CustomerDetailsModal
            :visible="showDetailsModal"
            :user-id="selectedUserId"
            @close="showDetailsModal = false"
        />
    </div>
</template>
