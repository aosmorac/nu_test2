<?php

namespace Tests\Feature\Http\Controllers\api\v1;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthControllerTest
 *
 * Run these specific tests
 * php artisan test tests/Feature/Http/Controllers/api/v1/AuthControllerTest.php
 *
 * @package Tests\Feature\Http\Controllers\api\v1
 */
class AuthControllerTest extends TestApi
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
            'name'      => 'Test Name',
            'email'     => 'test@email.com',
            'phone'     => '3133929844',
            'password'  => '123456'
        ];

        $response = $this->json('POST', self::ENDPOINT . '/register', $new_user_data);

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'User registered successfully');
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_can_not_register_a_user_with_an_email_on_use()
    {
        User::create([
            'name'          => 'Test Name',
            'email'         => 'test@myadomain.com',
            'phone_number'  => '3133929844',
            'password'      => Hash::make('123456'),
        ]);

        $new_user_data = [
            'name'      => 'Test Name',
            'email'     => 'test@myadomain.com',
            'phone'     => '3133929844',
            'password'  => '123456',
        ];

        $response = $this->json('POST', self::ENDPOINT . '/register', $new_user_data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['email']]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_can_not_register_a_user_with_a_wrong_phone_number()
    {
        $new_user_data = [
            'name'      => 'Test Name',
            'email'     => 'test@myadomain.com',
            'phone'     => '12',
            'password'  => '123456',
        ];

        $response = $this->json('POST', self::ENDPOINT . '/register', $new_user_data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['phone']]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_can_validate_new_user_with_code()
    {
        $user = User::create([
                'name'          => 'Test Name',
                'email'         => 'test@myadomain.com',
                'phone_number'  => '3133929844',
                'verify_code'   => 1234,
                'password'      => Hash::make('123456'),
            ]);

        $response = $this->json(
            'POST',
            self::ENDPOINT . "/register/verify/$user->id",
            ['verify_code' => 1234]
        );

//        print_r($response); die;

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'User verified successfully');
    }
}
