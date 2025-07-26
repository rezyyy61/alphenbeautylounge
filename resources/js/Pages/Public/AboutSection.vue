<template>
    <section id="overons" class="bg-gradient-to-b from-[#fdf9f3] to-[#f9f5ef] py-24 px-6 md:px-12">
        <header class="max-w-5xl mx-auto text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-serif text-[#9e8356] mb-6">Over ons</h2>
            <p class="text-[#4a4137] text-lg md:text-xl leading-relaxed">
                In een wereld vol haast, is onze salon jouw zachte pauze.
                Bij <strong>Alphen Beauty Lounge</strong>, jouw <strong>kapper in Alphen aan den Rijn</strong>,
                creëren wij momenten van rust, schoonheid en verzorging – speciaal voor jou.
                Onze <strong>beautysalon</strong> biedt professionele behandelingen zoals <strong>make-up</strong>,
                <strong>epileren</strong>, <strong>knippen</strong> en meer.
                Kom binnen, voel je welkom, en laat je stralen.
            </p>
        </header>

        <figure class="w-full max-w-6xl mx-auto mb-12">
            <img
                :src="getImage('salon.webp')"
                alt="Binnenzicht van onze beautysalon in Alphen aan den Rijn"
                width="1280"
                height="720"
                loading="lazy"
                class="w-full rounded-3xl border border-[#e7dbc6] shadow-md object-cover aspect-video"
            />
        </figure>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-6xl mx-auto">
            <figure v-for="i in 4" :key="i">
                <img
                    :src="getImage('salon.webp')"
                    :alt="`Impressie salon ${i}`"
                    width="480"
                    height="360"
                    loading="lazy"
                    class="aspect-[4/3] rounded-xl border border-[#e7dbc6] object-cover hover:scale-105 hover:shadow-lg transition duration-300"
                />
            </figure>
        </div>
    </section>
</template>

<script setup>
const isLocal = window.location.hostname === 'localhost'
const assetBase = isLocal ? window.assetUrl : `${window.assetUrl}public/`
const getImage = (file = '') => `${assetBase}images/${file || 'default.jpeg'}`
</script>
