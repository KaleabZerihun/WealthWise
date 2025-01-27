<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Add New Financial Goal
        </h1>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6">
            <form wire:submit.prevent="store" class="space-y-4">
                <!-- Goal Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Goal Type</label>
                    <input type="text" wire:model="goal_type"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           placeholder="e.g. Retirement, Home Purchase">
                    @error('goal_type')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Target Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Target Amount</label>
                    <input type="number" wire:model="target_amount" step="0.01"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           placeholder="e.g. 50000.00">
                    @error('target_amount')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Current Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Amount</label>
                    <input type="number" wire:model="current_amount" step="0.01"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           placeholder="e.g. 10000.00">
                    @error('current_amount')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date (Optional)</label>
                    <input type="date" wire:model="start_date"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    @error('start_date')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Target Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Target Date (Optional)</label>
                    <input type="date" wire:model="target_date"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    @error('target_date')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Goal Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Goal Status</label>
                    <select wire:model="goal_status"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="on_hold">On Hold</option>
                    </select>
                    @error('goal_status')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-2 mt-6">
                    <a href="{{ route('goals.manage') }}"
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Save Goal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
