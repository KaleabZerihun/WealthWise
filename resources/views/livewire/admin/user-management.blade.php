<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('User Management') }}
    </h2>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Greeting -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Manage Users
            </h1>
            <p class="text-gray-600 mt-1">
                View, filter, edit, or remove users from the platform.
            </p>
        </div>

        <!-- Flash Message -->
        @if(session('message'))
            <div class="mb-4 text-green-600 font-semibold bg-green-100 p-4 rounded">
                {{ session('message') }}
            </div>
        @endif

        <!-- Filter + Search + Add Button -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
            <select wire:model.live="filter"
                    class="border-gray-300 rounded p-2 w-full md:w-48 text-sm">
                <option value="all">All Users</option>
                <option value="clients">Clients</option>
                <option value="advisors">Advisors</option>
                <option value="admins">Admins</option>
            </select>

            <input wire:model.live="search"
                   type="text"
                   placeholder="Search by name or email..."
                   class="border-gray-300 rounded p-2 flex-1 text-sm"/>

            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm shadow">
                + Add Account
            </button>
        </div>

        <!-- User Table -->
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-xs text-gray-600 uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Name / Email</th>
                    <th class="px-6 py-3 text-center">Role</th>
                    <th class="px-6 py-3 text-center">Joined</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($rows as $u)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-semibold">{{ $u->first_name }} {{ $u->last_name }}</div>
                            <div class="text-xs text-gray-500">{{ $u->email }}</div>
                        </td>

                        <td class="px-6 py-4 text-center capitalize">{{ $u->user_type }}</td>

                        <td class="px-6 py-4 text-center">
                            {{ \Carbon\Carbon::parse($u->created_at)->format('M d, Y') }}
                        </td>

                        <td class="px-6 py-4 text-right space-x-2">
                            <button wire:click="openEditModal({{ $u->id }}, '{{ $u->user_type }}')"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                Edit
                            </button>

                            <button wire:click="confirmDelete({{ $u->id }}, '{{ $u->user_type }}')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="p-4">
                {{ $rows->links() }}
            </div>
        </div>

        <!-- Edit Modal -->
        @if($showEditModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div class="bg-white rounded-lg p-6 w-full max-w-md">
                    <h3 class="text-lg font-bold mb-6">Edit User</h3>

                    <form wire:submit.prevent="updateUser" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">First Name</label>
                            <input wire:model.defer="first_name" type="text" class="border-gray-300 rounded w-full p-2" />
                            @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input wire:model.defer="last_name" type="text" class="border-gray-300 rounded w-full p-2" />
                            @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input wire:model.defer="email" type="email" class="border-gray-300 rounded w-full p-2" />
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <div class="flex justify-end space-x-4 pt-4">
                            <button type="button" wire:click="$set('showEditModal', false)"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                                Cancel
                            </button>

                            <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Delete Confirm Modal -->
        @if($showDeleteModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div class="bg-white rounded-lg p-6 w-full max-w-sm">
                    <h3 class="text-lg font-semibold mb-4 text-red-600">Confirm Delete</h3>

                    <p class="text-sm text-gray-600 mb-6">
                        Are you sure you want to delete this user? This action cannot be undone.
                    </p>

                    <div class="flex justify-end space-x-4">
                        <button type="button" wire:click="$set('showDeleteModal', false)"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                            Cancel
                        </button>

                        <button wire:click="deleteUser"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
