<x-layout title="Admin — Users">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Users</h1>
        </div>

        @if(session('status'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded mb-6">
                {{ session('status') }}
            </div>
        @endif

        @if($users->isEmpty())
            <div class="bg-white shadow rounded-lg p-6 text-center text-gray-500">
                No users yet.
            </div>
        @else
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="{{ $user->banned_at ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-600">Reputation: {{ $user->reputation }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="text-indigo-600 hover:text-indigo-900 text-sm">
                                        View
                                    </a>

                                    @if(!$user->is_admin && !$user->banned_at)
                                        <form action="{{ route('admin.users.ban', $user) }}"
                                              method="post"
                                              class="inline"
                                              onsubmit="return confirm('Ban this user?')">
                                            @csrf
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 text-sm">
                                                Ban
                                            </button>
                                        </form>
                                    @elseif(!$user->is_admin && $user->banned_at)
                                        <form action="{{ route('admin.users.unban', $user) }}"
                                              method="post"
                                              class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-green-600 hover:text-green-900 text-sm">
                                                Unban
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layout>
