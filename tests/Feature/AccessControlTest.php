<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_is_blocked_from_admin_routes(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_routes(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertOk();
    }

    public function test_admin_nav_not_shown_for_non_admin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/');

        // The /admin nav link should NOT be present for non-admins.
        // Assert there is exactly one place the admin route could anchor — but
        // a non-admin must not get the nav link. We check the specific href.
        $content = $response->getContent();
        $this->assertStringNotContainsString('href="' . route('admin.dashboard', absolute: false) . '"', $content);
        $this->assertStringNotContainsString('>Admin</', $content);
    }

    public function test_guest_limited_to_five_free_searches(): void
    {
        // Simulate 5 previous free searches in session
        $session = app('session.store');
        $session->put('free_searches', 5);

        // 6th search by guest should force login
        $response = $this->withSession(['free_searches' => 5])->get('/search?keyword=miami');

        $response->assertOk();
        $this->assertStringContainsString("You've used your 5 free searches", $response->getContent());
        $this->assertStringNotContainsString('miamihomes.com (as a row result)', $response->getContent());
    }

    public function test_logged_in_user_gets_results_not_login_prompt(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/search?keyword=miami');

        $response->assertOk();
        $this->assertStringNotContainsString("You've used your 5 free searches", $response->getContent());
    }
}
