<x-app-layout>
    @php
        $hasSearch = request()->has('keyword') || request()->has('city') || request()->has('state') || request()->has('country') || request()->has('min_price') || request()->has('max_price') || request()->has('sort_by') || request()->has('sort_dir');
    @endphp
    <div class="min-h-screen">
        <!-- Hero Section (only on landing, no active search) -->
        @unless($hasSearch)
        <div class="relative overflow-hidden bg-gray-900 border-b border-gray-800">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-600 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-128 h-128 bg-purple-600 rounded-full blur-3xl"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl font-bold text-white tracking-tight">
                        Search <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-400">Geo Domain</span> Sales
                    </h1>
                    <p class="mt-4 text-lg text-gray-400 max-w-2xl mx-auto">
                        Find premium geographical domain names with instant analysis based on past sales and keywords.
                    </p>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('search') }}" class="mt-8 max-w-3xl mx-auto">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="keyword" value="{{ request('keyword') }}"
                                    placeholder="Search domains, keywords, cities, states, countries..."
                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                            </div>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-semibold rounded-xl hover:from-yellow-300 hover:to-orange-400 transition shadow-lg shadow-orange-900/30">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Search
                            </button>
                        </div>

                        <!-- Filters Row -->
                        <div class="mt-3 flex flex-wrap items-center justify-center gap-2">
                            <input type="text" name="city" value="{{ request('city') }}" placeholder="City"
                                class="px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 text-sm w-28">
                            <input type="text" name="state" value="{{ request('state') }}" placeholder="State"
                                class="px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 text-sm w-28">
                            <input type="text" name="country" value="{{ request('country') }}" placeholder="Country"
                                class="px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 text-sm w-28">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min $"
                                class="px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 text-sm w-24">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max $"
                                class="px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 text-sm w-24">
                            <select name="sort_by" class="px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none text-sm">
                                <option value="price" {{ !request('sort_by') || request('sort_by')=='price' ? 'selected' : '' }}>Sort: Price</option>
                                <option value="domain_name" {{ request('sort_by')=='domain_name' ? 'selected' : '' }}>Sort: Domain</option>
                                <option value="city" {{ request('sort_by')=='city' ? 'selected' : '' }}>Sort: City</option>
                            </select>
                            <select name="sort_dir" class="px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none text-sm">
                                <option value="desc" {{ request('sort_dir','desc')=='desc' ? 'selected' : '' }}>Desc</option>
                                <option value="asc" {{ request('sort_dir')=='asc' ? 'selected' : '' }}>Asc</option>
                            </select>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition text-sm font-medium">
                                Apply
                            </button>
                        </div>
                    </form>

                    @auth
                    <div class="mt-6">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-gray-300 hover:text-white hover:border-gray-500 transition text-sm">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Add Sales (Admin)
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
        @else
        <!-- Compact search bar (shown when a search is active) -->
        <div class="bg-gray-900 border-b border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <form method="GET" action="{{ route('search') }}" class="flex flex-col lg:flex-row gap-3">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                            placeholder="Search domains, keywords, cities, states, countries..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="text" name="city" value="{{ request('city') }}" placeholder="City"
                            class="flex-1 min-w-[90px] px-3 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 text-sm">
                        <input type="text" name="state" value="{{ request('state') }}" placeholder="State"
                            class="flex-1 min-w-[90px] px-3 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 text-sm">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min $"
                            class="flex-1 min-w-[90px] px-3 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 text-sm">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max $"
                            class="flex-1 min-w-[90px] px-3 py-2.5 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 text-sm">
                        <button type="submit" class="flex-1 min-w-[90px] px-4 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition text-sm font-medium text-center">
                            Apply
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endunless

        <!-- Valuation Notice -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="bg-indigo-900/20 border border-indigo-800/40 rounded-xl px-5 py-3 flex items-start gap-3">
                <span class="text-yellow-400 mt-0.5">⚠️</span>
                <p class="text-sm text-indigo-200">
                    Want to know a domain's <strong class="text-indigo-100">chance of selling</strong>? Use the
                    <a href="{{ route('valuation') }}" class="font-semibold text-yellow-300 hover:text-yellow-200 underline">Value a Domain</a> tool - it analyzes the city population and keyword sales from the domain itself. Estimates are <strong class="text-indigo-100">NOT guaranteed</strong>.
                </p>
            </div>
        </div>

        <!-- ClickHouse Connection Notice -->
        @if(isset($clickhouseConnected) && !$clickhouseConnected)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="bg-amber-900/30 border border-amber-800 rounded-2xl p-6 text-center">
                <div class="text-3xl mb-3">⚠️</div>
                <h3 class="text-lg font-bold text-amber-300">ClickHouse Not Connected</h3>
                <p class="text-amber-400 mt-2 max-w-xl mx-auto">
                    ClickHouse is required for search. Configure the connection in your <code class="bg-amber-900/40 px-2 py-0.5 rounded">.env</code> file.
                </p>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            @if(isset($loginRequired) && $loginRequired)
            <!-- Login Required (free searches used up) -->
            <div class="max-w-2xl mx-auto text-center py-16">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white">You've used your 5 free searches</h2>
                <p class="text-gray-400 mt-4 max-w-md mx-auto">
                    Create a free account to unlock <strong class="text-white">unlimited searches</strong>, keep your favorite domains, and get the full value estimates.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold rounded-xl hover:from-indigo-600 hover:to-purple-600 transition shadow-lg shadow-indigo-900/40">
                        Create Free Account
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3 bg-gray-800 border border-gray-700 text-gray-200 font-semibold rounded-xl hover:bg-gray-700 transition">
                        I already have an account
                    </a>
                </div>
                <p class="text-xs text-gray-600 mt-6">No card required. Free forever for logged-in members.</p>
            </div>
            @else

            <!-- Results Area -->
            <div id="search-results">
                <div id="search-loading" class="hidden">
                    <div class="py-24 flex flex-col items-center justify-center">
                        <svg class="animate-spin h-10 w-10 text-yellow-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <p class="mt-3 text-sm text-gray-300">Searching your dataset…</p>
                    </div>
                </div>
                <div id="search-content">
                    @include('search.partials.results')
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const content = document.getElementById('search-content');
        const loading = document.getElementById('search-loading');
        if (!content) return;

        function showLoading() {
            if (loading) loading.classList.remove('hidden');
            content.classList.add('hidden');
        }

        function hideLoading() {
            if (loading) loading.classList.add('hidden');
            content.classList.remove('hidden');
        }

        async function loadSearch(url) {
            const u = new URL(url, window.location.origin);
            u.searchParams.set('ajax', '1');
            showLoading();
            try {
                const res = await fetch(u.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) throw new Error('Search request failed: ' + res.status);
                const data = await res.json();
                if (data.html !== undefined) {
                    content.innerHTML = data.html;
                }
                const clean = u.toString().replace(/[?&]ajax=1/, '').replace(/[?&]ajax=1&/, '&');
                history.pushState({}, '', clean);
                requestAnimationFrame(() => {
                    const top = content.getBoundingClientRect().top + window.scrollY - 20;
                    window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
                });
            } catch (e) {
                console.error(e);
            } finally {
                hideLoading();
            }
        }

        document.addEventListener('submit', function (e) {
            const form = e.target.closest('form[method="GET"]');
            if (!form) return;
            const action = form.getAttribute('action') || '';
            if (!action.endsWith('/search') && !action.includes('/search?')) return;
            e.preventDefault();
            const fd = new FormData(form);
            const u = new URL(action || window.location.href, window.location.origin);
            fd.forEach((v, k) => u.searchParams.set(k, v));
            loadSearch(u.toString());
        });

        document.addEventListener('click', function (e) {
            const link = e.target.closest('a[data-ajax]');
            if (!link) return;
            const href = link.getAttribute('href');
            if (!href || link.classList.contains('pointer-events-none')) return;
            e.preventDefault();
            loadSearch(href);
        });

        window.addEventListener('popstate', function () {
            loadSearch(window.location.href);
        });
    });
    </script>
</x-app-layout>