<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'phone_number_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('phone_number_verified_at')->nullable()->after('email_verified_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'phone_number_verified_at')) {
            Schema::table('users', function($table) {
                $table->dropColumn('phone_number_verified_at');
            });
        }
    }
};
