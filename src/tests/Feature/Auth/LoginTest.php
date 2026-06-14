<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_login_id_is_required(): void
    {
        $response = $this->post(route('login'), [
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('login_id');
    }

    public function test_password_is_required(): void
    {
        $response = $this->post(route('login'), [
            'login_id' => 'admin',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'login_id' => 'test_id',
            'password' => $password = 'password',
        ]);

        $response = $this->post(route('login'), [
            'login_id' => $user->login_id,
            'password' => $password,
        ]);

        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'login_id' => 'test_id',
            'password' => 'password',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'login_id' => $user->login_id,
            'password' => 'wrong_password',
        ]);

        $response->assertRedirect(route('login'));

        $response->assertSessionHasErrors('login_id');

        $this->assertGuest();
    }
}
