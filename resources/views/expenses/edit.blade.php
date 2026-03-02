<x-layout title="Edit {{ $expense->title }} - {{ $category->name }} - {{ $colocation->name }}">
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center mb-8">
                <a href="{{ route('colocations.categories.expenses.index', [$colocation, $category]) }}"
                   class="text-gray-500 hover:text-gray-700">
                    ← Back to {{ $category->name }}
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded mb-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-xl p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Expense</h1>

                <form method="POST" action="{{ route('colocations.categories.expenses.update', [$colocation, $category, $expense]) }}">
                    @csrf @method('PUT')
                    <div class="space-y-6">
                        {{-- Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                            <input type="date" name="start_at"
                                   value="{{ old('start_at', $expense->start_at->format('Y-m-d')) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_at') border-red-300 bg-red-50 @enderror">
                            @error('start_at')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Payer --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Paid by *</label>
                            <select name="payer_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payer_id') border-red-300 bg-red-50 @enderror">
                                <option value="">Select member</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('payer_id', $expense->payer_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payer_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                            <input type="text" name="title"
                                   value="{{ old('title', $expense->title) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 bg-red-50 @enderror"
                                   placeholder="What was purchased?">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Amount --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount (DH) *</label>
                            <input type="number" name="amount" step="0.01" min="0"
                                   value="{{ old('amount', $expense->amount) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-300 bg-red-50 @enderror">
                            @error('amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-10">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium">
                            Update Expense
                        </button>
                        <a href="{{ route('colocations.categories.expenses.show', [$colocation, $category, $expense]) }}"
                           class="text-gray-600 hover:text-gray-900 font-medium">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
