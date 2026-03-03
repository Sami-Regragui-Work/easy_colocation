<x-layout title="Admin Dashboard">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Users -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Users</h2>
                <div class="space-y-2 text-sm text-gray-600">
                    <div>Total: {{ $totalUsers }}</div>
                    <div>Active: {{ $activeUsers }}</div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-indigo-600 text-sm mt-2 inline-block">
                    Manage Users
                </a>
            </div>

            <!-- Colocations -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Colocations</h2>
                <p class="text-sm text-gray-600">Total: {{ $totalColocations }}</p>
            </div>

            <!-- Expenses -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Expenses</h2>
                <p class="text-sm text-gray-600">Total: {{ $totalExpenses }}</p>
            </div>

            <!-- Settlements -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Settlements</h2>
                <p class="text-sm text-gray-600">Total: {{ $totalSettlements }}</p>
            </div>
        </div>

        <!-- Quick navigation -->
        <div class="mt-8 gap-6 text-sm text-gray-600 flex flex-wrap">
            <div><a href="{{ route('colocations.index') }}">Go to colocations</a></div>
            <div><a href="{{ route('profile.edit', Auth::user()) }}">Go to your profile</a></div>
            <div><a href="{{ route('admin.dashboard') }}">Go back to admin dashboard</a></div>
        </div>
    </div>
</x-layout>
