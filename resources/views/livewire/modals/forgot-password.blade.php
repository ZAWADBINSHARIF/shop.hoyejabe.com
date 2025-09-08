<!-- Forget Password Modal -->
<flux:modal name="forgot-password-modal" size="md" wire:ignore.self>
    <div class="p-6 space-y-4" x-data="{ 
        countdown: @js($countdown ?? 0),
        init() {
            this.$watch('countdown', (value) => {
                if (value > 0) {
                    setTimeout(() => {
                        if (this.countdown > 0) {
                            this.countdown--;
                            $wire.set('countdown', this.countdown);
                        }
                    }, 1000);
                }
            });
            
            Livewire.on('start-countdown', () => {
                this.countdown = 120;
            });
        }
    }">
        <h2 class="text-xl font-bold text-gray-800">Forgot Password</h2>
        <p class="text-sm text-gray-600">
            @if($step == 1)
                Enter your phone number and we'll send you a six digit OTP.
            @else
                Enter the OTP sent to your phone and create a new password.
            @endif
        </p>

        <!-- Success/Error Messages -->
        @if (session()->has('success'))
            <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="{{ $step == 1 ? 'sendOtp' : 'resetPassword' }}" class="space-y-4">
            <!-- Step 1: Phone Number -->
            @if($step == 1)
                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-700">Phone Number</label>
                    <flux:input.group>
                        <flux:input.group.prefix>+88</flux:input.group.prefix>
                        <flux:input 
                            wire:model="phone_number" 
                            type="tel" 
                            placeholder="01XXXXXXXXX" 
                            maxlength="11" 
                            name="phone_number" 
                            required
                        />
                    </flux:input.group>
                    @error('phone_number') 
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Send OTP Button -->
                <flux:button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>Send OTP</span>
                    <span wire:loading>Sending...</span>
                </flux:button>
            @endif

            <!-- Step 2: OTP and New Password -->
            @if($step == 2)
                <!-- Phone Number (readonly) -->
                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-700">Phone Number</label>
                    <flux:input.group>
                        <flux:input.group.prefix>+88</flux:input.group.prefix>
                        <flux:input 
                            wire:model="phone_number" 
                            type="tel" 
                            readonly
                            disabled
                            class="bg-gray-50"
                        />
                    </flux:input.group>
                </div>

                <!-- OTP -->
                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-700">OTP</label>
                    <div class="flex items-center gap-2">
                        <flux:input 
                            wire:model="otp" 
                            type="text" 
                            maxlength="6" 
                            placeholder="Enter 6 digit OTP" 
                            clearable
                            required
                            class="flex-1"
                        />
                        <flux:button 
                            type="button"
                            wire:click="resendOtp" 
                            variant="ghost" 
                            size="sm"
                            x-bind:disabled="countdown > 0"
                            wire:loading.attr="disabled"
                        >
                            <span x-show="countdown > 0">
                                Resend in <span x-text="countdown"></span>s
                            </span>
                            <span x-show="countdown <= 0" wire:loading.remove>Resend OTP</span>
                            <span wire:loading wire:target="resendOtp">Sending...</span>
                        </flux:button>
                    </div>
                    @error('otp') 
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-700">New Password</label>
                    <flux:input 
                        wire:model="password" 
                        type="password" 
                        placeholder="Create a new password" 
                        viewable
                        required
                    />
                    @error('password') 
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-700">Confirm Password</label>
                    <flux:input 
                        wire:model="password_confirmation" 
                        type="password" 
                        placeholder="Confirm your password" 
                        viewable
                        required
                    />
                </div>

                <!-- Reset Password Button -->
                <flux:button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>Reset Password</span>
                    <span wire:loading>Resetting...</span>
                </flux:button>

                <!-- Back to Phone Number -->
                <flux:button 
                    type="button"
                    wire:click="$set('step', 1)" 
                    variant="ghost" 
                    class="w-full"
                >
                    Change Phone Number
                </flux:button>
            @endif
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
