<?php

namespace App\Http\Controllers;

use App\Services\DomainSearchService;
use App\Services\DomainValuationService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected DomainSearchService $searchService;
    protected DomainValuationService $valuationService;
    public bool $guestSearchBlocked = false;

    public function __construct(DomainSearchService $searchService, DomainValuationService $valuationService)
    {
        $this->searchService = $searchService;
        $this->valuationService = $valuationService;
    }

    public function index()
    {
        return view('search', [
            'results' => null,
            'filters' => [],
            'valuations' => [],
            'clickhouseConnected' => $this->searchService->isConnected(),
        ]);
    }

    public function search(Request $request)
    {
        $filters = $request->only([
            'keyword', 'city', 'state', 'country', 'min_price', 'max_price',
        ]);

        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 20);
        $sortBy = $request->input('sort_by', 'price');
        $sortDir = $request->input('sort_dir', 'desc');

        // 5 free searches for guests, unlimited for logged-in users
        $this->enforceSearchLimit($request);

        if ($this->guestSearchBlocked) {
            if ($request->expectsJson()) {
                return response()->json(['login_required' => true], 403);
            }
            return view('search', [
                'results' => null,
                'filters' => $filters,
                'valuations' => [],
                'clickhouseConnected' => $this->searchService->isConnected(),
                'loginRequired' => true,
            ]);
        }

        $results = $this->searchService->search($filters, $page, $perPage, $sortBy, $sortDir);

        $valuations = [];
        if (!empty($results['data'])) {
            $valuations = $this->valuationService->valueMany($results['data']);
        }

        if ($request->boolean('ajax')) {
            return response()->json([
                'html' => view('search.partials.results', [
                    'results' => $results,
                    'valuations' => $valuations,
                ])->render(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['results' => $results, 'valuations' => $valuations]);
        }

        return view('search', [
            'results' => $results,
            'filters' => $filters,
            'valuations' => $valuations,
            'clickhouseConnected' => $this->searchService->isConnected(),
            'loginRequired' => $this->guestSearchBlocked,
        ]);
    }

    protected function enforceSearchLimit(Request $request): void
    {
        $this->guestSearchBlocked = false;

        // Logged-in users can search unlimited
        if ($request->user()) {
            \App\Models\SearchLog::create([
                'user_id' => $request->user()->id,
                'activity' => 'search',
                'query' => $request->input('keyword') ?: '',
                'ip_address' => $request->ip(),
            ]);
            return;
        }

        // Guests: track free searches in the session (limit 5)
        $count = (int) $request->session()->get('free_searches', 0);

        if ($count >= 5) {
            $this->guestSearchBlocked = true;
            return;
        }

        $request->session()->put('free_searches', $count + 1);
    }
}
