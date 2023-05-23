<?php

namespace Tests\Feature\Http\Controllers\api\v1;

use Tests\TestCase;

/**
 * Class ContactControllerTest
 *
 * Run these specific tests
 * php artisan test tests/Feature/Http/Controllers/api/v1/ContactControllerTest.php
 *
 * @package Tests\Feature\Http\Controllers\api\v1
 */
class ContactControllerTest extends TestCase
{
    /**
     * Contact endpoint
     */
    const ENDPOINT = '/api/v1/contact';

    /**
     * @test
     *
     * @return void
     */
    public function user_can_set_a_contact_form_with_name()
    {
        $contact_data = [
            'name' => 'Peter Parker',
            'email' => 'test@email.com'
        ];

        $response = $this->json('POST', self::ENDPOINT, $contact_data);

        $response->assertStatus(200);
        $response->assertJsonPath(
            'message', $contact_data['name'] . ', ' .
            $contact_data['email'] .
            ', contact saved successfully'
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_can_not_set_a_contact_form_with_name_shorter_than_two_chars()
    {
        $contact_data = [
            'name' => 'P'
        ];

        $response = $this->json('POST', self::ENDPOINT, $contact_data);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'name' => [
                    'The name must be at least 2 characters.'
                ]
            ]
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_can_set_a_contact_form_with_email()
    {
        $contact_data = [
            'name' => 'Peter Parker',
            'email' => 'test@email.com'
        ];

        $response = $this->json('POST', self::ENDPOINT, $contact_data);

        $response->assertStatus(200);
        $response->assertJsonPath(
            'message', $contact_data['name'] . ', ' .
            $contact_data['email'] .
            ', contact saved successfully'
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_can_not_set_a_contact_form_with_an_invalid_email()
    {
        $contact_data = [
            'name' => 'Peter Parker',
            'email' => 'test'
        ];

        $response = $this->json('POST', self::ENDPOINT, $contact_data);

        $response->assertStatus(422);
    }
}
