<?php
//namespace Tugelsikile\UserLevel\databases\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPrivilegesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create("user_privileges", function (Blueprint  $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('level');
            $table->string('route');
            $table->boolean('c')->default(false);
            $table->boolean('r')->default(false);
            $table->boolean('u')->default(false);
            $table->boolean('d')->default(false);
            $table->timestamps();

            $table->foreign('level')->on('user_levels')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('user_privileges');
    }
}
