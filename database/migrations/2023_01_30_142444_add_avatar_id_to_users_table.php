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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar_path');
            $table->foreignIdFor(
                \App\Models\File::class,
                'avatar_id'
            )->nullable()->constrained('files', 'id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable();
            $table->dropConstrainedForeignId('avatar_id');
        });
    }
};
