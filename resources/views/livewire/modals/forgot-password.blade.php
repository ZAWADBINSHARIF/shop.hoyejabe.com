<!-- Forget Password Modal -->
<flux:modal name="forgot-password-modal" size="md">
    <div class="p-6 space-y-4">
        <h2 class="text-xl font-bold text-gray-800">Forgot Password</h2>
        <p class="text-sm text-gray-600">
            Enter your phone number and we’ll send you a six digit OTP.
        </p>

        <form class="space-y-4">
            <!-- Email / Phone -->
            <div class="space-y-1">
                <label class="text-sm font-medium text-gray-700">Phone Number</label>
                <div class="flex items-center gap-2">
                    <flux:input.group>
                        <flux:input.group.prefix>+88</flux:input.group.prefix>
                        <flux:input type="tel" placeholder="01XXXXXXXXX" maxlength="11" name="phone_number" />
                    </flux:input.group>
                    <flux:button variant="primary" color="yellow" class="shrink-0">
                        Get OTP
                    </flux:button>
                    <!-- OTP Input -->
                </div>
            </div>

            <label class="text-sm font-medium text-gray-700">OTP</label>
            <flux:input type="text" maxlength="6" placeholder="Enter 6 digit OTP" clearable />

            <!-- Password -->
            <label class="text-sm font-medium text-gray-700">Password</label>
            <flux:input type="password" placeholder="Create a password" viewable />

            <!-- Confirm Password -->
            <label class="text-sm font-medium text-gray-700">Confirm Password</label>
            <flux:input type="password" placeholder="Confirm your password" viewable />

            <!-- Send Reset Button -->
            <flux:button variant="primary" class="w-full">
                Reset password
            </flux:button>
        </form>

        <!-- Back to Sign In -->
        <p class="text-sm text-gray-600 text-center">
            Remember your password?
            <button type="button"
                @click="$flux.modal('forgot-password-modal').close();$flux.modal('signin-modal').show();"
                class="text-indigo-600 hover:underline">
                Sign In
            </button>
        </p>
    </div>
</flux:modal>
