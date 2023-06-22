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
            $table->foreignIdFor(
                \App\Models\Project::class,
                'project_id'
            )
                ->constrained('projects')
                ->onDelete('cascade');
            $table->foreignIdFor(
                \App\Models\User::class,
                'author_id'
            )->constrained('users');
            $table->foreignIdFor(
                \App\Models\User::class,
                'user_id'
            )->constrained('users');
            $table->unsignedInteger('status')->default(1);
            $table->date('started_at')->nullable();
            $table->date('dued_at')->nullable();
            $table->longText('description');
            $table->boolean('is_stopped')->default(0);
            $table->boolean('is_returned')->default(0);
            $table->boolean('is_marked')->default(0);
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
        Schema::dropIfExists('tasks');
    }
}
