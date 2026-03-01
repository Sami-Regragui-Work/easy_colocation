<x-layout title="My Colocations">
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">My Colocations</h1>
                <a href="{{ route('colocations.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                    Create Colocation
                </a>
            </div>

            <!-- Success message -->
            @if (session('status'))
                <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <!-- ERROR message (NEW) -->
            @if (session('error'))
                <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @if ($colocations->isEmpty())
                <p class="text-gray-600">You don't belong to any colocation yet.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($colocations as $colocation)
                        <div class="bg-white shadow rounded-lg p-5">
                            <h2 class="text-lg font-semibold text-gray-900">{{ $colocation->name }}</h2>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="block">Owner: {{ optional($colocation->owner)->name ?? 'Unknown' }}</span>
                                <span class="block">Status: <span class="font-medium">{{ ucfirst($colocation->status) }}</span></span>
                            </p>
                            <a href="{{ route('colocations.show', $colocation) }}"
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-3 inline-block">
                                View details
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layout>
