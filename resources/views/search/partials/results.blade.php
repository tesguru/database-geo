@if(isset($results) && $results['total'] > 0)
<!-- Results Header -->
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 mb-6">
    <div>
        <h2 class="text-xl font-bold text-white">
            {{ number_format($results['total']) }} Results
        </h2>
        <p class="text-sm text-gray-400 mt-1">
            @if(request('keyword'))
                Showing results for "<span class="font-medium text-indigo-400">{{ request('keyword') }}</span>"
            @elseif(request('city') || request('state') || request('country'))
                Filtered by location filters
            @else
                Showing all available domain sales
            @endif
        </p>
    </div>
    <div class="text-sm text-gray-500">
        Page {{ $results['page'] }} of {{ max($results['last_page'], 1) }}
    </div>
</div>

<!-- Results Table -->
<div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden shadow-xl shadow-black/20">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-800">
            <thead class="bg-gray-800/60">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Domain</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Keyword</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Sale Price</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Chance to Sell</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @foreach($results['data'] as $sale)
                @php
                    $val = $valuations[$sale['id']] ?? null;
                @endphp
                <tr class="hover:bg-gray-800/40 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center mr-3">
                                <span class="text-white font-bold text-sm">{{ strtoupper(substr($sale['domain_name'], 0, 1)) }}</span>
                            </div>
                            <div class="font-semibold text-white">{{ $sale['domain_name'] }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($sale['keyword'])
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-purple-500/10 border border-purple-500/30 text-xs font-semibold text-purple-400">
                            {{ $sale['keyword'] }}
                        </span>
                        @else
                        <span class="text-gray-600 text-sm">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-300">
                            {{ $sale['city'] ?: '-' }}{{ $sale['state'] ? ', '.$sale['state'] : '' }}
                        </div>
                        <div class="text-xs text-gray-500">{{ $sale['country'] ?: '' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 text-sm font-bold">
                            ${{ number_format($sale['price'], 2) }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        @if($val && !empty($val['success']) && isset($val['chance']))
                            @php $p = (int) $val['chance']; @endphp
                            <div class="flex items-center gap-2">
                                <div class="w-16 h-2 rounded-full bg-gray-800 overflow-hidden">
                                    <div class="h-full rounded-full {{ $p >= 70 ? 'bg-emerald-500' : ($p >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                         style="width: {{ $p }}%"></div>
                                </div>
                                <span class="text-xs font-bold {{ $p >= 70 ? 'text-emerald-400' : ($p >= 40 ? 'text-yellow-400' : 'text-red-400') }}">{{ $p }}%</span>
                            </div>
                            <div class="text-[10px] text-gray-500 mt-1">{{ $p >= 70 ? 'High chance' : ($p >= 40 ? 'Moderate chance' : 'Low chance') }}</div>
                        @else
                        <span class="text-gray-600 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('valuation.analyze') }}" onclick="event.preventDefault(); document.getElementById('value-form-{{ $sale['id'] }}').submit();"
                           class="inline-flex items-center px-3 py-1.5 rounded-lg bg-yellow-500/10 border border-yellow-500/30 text-xs font-semibold text-yellow-400 hover:bg-yellow-500/20 transition">
                            Analyze
                        </a>
                        <form id="value-form-{{ $sale['id'] }}" method="POST" action="{{ route('valuation.analyze') }}" class="hidden">
                            @csrf
                            <input type="hidden" name="domain_name" value="{{ $sale['domain_name'] }}">
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Valuation Info Modal -->
<div x-data="{ open: false, note: '' }"
     x-on:open-val.window="note = $event.detail.html; open = true"
     x-show="open" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-transition.opacity>
    <div class="absolute inset-0 bg-black/70" @click="open = false"></div>
    <div class="relative bg-gray-900 border border-gray-700 rounded-2xl p-6 max-w-md w-full shadow-2xl">
        <h3 class="text-lg font-bold text-white mb-3">How is value estimated?</h3>
        <p class="text-sm text-gray-300" x-text="note"></p>
        <div class="mt-4 p-3 rounded-lg bg-yellow-900/20 border border-yellow-800/40">
            <p class="text-xs text-yellow-300">
                ⚠️ This is an ESTIMATE only, based on past sales and keyword matches. Not investment advice and NOT guaranteed.
            </p>
        </div>
        <button @click="open = false" class="mt-5 w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
            Close
        </button>
    </div>
</div>

<!-- Pagination -->
@if($results['last_page'] > 1)
<div class="mt-8 flex items-center justify-center gap-2">
    @php $q = array_merge(request()->except(['page']), ['page' => 1]); @endphp
    <a href="{{ route('search', $q) }}" data-ajax
       class="{{ $results['page'] == 1 ? 'opacity-40 pointer-events-none' : '' }} px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-sm font-medium text-gray-300 hover:bg-gray-700 transition">
        &laquo;
    </a>
    @php $start = max(1, $results['page'] - 2); $end = min($results['last_page'], $results['page'] + 2); @endphp
    @for($p = $start; $p <= $end; $p++)
        @php $q = array_merge(request()->except(['page']), ['page' => $p]); @endphp
        <a href="{{ route('search', $q) }}" data-ajax
           class="{{ $p == $results['page'] ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white border-transparent' : 'bg-gray-800 text-gray-300 border-gray-700 hover:bg-gray-700' }} px-4 py-2 rounded-lg border text-sm font-medium transition">
            {{ $p }}
        </a>
    @endfor
    @php $q = array_merge(request()->except(['page']), ['page' => $results['last_page']]); @endphp
    <a href="{{ route('search', $q) }}" data-ajax
       class="{{ $results['page'] == $results['last_page'] ? 'opacity-40 pointer-events-none' : '' }} px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-sm font-medium text-gray-300 hover:bg-gray-700 transition">
        &raquo;
    </a>
</div>
@endif

@elseif(isset($results))
<!-- Empty State -->
<div class="text-center py-20">
    <div class="w-20 h-20 mx-auto bg-gray-800 rounded-2xl flex items-center justify-center mb-4">
        <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
    <h3 class="text-xl font-bold text-white">No results found</h3>
    <p class="text-gray-500 mt-2">Try adjusting your search filters or browse all available sales.</p>
    <a href="{{ route('home') }}" class="inline-flex items-center mt-6 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold rounded-xl hover:from-indigo-600 hover:to-purple-600 transition">
        View All Sales
    </a>
</div>
@endif