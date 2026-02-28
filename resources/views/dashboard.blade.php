<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Welcome Back!
            </h1>
            <p class="text-xl text-gray-600">
                Manage your colocation services
            </p>
        </div>

        <!-- User Greeting -->
        <div class="bg-white shadow-xl rounded-2xl p-8 mb-8 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Hello, {{ Auth::user()->name }}!
                    </h2>
                    <p class="text-gray-600 mt-1">
                        @if(Auth::user()->is_admin)
                            🛠️ Admin Dashboard
                        @else
                            👋 User Dashboard
                        @endif
                    </p>
                    <p class="text-sm text-gray-500 mt-2">
                        Email: {{ Auth::user()->email }}
                    </p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('logout') }}"
                       class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Active Colocations -->
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4M4 20l4-4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Colocations</p>
                        <p class="text-3xl font-bold text-gray-900">12</p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2-0 2 3 .895 3 2-.895 2-2 2m0-8c1.11 0 2.08.402 2.599 1M12 8A9.74 9.74 0 018 6.75c-.996 0-1.97-.157-2.92-.444A6.5 6.5 0 003 4.5"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                        <p class="text-3xl font-bold text-green-600">$2,450</p>
                    </div>
                </div>
            </div>

            <!-- Pending Actions -->
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Actions</p>
                        <p class="text-3xl font-bold text-yellow-600">3</p>
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Account Status</p>
                        <p class="text-3xl font-bold @if(Auth::user()->is_admin) text-green-600 @else text-blue-600 @endif">
                            @if(Auth::user()->is_admin) Admin @else Active @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activity -->
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">New colocation request approved</p>
                            <p class="text-sm text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Payment received</p>
                            <p class="text-sm text-gray-500">1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="#" class="block p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors">
                        <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="font-medium text-gray-900">New Colocation</span>
                    </a>
                    <a href="#" class="block p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition-colors">
                        <svg class="w-8 h-8 mx-auto mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium text-gray-900">View Billing</span>
                    </a>
                    <a href="#" class="block p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition-colors">
                        <svg class="w-8 h-8 mx-auto mb-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium text-gray-900">Documents</span>
                    </a>
                    <a href="#" class="block p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg text-center transition-colors">
                        <svg class="w-8 h-8 mx-auto mb-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium text-gray-900">Settings</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
