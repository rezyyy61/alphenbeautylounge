<template>
    <footer class="bg-[#eee3d1] text-[#4a4137] py-16 px-6 md:px-12" aria-label="Website footer">
        <div class="max-w-6xl mx-auto grid md:grid-cols-4 gap-10 mb-10">
            <!-- Logo & Beschrijving -->
            <section aria-labelledby="footer-brand">
                <h3 id="footer-brand" class="text-2xl font-serif text-[#9e8356] mb-4">Alphen Beauty</h3>
                <p class="text-sm leading-relaxed text-gray-700">
                    Waar schoonheid en ontspanning samenkomen. Laat jezelf verwennen door onze professionals.
                </p>
            </section>

            <!-- Quick Links -->
            <nav aria-labelledby="footer-nav">
                <h4 id="footer-nav" class="font-semibold text-[#9e8356] mb-3">Navigatie</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#hero" class="hover:text-[#bfa477]">Home</a></li>
                    <li><a href="#services" class="hover:text-[#bfa477]">Diensten</a></li>
                    <li><a href="#about" class="hover:text-[#bfa477]">Over ons</a></li>
                    <li><a href="#booking" class="hover:text-[#bfa477]">Boek nu</a></li>
                    <li><a href="#testimonials" class="hover:text-[#bfa477]">Reviews</a></li>
                    <li><a href="#contact" class="hover:text-[#bfa477]">Contact</a></li>
                </ul>
            </nav>

            <!-- Contactgegevens -->
            <section aria-labelledby="footer-contact">
                <h4 id="footer-contact" class="font-semibold text-[#9e8356] mb-3">Contact</h4>
                <ul class="space-y-2 text-sm">
                    <li><span aria-hidden="true">📍</span> Ouvertureweg 135, 2402 DX</li>
                    <li><span aria-hidden="true">📞</span> (06) 24 67 45 53</li>
                    <li><span aria-hidden="true">📧</span> alphen.beauty.lounge@gmail.com</li>
                </ul>
            </section>

            <!-- Openingstijden -->
            <section aria-labelledby="footer-hours">
                <h4 id="footer-hours" class="font-semibold text-[#9e8356] mb-3">Openingstijden</h4>
                <ul class="text-sm space-y-1 text-gray-700">
                    <li>Maandag: 15:30 – 18:00</li>
                    <li>Dinsdag: 15:30 – 18:00</li>
                    <li>Woensdag: 15:30 – 18:00</li>
                    <li>Donderdag: 09:00 – 18:00</li>
                    <li>Vrijdag: 09:00 – 19:00</li>
                    <li>Zaterdag: 08:30 – 16:00</li>
                    <li>Zondag: Gesloten</li>
                </ul>
            </section>


        </div>

        <div class="border-t border-[#e4d4be] pt-6 flex flex-col md:flex-row items-center justify-between text-sm text-gray-600">
            <p>© 2025 Alphen Beauty Lounge – Alle rechten voorbehouden.</p>
            <div class="flex gap-4 mt-3 md:mt-0">
                <!-- Instagram -->
                <a href="https://instagram.com/alphen_beauty_lounge" target="_blank" aria-label="Instagram" class="hover:opacity-80 transition">
                    <svg class="w-6 h-6 text-[#9e8356] hover:text-[#bfa477]" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm0 1.5A4.25 4.25 0 003.5 7.75v8.5A4.25 4.25 0 007.75 20.5h8.5a4.25 4.25 0 004.25-4.25v-8.5A4.25 4.25 0 0016.25 3.5h-8.5zM12 7a5 5 0 110 10 5 5 0 010-10zm0 1.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zm5.25-.75a.75.75 0 110 1.5.75.75 0 010-1.5z"
                        />
                    </svg>
                </a>

                <!-- WhatsApp -->
                <a href="https://wa.me/31624674553" target="_blank" aria-label="WhatsApp" class="hover:opacity-80 transition">
                    <svg class="w-6 h-6 text-[#9e8356] hover:text-[#bfa477]" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12.004 2.001a9.994 9.994 0 00-8.663 14.89L2 22l5.222-1.34A9.998 9.998 0 1012.004 2zm0 1.65a8.35 8.35 0 017.307 12.65l-.237.375.665 3.305-3.292-.877-.386.225a8.35 8.35 0 11-4.057-15.678zm4.479 11.142c-.186-.093-1.1-.543-1.269-.604-.17-.062-.293-.093-.417.093-.124.186-.479.603-.587.726-.108.124-.217.14-.403.047a6.869 6.869 0 01-2.032-1.253 7.644 7.644 0 01-1.403-1.74c-.147-.252-.015-.387.11-.512.113-.113.252-.293.373-.44.124-.147.165-.248.248-.413.083-.165.041-.31-.02-.434-.062-.124-.548-1.33-.752-1.821-.197-.47-.396-.406-.547-.414l-.46-.008a.89.89 0 00-.648.31c-.217.248-.826.807-.826 1.97s.846 2.287.964 2.445c.124.165 1.662 2.541 4.028 3.56.563.242 1.003.386 1.345.495a3.212 3.212 0 002.297-.146c.703-.297 1.155-1.046 1.303-1.4.147-.356.147-.663.103-.726-.041-.062-.165-.103-.352-.196z"
                        />
                    </svg>
                </a>
            </div>
        </div>
    </footer>
</template>

<script setup>
</script>
