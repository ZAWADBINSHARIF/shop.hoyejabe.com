 <!-- Sign In Modal -->
    <flux:modal name="signin-modal" size="md">
        <div class="p-6 space-y-4">
            <h2 class="text-xl font-bold text-gray-800">Sign In</h2>
            <p class="text-sm text-gray-600">Welcome back! Please enter your details.</p>

            <form class="space-y-4">
                <flux:input label="Email" type="email" placeholder="Enter your email" />
                <flux:input label="Password" type="password" placeholder="Enter your password" />

                <div class="flex items-center justify-between">
                    <flux:checkbox label="Remember me" />
                    <a href="#" class="text-sm text-indigo-600 hover:underline">Forgot password?</a>
                </div>

                <flux:button variant="primary" class="w-full">
                    Sign In
                </flux:button>
            </form>

            <p class="text-sm text-gray-600 text-center">
                Don’t have an account?
                <button type="button"
                    onclick="$dispatch('open-modal', { id: 'signup-modal' }); $dispatch('close-modal', { id: 'signin-modal' });"
                    class="text-indigo-600 hover:underline">
                    Sign Up
                </button>
            </p>
        </div>
    </flux:modal>
