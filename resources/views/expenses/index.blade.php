<x-layout title="{{ $category->name }} - {{ $colocation->name }}">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                    <p class="text-lg text-gray-600 mt-1">{{ $expenses->total() }} expenses</p>
                </div>
                <div class="flex flex-wrap gap-3 justify-end">
                    <a href="{{ route('colocations.categories.expenses.create', [$colocation, $category]) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                        New Expense
                    </a>
                    <a href="{{ route('colocations.categories.index', $colocation) }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium">
                        Back to Categories
                    </a>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                        <select name="month"
                            class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All months</option>
                            <option value="1" {{ request('month') == '1' ? 'selected' : '' }}>January</option>
                            <option value="2" {{ request('month') == '2' ? 'selected' : '' }}>February</option>
                            <option value="3" {{ request('month') == '3' ? 'selected' : '' }}>March</option>
                            <option value="4" {{ request('month') == '4' ? 'selected' : '' }}>April</option>
                            <option value="5" {{ request('month') == '5' ? 'selected' : '' }}>May</option>
                            <option value="6" {{ request('month') == '6' ? 'selected' : '' }}>June</option>
                            <option value="7" {{ request('month') == '7' ? 'selected' : '' }}>July</option>
                            <option value="8" {{ request('month') == '8' ? 'selected' : '' }}>August</option>
                            <option value="9" {{ request('month') == '9' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>October</option>
                            <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>December</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Filter
                    </button>
                    @if (request('month'))
                        <a href="{{ route('colocations.categories.expenses.index', [$colocation, $category]) }}"
                            class="text-gray-500 hover:text-gray-700 text-sm underline">Clear</a>
                    @endif
                </form>
            </div>


            {{-- Table --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payer</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $expense->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $expense->start_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $expense->payer->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                    {{ number_format($expense->amount, 2) }} DH
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('colocations.categories.expenses.show', [$colocation, $category, $expense]) }}"
                                        class="text-blue-600 hover:text-blue-900">View</a>
                                    <a href="{{ route('colocations.categories.expenses.edit', [$colocation, $category, $expense]) }}"
                                        class="text-green-600 hover:text-green-900">Edit</a>
                                    <form
                                        action="{{ route('colocations.categories.expenses.destroy', [$colocation, $category, $expense]) }}"
                                        method="POST" class="inline" onsubmit="return confirm('Delete this expense?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 text-lg">
                                    No expenses found{{ request('start_at') ? ' for this date' : '' }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div
                class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 mt-4 sm:flex sm:flex-col sm:justify-between sm:flex-row">
                {{ $expenses->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</x-layout>
