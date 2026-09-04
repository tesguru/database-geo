<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">Edit Sale</h1>
                    <p class="text-gray-400 mt-1">Update this domain sale record</p>
                </div>
                <a href="{{ route('admin.sales') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium">← Back to Sales</a>
            </div>

            <div class="bg-gray-900 rounded-2xl border border-gray-800 p-8 space-y-6">
                @if(session('error'))
                    <div class="p-4 rounded-xl bg-red-900/30 border border-red-700 text-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="bg-amber-900/20 border border-amber-800/40 rounded-xl px-4 py-3">
                    <p class="text-sm text-amber-300">
                        Editing <strong class="text-amber-200">{{ $sale['domain_name'] }}</strong> (ID: {{ $saleId }}).
                        Updating the domain to one that already exists is blocked to prevent duplicates.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.update-sale', $saleId) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Domain Name *</label>
                            <input type="text" name="domain_name" value="{{ old('domain_name', $sale['domain_name']) }}" required
                                   class="w-full px-4 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="e.g. newyorkhomes.com">
                            @error('domain_name') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                            <p class="mt-1 text-xs text-gray-500">Duplicate domain names are not allowed.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Keyword</label>
                            <input type="text" name="keyword" value="{{ old('keyword', $sale['keyword']) }}"
                                   class="w-full px-4 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="e.g. apartments, homes, realestate">
                            <p class="mt-1 text-xs text-gray-500">The industry/keyword this domain targets - used for valuation.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Price (USD) *</label>
                            <input type="number" name="price" value="{{ old('price', $sale['price']) }}" required step="0.01" min="0"
                                   class="w-full px-4 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="e.g. 50000">
                            @error('price') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">City</label>
                            <input type="text" name="city" value="{{ old('city', $sale['city']) }}"
                                   class="w-full px-4 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="e.g. Los Angeles">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">State/Province</label>
                            <input type="text" name="state" value="{{ old('state', $sale['state']) }}"
                                   class="w-full px-4 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="e.g. California">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Country</label>
                            <input type="text" name="country" value="{{ old('country', $sale['country']) }}"
                                   class="w-full px-4 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="e.g. United States">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
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
