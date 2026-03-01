<x-layout :title="'Colocation: ' . $colocation->name">
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Colocation: {{ $colocation->name }}
                </h1>
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                    {{ ucfirst($colocation->status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Owner card -->
                <div class="bg-white shadow rounded-lg p-5">
                    <h2 class="text-lg font-semibold text-gray-900">Owner</h2>
                    <p class="text-gray-600 mt-2">
                        {{ optional($colocation->owner)->name ?? 'Unknown' }}
                    </p>
                </div>

                <!-- Actions card -->
                <div class="bg-white shadow rounded-lg p-5">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Actions</h2>
                    <div class="flex flex-col gap-2">
                        <!-- Edit -->
                        <a href="{{ route('colocations.edit', $colocation) }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 px-4 rounded text-sm font-medium text-center">
                            Edit
                        </a>

                        <!-- Quit (member only) -->
                        @if ($colocation->status === 'active' && auth()->user()->id !== $colocation->owner_id)
                            <form action="{{ route('colocations.quit', $colocation) }}" method="post" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-red-100 hover:bg-red-200 text-red-800 py-2 px-4 rounded text-sm font-medium text-center w-full"
                                    onclick="return confirm('Are you sure you want to quit this colocation?')">
                                    Quit
                                </button>
                            </form>
                        @endif

                        <!-- Cancel (owner only) -->
                        @if (auth()->user()->id === $colocation->owner_id && $colocation->status === 'active')
                            <form action="{{ route('colocations.cancel', $colocation) }}" method="post" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-red-100 hover:bg-red-200 text-red-800 py-2 px-4 rounded text-sm font-medium text-center w-full"
                                    onclick="return confirm('Are you sure you want to cancel this colocation?')">
                                    Cancel Colocation
                                </button>
                            </form>
                        @endif

                        <!-- Delete (owner only, cancelled colocations only) -->
                        @if (auth()->user()->id === $colocation->owner_id && $colocation->status === 'cancelled')
                            <form action="{{ route('colocations.destroy', $colocation) }}" method="post"
                                class="inline">
                                @csrf
                                @method('delete')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded text-sm font-medium text-center w-full"
                                    onclick="return confirm('Delete this colocation permanently?')">
                                    Delete Forever
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Members list -->
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Members</h2>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <ul>
                    @foreach ($colocation->members as $member)
                        <li
                            class="flex items-center justify-between px-5 py-3 border-b border-gray-100 last:border-b-0">
                            <span class="text-gray-900">{{ $member->name }}</span>
                            <span class="text-xs font-medium bg-gray-100 text-gray-700 py-1 px-2 rounded">
                                {{ $member->pivot->role }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Invite form -->
            <div class="mt-8 bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Invite a Member</h2>
                <form method="post" action="{{ route('colocations.invite', $colocation) }}">
                    @csrf
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <div class="flex-1">
                            <input type="email" name="email" placeholder="member@example.com"
                                class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-sm">
                            Invite
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
