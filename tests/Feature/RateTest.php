<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RateTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
    }

    public function test_user_can_see_profile_with_rates(): void
    {
        $response = $this->actingAs($this->user)->get('/users/'.$this->user->id);

        $response->assertStatus(200);
        $response->assertSee('Rates');

        $this->assertDatabaseCount('rates', 0);
    }

    public function test_user_can_get_to_create_rate_page(): void
    {
        $response = $this->actingAs($this->user)->get('/users/'.$this->user->id.'/rates/create');

        $response->assertStatus(200);
        $response->assertSee('Create Rate');
    }

    public function test_user_can_store_rate(): void
    {
        $rate = [
            'name' => 'Driving',
            'is_active' => true,
            'value' => '500',
            'note' => null,
        ];

        $response = $this->actingAs($this->user)->post('/users/'.$this->user->id.'/rates', $rate);

        $response->assertStatus(302);
        $response->assertRedirect('/users/'.$this->user->id);

        $this->assertDatabaseHas('rates', $rate);

        $lastRate = Rate::latest()->first();
        $this->assertEquals($rate['name'], $lastRate->name);
        $this->assertEquals($rate['value'], $lastRate->value);
    }

    public function test_user_can_get_to_edit_rate_page(): void
    {
        $rate = Rate::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('/users/'.$this->user->id.'/rates/'.$rate->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Rate');
        $response->assertSee('value="'.$rate->name.'"', false);
        $response->assertSee('value="'.$rate->value.'"', false);
        $response->assertViewHas('rate', $rate);
    }

    public function test_user_can_update_rate(): void
    {
        $rate = Rate::factory()->create(['user_id' => $this->user->id]);
        $editedRate = [
            'name' => 'Design',
            'value' => '400',
        ];

        $response = $this->actingAs($this->user)->put('/users/'.$this->user->id.'/rates/'.$rate->id, $editedRate);

        $response->assertStatus(302);
        $response->assertRedirect('/users/'.$this->user->id);

        $updatedRate = Rate::find($rate->id);

        $this->assertEquals($editedRate['name'], $updatedRate->name);
        $this->assertEquals($editedRate['value'], $updatedRate->value);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
        ]);
    }
}
