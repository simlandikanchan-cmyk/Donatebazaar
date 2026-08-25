<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminLastAdminProtectionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function last_active_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'password' => bcrypt('secret-password'),
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.profile.destroy'), ['password' => 'secret-password'])
            ->assertRedirect(); // back() with errors

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
        $this->assertAuthenticated();
    }

    #[Test]
    public function admin_who_is_not_the_last_can_delete_their_own_account(): void
    {
        $other = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'password' => bcrypt('secret-password'),
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.profile.destroy'), ['password' => 'secret-password'])
            ->assertRedirect('/');

        $this->assertDatabaseMissing('users', ['id' => $admin->id]);
        // The other administrator remains.
        $this->assertDatabaseHas('users', ['id' => $other->id]);
    }

    #[Test]
    public function non_admin_user_deletion_is_unaffected_by_the_guard(): void
    {
        $user = User::factory()->create([
            'role' => 'donor',
            'status' => 'active',
            'password' => bcrypt('secret-password'),
        ]);

        $this->actingAs($user)
            ->delete(route('admin.profile.destroy'), ['password' => 'secret-password'])
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
