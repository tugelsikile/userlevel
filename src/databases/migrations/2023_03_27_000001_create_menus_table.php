<?php
//namespace Tugelsikile\UserLevel\databases\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create("menus", function (Blueprint  $table) {
            $table->uuid('id')->primary()->unique();
            $table->integer('order')->default(0);
            $table->uuid('parent')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('route')->unique();
            $table->boolean('is_function')->default(false);
            $table->boolean('super')->default(false);
            $table->timestamps();
        });
        Schema::table('menus', function (Blueprint  $table) {
            $table->foreign('parent')->on('menus')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('menus');
    }
}
