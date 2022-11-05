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
            $table->foreignId('project_id');
            $table->foreignId('reporter_id');
            $table->foreignId('assignee_id')->nullable();
            $table->string('subject');
            $table->integer('type');
            $table->integer('priority');
            $table->integer('status')->default(1);
            $table->text('message');
            $table->date('due_date');
            $table->boolean('is_convert')->default(0);
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
        Schema::dropIfExists('tickets');
    }
}
