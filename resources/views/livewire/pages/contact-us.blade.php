<section class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    {{-- Hero Section --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 py-20">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>

        {{-- Animated Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 -left-4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl animate-blob">
            </div>
            <div
                class="absolute top-0 -right-4 w-72 h-72 bg-yellow-500 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-4000">
            </div>
        </div>

        <div class="relative container mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 animate-fade-in-up">
                Get in Touch
            </h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto animate-fade-in-up animation-delay-200">
                We're here to help and answer any questions you might have. We look forward to hearing from you!
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-16 max-w-7xl">
        {{-- Success Message --}}
        @if (session()->has('success'))
            <div
                class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg animate-slide-in-right">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Quick Contact Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            @if ($contact?->mobile_number)
                <div
                    class="group relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <div class="relative">
                        <div
                            class="w-14 h-14 bg-blue-100 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-white mb-2 transition-colors">Call Us
                        </h3>
                        <a href="tel:{{ $contact->mobile_number }}"
                            class="text-gray-600 group-hover:text-white/90 hover:underline transition-colors">
                            {{ $contact->mobile_number }}
                        </a>
                    </div>
                </div>
            @endif

            @if ($contact?->email)
                <div
                    class="group relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <div class="relative">
                        <div
                            class="w-14 h-14 bg-purple-100 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-7 h-7 text-purple-600 group-hover:text-white transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-white mb-2 transition-colors">Email Us
                        </h3>
                        <a href="mailto:{{ $contact->email }}"
                            class="text-gray-600 group-hover:text-white/90 hover:underline transition-colors break-all">
                            {{ $contact->email }}
                        </a>
                    </div>
                </div>
            @endif

            @if ($contact?->office_location)
                <div
                    class="group relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-green-600 to-green-700 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <div class="relative">
                        <div
                            class="w-14 h-14 bg-green-100 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-7 h-7 text-green-600 group-hover:text-white transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-white mb-2 transition-colors">Visit Us
                        </h3>
                        <p class="text-gray-600 group-hover:text-white/90 transition-colors">
                            {{ $contact->office_location }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - Social & Hours --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Social Media Links --}}
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <span class="w-8 h-1 bg-gradient-to-r from-blue-600 to-purple-600 mr-3 rounded-full"></span>
                        Connect With Us
                    </h3>

                    <div class="space-y-3">
                        @if ($contact?->facebook)
                            <a href="{{ $contact->facebook }}" target="_blank"
                                class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-300 group">
                                <div
                                    class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                                    </svg>
                                </div>
                                <span class="ml-4 font-medium text-gray-700 group-hover:text-gray-900">Facebook</span>
                                <svg class="w-5 h-5 ml-auto text-gray-400 group-hover:text-gray-600 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endif

                        @if ($contact?->instagram)
                            <a href="{{ $contact->instagram }}" target="_blank"
                                class="flex items-center p-4 bg-gradient-to-r from-pink-50 to-pink-100 rounded-xl hover:from-pink-100 hover:to-pink-200 transition-all duration-300 group">
                                <div
                                    class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                                    <svg class="w-6 h-6 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z" />
                                    </svg>
                                </div>
                                <span class="ml-4 font-medium text-gray-700 group-hover:text-gray-900">Instagram</span>
                                <svg class="w-5 h-5 ml-auto text-gray-400 group-hover:text-gray-600 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endif

                        @if ($contact?->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->whatsapp) }}"
                                target="_blank"
                                class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl hover:from-green-100 hover:to-green-200 transition-all duration-300 group">
                                <div
                                    class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                        <path
                                            d="M12 2C6.486 2 2 6.486 2 12c0 1.89.525 3.66 1.438 5.168L2.05 22l4.967-1.303A9.955 9.955 0 0012 22c5.514 0 10-4.486 10-10S17.514 2 12 2zm0 18.065a8.061 8.061 0 01-4.081-1.107l-.292-.173-2.722.714.726-2.652-.177-.298A8.031 8.031 0 013.933 12c0-4.449 3.619-8.065 8.067-8.065 4.448 0 8.065 3.616 8.065 8.065 0 4.449-3.617 8.065-8.065 8.065z" />
                                    </svg>
                                </div>
                                <span class="ml-4 font-medium text-gray-700 group-hover:text-gray-900">WhatsApp</span>
                                <svg class="w-5 h-5 ml-auto text-gray-400 group-hover:text-gray-600 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endif

                        @if ($contact?->messanger)
                            <a href="https://m.me/{{ $contact->messanger }}" target="_blank"
                                class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-300 group">
                                <div
                                    class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2C6.477 2 2 6.145 2 11.243c0 2.905 1.446 5.497 3.703 7.18V22l3.387-1.858c.904.251 1.862.386 2.854.386h.112C17.523 20.528 22 16.36 22 11.243 22 6.145 17.523 2 12 2zm1.178 14.892l-2.537-2.704-4.952 2.704 5.445-5.784 2.599 2.704 4.89-2.704-5.445 5.784z" />
                                    </svg>
                                </div>
                                <span class="ml-4 font-medium text-gray-700 group-hover:text-gray-900">Messenger</span>
                                <svg class="w-5 h-5 ml-auto text-gray-400 group-hover:text-gray-600 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Business Hours --}}
                <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl p-8 text-white shadow-xl">
                    <h3 class="text-2xl font-bold mb-6 flex items-center">
                        <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Business Hours
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-white/20">
                            <span class="text-white/80">Monday - Friday</span>
                            <span class="font-semibold bg-white/20 px-3 py-1 rounded-lg">9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-white/20">
                            <span class="text-white/80">Saturday</span>
                            <span class="font-semibold bg-white/20 px-3 py-1 rounded-lg">10:00 AM - 4:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/80">Sunday</span>
                            <span class="font-semibold bg-red-500/30 px-3 py-1 rounded-lg">Closed</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Map Section --}}
            <div class="lg:col-span-2">
                @if ($contact?->office_location)
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden h-full">
                        <div class="p-6 bg-gradient-to-r from-blue-600 to-purple-600">
                            <h3 class="text-2xl font-bold text-white flex items-center">
                                <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                                Find Us on Map
                            </h3>
                        </div>
                        <div class="h-96 bg-gradient-to-br from-gray-100 to-gray-200 relative">
                            {{-- Map placeholder with enhanced design --}}
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-500">
                                <div class="bg-white rounded-full p-6 shadow-lg mb-4">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <p class="text-lg font-medium mb-2">Interactive Map</p>
                                <p class="text-sm text-gray-400">Google Maps integration can be added here</p>

                                {{-- Address Card Overlay --}}
                                <div class="absolute bottom-4 left-4 right-4 bg-white rounded-xl p-4 shadow-lg">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-semibold text-gray-900">Office Location</p>
                                            <p class="text-sm text-gray-600">{{ $contact->office_location }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Alternative content when no map --}}
                    <div class="bg-white rounded-2xl shadow-xl p-12 h-full flex items-center justify-center">
                        <div class="text-center">
                            <div
                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">We're Here to Help</h3>
                            <p class="text-gray-600 max-w-md">
                                Contact us through any of the channels on this page. We typically respond within 24
                                hours during business days.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Additional Info Section --}}
        <div class="mt-16 bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-8 text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Need Immediate Assistance?</h3>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                For urgent matters, please call us directly during business hours. We're committed to providing you with
                the best support possible.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                @if ($contact?->mobile_number)
                    <a href="tel:{{ $contact->mobile_number }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Call Now
                    </a>
                @endif

                @if ($contact?->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->whatsapp) }}" target="_blank"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                        </svg>
                        WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-in-right {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out;
        }

        .animation-delay-200 {
            animation-delay: 0.2s;
            animation-fill-mode: both;
        }

        .animate-slide-in-right {
            animation: slide-in-right 0.5s ease-out;
        }
    </style>

</section>
