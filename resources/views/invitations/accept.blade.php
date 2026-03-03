<x-layout title="Accept Invitation">
    <div class="max-w-md mx-auto py-12">
        <div class="bg-white shadow-xl rounded-xl p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">
                Join {{ $invitation->colocation->name }}
            </h1>

            @if(session('error'))
                <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('invitations.process', $invitation) }}" id="inviteForm">
                @csrf

                <input type="hidden" name="email" value="{{ $invitation->email }}">

                <!-- Hidden action (updated by JS only when guest) -->
                <input type="hidden"
                       name="_action"
                       id="_action"
                       value="{{ Auth::check() ? 'login' : (old('_action') ?: 'register') }}">

                @guest
                    <!-- Show radios only when guest -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            How do you want to join?
                        </label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio"
                                       name="radio_action"
                                       value="register"
                                       class="text-blue-600"
                                       @checked(old('_action') !== 'login')
                                       onclick="setAction('register')"
                                       checked>
                                <span class="ml-2 text-sm">I'm new here</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio"
                                       name="radio_action"
                                       value="login"
                                       class="text-blue-600"
                                       @checked(old('_action') === 'login')
                                       onclick="setAction('login')">
                                <span class="ml-2 text-sm">I already have an account</span>
                            </label>
                        </div>
                    </div>
                @endguest

                <!-- Email -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        value="{{ $invitation->email }}"
                        readonly
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100"
                    >
                    <p class="text-xs text-gray-500 mt-1">
                        Must match the invitation email.
                    </p>
                </div>

                <!-- Name (only for register) -->
                <div class="mb-6"
                     id="nameSection"
                     style="{{ Auth::check() || (old('_action') === 'login' && !old('_action')) ? 'display: none;' : '' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    >
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password (always shown) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input
                    type="password"
                    name="password"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 @error('password') border-red-500 @enderror"
                    >
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password confirmation (only for register) -->
                <div class="mb-6"
                     id="confirmSection"
                     style="{{ Auth::check() || (old('_action') === 'login' && !old('_action')) ? 'display: none;' : '' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500"
                    >
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition"
                >
                    Join Colocation
                </button>
            </form>

            <script>
                function setAction(action) {
                    document.getElementById('_action').value = action;
                    const nameSec   = document.getElementById('nameSection');
                    const confirmSec = document.getElementById('confirmSection');

                    if (action === 'register') {
                        nameSec.style.display   = 'block';
                        confirmSec.style.display = 'block';
                    } else {
                        nameSec.style.display   = 'none';
                        confirmSec.style.display = 'none';
                    }
                }
            </script>
        </div>
    </div>
</x-layout>
