 <!-- Sign In Modal -->
 <flux:modal name="signin-modal" size="md" wire:ignore.self>
     <div class="p-6 space-y-4">
         <h2 class="text-xl font-bold text-gray-800">Sign In</h2>
         <p class="text-sm text-gray-600">Welcome back! Please enter your details.</p>

         <form wire:submit="login" class="space-y-4">
             <!-- Error Message -->
             @if ($loginError)
                 <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                     <div class="flex items-center gap-2 text-red-700">
                         <flux:icon.exclamation-circle class="size-5" />
                         <span class="text-sm font-medium">{{ $loginError }}</span>
                     </div>
                 </div>
             @endif

             <flux:input wire:model="phone_number" label="Phone Number" type="tel" placeholder="01XXXXXXXXX"
                 maxlength="11" name="phone_number" :error="$errors->first('phone_number')" />

             <flux:input wire:model="password" label="Password" type="password" placeholder="Enter your password"
                 :error="$errors->first('password')" />

             <div class="flex items-center justify-between">
                 <flux:checkbox wire:model="remember" label="Remember me" />
                 <button type="button"
                     @click="$flux.modal('forgot-password-modal').show(); $flux.modal('signin-modal').close()"
                     class="text-sm text-indigo-600 hover:underline">Forgot password?</button>
             </div>

             <flux:button icon="arrow-right-end-on-rectangle" type="submit" variant="primary" class="w-full">
                 Sign In
             </flux:button>
         </form>

         <p class="text-sm text-gray-600 text-center">
             Don't have an account?

             <button type="button" @click="$flux.modal('signin-modal').close();$flux.modal('signup-modal').show();"
                 class="text-indigo-600 hover:underline hover:cursor-pointer">
                 Sign Up
             </button>



         </p>
     </div>
 </flux:modal>
