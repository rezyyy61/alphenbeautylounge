<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue'
import DashboardHome from "@/Pages/Admin/Partials/DashboardHome.vue";
import Services from "@/Pages/Admin/Partials/Services.vue";
import Appointments from "@/Pages/Admin/Partials/Appointments.vue";
import Users from "@/Pages/Admin/Partials/Users.vue";
import Testimonials from "@/Pages/Admin/Partials/Testimonials.vue";
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import BlockedPeriodsManager from "@/Pages/Admin/Partials/BlockedPeriodsManager.vue";

const activeView = ref('dashboard')
const mobileMenuOpen = ref(false)

const setView = (view) => {
    activeView.value = view
    mobileMenuOpen.value = false
}

const currentComponent = computed(() => {
    switch (activeView.value) {
        case 'services': return Services
        case 'appointments': return Appointments
        case 'blocks': return BlockedPeriodsManager
        case 'users': return Users
        case 'testimonials': return Testimonials
        default: return DashboardHome
    }
})
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-[#fdf9f3] flex flex-col md:flex-row">
            <!-- Sidebar (Desktop) -->
            <aside class="w-full md:w-64 bg-white shadow-lg border-r border-[#e7dbc6] hidden md:block">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-[#9e8356]">Admin Panel</h2>
                </div>
                <ul class="space-y-2 text-sm text-[#4a4137] px-6">
                    <li><a href="#" @click.prevent="setView('dashboard')" class="block py-2 hover:text-[#9e8356]">📊 Dashboard</a></li>
                    <li><a href="#" @click.prevent="setView('services')" class="block py-2 hover:text-[#9e8356]">🛠️ Services beheren</a></li>
                    <li><a href="#" @click.prevent="setView('appointments')" class="block py-2 hover:text-[#9e8356]">🗓️ Afspraken</a></li>
                    <li><a href="#" @click.prevent="setView('blocks')" class="block py-2 hover:text-[#9e8356]">🛑 Blokkades beheren</a></li>
                    <li><a href="#" @click.prevent="setView('users')" class="block py-2 hover:text-[#9e8356]">👤 Klanten</a></li>
                    <li><a href="#" @click.prevent="setView('testimonials')" class="block py-2 hover:text-[#9e8356]">💬 Reviews beheren</a></li>
                </ul>
            </aside>

            <!-- Hamburger (Mobile) -->
            <div class="md:hidden p-4 flex justify-between items-center border-b border-[#e7dbc6] bg-white shadow-sm">
                <h2 class="text-lg font-bold text-[#9e8356]">Admin Panel</h2>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-2xl text-[#9e8356]">☰</button>
            </div>

            <!-- Mobile Menu -->
            <div v-if="mobileMenuOpen" class="md:hidden bg-white px-4 py-2 space-y-2 border-b border-[#e7dbc6]">
                <ResponsiveNavLink asButton :active="activeView === 'dashboard'" @click="setView('dashboard')" href="#">
                    📊 Dashboard
                </ResponsiveNavLink>

                <ResponsiveNavLink asButton :active="activeView === 'services'" @click="setView('services')" href="#">
                    🛠️ Services beheren
                </ResponsiveNavLink>
                <ResponsiveNavLink asButton :active="activeView === 'appointments'" @click="setView('appointments')" href="#">
                    🗓️ Afspraken
                </ResponsiveNavLink>
                <ResponsiveNavLink asButton :active="activeView === 'blocks'" @click="setView('blocks')" href="#">
                    🛑 Blokkades beheren
                </ResponsiveNavLink>
                <ResponsiveNavLink asButton :active="activeView === 'users'" @click="setView('users')" href="#">
                    👤 Klanten
                </ResponsiveNavLink>
                <ResponsiveNavLink asButton :active="activeView === 'testimonials'" @click="setView('testimonials')" href="#">
                    💬 Reviews beheren
                </ResponsiveNavLink>
            </div>

            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-10">
                <component :is="currentComponent" />
            </main>
        </div>
    </AuthenticatedLayout>
</template>
