 <div class="{{ $className ?? 'w-full md:w-1/2 xl:w-1/3 p-4 flex flex-col items-center' }}">
     <a href="shop/{{ $slug }}">
         <img class="transform transition-transform duration-300 rounded-xl hover:scale-105 hover:shadow-lg"
             src="storage/{{ $image }}">
         <div class="pt-3 flex items-center justify-between">
             <p class="font-medium text-gray-800">{{ $name }}</p>
         </div>
         <div class="flex flex-row items-center pt-1">
             <flux:icon.currency-bangladeshi class="size-6" />
             <p class="text-gray-900">{{ $basePrice }}</p>
             <flux:button icon="eye" class="hover:cursor-pointer ms-auto">
                 See details
             </flux:button>
         </div>
     </a>
 </div>
