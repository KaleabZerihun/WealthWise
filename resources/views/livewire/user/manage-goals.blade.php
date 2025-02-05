<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Manage Financial Goals') }}
    </h2>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Link to "Add New Goal" page-->
        <div class="mb-6 flex justify-end items-center">
            <a href="{{ route('goals.add') }}"
               class="inline-block bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
                Add New Goal
            </a>
        </div>

        <!-- Success Message -->
        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show mb-4 text-green-600 font-semibold" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <!-- Summary Card -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-700">
                Goals Summary
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-3">
                <!-- In Progress -->
                <div class="bg-gray-50 p-3 rounded shadow text-center">
                    <p class="text-sm font-medium text-gray-500">In Progress</p>
                    <p class="text-xl font-bold text-gray-800">
                        {{ $inProgress }}
                    </p>
                </div>
                <!-- Completed -->
                <div class="bg-gray-50 p-3 rounded shadow text-center">
                    <p class="text-sm font-medium text-gray-500">Completed</p>
                    <p class="text-xl font-bold text-gray-800">
                        {{ $completed }}
                    </p>
                </div>
                <!-- On Hold -->
                <div class="bg-gray-50 p-3 rounded shadow text-center">
                    <p class="text-sm font-medium text-gray-500">On Hold</p>
                    <p class="text-xl font-bold text-gray-800">
                        {{ $onHold }}
                    </p>
                </div>
                <!-- Total -->
                <div class="bg-gray-50 p-3 rounded shadow text-center">
                    <p class="text-sm font-medium text-gray-500">Total</p>
                    <p class="text-xl font-bold text-gray-800">
                        {{ $total }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Goals Table -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Goal Type</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Target</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Current</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Target Date</th>
                    <th class="px-4 py-2"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($goals as $goal)
                    <tr class="border-b">
                        <td class="px-4 py-2 text-sm text-gray-600">
                            {{ $goal->goal_type }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-600">
                            ${{ number_format($goal->target_amount, 2) }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-600">
                            ${{ number_format($goal->current_amount, 2) }}
                        </td>
                        <!-- Color-coded status -->
                        @php
                            $displayStatus = ucwords(str_replace('_',' ', $goal->goal_status));
                            switch($goal->goal_status) {
                                case 'in_progress':
                                    $statusColor = 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'completed':
                                    $statusColor = 'bg-green-100 text-green-800';
                                    break;
                                case 'on_hold':
                                    $statusColor = 'bg-red-100 text-red-800';
                                    break;
                                default:
                                    $statusColor = 'bg-gray-100 text-gray-800';
                            }
                        @endphp
                        <td class="px-4 py-2 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                    {{ $displayStatus }}
                                </span>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-600">
                            {{ $goal->start_date ?? '--' }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-600">
                            {{ $goal->target_date ?? '--' }}
                        </td>
                        <td class="px-4 py-2 text-right text-sm">
                            <button wire:click="openEditModal({{ $goal->id }})"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded mr-2">
                                Edit
                            </button>
                            <button wire:click="deleteGoal({{ $goal->id }})"
                                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-gray-500">No goals found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <!-- Edit Modal -->
        @if($showEditModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg p-6 w-1/3">
                    <h2 class="text-xl font-bold mb-4">Edit Goal</h2>

                    <!-- Fields -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Goal Type
                        </label>
                        <input type="text" wire:model.defer="editGoalType"
                               class="form-control w-full border p-2 rounded">
                        @error('editGoalType')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Target Amount</label>
                        <input type="number" wire:model.defer="editTarget" step="0.01"
                               class="form-control w-full border p-2 rounded">
                        @error('editTarget')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Current Amount</label>
                        <input type="number" wire:model.defer="editCurrent" step="0.01"
                               class="form-control w-full border p-2 rounded">
                        @error('editCurrent')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Start Date (Optional)</label>
                        <input type="date" wire:model.defer="editStartDate"
                               class="form-control w-full border p-2 rounded">
                        @error('editStartDate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Target Date (Optional)</label>
                        <input type="date" wire:model.defer="editTargetDate"
                               class="form-control w-full border p-2 rounded">
                        @error('editTargetDate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Goal Status</label>
                        <select wire:model.defer="editStatus" class="form-control w-full border p-2 rounded">
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                        @error('editStatus')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button wire:click="saveEdit"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Save
                        </button>
                        <button wire:click="closeEditModal"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>


