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
        </div>
    </div>
</x-app-layout>
