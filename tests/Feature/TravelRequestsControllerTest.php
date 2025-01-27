<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TravelRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class TravelRequestsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateTravelRequest()
    {
        $user = User::factory()->create(['name' => 'Altieres', 'id' => 2]);
        $this->actingAs($user);

        $response = $this->postJson('/travel-requests', [
            'user_id' => $user->id,
            'departure_date' => '2023-10-07',
            'return_date' => '2023-11-07',
            'destination' => 'New York'
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'user_id' => $user->id,
                     'departure_date' => '2023-10-07',
                     'return_date' => '2023-11-07',
                     'destination' => 'New York'
                 ]);
    }

    public function testUpdateTravelRequest()
    {
        $user = User::factory()->create(['name' => 'Altieres', 'id' => 2]);
        $this->actingAs($user);

        $travelRequest = TravelRequest::factory()->create([
            'user_id' => $user->id,
            'departure_date' => '2023-10-07',
            'return_date' => '2023-11-07',
            'destination' => 'New York'
        ]);

        $response = $this->putJson("/travel-requests/{$travelRequest->id}", [
            'status' => 'aprovado'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $travelRequest->id,
                     'status' => 'aprovado'
                 ]);
    }

    public function testGetTravelRequest()
    {
        $user = User::factory()->create(['name' => 'Altieres', 'id' => 2]);
        $this->actingAs($user);

        $travelRequest = TravelRequest::factory()->create([
            'user_id' => $user->id,
            'departure_date' => '2023-10-07',
            'return_date' => '2023-11-07',
            'destination' => 'New York'
        ]);

        $response = $this->getJson("/travel-requests/{$travelRequest->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $travelRequest->id,
                     'user_id' => $user->id,
                     'departure_date' => '2023-10-07',
                     'return_date' => '2023-11-07',
                     'destination' => 'New York'
                 ]);
    }

    public function testListTravelRequests()
    {
        $user = User::factory()->create(['name' => 'Altieres', 'id' => 2]);
        $this->actingAs($user);

        TravelRequest::factory()->create([
            'user_id' => $user->id,
            'departure_date' => '2023-10-07',
            'return_date' => '2023-11-07',
            'destination' => 'New York'
        ]);

        $response = $this->getJson('/travel-requests');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'user_id', 'departure_date', 'return_date', 'destination']
                 ]);
    }
}
