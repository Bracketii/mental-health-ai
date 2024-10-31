<div class="flex flex-wrap gap-4">
    <button wire:click="selectGender('female')" class="w-full sm:w-auto flex-1 px-4 py-2 rounded-lg bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 transition text-white">
        Female
    </button>
    <button wire:click="selectGender('male')" class="w-full sm:w-auto flex-1 px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-teal-500 hover:from-blue-600 hover:to-teal-600 transition text-white">
        Male
    </button>
    <button wire:click="selectGender('non_binary')" class="w-full sm:w-auto flex-1 px-4 py-2 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 transition text-white">
        Non-Binary
    </button>
</div>
