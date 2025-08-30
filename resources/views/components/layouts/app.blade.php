<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Page Title' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    {{--
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" /> --}}

    <link href="https://fonts.googleapis.com/css?family=Work+Sans:200,400&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif


</head>

{{-- <style>
    :root {
        --color-accent: var(--color-blue-500);
        --color-accent-content: var(--color-blue-600);
        --color-accent-foreground: var(--color-white);
    }

    .dark {
        --color-accent: var(--color-rose-500);
        --color-accent-content: var(--color-rose-400);
        --color-accent-foreground: var(--color-white);
    }
</style> --}}

<body>

    <livewire:components.navbar />

    <livewire:components.cart-slider />
    <livewire:components.favorite-slider />
    <livewire:components.profile-slider />

    {{ $slot }}

    <livewire:modals.sign-up />
    <livewire:modals.sign-in />
    <livewire:modals.forgot-password />

    <livewire:components.footer />



    @fluxScripts
</body>

</html>
