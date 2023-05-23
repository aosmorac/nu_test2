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
            'name' => 'Peter Parker'
        ];

        $response = $this->json('POST', self::ENDPOINT, $contact_data);

        $response->assertStatus(200);
        $response->assertJsonPath('message', $contact_data['name'] . ' message saved successfully');
    }
}
