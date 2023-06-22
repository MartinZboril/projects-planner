<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(
                \App\Models\Client::class,
                'client_id'
            )
                ->constrained('clients')
                ->onDelete('cascade');
            $table->date('started_at');
            $table->date('dued_at');
            $table->unsignedInteger('estimated_hours')->nullable();
            $table->unsignedInteger('budget')->nullable();
            $table->longText('description');
            $table->unsignedInteger('status')->default(1);
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
        Schema::dropIfExists('projects');
    }
}
