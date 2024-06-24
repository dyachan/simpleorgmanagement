<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use App\Models\User;
use App\Models\Proyect;
use App\Models\Worklog;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function _createUsers(){
        for ($i=0; $i < 3; $i++) { 
            User::create([
                'email' => "user".$i."@yoy.cl",
                'password' => ""
            ]);
        }
    }

    protected function _createProyects(){
        for ($i=0; $i < 2; $i++) { 
            Proyect::create(["name" => "proyect ".$i]);
        }
    }
}
