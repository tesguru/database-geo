<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">Sales Analytics</h1>
                    <p class="text-gray-400 mt-1">The keywords and cities that appear most often across our past-sales records.</p>
                </div>
                <a href="{{ route('search') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition text-sm font-medium">
                    Browse all sales
                </a>
            </div>

            @php
                $totalSales = (int) ($stats['total_sales'] ?? 0);
                $topKeyword = $keywords['items'][0] ?? null;
                $topCity = $cities['items'][0] ?? null;
            @endphp

            @if($totalSales > 0)

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800">
                    <p class="text-sm text-gray-400 font-medium">Recorded Sales</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalSales) }}</p>
                    <p class="text-xs text-gray-500 mt-2">total records in database</p>
                </div>
                <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800">
                    <p class="text-sm text-gray-400 font-medium">Sales with Keyword</p>
                    <p class="text-3xl font-bold text-purple-400 mt-1">{{ number_format($keywords['total_sales']) }}</p>
                    <p class="text-xs text-gray-500 mt-2">weighted keywords captured</p>
                </div>
                <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800">
                    <p class="text-sm text-gray-400 font-medium">Sales with City</p>
                    <p class="text-3xl font-bold text-indigo-400 mt-1">{{ number_format($cities['total_sales']) }}</p>
                    <p class="text-xs text-gray-500 mt-2">city-attributed records</p>
                </div>
                <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800">
                    <p class="text-sm text-gray-400 font-medium">Most Frequent Keyword</p>
                    <p class="text-2xl font-bold text-emerald-300 mt-2 truncate">{{ $topKeyword['keyword'] ?? '—' }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $topKeyword ? number_format((int) $topKeyword['sales_count']) : 0 }} sales in our data</p>
                </div>
            </div>

            <!-- Insight banner -->
            @if($topKeyword && $keywords['total_sales'] > 0)
            @php
                $top10 = array_sum(array_map(fn ($i) => (int) $i['sales_count'], array_slice($keywords['items'], 0, 10)));
                $top10Share = $top10 / $keywords['total_sales'] * 100;
            @endphp
            <div class="mb-10 bg-gradient-to-r from-indigo-900/40 to-purple-900/40 border border-indigo-800/40 rounded-2xl px-6 py-5">
                <p class="text-sm text-indigo-200">
                    <span class="font-bold text-white">{{ $topKeyword['keyword'] }}</span> is our #1 most frequent keyword, appearing in
                    <span class="font-bold text-emerald-300">{{ number_format((int) $topKeyword['sales_count']) }} sales</span>.
                    The top 10 keywords cover
                    <span class="font-bold text-white">{{ number_format($top10Share, 1) }}%</span> of all keyword-attributed sales.
                </p>
            </div>
            @endif

            <!-- Premium Sales Board -->
            @if(count($premiumSales))
            <div class="mb-10 bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-white">Premium Sales Board</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Top 20 highest-priced domains in our records</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-yellow-500/10 border border-yellow-500/30 text-xs font-semibold text-yellow-400">
                        Record Highs
                    </span>
                </div>
                <div class="divide-y divide-gray-800">
                    @foreach($premiumSales as $i => $sale)
                    @php $rank = $i + 1; @endphp
                    <div class="px-6 py-3.5 flex items-center gap-4 hover:bg-gray-800/40 transition">
                        <div class="{{ $rank <= 3 ? 'w-9 h-9 shrink-0 rounded-xl flex items-center justify-center font-bold ' . ($rank == 1 ? 'bg-yellow-500 text-gray-900' : ($rank == 2 ? 'bg-gray-300 text-gray-900' : 'bg-amber-600 text-white')) : 'w-9 h-9 shrink-0 rounded-xl bg-gray-800 text-gray-400 flex items-center justify-center font-semibold' }}">
                            {{ $rank }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="font-mono font-semibold text-white block truncate">{{ $sale['domain_name'] }}</span>
                            <div class="flex items-center gap-2 mt-1">
                                @if($sale['keyword'])
                                <span class="px-2 py-0.5 rounded-md bg-purple-500/10 border border-purple-500/30 text-[11px] font-medium text-purple-300">{{ $sale['keyword'] }}</span>
                                @endif
                                <span class="text-[11px] text-gray-500">{{ $sale['city'] ?: '—' }}{{ $sale['state'] ? ' · ' . $sale['state'] : '' }}</span>
                            </div>
                        </div>
                        <span class="shrink-0 font-bold text-emerald-300">${{ number_format((float) $sale['price']) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

                <!-- Top 50 Keywords -->
                <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-white">Top 50 Keywords</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Most frequent keywords by number of sales</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-purple-500/10 border border-purple-500/30 text-xs font-semibold text-purple-400">
                            {{ count($keywords['items']) }} shown
                        </span>
                    </div>
                    <div class="divide-y divide-gray-800 max-h-[720px] overflow-y-auto">
                        @forelse($keywords['items'] as $i => $kw)
                        @php
                            $count = (int) $kw['sales_count'];
                            $maxCount = (int) ($keywords['items'][0]['sales_count'] ?? 1);
                            $barPct = $maxCount > 0 ? round($count / $maxCount * 100, 1) : 0;
                            $share = $keywords['total_sales'] > 0 ? $count / $keywords['total_sales'] * 100 : 0;
                            $rank = $i + 1;
                        @endphp
                        <div class="px-6 py-3.5 flex items-center gap-4 hover:bg-gray-800/40 transition">
                            <div class="{{ $rank <= 3 ? 'w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm ' . ($rank == 1 ? 'bg-yellow-500 text-gray-900' : ($rank == 2 ? 'bg-gray-300 text-gray-900' : 'bg-amber-600 text-white')) : 'w-8 h-8 rounded-lg bg-gray-800 text-gray-400 flex items-center justify-center font-semibold text-sm' }}">
                                {{ $rank }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline justify-between gap-2">
                                    <span class="font-semibold text-white truncate">{{ $kw['keyword'] }}</span>
                                    <span class="shrink-0 font-bold text-white text-sm">{{ number_format($count) }} sales</span>
                                </div>
                                <div class="mt-2 h-1.5 rounded-full bg-gray-800 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-fuchsia-400" style="width: {{ max(1, $barPct) }}%"></div>
                                </div>
                                <div class="mt-1 flex items-center justify-between text-[11px] text-gray-500">
                                    <span>{{ number_format((int) $kw['cities_count']) }} cities</span>
                                    <span class="text-purple-400 font-medium">{{ number_format($share, 1) }}% of sales</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-12 text-center text-gray-500">No keyword-attributed sales yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Top 50 Cities -->
                <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-white">Top 50 Cities</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Most frequent cities by number of sales</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-500/10 border border-indigo-500/30 text-xs font-semibold text-indigo-400">
                            {{ count($cities['items']) }} shown
                        </span>
                    </div>
                    <div class="divide-y divide-gray-800 max-h-[720px] overflow-y-auto">
                        @forelse($cities['items'] as $i => $city)
                        @php
                            $count = (int) $city['sales_count'];
                            $maxCount = (int) ($cities['items'][0]['sales_count'] ?? 1);
                            $barPct = $maxCount > 0 ? round($count / $maxCount * 100, 1) : 0;
                            $share = $cities['total_sales'] > 0 ? $count / $cities['total_sales'] * 100 : 0;
                            $rank = $i + 1;
                        @endphp
                        <div class="px-6 py-3.5 flex items-center gap-4 hover:bg-gray-800/40 transition">
                            <div class="{{ $rank <= 3 ? 'w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm ' . ($rank == 1 ? 'bg-yellow-500 text-gray-900' : ($rank == 2 ? 'bg-gray-300 text-gray-900' : 'bg-amber-600 text-white')) : 'w-8 h-8 rounded-lg bg-gray-800 text-gray-400 flex items-center justify-center font-semibold text-sm' }}">
                                {{ $rank }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline justify-between gap-2">
                                    <span class="font-semibold text-white truncate">{{ $city['city'] }}</span>
                                    <span class="shrink-0 font-bold text-white text-sm">{{ number_format($count) }} sales</span>
                                </div>
                                <div class="mt-2 h-1.5 rounded-full bg-gray-800 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-400" style="width: {{ max(1, $barPct) }}%"></div>
                                </div>
                                <div class="mt-1 flex items-center justify-between text-[11px] text-gray-500">
                                    <span>{{ $city['state'] ?: '—' }}</span>
                                    <span class="text-indigo-400 font-medium">{{ number_format($share, 1) }}% of sales</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-12 text-center text-gray-500">No city-attributed sales yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            @else
            <div class="bg-gray-900 rounded-2xl border border-gray-800 p-16 text-center">
                <div class="w-16 h-16 mx-auto bg-gray-800 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white">No sales data yet</h3>
                <p class="text-gray-500 mt-2">Add past sales records to unlock the analytics.</p>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>