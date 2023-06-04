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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('noteable_id')->nullable();
            $table->string('noteable_type')->nullable();   
            $table->foreignIdFor(
                \App\Models\User::class,
                'user_id'
            )->constrained('users');
            $table->string('name');
            $table->text('content');
            $table->boolean('is_private')->default(0);
            $table->boolean('is_basic')->default(1);
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
        Schema::dropIfExists('notes');
    }
};
