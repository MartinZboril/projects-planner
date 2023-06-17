<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_rate', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(
                \App\Models\User::class,
                'user_id'
            )->constrained('users');
            $table->foreignIdFor(
                \App\Models\Rate::class,
                'rate_id'
            )->constrained('rates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_rate');
    }
}
