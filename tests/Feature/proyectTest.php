<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Models\User;
use App\Models\Proyect;
use App\Models\Worklog;

class ProyectTest extends TestCase
{
  /**
   * test get proyect names for input in a view
   */
  public function test_get_proyect_names_for_input(): void {
    $this->_createProyects();
    $proyect1 = Proyect::first();

    $response = $this->get('/api/getproyectinputs');

    $response->assertStatus(200);

    $proyects = Proyect::all();
    $this->assertIsArray( $response['data'] );
    $this->assertTrue( count($response['data']) == count($proyects) );
    for ($i=0; $i < count($response['data']); $i++) {
      $this->assertTrue( $this->_responseIsInInstances($response['data'][$i]["name"], $proyects, "name") );
    }
  }

  /**
   * test add a proyect
   */
  public function test_add_proyect(): void {
    $this->assertTrue( Proyect::count() == 0 );

    $response = $this->post('/api/updateproyect', [
      'name' => "mega proyect"
    ]);

    $response->assertStatus(200);

    $this->assertTrue( Proyect::count() == 1 );
  }

  /**
   * test update a proyect
   */
  public function test_update_proyect(): void {
    $this->assertTrue( Proyect::count() == 0 );

    $proyect = Proyect::create(["name" => "mega proyect "]);
    
    $response = $this->post('/api/updateproyect', [
      'proyect_id' => $proyect->id,
      'name' => "super mega proyect"
    ]);

    $response->assertStatus(200);

    $proyect->refresh();
    $this->assertTrue( Proyect::count() == 1 );
    $this->assertTrue( $proyect->name == "super mega proyect" );
  }
}
