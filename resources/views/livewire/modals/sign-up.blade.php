 <!-- Sign Up Modal -->
 <flux:modal name="signup-modal" size="md">
     <div class="p-6 space-y-4">
         <h2 class="text-xl font-bold text-gray-800">Sign Up</h2>
         <p class="text-sm text-gray-600">Create an account to start reviewing products.</p>

         <form class="space-y-4">
             <flux:input label="Full Name" type="text" placeholder="Enter your name" />
             <flux:input label="Email" type="email" placeholder="Enter your email" />
             <flux:input label="Password" type="password" placeholder="Create a password" />
             <flux:input label="Confirm Password" type="password" placeholder="Confirm your password" />

             <flux:button variant="primary" class="w-full">
                 Sign Up
             </flux:button>
         </form>

         <p class="text-sm text-gray-600 text-center">
             Already have an account?
             <button type="button"
                 onclick="$dispatch('open-modal', { id: 'signin-modal' }); $dispatch('close-modal', { id: 'signup-modal' });"
                 class="text-indigo-600 hover:underline">
                 Sign In
             </button>
         </p>
     </div>
 </flux:modal>
