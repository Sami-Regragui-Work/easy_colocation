<x-layout title="Invitations - {{ $colocation->name }}">
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">
                    Invitations for {{ $colocation->name }}
                </h2>
            </div>

            @if($invitations->isEmpty())
                <div class="px-6 py-12 text-center text-gray-500">
                    <p>No invitations sent yet.</p>
                </div>
            @else
                <ul>
                    @foreach($invitations as $invitation)
                        <li class="px-6 py-4 border-b border-gray-100 last:border-b-0 flex justify-between items-center hover:bg-gray-50">
                            <div>
                                <span class="font-medium text-gray-900">{{ $invitation->email }}</span>
                            </div>
                            <div class="text-sm text-gray-500">
                                @if($invitation->accepted_at)
                                    <span class="text-green-600">Accepted</span>
                                @elseif($invitation->refused_at)
                                    <span class="text-red-600">Refused</span>
                                @else
                                    <span class="text-yellow-600">Pending</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layout>
