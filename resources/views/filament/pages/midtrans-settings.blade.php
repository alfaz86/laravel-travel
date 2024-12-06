<x-filament::page>
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="space-y-4">
            <div>
                <label for="midtrans_environment" class="block text-sm font-medium">Environment</label>
                <select wire:model="midtrans_environment" id="midtrans_environment" 
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                    <option value="sandbox">Sandbox</option>
                    <option value="production">Production</option>
                </select>
            </div>

            <div>
                <label for="midtrans_server_key" class="block text-sm font-medium">Midtrans Server Key</label>
                <input type="text" wire:model="midtrans_server_key" id="midtrans_server_key" 
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800" />
            </div>

            <div>
                <label for="midtrans_client_key" class="block text-sm font-medium">Midtrans Client Key</label>
                <input type="text" wire:model="midtrans_client_key" id="midtrans_client_key" 
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800" />
            </div>
        </div>

        <button type="submit" 
            class="bg-primary-500 text-white px-4 py-2 rounded-md hover:bg-primary-600 dark:bg-primary-700 dark:hover:bg-primary-800">
            Save
        </button>
    </form>
</x-filament::page>
