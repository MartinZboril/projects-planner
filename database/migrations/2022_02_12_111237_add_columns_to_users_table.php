<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username');
            $table->string('surname');
            $table->string('job_title')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            //TODO: remove into address
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('surname');
            $table->dropColumn('job_title');
            $table->dropColumn('mobile');
            $table->dropColumn('phone');
            $table->dropColumn('street');
            $table->dropColumn('house_number');
            $table->dropColumn('city');
            $table->dropColumn('country');
            $table->dropColumn('zip_code');
        });
    }
}
