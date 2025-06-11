<section class="bg-white py-12">
    <div class="container mx-auto px-4 max-w-4xl">

        <div class="prose text-gray-500 leading-relaxed">{!! str($companyDetails->about)->sanitizeHtml() !!}</div>

        <div class="mt-12">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Contact Us</h2>
            <div class="space-y-6">
                @if ($contact->mobile_number)
                    <div class="flex items-center gap-4">
                        <flux:icon.phone class="size-6" />
                        <a href="tel:{{ $contact->mobile_number }}" href="tel:{{ $contact->mobile_number }}"
                            class="text-gray-700">{{ $contact->mobile_number }}</a>
                    </div>
                @endif

                @if ($contact->facebook)
                    <div class="flex items-center gap-4">
                        <flux:icon.facebook class="size-6" />
                        <a href="{{ $contact->facebook }}" class="text-gray-700">{{ $contact->facebook }}</a>
                    </div>
                @endif

                @if ($contact->whatsapp)
                    <div class="flex items-center gap-4">
                        <flux:icon.message-circle class="size-6" />
                        <a href="https://{{ $contact->whatsapp }}" class="text-gray-700">{{ $contact->whatsapp }}</a>
                    </div>
                @endif

                @if ($contact->messanger)
                    <div class="flex items-center gap-4">
                        <flux:icon.chat-bubble-bottom-center class="size-6" />
                        <a href="https://{{ $contact->messanger }}" class="text-gray-700">{{ $contact->messanger }}</a>
                    </div>
                @endif

                @if ($contact->instagram)
                    <div class="flex items-center gap-4">
                        <flux:icon.instagram class="size-6" />
                        <a href="{{ $contact->instagram }}" class="text-gray-700">{{ $contact->instagram }}</a>
                    </div>
                @endif

                @if ($contact->email)
                    <div class="flex items-center gap-4">
                        <flux:icon.envelope class="size-6" />
                        <a href="mailto:{{ $contact->email }}" class="text-gray-700">{{ $contact->email }}</a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>
