<!-- Sign Up Modal -->
<flux:modal name="signup-modal" size="md" wire:ignore.self>
    <div class="p-6 space-y-4">
        <h2 class="text-xl font-bold text-gray-800">Sign Up</h2>
        <p class="text-sm text-gray-600">Create an account to start shopping.</p>

        <!-- Success Message -->
        @if($signupSuccess)
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700">Account created successfully! Redirecting...</p>
            </div>
        @endif

        <!-- OTP Success Message -->
        @if($otpMessage)
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-blue-700">{{ $otpMessage }}</p>
            </div>
        @endif

        <!-- OTP Error Message -->
        @if($otpError)
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-700">{{ $otpError }}</p>
            </div>
        @endif

        <!-- Signup Error Message -->
        @if($signupError)
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-700">{{ $signupError }}</p>
            </div>
        @endif

        <form wire:submit="signup" class="space-y-4">
            <!-- Full Name -->
            <div>
                <flux:input 
                    wire:model="full_name"
                    label="Full Name" 
                    type="text" 
                    placeholder="Enter your name"
                    :disabled="$otpSent"
                />
                @error('full_name')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone Number with +88 prefix and Get OTP button -->
            <div class="space-y-1">
                <label class="text-sm font-medium text-gray-700">Phone Number</label>
                <div class="flex items-center gap-2">
                    <flux:input.group>
                        <flux:input.group.prefix>+88</flux:input.group.prefix>
                        <flux:input 
                            wire:model="phone_number"
                            type="tel" 
                            placeholder="01XXXXXXXXX" 
                            maxlength="11" 
                            name="phone_number"
                            :disabled="$otpSent"
                        />
                    </flux:input.group>
                    @if(!$otpSent)
                        <flux:button 
                            wire:click="sendOtp"
                            wire:loading.attr="disabled"
                            type="button"
                            variant="primary" 
                            color="yellow" 
                            class="shrink-0">
                            <span wire:loading.remove wire:target="sendOtp">Get OTP</span>
                            <span wire:loading wire:target="sendOtp">Sending...</span>
                        </flux:button>
                    @else
                        <flux:button 
                            wire:click="resendOtp"
                            wire:loading.attr="disabled"
                            type="button"
                            variant="ghost" 
                            class="shrink-0">
                            <span wire:loading.remove wire:target="resendOtp">Resend OTP</span>
                            <span wire:loading wire:target="resendOtp">Sending...</span>
                        </flux:button>
                    @endif
                </div>
                @error('phone_number')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <!-- OTP Input - Only show after OTP is sent -->
            @if($otpSent)
                <div>
                    <flux:input 
                        wire:model="otp"
                        label="OTP" 
                        type="text" 
                        maxlength="6" 
                        placeholder="Enter 6 digit OTP" 
                        clearable 
                    />
                    @error('otp')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">OTP is valid for 5 minutes</p>
                </div>

                <!-- Password -->
                <div>
                    <flux:input 
                        wire:model="password"
                        label="Password" 
                        type="password" 
                        placeholder="Create a password (min 6 characters)" 
                        viewable 
                    />
                    @error('password')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <flux:input 
                        wire:model="password_confirmation"
                        label="Confirm Password" 
                        type="password" 
                        placeholder="Confirm your password" 
                        viewable 
                    />
                    @error('password_confirmation')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit -->
                <flux:button 
                    type="submit"
                    variant="primary" 
                    class="w-full"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="signup">Sign Up</span>
                    <span wire:loading wire:target="signup">Creating Account...</span>
                </flux:button>
            @else
                <!-- Disabled Submit Button - Show when OTP not sent -->
                <flux:button 
                    type="button"
                    variant="primary" 
                    class="w-full opacity-50 cursor-not-allowed"
                    disabled>
                    Please Get OTP First
                </flux:button>
            @endif
        </form>

        <!-- Switch to Sign In -->
        <p class="text-sm text-gray-600 text-center">
            Already have an account?
            <button type="button" 
                @click="$flux.modal('signup-modal').close();$flux.modal('signin-modal').show();"
                class="text-indigo-600 hover:underline hover:cursor-pointer">
                Sign In
            </button>
        </p>
    </div>
</flux:modal>