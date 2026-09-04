<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-8 p-4 rounded-xl bg-emerald-900/30 border border-emerald-700 text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('import_errors') && count(session('import_errors')))
                <div class="mb-8 p-4 rounded-xl bg-amber-900/30 border border-amber-700 text-amber-300">
                    <strong>Import Warnings:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">Population Data</h1>
                    <p class="text-gray-400 mt-1">
                        Add city/state populations. The valuation tool uses these to judge demand.
                    </p>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.populations.seed') }}">
                        @csrf
                        <button class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                            Seed Sample Cities
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.populations.seed-states') }}">
                        @csrf
                        <button class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                            Seed US + Int'l States
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                <!-- Add single -->
                <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6">
                    <h2 class="text-lg font-bold text-white mb-4">Add One</h2>
                    <form method="POST" action="{{ route('admin.populations.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">City *</label>
                                <input type="text" name="city" value="{{ old('city') }}" required
                                       class="w-full px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                       placeholder="e.g. Los Angeles">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">Population *</label>
                                <input type="number" name="population" value="{{ old('population') }}" required min="1"
                                       class="w-full px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                       placeholder="e.g. 3898747">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">State</label>
                                <input type="text" name="state" value="{{ old('state') }}"
                                       class="w-full px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                       placeholder="e.g. California">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">Country</label>
                                <input type="text" name="country" value="{{ old('country') }}"
                                       class="w-full px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                       placeholder="e.g. United States">
                            </div>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-medium">
                            Add City Population
                        </button>
                        @error('city') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
                        @error('population') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
                    </form>
                </div>

                <!-- Bulk paste -->
                <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6">
                    <h2 class="text-lg font-bold text-white mb-1">Bulk Add</h2>
                    <p class="text-xs text-gray-500 mb-3">Format per line: <span class="font-mono">city, state, country, population</span></p>
                    <form method="POST" action="{{ route('admin.populations.bulk') }}" class="space-y-4">
                        @csrf
                        <textarea name="bulk_data" rows="5" required
                                  class="w-full px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Los Angeles, California, United States, 3898747&#10;New York, New York, United States, 8336817">{{ old('bulk_data') }}</textarea>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-medium">
                            Bulk Import
                        </button>
                        @error('bulk_data') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h2 class="text-lg font-bold text-white">Population Records ({{ count($populations) }})</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-800/60">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">City</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">State</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Country</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Population</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900 divide-y divide-gray-800">
                            @forelse($populations as $p)
                            <tr class="hover:bg-gray-800/40 transition">
                                <td class="px-6 py-4 font-semibold text-white">{{ $p['city'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-300">{{ $p['state'] ?: '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-300">{{ $p['country'] ?: '-' }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-emerald-400">{{ number_format((int) $p['population']) }}</td>
                                <td class="px-6 py-4">
                                    @php $pop = (int) $p['population']; @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold
                                        {{ $pop >= 1000000 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : ($pop >= 500000 ? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/30' : 'bg-gray-800 text-gray-400 border border-gray-700') }}">
                                        {{ $pop >= 1000000 ? 'Big City' : ($pop >= 500000 ? 'Medium' : 'Small') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('admin.populations.destroy', $p['id']) }}" onsubmit="return confirm('Delete this population record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-400 hover:text-red-300 text-sm font-medium">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    No population records yet. Add city + state + population above.
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
