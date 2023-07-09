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
            $table->text('settings')->nullable();
            $table->softDeletes()->after('updated_at');
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
            $table->dropSoftDeletes();
        });
    }
}
