<x-layout title="Edit {{ $category->name }} - {{ $category->colocation->name }}">
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center mb-8">
                <a href="{{ route('colocations.categories.index', $category->colocation) }}" class="text-gray-500 hover:text-gray-700">
                    ← Back to categories
                </a>
            </div>

            <div class="bg-white shadow-xl rounded-xl p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Category</h1>

                <form method="POST" action="{{ route('colocations.categories.update', [$category->colocation, $category]) }}">
                    @csrf @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name', $category->name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 bg-red-50 @enderror"
                                   maxlength="70" required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-10">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium">
                            Update Category
                        </button>
                        <a href="{{ route('colocations.categories.index', $category->colocation) }}"
                           class="text-gray-600 hover:text-gray-900 font-medium">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
