<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ClickHouseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleEditDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['is_admin' => true]);
        $this->ch = app(ClickHouseService::class);
    }

    // Clean up any rows the tests create in ClickHouse (which RefreshDatabase
    // does not reset) so test runs never pollute real data.
    protected function tearDown(): void
    {
        $all = $this->ch->query("SELECT id, domain_name FROM domain_sales")->rows();
        foreach ($all as $row) {
            $name = strtolower($row['domain_name'] ?? '');
            if (
                str_starts_with($name, 'editsale')
                || str_starts_with($name, 'editdup')
                || str_starts_with($name, 'deletesale')
                || str_starts_with($name, 'temp')
            ) {
                $this->ch->execute("ALTER TABLE domain_sales DELETE WHERE id = " . (int) $row['id'] . " SETTINGS mutations_sync = 2");
            }
        }
        parent::tearDown();
    }

    private function insertTempSale(string $domain): int
    {
        $maxId = (int) ($this->ch->query("SELECT max(id) as max_id FROM domain_sales")->fetchOne('max_id') ?? 0);
        $id = $maxId + 1;
        $this->ch->insert('domain_sales', [[$id, $domain, 'homes', 12345, 'Testville', 'Test', 'Testland']],
            ['id', 'domain_name', 'keyword', 'price', 'city', 'state', 'country']);
        return $id;
    }

    private function countRows(): int
    {
        return (int) $this->ch->query("SELECT count() as c FROM domain_sales")->fetchOne('c');
    }

    public function test_admin_can_edit_a_sale(): void
    {
        $domain = 'editsale' . time() . '.com';
        $id = $this->insertTempSale($domain);

        $response = $this->actingAs($this->user)->put('/admin/sales/' . $id, [
            'domain_name' => $domain,
            'keyword' => 'apartments',
            'price' => 99999,
            'city' => 'Editedville',
            'state' => 'Edited',
            'country' => 'Editland',
        ]);

        $response->assertRedirect(route('admin.sales'));
        $response->assertSessionHas('success');

        // Verify updated values landed (clear and re-read by domain)
        $rows = $this->ch->query("SELECT * FROM domain_sales WHERE lower(domain_name) = :d LIMIT 1", ['d' => strtolower($domain)])->rows();
        $this->assertNotEmpty($rows);
        $this->assertEquals('apartments', $rows[0]['keyword']);
        $this->assertEquals('99999', $rows[0]['price']);
        $this->assertEquals('Editedville', $rows[0]['city']);

        // Cleanup: delete the temp sale
        $this->ch->execute("ALTER TABLE domain_sales DELETE WHERE domain_name = '" . strtolower($domain) . "'");
    }

    public function test_edit_rejects_changing_to_existing_domain(): void
    {
        $rows = $this->ch->query("SELECT domain_name FROM domain_sales LIMIT 1")->rows();
        $existingDomain = $rows[0]['domain_name'];

        $tempDomain = 'editdup' . time() . '.com';
        $id = $this->insertTempSale($tempDomain);

        $response = $this->actingAs($this->user)->put('/admin/sales/' . $id, [
            'domain_name' => $existingDomain,
            'keyword' => 'homes',
            'price' => 50000,
            'city' => 'X',
            'state' => 'Y',
            'country' => 'Z',
        ]);

        $response->assertSessionHasErrors('domain_name');

        // Cleanup temp
        $this->ch->execute("ALTER TABLE domain_sales DELETE WHERE domain_name = '" . strtolower($tempDomain) . "'");
    }

    public function test_admin_can_delete_a_sale(): void
    {
        $domain = 'deletesale' . time() . '.com';
        $id = $this->insertTempSale($domain);
        $before = $this->countRows();

        $response = $this->actingAs($this->user)->delete('/admin/sales/' . $id);

        $response->assertRedirect(route('admin.sales'));
        $response->assertSessionHas('success');

        $rows = $this->ch->query("SELECT 1 FROM domain_sales WHERE id = :id LIMIT 1", ['id' => $id])->rows();
        $this->assertEmpty($rows);
        $this->assertSame($before - 1, $this->countRows());
    }
}
