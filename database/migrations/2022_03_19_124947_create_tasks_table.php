<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('project_id');
            $table->foreignId('author_id');
            $table->foreignId('user_id');
            $table->unsignedInteger('status')->default(1);
            $table->date('start_at')->nullable();
            $table->date('due_at')->nullable();
            $table->longText('description');
            $table->boolean('is_stopped')->default(0);
            $table->boolean('is_returned')->default(0);
            $table->boolean('is_marked')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
