<x-filament::page>
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="space-y-4">
            <div>
                <label for="pusher_app_id" class="block text-sm font-medium">Pusher App ID</label>
                <input type="text" wire:model="pusher_app_id" id="pusher_app_id" 
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800" />
            </div>

            <div>
                <label for="pusher_app_key" class="block text-sm font-medium">Pusher App Key</label>
                <input type="text" wire:model="pusher_app_key" id="pusher_app_key" 
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800" />
            </div>

            <div>
                <label for="pusher_app_secret" class="block text-sm font-medium">Pusher App Secret</label>
                <input type="text" wire:model="pusher_app_secret" id="pusher_app_secret" 
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800" />
            </div>

            <div>
                <label for="app_pusher_setting" class="text-sm font-medium mr-4">Enable Pusher</label>
                <input type="checkbox" wire:model="app_pusher_setting" id="app_pusher_setting" 
                    class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 ml-5" />
            </div>
        </div>

        <button type="submit" 
            class="bg-primary-500 text-white px-4 py-2 rounded-md hover:bg-primary-600 dark:bg-primary-700 dark:hover:bg-primary-800">
            Save
        </button>
    </form>
</x-filament::page>
