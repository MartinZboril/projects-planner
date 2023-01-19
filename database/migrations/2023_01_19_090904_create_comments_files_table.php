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
        Schema::create('comments_files', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(
                \App\Models\Comment::class,
                'comment_id'
            )->constrained('comments');
            $table->foreignIdFor(
                \App\Models\File::class,
                'file_id'
            )->constrained('files');
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
        Schema::dropIfExists('comments_files');
    }
};
