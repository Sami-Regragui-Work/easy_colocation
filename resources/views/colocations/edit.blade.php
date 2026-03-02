<x-layout :title="'Edit Colocation: ' . $colocation->name">
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Colocation</h1>

            <form method="POST" action="{{ route('colocations.update', $colocation) }}">
                @csrf
                @method('put')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Name
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $colocation->name) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 mt-6">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                        Update
                    </button>
                    <a href="{{ route('colocations.show', $colocation) }}"
                       class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
