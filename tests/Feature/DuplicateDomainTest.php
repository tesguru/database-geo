<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuplicateDomainTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['is_admin' => true]);
    }

    public function test_add_sale_rejects_duplicate_domain(): void
    {
        $existing = \App\Services\ClickHouseService::class;
        $ch = app($existing);
        $rows = $ch->query("SELECT domain_name FROM domain_sales LIMIT 1")->rows();
        $this->assertNotEmpty($rows, 'Need at least one existing sale to test against');
        $existingDomain = $rows[0]['domain_name'];

        $response = $this->actingAs($this->user)->post('/admin/sales/store', [
            'domain_name' => $existingDomain,
            'keyword' => 'homes',
            'price' => 50000,
            'city' => 'Miami',
            'state' => 'Florida',
            'country' => 'United States',
        ]);

        $response->assertSessionHasErrors('domain_name');
    }

    public function test_bulk_paste_skips_duplicate_domain(): void
    {
        $ch = app(\App\Services\ClickHouseService::class);
        $rows = $ch->query("SELECT domain_name FROM domain_sales LIMIT 1")->rows();
        $this->assertNotEmpty($rows);
        $existingDomain = $rows[0]['domain_name'];
        $countBefore = (int) $ch->query("SELECT count() as c FROM domain_sales")->fetchOne('c');

        // Both lines are duplicates: one already in DB, one repeated in the paste.
        $data = $existingDomain . ', homes, 50000, Miami, Florida, United States' . "\n"
              . $existingDomain . ', homes, 60000, Miami, Florida, United States';

        $response = $this->actingAs($this->user)->post('/admin/sales/bulk-store', [
            'bulk_data' => $data,
            'delimiter' => ',',
        ]);

        $countAfter = (int) $ch->query("SELECT count() as c FROM domain_sales")->fetchOne('c');

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHas('import_duplicates');
        $this->assertSame($countBefore, $countAfter, 'No rows should be added when every line is a duplicate');
    }
}
