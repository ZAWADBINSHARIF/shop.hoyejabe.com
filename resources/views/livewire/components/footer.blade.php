<footer class="bg-gradient-to-b from-gray-50 to-white border-t border-gray-100 mt-5" {!! $attributes ?? '' !!}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Main Footer Content --}}
        <div class="py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                {{-- Company Info --}}
                <div class="space-y-4">
                    <div class="flex items-center">
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                            {{ $companyDetails->name ?? 'Store' }}
                        </h3>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $companyDetails->description ?? 'Your trusted online shopping destination for quality products and excellent service.' }}
                    </p>
                    {{-- Social Media Icons --}}
                    <div class="flex space-x-4 pt-2">
                        @if ($contact?->facebook)
                            <a href="{{ $contact?->facebook }}" 
                               class="group relative p-2 bg-gray-100 rounded-lg hover:bg-blue-500 transition-all duration-300 hover:scale-110">
                                <span class="sr-only">Facebook</span>
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                                </svg>
                            </a>
                        @endif

                        @if ($contact?->whatsapp)
                            <a href="https://{{ $contact?->whatsapp }}"
                               class="group relative p-2 bg-gray-100 rounded-lg hover:bg-green-500 transition-all duration-300 hover:scale-110">
                                <span class="sr-only">WhatsApp</span>
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    <path d="M12 2C6.486 2 2 6.486 2 12c0 1.89.525 3.66 1.438 5.168L2.05 22l4.967-1.303A9.955 9.955 0 0012 22c5.514 0 10-4.486 10-10S17.514 2 12 2zm0 18.065a8.061 8.061 0 01-4.081-1.107l-.292-.173-2.722.714.726-2.652-.177-.298A8.031 8.031 0 013.933 12c0-4.449 3.619-8.065 8.067-8.065 4.448 0 8.065 3.616 8.065 8.065 0 4.449-3.617 8.065-8.065 8.065z"/>
                                </svg>
                            </a>
                        @endif

                        @if ($contact?->messanger)
                            <a href="https://{{ $contact?->messanger }}"
                               class="group relative p-2 bg-gray-100 rounded-lg hover:bg-blue-600 transition-all duration-300 hover:scale-110">
                                <span class="sr-only">Messenger</span>
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.905 1.446 5.497 3.703 7.18V22l3.387-1.858c.904.251 1.862.386 2.854.386h.112C17.523 20.528 22 16.36 22 11.243 22 6.145 17.523 2 12 2zm1.178 14.892l-2.537-2.704-4.952 2.704 5.445-5.784 2.599 2.704 4.89-2.704-5.445 5.784z"/>
                                </svg>
                            </a>
                        @endif

                        @if ($contact?->instagram)
                            <a href="{{ $contact?->instagram }}" 
                               class="group relative p-2 bg-gray-100 rounded-lg hover:bg-gradient-to-br hover:from-purple-600 hover:to-pink-500 transition-all duration-300 hover:scale-110">
                                <span class="sr-only">Instagram</span>
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Quick Links</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="/" class="text-gray-600 hover:text-gray-900 transition-colors duration-200 flex items-center group">
                                <span class="w-1 h-1 bg-gray-400 rounded-full mr-2 group-hover:w-4 transition-all duration-200"></span>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="/shop" class="text-gray-600 hover:text-gray-900 transition-colors duration-200 flex items-center group">
                                <span class="w-1 h-1 bg-gray-400 rounded-full mr-2 group-hover:w-4 transition-all duration-200"></span>
                                Shop
                            </a>
                        </li>
                        <li>
                            <a href="/about" class="text-gray-600 hover:text-gray-900 transition-colors duration-200 flex items-center group">
                                <span class="w-1 h-1 bg-gray-400 rounded-full mr-2 group-hover:w-4 transition-all duration-200"></span>
                                About Us
                            </a>
                        </li>
                        <li>
                            <a href="/contact-us" class="text-gray-600 hover:text-gray-900 transition-colors duration-200 flex items-center group">
                                <span class="w-1 h-1 bg-gray-400 rounded-full mr-2 group-hover:w-4 transition-all duration-200"></span>
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Customer Service --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Customer Service</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="/track-order" class="text-gray-600 hover:text-gray-900 transition-colors duration-200 flex items-center group">
                                <span class="w-1 h-1 bg-gray-400 rounded-full mr-2 group-hover:w-4 transition-all duration-200"></span>
                                Track Order
                            </a>
                        </li>
                        <li>
                            <a href="/my-order" class="text-gray-600 hover:text-gray-900 transition-colors duration-200 flex items-center group">
                                <span class="w-1 h-1 bg-gray-400 rounded-full mr-2 group-hover:w-4 transition-all duration-200"></span>
                                My Orders
                            </a>
                        </li>
                        <li>
                            <a href="/returns" class="text-gray-600 hover:text-gray-900 transition-colors duration-200 flex items-center group">
                                <span class="w-1 h-1 bg-gray-400 rounded-full mr-2 group-hover:w-4 transition-all duration-200"></span>
                                Returns & Refunds
                            </a>
                        </li>
                        <li>
                            <a href="/faq" class="text-gray-600 hover:text-gray-900 transition-colors duration-200 flex items-center group">
                                <span class="w-1 h-1 bg-gray-400 rounded-full mr-2 group-hover:w-4 transition-all duration-200"></span>
                                FAQs
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Contact Info --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Get in Touch</h4>
                    <div class="space-y-3">
                        @if($contact?->phone1 || $contact?->phone2)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <div class="text-gray-600 text-sm">
                                    @if($contact?->phone1)
                                        <p>{{ $contact->phone1 }}</p>
                                    @endif
                                    @if($contact?->phone2)
                                        <p>{{ $contact->phone2 }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($contact?->email)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:{{ $contact->email }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors">
                                    {{ $contact->email }}
                                </a>
                            </div>
                        @endif

                        @if($contact?->address)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-gray-600 text-sm">{{ $contact->address }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Newsletter Section --}}
        {{-- <div class="border-t border-gray-200 py-8">
            <div class="max-w-md mx-auto text-center lg:max-w-none lg:flex lg:items-center lg:justify-between">
                <div class="lg:text-left">
                    <h3 class="text-lg font-semibold text-gray-900">Stay Updated</h3>
                    <p class="text-sm text-gray-600 mt-1">Get exclusive offers and latest product updates</p>
                </div>
                <form class="mt-4 lg:mt-0 lg:ml-8 flex flex-col sm:flex-row gap-2 max-w-md w-full lg:w-auto">
                    <input type="email" 
                           placeholder="Enter your email" 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent text-sm">
                    <button type="submit" 
                            class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors duration-200 text-sm font-medium">
                        Subscribe
                    </button>
                </form>
            </div>
        </div> --}}

        {{-- Bottom Bar --}}
        <div class="border-t border-gray-200 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0">
                <p class="text-xs text-gray-500">
                    © {{ date('Y') }} {{ $companyDetails->name ?? 'Store' }}. All rights reserved.
                </p>
                <div class="flex space-x-6">
                    <a href="/privacy" class="text-xs text-gray-500 hover:text-gray-700 transition-colors">Privacy Policy</a>
                    <a href="/terms" class="text-xs text-gray-500 hover:text-gray-700 transition-colors">Terms of Service</a>
                    <a href="/shipping" class="text-xs text-gray-500 hover:text-gray-700 transition-colors">Shipping Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>
