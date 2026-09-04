<?php

namespace App\Http\Controllers;

use App\Services\DomainValuationService;
use Illuminate\Http\Request;

class ValuationController extends Controller
{
    protected DomainValuationService $valuationService;

    public function __construct(DomainValuationService $valuationService)
    {
        $this->valuationService = $valuationService;
    }

    public function index()
    {
        return view('valuation', [
            'result' => null,
            'domainInput' => '',
        ]);
    }

    public function analyze(Request $request)
    {
        $domain = trim($request->input('domain_name'));

        if (empty($domain)) {
            return back()->withErrors(['domain_name' => 'Please enter a domain name to analyze.']);
        }

        $result = $this->valuationService->valueDomain($domain);

        return view('valuation', [
            'result' => $result,
            'domainInput' => $domain,
        ]);
    }
}
