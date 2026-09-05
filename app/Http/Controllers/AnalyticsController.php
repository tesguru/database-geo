<?php

namespace App\Http\Controllers;

use App\Services\DomainSearchService;

class AnalyticsController extends Controller
{
    protected DomainSearchService $searchService;

    public function __construct(DomainSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function index()
    {
        $keywords = $this->searchService->topKeywords(50);
        $cities = $this->searchService->topCities(50);
        $stats = $this->searchService->getStats();
        $premiumSales = $this->searchService->premiumSales(20);
        $avgPriceByKeyword = $this->searchService->averagePriceByKeyword(30);

        return view('analytics', compact('keywords', 'cities', 'stats', 'premiumSales', 'avgPriceByKeyword'));
    }
}