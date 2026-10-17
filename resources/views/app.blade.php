<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <title inertia>{{ config('app.name', 'GENREVERSE') }}</title>
        <meta name="description" content="GENREVERSE: The Digital Playground for GenRe Youth. Edukasi, LifeQuest interaktif, advokasi, dan komunitas remaja Duta GenRe Indonesia.">

        <!-- PWA Meta & Manifest -->
        <link rel="manifest" href="/manifest.webmanifest">
        <meta name="theme-color" content="#174D3A">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="GENREVERSE">

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead

        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js').catch(console.error);
                });
            }
        </script>
    </head>
    <body class="font-sans antialiased bg-[#F7F8F3] text-gray-900 selection:bg-[#F4C542] selection:text-[#174D3A] min-h-screen">
        @inertia
    </body>
</html>
