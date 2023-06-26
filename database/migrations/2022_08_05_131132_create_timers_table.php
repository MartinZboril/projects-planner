<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(
                \App\Models\Project::class,
                'project_id'
            )
                ->constrained('projects')
                ->onDelete('cascade');
            $table->foreignIdFor(
                \App\Models\User::class,
                'user_id'
            )->constrained('users');
            $table->dateTime('since_at');
            $table->dateTime('until_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timers');
    }
}
