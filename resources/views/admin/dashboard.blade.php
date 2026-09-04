<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-8 p-4 rounded-xl bg-emerald-900/30 border border-emerald-700 text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Welcome + Actions -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
                    <p class="text-gray-400 mt-1">Manage your geo domain sales database</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.setup') }}"
                       onclick="event.preventDefault(); document.getElementById('setup-form').submit();"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        Setup Table
                    </a>
                    <a href="{{ route('admin.seed' )}}"
                       onclick="event.preventDefault(); document.getElementById('seed-form').submit();"
                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                        Seed Data
                    </a>
                    <a href="{{ route('admin.add-sale') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-medium">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Add Sale
                    </a>
                    <a href="{{ route('admin.bulk-paste') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Bulk Paste
                    </a>
                    <a href="{{ route('admin.populations') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-medium">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        City Populations
                    </a>
                    <a href="{{ route('admin.users') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Users
                    </a>
                </div>
            </div>

            <form id="setup-form" method="POST" action="{{ route('admin.setup') }}" class="hidden">
                @csrf
            </form>
            <form id="seed-form" method="POST" action="{{ route('admin.seed') }}" class="hidden">
                @csrf
            </form>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-gray-900 rounded-2xl shadow-sm p-6 border border-gray-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 font-medium">Total Users</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalUsers) }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-2xl shadow-sm p-6 border border-gray-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 font-medium">Total Searches</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalSearches) }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-2xl shadow-sm p-6 border border-gray-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 font-medium">Total Logins</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalLogins) }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.users') }}" class="bg-gray-900 rounded-2xl shadow-sm p-6 border border-gray-800 hover:border-indigo-600 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 font-medium">Sales Records</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ number_format($stats['total_sales']) }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Sales -->
                <div class="lg:col-span-2 bg-gray-900 rounded-2xl shadow-sm border border-gray-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white">Recent Sales</h2>
                        <a href="{{ route('admin.sales') }}" class="text-sm text-indigo-400 hover:text-indigo-300 font-medium">View All</a>
                    </div>
                    <div class="divide-y divide-gray-800">
                        @forelse($recentSales as $sale)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-800/40 transition">
                            <div class="flex items-center min-w-0">
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($sale['domain_name'], 0, 1)) }}</span>
                                </div>
                                <div class="ml-3 min-w-0">
                                    <p class="font-semibold text-white truncate">{{ $sale['domain_name'] }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ $sale['city'] }}{{ $sale['state'] ? ', '.$sale['state'] : '' }} · {{ $sale['keyword'] ?: 'No keyword' }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 ml-4">
                                <p class="font-bold text-emerald-400">${{ number_format($sale['price'], 2) }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-12 text-center">
                            <p class="text-gray-500">No sales recorded yet. Click "Add Sale" or "Seed Data" to get started.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="bg-gray-900 rounded-2xl shadow-sm border border-gray-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white">Recent Users</h2>
                        <a href="{{ route('admin.users') }}" class="text-sm text-indigo-400 hover:text-indigo-300 font-medium">View All</a>
                    </div>
                    <div class="divide-y divide-gray-800">
                        @forelse($recentUsers as $user)
                        <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-800/40 transition">
                            <div class="flex items-center min-w-0">
                                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div class="ml-3 min-w-0">
                                    <p class="font-semibold text-white truncate">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 ml-4">
                                @if($user->is_admin)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-purple-500/10 text-purple-400 text-xs font-semibold">Admin</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-800 text-gray-400 text-xs font-semibold">Member</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-12 text-center">
                            <p class="text-gray-500">No users registered yet.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>