<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(
                \App\Models\Project::class,
                'project_id'
            )->constrained('projects');
            $table->foreignIdFor(
                \App\Models\User::class,
                'reporter_id'
            )->constrained('users');
            $table->foreignIdFor(
                \App\Models\User::class,
                'assignee_id'
            )->nullable()->constrained('users');          
            $table->string('subject');
            $table->unsignedInteger('type');
            $table->unsignedInteger('priority');
            $table->unsignedInteger('status')->default(1);
            $table->text('message');
            $table->date('dued_at');
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
        Schema::dropIfExists('tickets');
    }
}
