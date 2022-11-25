<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test_can_open_home_without_begin_authenticated()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
