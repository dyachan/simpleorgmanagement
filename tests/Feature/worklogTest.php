<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Proyect;
use App\Models\Worklog;

class WorklogTest extends TestCase
{
  /**
   * test current user add a worklog for him
   */
  public function test_add_worklog(): void {
    $this->_createUsers();
    $this->_createProyects();

    $user1 = User::first();
    $proyect1 = Proyect::first();

    $response = $this->actingAs($user1)->post('/api/addworklog', [
      'start' => "2024-06-21T08:00",
      'end' => "2024-06-21T18:00",
      'user_id' => $user1->id,
      'proyect_id' => $proyect1->id,
      'description' => "some description"
    ]);

    $response->assertStatus(200);
  }

  /**
   * test current user add a worklog for other
   */
  public function test_other_add_worklog(): void {
    $this->_createUsers();
    $this->_createProyects();

    $user1 = User::first();
    $user2 = User::latest()->first();
    $proyect1 = Proyect::first();

    $response = $this->actingAs($user2)->post('/api/addworklog', [
      'start' => "2024-06-21T08:00",
      'end' => "2024-06-21T18:00",
      'user_id' => $user1->id,
      'proyect_id' => $proyect1->id,
      'description' => "some description"
    ]);

    $response->assertStatus(401);
  }

  /**
   * test get worklogs of a user
   */
  public function test_get_user_worklog(): void {
    $this->_createUsers();
    $this->_createProyects();

    
    $user1 = User::first();
    $proyect1 = Proyect::first();
    $proyect2 = Proyect::latest()->first();

    // create some worklogs
    $w1 = Worklog::create([
      'start' => "2024-06-21T08:00",
      'end' => "2024-06-21T13:00",
      'fk_user' => $user1->id,
      'fk_proyect' => $proyect1->id,
      'description' => "before lunch"
    ]);
    $w2 = Worklog::create([
      'start' => "2024-06-21T14:00",
      'end' => "2024-06-21T18:00",
      'fk_user' => $user1->id,
      'fk_proyect' => $proyect2->id,
      'description' => "after lunch"
    ]);

    // make tests

    $response = $this->post('/api/getuserworklog', [
      'userID' => $user1->id
    ]);

    $response->assertStatus(200);

    $this->assertIsArray( $response['data'] );
    $this->assertTrue( $response['data']['id'] == $user1->id );
    $this->assertTrue( $response['data']['user'] == $user1->email );

    $this->assertIsArray( $response['data']['worklogs'] );
    $this->assertTrue( count($response['data']['worklogs']) == 2 );

    $this->assertTrue( $response['data']['worklogs'][0]['proyect'] == $proyect1->name );
    $this->assertTrue( $response['data']['worklogs'][0]['description'] == $w1->description );

    $this->assertTrue( $response['data']['worklogs'][1]['proyect'] == $proyect2->name );
    $this->assertTrue( $response['data']['worklogs'][1]['description'] == $w2->description );
  }
}
