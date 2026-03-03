<x-layout title="Admin — User {{ $user->name }}">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">User Details</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $user->is_admin ? 'Admin' : 'User' }}
                        @if ($user->banned_at)
                            (Banned)
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Reputation</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->reputation }}</dd>
                </div>
            </dl>
        </div>

        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
            &larr; Back to user list
        </a>
    </div>
</x-layout>
