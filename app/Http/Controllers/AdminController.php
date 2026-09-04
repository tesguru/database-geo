<?php

namespace App\Http\Controllers;

use App\Models\DomainSale;
use App\Services\ClickHouseService;
use App\Services\DomainSearchService;
use App\Services\DomainValuationService;
use Illuminate\Http\Request;

use Database\Seeders\StatePopulationSeeder;

class AdminController extends Controller
{
    protected ClickHouseService $clickhouse;
    protected DomainSearchService $searchService;
    protected DomainValuationService $valuationService;

    public function __construct(
        ClickHouseService $clickhouse,
        DomainSearchService $searchService,
        DomainValuationService $valuationService
    ) {
        $this->clickhouse = $clickhouse;
        $this->searchService = $searchService;
        $this->valuationService = $valuationService;
    }

    public function dashboard()
    {
        $stats = $this->searchService->getStats();

        $totalUsers = \App\Models\User::count();
        $totalSearches = \App\Models\SearchLog::where('activity', 'search')->count();
        $totalLogins = \App\Models\SearchLog::where('activity', 'login')->count();
        $recentSales = $this->searchService->getRecentSales(10);
        $recentUsers = \App\Models\User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentSales', 'recentUsers', 'totalUsers', 'totalSearches', 'totalLogins'));
    }

    public function users()
    {
        $users = \App\Models\User::withCount([
            'searchLogs as login_count' => fn ($q) => $q->where('activity', 'login'),
            'searchLogs as search_count' => fn ($q) => $q->where('activity', 'search'),
        ])->latest()->paginate(25);

        return view('admin.users', ['users' => $users]);
    }

    public function sales()
    {
        $results = $this->searchService->search([], 1, 50, 'id', 'desc');
        return view('admin.sales', ['results' => $results]);
    }

    public function addSale()
    {
        return view('admin.add-sale');
    }

