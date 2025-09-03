<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title inertia>Alphen Beauty Lounge – Kapper & Beautysalon</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Professionele kapper en beautysalon in Alphen aan den Rijn. Wij bieden knippen, kleuren, epileren en make-up behandelingen. Boek direct!">
    <meta name="keywords" content="kapper, beautysalon, Alphen aan den Rijn, epileren, make-up, knippen, kleuren">
    <meta name="robots" content="index, follow">

    <!-- Open Graph (Facebook, WhatsApp, LinkedIn) -->
    <meta property="og:title" content="Alphen Beauty Lounge – Kapper & Beautysalon">
    <meta property="og:description" content="Professionele behandelingen zoals knippen, kleuren, epileren en make-up in Alphen aan den Rijn.">
    <meta property="og:image" content="https://alphenbeautylounge.nl/images/salon.webp">
    <meta property="og:url" content="https://alphenbeautylounge.nl">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Alphen Beauty Lounge – Kapper & Beautysalon">
    <meta name="twitter:description" content="Professionele behandelingen in Alphen aan den Rijn – knippen, kleuren, make-up, epileren en meer.">
    <meta name="twitter:image" content="https://alphenbeautylounge.nl/images/salon.webp">

    <!-- Favicon -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Laravel + Inertia Scripts -->
    @routes
    @vite('resources/js/app.js')
    @inertiaHead

    <!-- Asset base URL -->
    <script>
        window.assetUrl = "{{ asset('') }}";
    </script>
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "BeautySalon",
          "name": "Alphen Beauty Lounge",
          "image": "https://alphenbeautylounge.nl/images/logo.webp",
          "url": "https://alphenbeautylounge.nl",
          "telephone": "+31624674553",
          "email": "alphen.beauty.lounge@gmail.com",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "Ouvertureweg 135",
            "addressLocality": "Alphen aan den Rijn",
            "postalCode": "2402 DX",
            "addressCountry": "NL"
          },
          "openingHoursSpecification": [
            {
              "@type": "OpeningHoursSpecification",
              "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday"],
              "opens": "09:00",
              "closes": "17:00"
            },
            {
              "@type": "OpeningHoursSpecification",
              "dayOfWeek": "Friday",
              "opens": "09:00",
              "closes": "19:00"
            },
            {
              "@type": "OpeningHoursSpecification",
              "dayOfWeek": "Saturday",
              "opens": "08:30",
              "closes": "16:00"
            }
          ],
          "sameAs": [
            "https://instagram.com",
            "https://wa.me/31624674553"
          ]
        }
    </script>

</head>

    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
