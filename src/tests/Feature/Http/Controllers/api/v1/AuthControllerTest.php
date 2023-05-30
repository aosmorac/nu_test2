<?php

namespace Tests\Feature\Http\Controllers\api\v1;

use Tests\TestCase;

/**
 * Class AuthControllerTest
 *
 * Run these specific tests
 * php artisan test tests/Feature/Http/Controllers/api/v1/AuthControllerTest.php
 *
 * @package Tests\Feature\Http\Controllers\api\v1
 */
class AuthControllerTest extends TestCase
{
    /**
     * Political party api endpoint
     */
    const ENDPOINT = '/api/v1/auth';

    /**
     * @test
     *
     * @return void
     */
    public function user_can_register_a_new_user()
    {
        $new_user_data = [
            'name' => 'Test Name',
            'phone' => '3133929826',
            'password' => '123456'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->getToken())
            ->json('POST', self::ENDPOINT . '/register', $new_user_data);

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'User registered successfully');
    }
}
