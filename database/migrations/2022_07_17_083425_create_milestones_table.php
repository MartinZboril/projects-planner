<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilestonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('colour');
            $table->foreignIdFor(
                \App\Models\Project::class,
                'project_id'
            )->constrained('projects');
            $table->foreignIdFor(
                \App\Models\User::class,
                'owner_id'
            )->constrained('users');
            $table->date('started_at')->nullable();
            $table->date('dued_at')->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('milestones');
    }
}
