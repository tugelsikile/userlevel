<?php
//namespace Tugelsikile\UserLevel\databases\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_levels', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_super')->default(false);
            $table->boolean('can_delete')->default(true);
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
        Schema::dropIfExists('user_levels');
    }
}
