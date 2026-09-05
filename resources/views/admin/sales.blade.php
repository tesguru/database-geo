<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-8 p-4 rounded-xl bg-emerald-900/30 border border-emerald-700 text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('import_errors'))
                <div class="mb-8 p-4 rounded-xl bg-amber-900/30 border border-amber-700 text-amber-300" x-data="{ show: true }" x-show="show">
                    <div class="flex items-start justify-between">
                        <div>
                            <strong>Import Warnings:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button @click="show = false" class="text-amber-400 hover:text-amber-200 text-sm">&times;</button>
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">All Sales</h1>
                    <p class="text-gray-400 mt-1">Manage your domain sales records</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.add-sale') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-medium">
                        Add Sale
                    </a>
                    <a href="{{ route('admin.bulk-paste') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium">
                        Bulk Paste
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.sales') }}" class="mb-6 flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[220px] relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search domain, keyword, city, state..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                </div>
                <button type="submit" class="px-4 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition text-sm font-medium">
                    Search
                </button>
                @if(request('keyword'))
                <a href="{{ route('admin.sales') }}" class="px-4 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition text-sm font-medium">
                    Clear
                </a>
                @endif
            </form>

            <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-800/60">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Domain</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Keyword</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-800">
                            @forelse($results['data'] as $sale)
                            <tr class="hover:bg-gray-800/40 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center mr-3">
                                            <span class="text-white font-bold text-xs">{{ strtoupper(substr($sale['domain_name'], 0, 1)) }}</span>
                                        </div>
                                        <div class="font-semibold text-white">{{ $sale['domain_name'] }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">{{ $sale['keyword'] ?: '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    {{ $sale['city'] }}{{ $sale['state'] ? ', '.$sale['state'] : '' }}
                                    {{ $sale['country'] ? ' · '.$sale['country'] : '' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 font-bold text-sm">${{ number_format($sale['price'], 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.edit-sale', $sale['id']) }}"
                                           class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.destroy-sale', $sale['id']) }}"
                                              onsubmit="return confirm('Delete sale for {{ addslashes($sale['domain_name']) }}? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-red-600/80 text-white text-xs font-semibold hover:bg-red-700 transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    No sales found. <a href="{{ route('admin.add-sale') }}" class="text-indigo-400 hover:underline">Add your first sale</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if(($results['last_page'] ?? 1) > 1)
            <div class="mt-6 flex items-center justify-center gap-2">
                @php $q = array_merge(request()->except(['page']), ['page' => 1]); @endphp
                <a href="{{ route('admin.sales', $q) }}"
                   class="{{ $results['page'] == 1 ? 'opacity-40 pointer-events-none' : '' }} px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-sm font-medium text-gray-300 hover:bg-gray-700 transition">
                    &laquo;
                </a>
                @php $start = max(1, $results['page'] - 2); $end = min($results['last_page'], $results['page'] + 2); @endphp
                @for($p = $start; $p <= $end; $p++)
                    @php $q = array_merge(request()->except(['page']), ['page' => $p]); @endphp
                    <a href="{{ route('admin.sales', $q) }}"
                       class="{{ $p == $results['page'] ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white border-transparent' : 'bg-gray-800 text-gray-300 border-gray-700 hover:bg-gray-700' }} px-4 py-2 rounded-lg border text-sm font-medium transition">
                        {{ $p }}
                    </a>
                @endfor
                @php $q = array_merge(request()->except(['page']), ['page' => $results['last_page']]); @endphp
                <a href="{{ route('admin.sales', $q) }}"
                   class="{{ $results['page'] == $results['last_page'] ? 'opacity-40 pointer-events-none' : '' }} px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-sm font-medium text-gray-300 hover:bg-gray-700 transition">
                    &raquo;
                </a>
            </div>
            <p class="mt-3 text-center text-sm text-gray-500">
                Page {{ $results['page'] }} of {{ max($results['last_page'], 1) }} · {{ number_format($results['total']) }} sales total
            </p>
            @endif
        </div>
    </div>
</x-app-layout>
