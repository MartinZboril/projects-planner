<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(
                \App\Models\Rate::class,
                'rate_id'
            )
                ->constrained('rates')
                ->onDelete('cascade');
            $table->foreignIdFor(
                \App\Models\User::class,
                'user_id'
            )
                ->constrained('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rate_user');
    }
}
