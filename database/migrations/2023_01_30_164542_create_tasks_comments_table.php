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
        Schema::create('tasks_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(
                \App\Models\Task::class,
                'task_id'
            )->constrained('tasks');
            $table->foreignIdFor(
                \App\Models\Comment::class,
                'comment_id'
            )->constrained('comments');
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
        Schema::dropIfExists('tasks_comments');
    }
};