    public function storeSale(Request $request)
    {
        $validated = $request->validate([
            'domain_name' => 'required|string|max:255',
            'keyword' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        if ($this->domainExists($validated['domain_name'])) {
            return back()
                ->withInput()
                ->withErrors(['domain_name' => 'This domain already exists in the database. Duplicate domains are not allowed.'])
                ->with('error', 'Duplicate domain: "' . $validated['domain_name'] . '" already exists.');
        }

        $maxId = $this->clickhouse->query("SELECT max(id) as max_id FROM domain_sales")->fetchOne('max_id') ?? 0;
        $id = (int) $maxId + 1;

        $this->clickhouse->insert('domain_sales', [
            [$id, $validated['domain_name'], $validated['keyword'] ?? '', $validated['price'], $validated['city'] ?? '', $validated['state'] ?? '', $validated['country'] ?? ''],
        ], ['id', 'domain_name', 'keyword', 'price', 'city', 'state', 'country']);

        return redirect()->route('admin.sales')->with('success', 'Sale added successfully!');
    }

    public function editSale(int $id)
    {
        $stmt = $this->clickhouse->query("SELECT * FROM domain_sales WHERE id = :id LIMIT 1", ['id' => $id]);
        $sale = $stmt ? ($stmt->rows()[0] ?? null) : null;

        if (!$sale) {
            return redirect()->route('admin.sales')->with('error', 'Sale not found.');
        }

        return view('admin.edit-sale', ['sale' => $sale, 'saleId' => $id]);
    }

    public function updateSale(Request $request, int $id)
    {
        $validated = $request->validate([
            'domain_name' => 'required|string|max:255',
            'keyword' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $stmt = $this->clickhouse->query("SELECT * FROM domain_sales WHERE id = :id LIMIT 1", ['id' => $id]);
        $existing = $stmt ? ($stmt->rows()[0] ?? null) : null;
        if (!$existing) {
            return redirect()->route('admin.sales')->with('error', 'Sale not found.');
        }

        $newDomain = strtolower(trim($validated['domain_name']));
        $oldDomain = strtolower(trim($existing['domain_name']));

        // Block changing to a domain that already exists (and is a different row than this one)
        if ($newDomain !== $oldDomain) {
            $dup = $this->clickhouse->query(
                "SELECT 1 FROM domain_sales WHERE lower(domain_name) = :d AND id != :id LIMIT 1",
                ['d' => $newDomain, 'id' => $id]
            )->rows();
            if (!empty($dup)) {
                return back()
                    ->withInput()
                    ->withErrors(['domain_name' => 'This domain already exists in the database. Duplicate domains are not allowed.'])
                    ->with('error', 'Duplicate domain: "' . $validated['domain_name'] . '" already exists.');
            }
        }

        $this->clickhouse->execute("ALTER TABLE domain_sales DELETE WHERE id = " . (int) $id . " SETTINGS mutations_sync = 2");
        $maxId = $this->clickhouse->query("SELECT max(id) as max_id FROM domain_sales")->fetchOne('max_id') ?? 0;
        $newId = (int) $maxId + 1;

        $this->clickhouse->insert('domain_sales', [
            [$newId, $validated['domain_name'], $validated['keyword'] ?? '', $validated['price'], $validated['city'] ?? '', $validated['state'] ?? '', $validated['country'] ?? ''],
        ], ['id', 'domain_name', 'keyword', 'price', 'city', 'state', 'country']);

        return redirect()->route('admin.sales')->with('success', 'Sale updated successfully!');
    }

    public function destroySale(Request $request, int $id)
    {
        $stmt = $this->clickhouse->query("SELECT * FROM domain_sales WHERE id = :id LIMIT 1", ['id' => $id]);
        $existing = $stmt ? ($stmt->rows()[0] ?? null) : null;
        if (!$existing) {
            return redirect()->route('admin.sales')->with('error', 'Sale not found.');
        }

        $this->clickhouse->execute("ALTER TABLE domain_sales DELETE WHERE id = " . (int) $id . " SETTINGS mutations_sync = 2");
        return redirect()->route('admin.sales')->with('success', 'Sale "' . $existing['domain_name'] . '" deleted.');
    }

    public function bulkPaste()
    {
        return view('admin.bulk-paste');
    }

    public function storeBulkPaste(Request $request)
    {
        $request->validate([
            'bulk_data' => 'required|string',
            'delimiter' => 'nullable|string|max:5',
        ]);

        $delimiter = $request->input('delimiter', ',');
        $lines = preg_split('/\r\n|\r|\n/', $request->input('bulk_data'));
        $inserted = 0;
        $errors = [];
        $duplicates = [];

        $existing = $this->existingDomains();
        $seen = [];

        $maxId = $this->clickhouse->query("SELECT max(id) as max_id FROM domain_sales")->fetchOne('max_id') ?? 0;
        $currentId = (int) $maxId;

        $batch = [];
        $batchSize = 500;

        foreach ($lines as $lineNum => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = str_getcsv($line, $delimiter[0] ?? ',');
            if (count($parts) < 1) {
                $errors[] = "Line " . ($lineNum + 1) . ": Empty row";
                continue;
            }

            $domain = strtolower(trim($parts[0] ?? ''));

            if (isset($existing[$domain]) || isset($seen[$domain])) {
                $duplicates[] = $domain;
                continue;
            }
            $seen[$domain] = true;

            $currentId++;
            $batch[] = [
                $currentId,
                trim($parts[0] ?? ''),
                trim($parts[1] ?? ''),
                (float) trim($parts[2] ?? '0'),
                trim($parts[3] ?? ''),
                trim($parts[4] ?? ''),
                trim($parts[5] ?? ''),
            ];
            $inserted++;

            if (count($batch) >= $batchSize) {
                $this->flushBatch($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            $this->flushBatch($batch);
        }

        $message = "Successfully imported " . number_format($inserted) . " sales records.";
        if (!empty($duplicates)) {
            $message .= " Skipped " . count($duplicates) . " duplicate domain(s).";
        }
        if (!empty($errors)) {
            $message .= " " . count($errors) . " lines had errors.";
        }

        return redirect()->route('admin.sales')
            ->with('success', $message)
            ->with('import_errors', $errors)
            ->with('import_duplicates', array_slice(array_unique($duplicates), 0, 100));
    }

    protected function existingDomains(): array
    {
        $rows = $this->clickhouse->query("SELECT domain_name FROM domain_sales")->rows();
        $map = [];
        foreach ($rows as $row) {
            $map[strtolower(trim($row['domain_name'] ?? ''))] = true;
        }
        return $map;
    }

    protected function domainExists(string $domain): bool
    {
        $domain = strtolower(trim($domain));
        $stmt = $this->clickhouse->query(
            "SELECT 1 FROM domain_sales WHERE lower(domain_name) = :d LIMIT 1",
            ['d' => $domain]
        );
        $row = $stmt ? $stmt->rows() : [];

        return !empty($row);
    }

    protected function flushBatch(array $batch): void
    {
        $this->clickhouse->insert(
            'domain_sales',
            $batch,
            ['id', 'domain_name', 'keyword', 'price', 'city', 'state', 'country']
        );
    }

    public function setupDatabase()
    {
        DomainSale::createTable($this->clickhouse);
        return redirect()->route('admin.dashboard')->with('success', 'ClickHouse database table created successfully!');
    }

    // ==================== CITY POPULATION MANAGEMENT ====================

    public function populations()
    {
        $rows = [];
        if ($this->clickhouse->isConnected()) {
            $stmt = $this->clickhouse->query("SELECT * FROM city_population ORDER BY id DESC LIMIT 500");
            if ($stmt) $rows = $stmt->rows();
        }
        return view('admin.populations', ['populations' => $rows]);
    }

    public function storePopulation(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'population' => 'required|numeric|min:1',
        ]);

        $maxId = $this->clickhouse->query("SELECT max(id) as max_id FROM city_population")->fetchOne('max_id') ?? 0;
        $id = (int) $maxId + 1;

        $this->clickhouse->insert('city_population', [
            [$id, $validated['city'], $validated['state'] ?? '', $validated['country'] ?? '', (int) $validated['population']],
        ], ['id', 'city', 'state', 'country', 'population']);

        return redirect()->route('admin.populations')->with('success', 'City population added successfully!');
    }

    public function bulkPopulation(Request $request)
    {
        $request->validate(['bulk_data' => 'required|string']);
        $lines = preg_split('/\r\n|\r|\n/', $request->input('bulk_data'));
        $inserted = 0;
        $errors = [];

        $maxId = $this->clickhouse->query("SELECT max(id) as max_id FROM city_population")->fetchOne('max_id') ?? 0;
        $id = (int) $maxId;

        $batch = [];
        foreach ($lines as $lineNum => $line) {
            $line = trim($line);
            if (empty($line)) continue;
            $parts = str_getcsv($line, ',');
            if (count($parts) < 2) {
                $errors[] = "Line " . ($lineNum + 1) . ": Need at least city and population";
                continue;
            }
            $id++;
            $batch[] = [
                $id,
                trim($parts[0]),
                trim($parts[1] ?? ''),
                trim($parts[2] ?? ''),
                (int) trim($parts[3] ?? '0'),
            ];
            $inserted++;
            if (count($batch) >= 500) {
                $this->clickhouse->insert('city_population', $batch, ['id', 'city', 'state', 'country', 'population']);
                $batch = [];
            }
        }
        if (!empty($batch)) {
            $this->clickhouse->insert('city_population', $batch, ['id', 'city', 'state', 'country', 'population']);
        }

        return redirect()->route('admin.populations')
            ->with('success', 'Imported ' . number_format($inserted) . ' city population records.')
            ->with('import_errors', $errors);
    }

    public function destroyPopulation(Request $request, int $id)
    {
        if ($this->clickhouse->isConnected()) {
            $this->clickhouse->query("ALTER TABLE city_population DELETE WHERE id = :id SETTINGS mutations_sync = 2", ['id' => $id]);
        }
        return redirect()->route('admin.populations')->with('success', 'City population record deleted.');
    }

    public function seedPopulations()
    {
        $this->callPopulationSeeder();
        return redirect()->route('admin.populations')->with('success', 'City populations seeded!');
    }

    protected function callPopulationSeeder(): void
    {
        // city, state, country, population
        $rows = [
            ['Los Angeles', 'California', 'United States', 3898747],
            ['New York', 'New York', 'United States', 8336817],
            ['Miami', 'Florida', 'United States', 442241],
            ['Paris', 'Ile-de-France', 'France', 2140526],
            ['Tokyo', 'Tokyo', 'Japan', 13960000],
            ['Sydney', 'New South Wales', 'Australia', 5312000],
            ['London', 'England', 'United Kingdom', 8982000],
            ['Dubai', 'Dubai', 'United Arab Emirates', 3331000],
            ['Berlin', 'Berlin', 'Germany', 3769000],
            ['Toronto', 'Ontario', 'Canada', 2731000],
            ['Singapore', 'Singapore', 'Singapore', 5686000],
            ['Amsterdam', 'North Holland', 'Netherlands', 821752],
            ['Las Vegas', 'Nevada', 'United States', 641903],
            ['Seattle', 'Washington', 'United States', 733919],
            ['Barcelona', 'Catalonia', 'Spain', 1620343],
            ['Rancho Mesa', 'California', 'United States', 7500],
            ['Zurich', 'Zurich', 'Switzerland', 415367],
            ['Cape Town', 'Western Cape', 'South Africa', 433688],
            ['Houston', 'Texas', 'United States', 2304580],
            ['Mumbai', 'Maharashtra', 'India', 20411000],
        ];

        $maxId = $this->clickhouse->query("SELECT max(id) as max_id FROM city_population")->fetchOne('max_id') ?? 0;
        $id = (int) $maxId;
        $batch = [];
        foreach ($rows as $r) {
            $id++;
            $batch[] = [$id, $r[0], $r[1], $r[2], $r[3]];
        }
        $this->clickhouse->insert('city_population', $batch, ['id', 'city', 'state', 'country', 'population']);
    }

    public function seedData()
    {
        $result = $this->callSeeder();
        return redirect()->route('admin.dashboard')->with('success', $result);
    }

    protected function callSeeder(): string
    {
        // domain_name, keyword, price, city, state, country
        $sales = [
            ['losangelesapartments.com', 'apartments', 125000, 'Los Angeles', 'California', 'United States'],
            ['nycproperties.com', 'properties', 98500, 'New York', 'New York', 'United States'],
            ['miamihomes.com', 'homes', 45000, 'Miami', 'Florida', 'United States'],
            ['parisapartments.fr', 'apartments', 78000, 'Paris', 'Ile-de-France', 'France'],
            ['tokyorealestate.jp', 'realestate', 156000, 'Tokyo', 'Tokyo', 'Japan'],
            ['sydneybeachfront.com.au', 'beachfront', 67000, 'Sydney', 'New South Wales', 'Australia'],
            ['londonproperty.co.uk', 'property', 112000, 'London', 'England', 'United Kingdom'],
            ['dubaiinvestments.ae', 'investments', 234000, 'Dubai', 'Dubai', 'United Arab Emirates'],
            ['berlinproperties.de', 'property', 54000, 'Berlin', 'Berlin', 'Germany'],
            ['torontohomes.ca', 'homes', 41000, 'Toronto', 'Ontario', 'Canada'],
            ['singaporecondo.sg', 'condo', 89000, 'Singapore', 'Singapore', 'Singapore'],
            ['amsterdamhouses.nl', 'houses', 38000, 'Amsterdam', 'North Holland', 'Netherlands'],
            ['lasvegasrealestate.com', 'realestate', 72000, 'Las Vegas', 'Nevada', 'United States'],
            ['seattlehomes.com', 'homes', 56000, 'Seattle', 'Washington', 'United States'],
            ['barcelonaapartments.es', 'apartments', 47000, 'Barcelona', 'Catalonia', 'Spain'],
            ['ranchomesa.com', 'homes', 28500, 'Rancho Mesa', 'California', 'United States'],
            ['zurichwealth.ch', 'wealth', 145000, 'Zurich', 'Zurich', 'Switzerland'],
            ['capetownbeach.co.za', 'beach', 31000, 'Cape Town', 'Western Cape', 'South Africa'],
            ['houstonenergy.com', 'energy', 82000, 'Houston', 'Texas', 'United States'],
            ['mumbaihomes.in', 'homes', 39000, 'Mumbai', 'Maharashtra', 'India'],
        ];

        $existing = $this->existingDomains();

        $maxId = $this->clickhouse->query("SELECT max(id) as max_id FROM domain_sales")->fetchOne('max_id') ?? 0;
        $id = (int) $maxId;

        $batch = [];
        $inserted = 0;
        $skipped = 0;
        foreach ($sales as $sale) {
            if (isset($existing[strtolower(trim($sale[0]))])) {
                $skipped++;
                continue;
            }
            $id++;
            $batch[] = [$id, $sale[0], $sale[1], $sale[2], $sale[3], $sale[4], $sale[5]];
            $inserted++;
        }
        if (!empty($batch)) {
            $this->clickhouse->insert('domain_sales', $batch, ['id', 'domain_name', 'keyword', 'price', 'city', 'state', 'country']);
        }

        return 'Sample data seeding complete. ' . $inserted . ' sale(s) added, ' . $skipped . ' duplicate(s) skipped.';
    }

    public function seedStatePopulations()
    {
        $result = StatePopulationSeeder::run($this->clickhouse);
        return redirect()->route('admin.populations')->with('success', $result);
    }
}
