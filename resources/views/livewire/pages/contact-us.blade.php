<section class="bg-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Contact Us</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Contact Details -->
            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <flux:icon.envelope class="size-6" />
                    <span class="text-gray-700">example@email.com</span>
                </div>

                <div class="flex items-center gap-4">
                    <flux:icon.phone class="size-6" />
                    <span class="text-gray-700">+880 1234 567 890</span>
                </div>

                <div class="flex items-center gap-4">
                    <flux:icon.facebook class="size-6" />
                    <span class="text-gray-700">facebook.com/yourpage</span>
                </div>

                <div class="flex items-center gap-4">
                    <flux:icon.instagram class="size-6" />
                    <span class="text-gray-700">@yourinstagram</span>
                </div>

                <div class="flex items-center gap-4">
                    <flux:icon.message-circle class="size-6" />
                    <span class="text-gray-700">Whatsapp</span>
                </div>

                <div class="flex items-center gap-4">
                    <flux:icon.chat-bubble-bottom-center class="size-6" />
                    <span class="text-gray-700">Messanger</span>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <form class="space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-1" for="name">Name</label>
                        <input type="text" id="name"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2" />
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1" for="email">Email</label>
                        <input type="email" id="email"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2" />
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1" for="message">Message</label>
                        <textarea id="message" rows="4"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2"></textarea>
                    </div>
                    <flux:button variant="primary" type="submit">Send Message</flux:button>
                </form>
            </div>

        </div>
    </div>
    </section>