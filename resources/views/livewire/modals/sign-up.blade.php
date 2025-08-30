<!-- Sign Up Modal -->
<flux:modal name="signup-modal" size="md">
    <div class="p-6 space-y-4">
        <h2 class="text-xl font-bold text-gray-800">Sign Up</h2>
        <p class="text-sm text-gray-600">Create an account to start reviewing products.</p>

        <form class="space-y-4">
            <!-- Full Name -->
            <flux:input label="Full Name" type="text" placeholder="Enter your name" />

            <!-- Phone Number with +88 prefix and Get OTP button -->
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
                </div>
            </div>

            <!-- OTP Input -->
            <flux:input label="OTP" type="text" maxlength="6" placeholder="Enter 6 digit OTP" clearable />

            <!-- Password -->
            <flux:input label="Password" type="password" placeholder="Create a password" viewable />

            <!-- Confirm Password -->
            <flux:input label="Confirm Password" type="password" placeholder="Confirm your password" viewable />

            <!-- Submit -->
            <flux:button variant="primary" class="w-full">
                Sign Up
            </flux:button>
        </form>

        <!-- Switch to Sign In -->
        <p class="text-sm text-gray-600 text-center">
            Already have an account?
            <button type="button" @click="$flux.modal('signup-modal').close();$flux.modal('signin-modal').show();"
                class="text-indigo-600 hover:underline hover:cursor-pointer">
                Sign In
            </button>
        </p>
    </div>
</flux:modal>
