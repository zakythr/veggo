<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\Pembeli\ProfilController;

class EditProfileControllerTest extends TestCase
{
    /* @test */
    public function validatePhoneTest()
    {
        $no_hp="087780402785";

        $this->assertTrue(ProfilController::validatePhone($no_hp));
    }
}