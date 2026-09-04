<x-app-layout>
    <div class="min-h-screen">
        <!-- Hero -->
        <div class="relative overflow-hidden bg-gray-900 border-b border-gray-800">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 left-0 w-96 h-96 bg-yellow-600 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-128 h-128 bg-orange-600 rounded-full blur-3xl"></div>
            </div>
            <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-white tracking-tight">
                    Value a <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-400">Geo Domain</span>
                </h1>
                <p class="mt-4 text-lg text-gray-400">
                    Enter a domain and we'll analyze the <strong class="text-white">city</strong> and <strong class="text-white">keyword</strong> inside it against our past sales data.
                </p>

                <!-- Alert: geo domains only -->
                <div class="mt-6 bg-yellow-900/20 border border-yellow-800/40 rounded-xl px-5 py-3 flex items-start justify-center gap-3 text-left">
                    <span class="text-yellow-400 mt-0.5">⚠️</span>
                    <p class="text-sm text-yellow-200">
                        This tool <strong>ONLY analyzes GEO domains</strong> (a city + keyword + TLD, e.g. <span class="font-mono">losangeleshomes.com</span>).
                        The result is an <strong>estimate</strong> based on city population and how many times the keyword has sold in our data. It is <strong>NOT guaranteed</strong>.
                    </p>
                </div>

                <!-- Domain input form -->
                <form method="POST" action="{{ route('valuation.analyze') }}" class="mt-8 max-w-xl mx-auto">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <input type="text" name="domain_name" value="{{ $domainInput ?? '' }}"
                                placeholder="e.g. losangeleshomes.com or nycapartments.com"
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-semibold rounded-xl hover:from-yellow-300 hover:to-orange-400 transition shadow-lg shadow-orange-900/30">
                            Analyze Domain
                        </button>
                    </div>
                    @error('domain_name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                </form>
            </div>
        </div>

        <!-- Results -->
        @if(isset($result))
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            @if($result['success'])
            <!-- Success analysis card -->
            <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-5 border-b border-gray-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $result['domain_name'] }}</h2>
                        <p class="text-sm text-gray-400 mt-1">
                            Detected as a <span class="text-yellow-400 font-semibold">GEO domain</span> — parsed correctly.
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Chance of selling</p>
                        <p class="text-3xl font-bold {{ $result['chance'] >= 70 ? 'text-emerald-400' : ($result['chance'] >= 40 ? 'text-yellow-400' : 'text-red-400') }}">{{ $result['chance'] }}%</p>
                    </div>
                </div>

                <!-- The 3 analysis factors -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-6">
                    <!-- CITY -->
                    <div class="bg-gray-800/50 rounded-xl p-4 border border-gray-700">
                        <p class="text-xs text-gray-400 uppercase tracking-wider flex items-center">
                            <svg class="h-3.5 w-3.5 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                            The City
                        </p>
                        <p class="text-lg font-bold text-white mt-2">{{ $result['city'] }}</p>
                        <p class="text-sm {{ $result['population'] > 0 ? 'text-gray-300' : 'text-gray-500' }}">
                            {{ $result['city_rating'] }}
                            @if($result['population'] > 0)
                                <span class="text-gray-400 block text-xs mt-0.5">~{{ number_format($result['population']) }} people</span>
                            @endif
                        </p>
                        <div class="mt-3 pt-3 border-t border-gray-700">
                            <p class="text-xs text-gray-400">Past sales in this city:</p>
                            <p class="text-xl font-bold text-white">{{ $result['city_sales'] }}</p>
                        </div>
                    </div>

                    <!-- KEYWORD -->
                    <div class="bg-gray-800/50 rounded-xl p-4 border border-gray-700">
                        <p class="text-xs text-gray-400 uppercase tracking-wider flex items-center">
                            <svg class="h-3.5 w-3.5 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                            The Keyword
                        </p>
                        <p class="text-lg font-bold text-white mt-2">"{{ $result['keyword'] }}"</p>
                        <p class="text-sm text-gray-300">{{ $result['keyword_demand'] }}</p>
                        <div class="mt-3 pt-3 border-t border-gray-700">
                            <p class="text-xs text-gray-400">Times keyword sold in our data:</p>
                            <p class="text-xl font-bold text-white">{{ $result['keyword_sales'] }}</p>
                        </div>
                    </div>

                    <!-- EXACT MATCHES -->
                    <div class="bg-gray-800/50 rounded-xl p-4 border border-gray-700">
                        <p class="text-xs text-gray-400 uppercase tracking-wider flex items-center">
                            <svg class="h-3.5 w-3.5 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Exact Past Sales
                        </p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $result['exact_matches'] }}</p>
                        <p class="text-sm text-gray-400 mt-1">sales of this exact city + keyword combo</p>
                        <div class="mt-3 pt-3 border-t border-gray-700">
                            <p class="text-xs text-gray-400">TLD:</p>
                            <p class="text-lg font-bold text-white">{{ $result['tld'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Estimated value -->
                <div class="px-6 pb-6">
                    @if(!empty($result['has_estimate']) && $result['estimated_value'] > 0)
                    <div class="bg-emerald-900/20 border border-emerald-800/40 rounded-xl p-5">
                        <p class="text-xs text-emerald-300 uppercase tracking-wider">Estimated Value (from comparable past sales)</p>
                        <div class="flex items-end gap-4 mt-2 flex-wrap">
                            <p class="text-3xl font-bold text-emerald-400">${{ number_format($result['estimated_value'], 0) }}</p>
                            <p class="text-sm text-emerald-200">range ${{ number_format($result['estimate_low'], 0) }} – ${{ number_format($result['estimate_high'], 0) }}</p>
                        </div>
                    </div>
                    @else
                    <div class="bg-gray-800/40 border border-gray-700 rounded-xl p-5">
                        <p class="text-sm text-gray-400">Could not estimate a value yet — no comparable city + keyword sales in our data.</p>
                    </div>
                    @endif

                    <!-- Domains in our past data -->
                    @if(!empty($result['comparables']))
                    <div class="mt-5 bg-gray-800/40 border border-gray-700 rounded-xl p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-300">Domains in our past data</h3>
                            <span class="text-xs text-gray-500">{{ count($result['comparables']) }} matching sale(s)</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 mb-3">These are the actual domains we have on record that match this city and/or keyword.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($result['comparables'] as $cmp)
                            <div class="flex items-center justify-between bg-gray-900/60 border border-gray-800 rounded-lg px-3 py-2">
                                <div class="min-w-0">
                                    <p class="font-mono text-sm text-white truncate">{{ $cmp['domain_name'] }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">
                                        {{ $cmp['city'] ?: '-' }}{{ $cmp['keyword'] ? ' · '.$cmp['keyword'] : '' }}
                                    </p>
                                </div>
                                <span class="shrink-0 ml-2 text-sm font-semibold text-emerald-400">${{ number_format($cmp['price'], 0) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Explanation -->
                    <div class="mt-5 bg-gray-800/40 border border-gray-700 rounded-xl p-5">
                        <h3 class="text-sm font-semibold text-gray-300 mb-2">Why this score?</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">{{ $result['note'] }}</p>
                    </div>

                    <!-- Warning -->
                    <div class="mt-5 bg-yellow-900/20 border border-yellow-800/40 rounded-xl p-4">
                        <p class="text-xs text-yellow-300">⚠️ {{ $result['warning'] }}</p>
                    </div>
                </div>
            </div>

            @else
            <!-- Error / no data card -->
            <div class="bg-gray-900 rounded-2xl border border-gray-800 p-8 text-center">
                <div class="text-4xl mb-4">🤔</div>
                <h3 class="text-xl font-bold text-white">{{ $result['message'] }}</h3>
                @if(!empty($result['city']) && !empty($result['keyword']))
                <div class="mt-6 bg-gray-800/50 rounded-xl p-4 border border-gray-700">
                    <p class="text-sm text-gray-400">We parsed:</p>
                    <div class="flex items-center justify-center gap-4 mt-2 flex-wrap">
                        <span class="px-3 py-1 rounded-lg bg-yellow-500/10 border border-yellow-500/30 text-xs font-semibold text-yellow-400">City: {{ $result['city'] }}</span>
                        <span class="px-3 py-1 rounded-lg bg-purple-500/10 border border-purple-500/30 text-xs font-semibold text-purple-400">Keyword: {{ $result['keyword'] }}</span>
                        <span class="px-3 py-1 rounded-lg bg-indigo-500/10 border border-indigo-500/30 text-xs font-semibold text-indigo-400">TLD: {{ $result['tld'] }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">Add sales for this city/keyword to get an analysis.</p>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif
    </div>
</x-app-layout>
