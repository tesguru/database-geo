<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">Bulk Paste Sales</h1>
                    <p class="text-gray-400 mt-1">Paste thousands of sales records at once. Handles 20,000+ rows.</p>
                </div>
                <a href="{{ route('admin.sales') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium">← Back to Sales</a>
            </div>

            <div class="bg-gray-900 rounded-2xl border border-gray-800 p-8 space-y-6">
                @if(session('import_duplicates') && count(session('import_duplicates')) > 0)
                <div class="bg-amber-900/30 border border-amber-700 rounded-xl p-4">
                    <h3 class="font-semibold text-amber-300 mb-1">⚠️ Skipped {{ count(session('import_duplicates')) }} duplicate domain(s)</h3>
                    <p class="text-sm text-amber-400 mb-2">These were already in the database (or repeated in your paste), so they were NOT inserted:</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(session('import_duplicates') as $dup)
                            <span class="px-2 py-0.5 bg-amber-900/40 border border-amber-700 rounded text-xs font-mono text-amber-200">{{ $dup }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="bg-indigo-900/30 border border-indigo-700 rounded-xl p-4">
                    <h3 class="font-semibold text-indigo-300 mb-2">Format</h3>
                    <p class="text-sm text-indigo-400 mb-2">Each line is one sale. Columns comma-separated in this order:</p>
                    <p class="text-sm text-indigo-300 font-mono bg-gray-950 rounded-lg p-2 border border-indigo-800">
                        domain_name, keyword, price, city, state, country
                    </p>
                        <p class="text-sm text-indigo-400 mt-2">
                            Minimum required: <strong>domain_name</strong> and <strong>price</strong>. The rest are optional.
                            <strong>Duplicate domain names are skipped automatically</strong> (both repeats in your paste and ones already in the database).
                        </p>
                    <div class="mt-3 p-3 rounded-lg bg-emerald-900/20 border border-emerald-800/40">
                        <p class="text-xs text-emerald-300 font-semibold mb-1">🚀 Fast import - optimized for 20,000+ rows</p>
                        <p class="text-xs text-emerald-400">
                            <strong>City population is NOT here</strong> - add it separately under
                            <a href="{{ route('admin.populations') }}" class="underline font-semibold">City Populations</a>.
                            Example lines:<br>
                            <span class="font-mono">newyorkapartments.com, apartments, 85000, New York, New York, United States</span><br>
                            <span class="font-mono">miamibeachhomes.com, homes, 42000, Miami, Florida, United States</span>
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.bulk-store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Paste Sales Data *</label>
                        <textarea name="bulk_data" rows="14" required
                                  class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm"
                                  placeholder="newyorkapartments.com, apartments, 85000, New York, New York, United States
losangelestours.com, tours, 42000, Los Angeles, California, United States">{{ old('bulk_data') }}</textarea>
                        @error('bulk_data') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                        <p class="mt-2 text-xs text-gray-500">You can paste 1 or 20,000+ lines. Data is inserted in fast batches.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Delimiter (default: comma)</label>
                        <input type="text" name="delimiter" value="{{ old('delimiter', ',') }}" maxlength="2"
                               class="w-24 px-4 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Import Sales
                        </button>
                        <a href="{{ route('admin.sales') }}" class="inline-flex items-center px-6 py-3 bg-gray-800 text-gray-300 font-semibold rounded-xl hover:bg-gray-700 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
