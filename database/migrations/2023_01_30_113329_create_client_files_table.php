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
        Schema::create('clients_files', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(
                \App\Models\Client::class,
                'client_id'
            )->constrained('clients');
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
        Schema::dropIfExists('clients_files');
    }
};
