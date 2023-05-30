<?php


namespace Tests\Feature\Http\Controllers\api\v1;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class TestApi extends TestCase
{
    /**
     * Initialize migration
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        Artisan::call('passport:install');
    }

}
