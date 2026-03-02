<x-layout title="Categories - {{ $colocation->name }}">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded mb-6">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
                <div class="space-x-3">
                    <a href="{{ route('colocations.categories.create', $colocation) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                        New Category
                    </a>
                    <a href="{{ route('colocations.show', $colocation) }}"
                       class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium">
                        Back
                    </a>
                </div>
            </div>

            @if($categories->isEmpty())
                <div class="bg-white shadow-xl rounded-xl p-12 text-center border-2 border-dashed border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No categories yet</h3>
                    <p class="text-gray-500 mb-6">Categories help you organize expenses.</p>
                    <a href="{{ route('colocations.categories.create', $colocation) }}" class="btn-primary">
                        Create first category
                    </a>
                </div>
            @else
                <div class="bg-white shadow-xl rounded-xl overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expenses</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($categories as $category)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-900 font-medium">{{ $category->expenses_count }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <a href="{{ route('colocations.categories.edit', [$colocation, $category]) }}"
                                           class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('colocations.categories.destroy', [$colocation, $category]) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Delete category and all expenses?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layout>
