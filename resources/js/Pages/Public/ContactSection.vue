<template>
    <section class="bg-[#fdf9f3] py-24 px-6 md:px-12" id="contact">
        <header class="max-w-5xl mx-auto text-center mb-16">
            <h2 class="text-4xl font-serif text-[#9e8356] mb-4">Neem contact met ons op</h2>
            <p class="text-gray-600 text-lg">
                Heb je vragen of wil je iets bespreken? We horen graag van je! 💌
            </p>
        </header>

        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 mb-14 items-start">
            <address class="space-y-6 not-italic text-[#4a4137] text-[16px] leading-relaxed">
                <div class="flex items-start gap-4 bg-[#f9f5ef] border border-[#e7dbc6] rounded-xl p-4 shadow-sm">
                    <div class="text-2xl text-[#9e8356]">📞</div>
                    <div>
                        <p class="font-semibold text-[#9e8356]">Telefoon</p>
                        <a href="tel:0624674553" class="hover:underline text-[#4a4137]">(06) 24 67 45 53</a>
                    </div>
                </div>

                <div class="flex items-start gap-4 bg-[#f9f5ef] border border-[#e7dbc6] rounded-xl p-4 shadow-sm">
                    <div class="text-2xl text-[#9e8356]">📧</div>
                    <div>
                        <p class="font-semibold text-[#9e8356]">E-mail</p>
                        <a href="mailto:Alphen.beauty.lounge@gmail.com" class="hover:underline text-[#4a4137]">Alphen.beauty.lounge@gmail.com</a>
                    </div>
                </div>

                <div class="flex items-start gap-4 bg-[#f9f5ef] border border-[#e7dbc6] rounded-xl p-4 shadow-sm">
                    <div class="text-2xl text-[#9e8356]">🕒</div>
                    <div>
                        <p class="font-semibold text-[#9e8356]">Openingstijden</p>
                        <ul class="mt-2 space-y-1 text-sm text-gray-600">
                            <li>Maandag: 15:30 – 18:00</li>
                            <li>Dinsdag: 15:30 – 18:00</li>
                            <li>Woensdag: 15:30 – 18:00</li>
                            <li>Donderdag: 09:00 – 18:00</li>
                            <li>Vrijdag: 09:00 – 19:00</li>
                            <li>Zaterdag: 08:30 – 16:00</li>
                            <li>Zondag: Gesloten</li>
                        </ul>
                    </div>
                </div>
            </address>

            <div class="space-y-6">
                <div class="flex items-start gap-4 bg-[#f9f5ef] border border-[#e7dbc6] rounded-xl p-4 shadow-sm">
                    <div class="text-2xl text-[#9e8356]">📍</div>
                    <div>
                        <p class="font-semibold text-[#9e8356]">Adres</p>
                        <p>Ouvertureweg 135<br />2402 DX Alphen aan den Rijn</p>
                    </div>
                </div>

                <iframe
                    title="Alphen Beauty Lounge locatie op de kaart"
                    class="w-full h-[320px] rounded-2xl border border-[#e7dbc6] shadow"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2443.6830145017296!2d4.660251076882525!3d52.12700517195364!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5d01cdd0b8579%3A0x61f61e6e2fd90bcf!2sOuvertureweg%20135%2C%202402%20DX%20Alphen%20aan%20den%20Rijn%2C%20Netherlands!5e0!3m2!1sen!2snl!4v1713000000000!5m2!1sen!2snl"
                ></iframe>
            </div>
        </div>

        <div class="max-w-3xl mx-auto bg-[#fefcf9] p-10 rounded-[2rem] border border-[#e7dbc6] shadow-2xl mt-16 relative overflow-hidden">
            <div class="absolute -top-24 -left-20 w-[300px] h-[300px] rounded-full bg-gradient-to-br from-[#9e835622] to-transparent blur-3xl opacity-50"></div>

            <h3 class="text-3xl font-serif text-[#9e8356] mb-10 text-center z-10 relative">Stuur ons een bericht ✉️</h3>

            <form @submit.prevent="submitContact" class="grid gap-6 z-10 relative" aria-label="Contactformulier">
                <div class="relative">
                    <label for="name" class="sr-only">Naam</label>
                    <span class="absolute left-4 top-3 text-xl text-[#9e8356]">👤</span>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        placeholder="Naam"
                        required
                        class="w-full pl-12 pr-4 py-3 rounded-xl bg-white border border-[#e7dbc6] text-[#4a4137] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9e8356]/30 shadow-sm"
                    />
                </div>

                <div class="relative">
                    <label for="email" class="sr-only">E-mail</label>
                    <span class="absolute left-4 top-3 text-xl text-[#9e8356]">📧</span>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="E-mail"
                        required
                        class="w-full pl-12 pr-4 py-3 rounded-xl bg-white border border-[#e7dbc6] text-[#4a4137] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9e8356]/30 shadow-sm"
                    />
                </div>

                <div class="relative">
                    <label for="message" class="sr-only">Bericht</label>
                    <span class="absolute right-4 top-4 text-sm text-[#9e8356] italic">✍️</span>
                    <textarea
                        id="message"
                        v-model="form.message"
                        placeholder="Je bericht..."
                        rows="6"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-white border border-[#e7dbc6] text-[#4a4137] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9e8356]/30 shadow-sm resize-y"
                    ></textarea>
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-[#9e8356] to-[#7a6847] text-white py-3 rounded-xl hover:from-[#bfa477] hover:to-[#9e8356] transition-all shadow-md text-lg font-semibold"
                    >
                        Versturen
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>

<script setup>
import { reactive } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

const form = reactive({
    name: '',
    email: '',
    message: ''
})

const submitContact = async () => {
    if (form.name && form.email && form.message) {
        try {
            await axios.post('/api/contact', form)

            await Swal.fire({
                icon: 'success',
                title: 'Bedankt!',
                text: 'Je bericht is succesvol verzonden.',
                confirmButtonColor: '#9e8356',
            })

            form.name = ''
            form.email = ''
            form.message = ''
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Er is iets misgegaan. Probeer het later opnieuw.',
                confirmButtonColor: '#9e8356',
            })
        }
    }
}
</script>
