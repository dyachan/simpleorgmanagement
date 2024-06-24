<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Proyect;
use App\Models\Worklog;

class WorklogTest extends TestCase
{
  /**
   * A basic test example.
   */
  public function test_add_worklog(): void {
    $this->_createUsers();
    $this->_createProyects();

    $user1 = User::first();
    $proyect1 = Proyect::first();

    $response = $this->post('/api/addworklog', [
      'start' => "2024-06-21T08:00",
      'end' => "2024-06-21T18:00",
      'user_id' => $user1->id,
      'proyect_id' => $proyect1->id,
      'description' => "some description"
    ]);

    $response->assertStatus(200);
  }
}
