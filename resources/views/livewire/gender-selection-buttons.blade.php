<!-- resources/views/components/gender-selection-buttons.blade.php -->

<div class="flex flex-col sm:flex-row gap-4">
    <!-- Female Button -->
    <button
        wire:click="selectGender('female')"
        class="w-full sm:w-auto flex-1 px-4 py-3 rounded-lg bg-gradient-to-r from-ap3 to-ap2 hover:from-ap3-hover hover:to-ap1-hover transition-transform transform hover:scale-105 shadow-lg text-white font-medium focus:outline-none focus:ring-2 focus:ring-ap1 focus:ring-opacity-50"
    >
        Female
    </button>

    <!-- Male Button -->
    <button
        wire:click="selectGender('male')"
        class="w-full sm:w-auto flex-1 px-4 py-3 rounded-lg bg-gradient-to-r from-ap5 to-ap6 hover:from-ap5-hover hover:to-ap6-hover transition-transform transform hover:scale-105 shadow-lg text-white font-medium focus:outline-none focus:ring-2 focus:ring-ap5 focus:ring-opacity-50"
    >
        Male
    </button>

    <!-- Non-Binary Button -->
    <button
        wire:click="selectGender('non_binary')"
        class="w-full sm:w-auto flex-1 px-4 py-3 rounded-lg bg-gradient-to-r from-ap7 to-ap4 hover:from-ap7-hover hover:to-ap4-hover transition-transform transform hover:scale-105 shadow-lg text-white font-medium focus:outline-none focus:ring-2 focus:ring-ap7 focus:ring-opacity-50"
    >
        Non-Binary
    </button>
</div>
