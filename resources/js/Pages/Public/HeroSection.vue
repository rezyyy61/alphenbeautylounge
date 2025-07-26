<script setup>
const scrollToServices = () => {
    const target = document.getElementById('services')
    if (!target) return

    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset
    const startPosition = window.pageYOffset
    const distance = targetPosition - startPosition
    const duration = 1000
    let start = null

    const easeInOutCubic = (t) =>
        t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2

    const animateScroll = (timestamp) => {
        if (!start) start = timestamp
        const progress = timestamp - start
        const percentage = Math.min(progress / duration, 1)
        const eased = easeInOutCubic(percentage)

        window.scrollTo(0, startPosition + distance * eased)

        if (progress < duration) {
            requestAnimationFrame(animateScroll)
        }
    }

    requestAnimationFrame(animateScroll)
}

const isLocal = window.location.hostname === 'localhost'
const assetBase = isLocal ? window.assetUrl : `${window.assetUrl}public/`

const getAsset = (file = '') => `${assetBase}images/${file}`
const heroImage = getAsset('heroo.webp')
const Logo = getAsset('logo.webp')

</script>


<template>
    <section class="relative bg-[#f9f4ef] overflow-hidden">
        <!-- Golden Light Effect -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute w-[800px] h-[800px] -top-48 -left-48 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-[#9e835611] to-transparent"></div>
        </div>

        <!-- Logo Section with Floral Frame -->
        <div class="flex justify-center pt-8 relative z-10">
            <div class="relative bg-white/80 backdrop-blur-md rounded-full p-3 shadow-xl border-2 border-[#e8d9bd]">
                <img
                    :src="Logo"
                    alt="Alphen Beauty Lounge Logo"
                    decoding="async"
                    fetchpriority="high"
                    class="h-28 sm:h-36 md:h-48 object-contain"
                />

            </div>
        </div>

        <!-- Title Section -->
        <div class="text-center mt-4 relative z-10">
            <div class="inline-block pb-2 border-b-2 border-[#9e835633]">
                <h1 class="text-[#9e8356] font-serif text-4xl md:text-5xl italic tracking-wide">
                    ALPHEN BEAUTY LOUNGE
                </h1>
            </div>
            <p class="text-[#b08968] text-xs md:text-sm uppercase tracking-widest mt-3 font-medium">
                Waar schoonheid en rust samenkomen
            </p>
        </div>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto flex flex-col-reverse md:flex-row items-center justify-between px-6 pt-16 pb-24 gap-16">
            <!-- Text Content -->
            <div class="w-full md:w-1/2 text-center md:text-left space-y-8 relative">
                <!-- Decorative Swirl -->
                <div class="hidden md:block absolute -left-24 -top-16 w-48 opacity-10">
                    <svg viewBox="0 0 100 100" class="text-[#9e8356] fill-current">
                        <path d="M73.9 49.1c-4.3-9.7-14-16.3-25-16.3-15.5 0-28 12.5-28 28 0 3.8.8 7.4 2.1 10.7 2.4 6.1 6.9 11.2 12.6 14.4 9.6 5.4 21.4 4.4 30-2.5 6.3-5.1 10.3-12.7 10.3-21.2 0-7.4-3.2-14.1-8.3-18.7-2.8-2.5-6.1-4.4-9.7-5.4z"/>
                    </svg>
                </div>

                <div class="space-y-6">
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-serif italic text-[#9e8356] leading-tight tracking-wide drop-shadow-lg">
                        Luxe verzorging,<br/><span class="text-[#7a5d3d]">rust en uitstraling</span>
                    </h2>
                    <p class="text-[#4a4137] text-lg md:text-xl max-w-md mx-auto md:mx-0 leading-relaxed">
                        Jouw luxe beauty lounge in het hart van Alphen.
                        Stap binnen en straal.
                    </p>
                    <button
                        @click="scrollToServices"
                        class="group relative inline-flex items-center gap-3 bg-gradient-to-r from-[#9e8356] to-[#7a6847] hover:from-[#bfa477] hover:to-[#9e8356] text-white font-medium px-8 py-4 rounded-full text-sm transition-all shadow-lg hover:shadow-xl hover:scale-[1.02] duration-300"
                    >
                        ...
                        Maak een afspraak
                    </button>

                </div>
            </div>

            <!-- Image Container -->
            <div class="w-full md:w-1/2 relative">
                <div class="relative bg-white/40 backdrop-blur-xl p-3 rounded-[2.5rem] shadow-2xl border-2 border-[#e7dbc6] transform rotate-1 hover:rotate-0 transition-transform duration-300">
                    <div class="absolute inset-0 rounded-[2rem] border-2 border-white/30"></div>
                    <img
                        :src="heroImage"
                        alt="Beauty woman"
                        width="600"
                        height="800"
                        loading="lazy"
                        class="rounded-[1.8rem] object-cover w-full h-[500px] md:h-[600px] shadow-inner"
                    />

                    <!-- Decorative Corner -->
                    <div class="absolute -right-6 -top-6 w-24 h-24 opacity-20">
                        <svg viewBox="0 0 100 100" class="text-[#9e8356] fill-current">
                            <path d="M100 0H0v100C20 90 40 80 60 70 80 60 90 40 100 20V0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Curved Divider -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden transform translate-y-1">
            <svg viewBox="0 0 1440 120" class="fill-current text-[#f9f4ef]">
                <path d="M1440 0H0v61.68C195.63 96.82 400 120 720 120s524.37-23.18 720-58.32V0z"/>
            </svg>
        </div>
    </section>
</template>

<style scoped>
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

.drop-shadow-lg {
    text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.15);
}

.rotate-1 {
    transform: rotate(1deg);
}

.hover\:rotate-0:hover {
    transform: rotate(0deg);
}
</style>
