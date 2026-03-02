<x-layout title="{{ $expense->title }} - {{ $category->name }} - {{ $colocation->name }}">
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center mb-8">
                <a href="{{ route('colocations.categories.expenses.index', [$colocation, $category]) }}"
                   class="text-gray-500 hover:text-gray-700">
                    ← Back to {{ $category->name }}
                </a>
            </div>

            <div class="bg-white shadow-xl rounded-xl p-8">
                <div class="border-b pb-6 mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $expense->title }}</h1>
                    <p class="text-4xl font-bold text-blue-600 mt-2">
                        {{ number_format($expense->amount, 2) }} DH
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $expense->start_at->format('F d, Y') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paid by</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $expense->payer->name }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $category->name }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Colocation</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $colocation->name }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4 justify-end">
                    <a href="{{ route('colocations.categories.expenses.edit', [$colocation, $category, $expense]) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                        Edit Expense
                    </a>
                    <form action="{{ route('colocations.categories.expenses.destroy', [$colocation, $category, $expense]) }}"
                          method="POST" class="inline"
                          onsubmit="return confirm('Delete this expense?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium">
                            Delete Expense
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
