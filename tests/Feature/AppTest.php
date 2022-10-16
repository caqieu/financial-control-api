<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_the_application_is_up()
    {
        $response = $this->get('/api');

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => "it works!"
            ]);
    }
}
