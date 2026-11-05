<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login_when_opening_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_in_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@test.local',
            'password' => 'secret123',
        ]);

        $response = $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_login_is_rate_limited_after_too_many_failed_attempts(): void
    {
        User::factory()->create([
            'email' => 'admin@test.local',
            'password' => 'secret123',
        ]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $response = $this->from(route('admin.login'))->post(route('admin.login.store'), [
                'email' => 'admin@test.local',
                'password' => 'wrong-password',
            ]);

            $response->assertRedirect(route('admin.login'));
            $response->assertSessionHasErrors('email');
        }

        $response = $this->from(route('admin.login'))->post(route('admin.login.store'), [
            'email' => 'admin@test.local',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString(
            'Terlalu banyak percobaan login. Coba lagi dalam',
            session('errors')->first('email')
        );
    }
}
